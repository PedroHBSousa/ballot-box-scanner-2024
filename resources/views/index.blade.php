<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apura QRCode</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }
        .buttons-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px; /* Espaço entre os botões */
        }
        .qr-reader {
            width: 500px;
        }
        .result-container {
            text-align: center;
        }
        .green-button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> <!-- Biblioteca para leitura de QR Code -->
    <div class="center-container">
        <div class="buttons-container">
            <button id="start-scan" class="green-button">Escanear QR Codes</button> <!-- Botão para iniciar a leitura dos QR Codes -->
            <button id="clear-data" class="green-button">Limpar QR Codes Armazenados</button> <!-- Botão para limpar os QR Codes armazenados -->
        </div>
        <div id="reader" class="qr-reader"></div> <!-- Contêiner para o leitor de QR Code -->
        <div id="show" style="display: none;">
            <h4>Resultado Escaneado</h4>
            <pre id="result" style="color: blue;"></pre> <!-- Exibição do resultado escaneado em JSON -->
        </div>
        <p id="progress">0/7 QR Codes Escaneados</p> <!-- Exibição do progresso de QR Codes escaneados -->
        <div id="scanned-list" style="text-align: left; margin: 20px;">
            <h4>QR Codes Escaneados:</h4>
            <ul id="qr-list"></ul> <!-- Lista de QR Codes escaneados -->
        </div>
    </div>
    <script>
        const totalQrCodes = ''; // Número de QR Codes necessários

        document.getElementById('start-scan').addEventListener('click', function() {
            const html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                qrCodeMessage => {
                    document.getElementById('show').style.display = 'block';
                    document.getElementById('result').textContent = formatToJson(qrCodeMessage);
                    storeQrCodeData(qrCodeMessage); // Armazena o QR Code escaneado
                },
                errorMessage => {
                    console.log(`QR Code no longer in front of camera.`);
                }
            ).catch(err => {
                console.error(`Unable to start scanning, error: ${err}`);
            });
        });

        document.getElementById('clear-data').addEventListener('click', function() {
            clearScannedData(); // Limpa os dados dos QR Codes escaneados
        });

        function storeQrCodeData(qrCodeMessage) {
            let scannedData = JSON.parse(localStorage.getItem('scannedData')) || [];

            if (scannedData.includes(qrCodeMessage)) {
                alert('Este QR Code já foi escaneado, escaneie outro QR Code.');
                return;
            }

            scannedData.push(qrCodeMessage);
            localStorage.setItem('scannedData', JSON.stringify(scannedData));
            updateScannedList(); // Atualiza a lista de QR Codes escaneados
        }

        function updateScannedList() {
            let scannedData = JSON.parse(localStorage.getItem('scannedData')) || [];
            const qrList = document.getElementById('qr-list');
            qrList.innerHTML = ''; // Limpa a lista

            scannedData.forEach((data, index) => {
                const li = document.createElement('li');
                li.textContent = `QR Code ${index + 1}: ${formatToJson(data)}`;
                qrList.appendChild(li);
            });

            const scannedQrCodes = scannedData.length;
            document.getElementById('progress').textContent = `${scannedQrCodes}/${totalQrCodes} QR Codes scanned`;

            if (scannedQrCodes === totalQrCodes) {
                sendDataToServer(); // Envia os dados para o servidor se todos os QR Codes foram escaneados
            }
        }

        function clearScannedData() {
            localStorage.removeItem('scannedData');
            updateScannedList(); // Atualiza a lista e o progresso após limpar
            document.getElementById('show').style.display = 'none';
            document.getElementById('result').textContent = '';
        }

        function sendDataToServer() {
            let scannedData = JSON.parse(localStorage.getItem('scannedData')) || [];

            if (scannedData.length === totalQrCodes) {
                fetch('/api/save-qrcodes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ qrcodes: scannedData })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    localStorage.removeItem('scannedData');
                    alert('Todos os QR Codes foram escaneados e os dados foram enviados com sucesso!');
                    updateScannedList();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            } else {
                alert('Nem todos os QR Codes foram escaneados.');
            }
        }

        function formatToJson(data) {
            try {
                // Tenta converter os dados para JSON
                return JSON.stringify(JSON.parse(data), null, 2);
            } catch (e) {
                // Caso não seja possível, apenas retorna os dados como estão
                return data;
            }
        }

        // Atualiza a lista ao carregar a página
        updateScannedList();
    </script>
</body>
</html>
