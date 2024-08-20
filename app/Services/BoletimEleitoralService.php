<?php

namespace App\Services;

class BoletimEleitoralService
{
    protected $qrCodesLidos = [];
    protected $totalQrCodes = 0;
    protected $dadosBoletim = [];

    public function __construct($qrCodeValue, $qrCodesLidos)
    {
        $this->qrCodesLidos = $qrCodesLidos;  // Inicializa com os QR codes lidos da sessão
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
