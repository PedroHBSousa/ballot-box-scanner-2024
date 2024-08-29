<?php

namespace App\Services;

class ScannerService
{
    protected $qrCodesLidos = [];
    protected $totalQrCodes = 0;
    protected $dadosBoletim = [];
    protected $conteudoCompleto = ""; // String que acumula todos os QR Codes
    protected $votos = [];


    public function __construct($qrCodeValue, $qrCodesLidos)
    {
        $this->qrCodesLidos = $qrCodesLidos;  // Inicializa com os QR codes lidos da sessão
        $this->dadosBoletim = session()->get('dadosBoletim', []); // Carrega os dados do boletim da sessão se existirem
        $this->conteudoCompleto = session()->get('conteudoCompleto', ""); // Carrega o conteúdo acumulado dos QR Codes da sessão
        $this->votos = session()->get('votos', []); // Carrega os votos acumulados da sessão se existirem
        $this->validarEArmazenarQRCode($qrCodeValue);
    }

    protected function validarEArmazenarQRCode($qrCodeValue)
    {
        $qrCodeArray = $this->parseQRCodeConteudo($qrCodeValue);

        foreach ($qrCodeArray as $item) {
            if ($item[0] === 'ZONA') {
                if ($item[1] != '132') {
                    throw new \Exception("ZONA ELEITORAL INVÁLIDA");
                }
            }
            if ($item[0] === 'MUNI') {
                if ($item[1] != '71153') {
                    throw new \Exception("MUNICÍPIO INVÁLIDO");
                }
            }
            if ($item[0] === 'QRBU') {
                $this->totalQrCodes = (int)$item[2];
                $posicaoAtual = (int)$item[1];

                // Verifica se já foi lido ou se está fora de ordem
                if (in_array($posicaoAtual, $this->qrCodesLidos)) {
                    throw new \Exception("O QR Code $posicaoAtual já foi lido.");
                } elseif ($posicaoAtual != count($this->qrCodesLidos) + 1) {
                    throw new \Exception("Você pulou o QR Code " . (count($this->qrCodesLidos) + 1) . ". Por favor, escaneie novamente.");
                }

                // Adiciona o QR code lido ao array
                $this->qrCodesLidos[] = $posicaoAtual;
            }
            if (in_array($item[0], ['SECA', 'APTO', 'COMP', 'FALT', 'ASSI'])) {
                $this->dadosBoletim[$item[0]] = $item[1];
            }
            $this->conteudoCompleto .= implode(':', $item) . " ";
        }

        // Persistir os dados do boletim na sessão
        session()->put('dadosBoletim', $this->dadosBoletim);
        session()->put('qrCodesLidos', $this->qrCodesLidos);
        session()->put('conteudoCompleto', $this->conteudoCompleto);

        // Se todos os QR Codes foram lidos, extrair os votos
        if (count($this->qrCodesLidos) === $this->totalQrCodes) {
            $this->extrairVotos($this->conteudoCompleto);
        }
    }

    protected function extrairVotos($conteudoCompleto)
    {
        $qrCodeArray = $this->parseQRCodeConteudo($conteudoCompleto);
        $cargoAtual = null;
        $boletim_id = $this->dadosBoletim['boletim_id'] ?? null;
        $secao_id = $this->dadosBoletim['SECA'] ?? null;
        $processandoVotosCargo13 = false;
        $processandoVotosCargo11 = false;

        foreach ($qrCodeArray as $item) {
            if ($item[0] === 'CARG') {
                $cargoAtual = (int)$item[1];
                $processandoVotosCargo13 = ($cargoAtual === 13);
                $processandoVotosCargo11 = ($cargoAtual === 11);
            }

            if ($cargoAtual === 13 && $processandoVotosCargo13) {
                $this->processarVotosCargo13($boletim_id, $secao_id, $item);
            }

            if ($cargoAtual === 11 && $processandoVotosCargo11) {
                $this->processarVotosCargo11($boletim_id, $secao_id, $item);
            }
        }

        // Persistir os votos acumulados na sessão
        session()->put('votos', $this->votos);
    }

    protected function parseQRCodeConteudo($conteudoCompleto)
    {
        $items = explode(" ", trim($conteudoCompleto));
        $qrCodeArray = [];

        foreach ($items as $item) {
            $qrCodeArray[] = explode(":", $item);
        }

        return $qrCodeArray;
    }

    protected function processarVotosCargo13($boletim_id, $secao_id, $item)
    {
        static $processandoVotos = false;

        if ($item[0] === 'PART') {
            $processandoVotos = true;
        } elseif ($item[0] === 'LEGP' || $item[0] === 'APTA') {
            $processandoVotos = false;
        }

        if ($processandoVotos && is_numeric($item[0]) && isset($item[1])) {
            $this->salvarVoto(13, $boletim_id, $secao_id, $item[0], $item[1]);
        }

        if ($item[0] === 'BRAN' || $item[0] === 'NULO') {
            $this->processarVotosBrancosNulos(13, $item[0] === 'BRAN' ? 'branco' : 'nulo', (int)$item[1], $boletim_id, $secao_id);
        }
    }

    protected function processarVotosCargo11($boletim_id, $secao_id, $item)
    {
        static $processandoVotos = false;

        if ($item[0] === 'VERC') {
            $processandoVotos = true;
        } elseif ($item[0] === 'APTA') {
            $processandoVotos = false;
        }

        if ($processandoVotos && is_numeric($item[0]) && isset($item[1])) {
            $this->salvarVoto(11, $boletim_id, $secao_id, $item[0], $item[1]);
        }

        if ($item[0] === 'BRAN' || $item[0] === 'NULO') {
            $this->processarVotosBrancosNulos(11, $item[0] === 'BRAN' ? 'branco' : 'nulo', (int)$item[1], $boletim_id, $secao_id);
        }
    }


    protected function salvarVoto($cargo_id, $boletim_id, $secao_id, $candidato_id, $quantidade)
    {
        for ($i = 0; $i < $quantidade; $i++) {
            $this->votos[] = [
                'cargo_id' => $cargo_id,
                'boletim_id' => $boletim_id,
                'secao_id' => $secao_id,
                'candidato_id' => $candidato_id,
                'nominal' => 'sim',
                'nulo' => 'não',
                'branco' => 'não',
            ];
        }
    }

    protected function processarVotosBrancosNulos($cargo_id, $tipo, $quantidade, $boletim_id, $secao_id)
    {
        for ($i = 0; $i < $quantidade; $i++) {
            $this->votos[] = [
                'cargo_id' => $cargo_id,
                'boletim_id' => $boletim_id,
                'secao_id' => $secao_id,
                'candidato_id' => null, // Para votos brancos e nulos, o candidato_id é null
                'nominal' => 'não',
                'nulo' => $tipo === 'nulo' ? 'sim' : 'não',
                'branco' => $tipo === 'branco' ? 'sim' : 'não',
            ];
        }
    }

    public function getVotos()
    {
        return $this->votos;
    }

    public function getStatus()
    {
        return [
            'qr_codes_lidos' => count($this->qrCodesLidos),
            'qr_codes_totais' => $this->totalQrCodes,
            'qr_codes_restantes' => $this->totalQrCodes - count($this->qrCodesLidos),
        ];
    }

    public function getQrCodesLidos()
    {
        return $this->qrCodesLidos;
    }

    public function getDadosBoletim()
    {
        return $this->dadosBoletim;
    }
}
