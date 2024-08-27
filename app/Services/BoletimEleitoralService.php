<?php

namespace App\Services;

class BoletimEleitoralService
{
    protected $qrCodesLidos = [];
    protected $totalQrCodes = 0;
    protected $dadosBoletim = [];
    protected $votos = [];


    public function __construct($qrCodeValue, $qrCodesLidos)
    {
        $this->qrCodesLidos = $qrCodesLidos;  // Inicializa com os QR codes lidos da sessão
        $this->dadosBoletim = session()->get('dadosBoletim', []); // Carrega os dados do boletim da sessão se existirem
        $this->processarQRCode($qrCodeValue);

    }

    protected function processarQRCode($qrCodeValue)
    {
        // Redefinir votos para cada novo QR Code processado
        $this->votos = [];

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
          // Persistir os dados do boletim na sessão
          session()->put('dadosBoletim', $this->dadosBoletim);
        $this->extrairVotos($qrCodeValue);
    }
    // começa aqui
    protected function extrairVotos($qrCodeValue)
    {
        $cargoAtual = null;
        $boletim_id = $this->dadosBoletim['boletim_id'] ?? null;
        $secao_id = $this->dadosBoletim['SECA'] ?? null;

        foreach ($qrCodeValue as $item) {
            if ($item[0] === 'CARG') {
                $cargoAtual = (int)$item[1];
            }

            if ($cargoAtual === 13 || $cargoAtual === 11) {
                if ($item[0] === 'BRAN' || $item[0] === 'NULO') {
                    $this->processarVotosBrancosNulos($cargoAtual, $item[0] === 'BRAN' ? 'branco' : 'nulo', (int)$item[1], $boletim_id, $secao_id);
                }
            }
        }
        // Processa os votos dos candidatos após verificar os votos brancos e nulos
        if ($cargoAtual === 13 || $cargoAtual === 11) {
            $this->processarVotosCandidatos($cargoAtual, $boletim_id, $secao_id, $qrCodeValue);
        }
    }

    protected function processarVotosCandidatos($cargo_id, $boletim_id, $secao_id, $qrCodeData)
    {
        $processandoVotos = false;
        $tipoProcessamento = null;

        foreach ($qrCodeData as $item) {
            if ($item[0] === 'CARG') {
                if ($item[1] == 13 || $item[1] == 11) {
                    $tipoProcessamento = (int)$item[1];
                }
            }

            if ($tipoProcessamento === 13) {
                if ($item[0] === 'PART') {
                    $processandoVotos = true;
                } elseif ($item[0] === 'LEGP') {
                    $processandoVotos = false;
                } elseif ($item[0] === 'APTA') {
                    $tipoProcessamento = null;
                }

                if ($processandoVotos && is_numeric($item[0]) && isset($item[1])) {
                    $this->salvarVoto($cargo_id, $boletim_id, $secao_id, $item[0], $item[1]);
                }
            }

            if ($tipoProcessamento === 11) {
                if ($item[0] === 'VERC') {
                    $processandoVotos = true;
                } elseif ($item[0] === 'APTA') {
                    $processandoVotos = false;
                    $tipoProcessamento = null;
                }

                if ($processandoVotos && is_numeric($item[0]) && isset($item[1])) {
                    $this->salvarVoto($cargo_id, $boletim_id, $secao_id, $item[0], $item[1]);
                }
            }
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
