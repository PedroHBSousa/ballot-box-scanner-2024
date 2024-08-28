<?php

namespace App\Services;

class BoletimEleitoralService
{
    protected $qrCodesLidos = [];
    protected $totalQrCodes = 0;
    protected $dadosBoletim = [];
    protected $votos = [];
    protected $dadosQrCodes = []; // Nova propriedade para armazenar os dados dos QR codes lidos


    public function __construct($qrCodeValue, $qrCodesLidos)
    {
        $this->qrCodesLidos = $qrCodesLidos;  // Inicializa com os QR codes lidos da sessão
        $this->dadosBoletim = session()->get('dadosBoletim', []); // Carrega os dados do boletim da sessão se existirem
        $this->votos = session()->get('votos', []); // Carrega os votos acumulados da sessão se existirem
        $this->dadosQrCodes = session()->get('dadosQrCodes', []); // Carrega os dados dos QR codes da sessão se existirem
        $this->processarQRCode($qrCodeValue);
    }

    protected function processarQRCode($qrCodeValue)
    {
        // Buscar a chave 'QRBU' no array
        foreach ($qrCodeValue as $item) {
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
        }

        // Armazena os dados do QR code atual
        $this->dadosQrCodes[] = $qrCodeValue;

        // Persistir os dados na sessão
        session()->put('dadosBoletim', $this->dadosBoletim);
        session()->put('dadosQrCodes', $this->dadosQrCodes);

        // Verifica se todos os QR codes foram lidos
        if (count($this->qrCodesLidos) === $this->totalQrCodes) {
            $this->extrairVotos();
        }
    }

    protected function extrairVotos()
    {
        foreach ($this->dadosQrCodes as $qrCodeValue) {
            $cargoAtual = null;
            $boletim_id = $this->dadosBoletim['boletim_id'] ?? null;
            $secao_id = $this->dadosBoletim['SECA'] ?? null;
            $processandoVotosCargo13 = false;
            $processandoVotosCargo11 = false;

            foreach ($qrCodeValue as $item) {
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
        }

        // Persistir os votos acumulados na sessão
        session()->put('votos', $this->votos);
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
