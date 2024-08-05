<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 500px;
            width: 100%;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }
        .qr-reader {
            width: 500px;
        }
        .result-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <div class="center-container" >
        <button id="start-scan">Escanear QR Codes</button>
        <button id="clear-data">Limpar QR Codes Armazenados</button>
        <div id="reader" class="qr-reader"></div>
        <div id="show" style="display: none;">
            <h4> Resultado Escaneado </h4>
            <p style="color: blue;" id="result"></p>
        </div>
        <p id="progress">0/7 QR Codes Escaneados</p>
        <div id="scanned-list" style="text-align: left; margin: 20px;">
            <h4>QR Codes Escaneados:</h4>
            <ul id="qr-list"></ul>
        </div>
    </div>
    <script>
        const totalQrCodes = 10; // Número de QR Codes necessários

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
                    document.getElementById('result').textContent = qrCodeMessage;
                    storeQrCodeData(qrCodeMessage);
                },
                errorMessage => {
                    console.log(`QR Code no longer in front of camera.`);
                }
            ).catch(err => {
                console.error(`Unable to start scanning, error: ${err}`);
            });
        });

        document.getElementById('clear-data').addEventListener('click', function() {
            clearScannedData();
        });

        function storeQrCodeData(qrCodeMessage) {
            let scannedData = JSON.parse(localStorage.getItem('scannedData')) || [];

            if (scannedData.includes(qrCodeMessage)) {
                alert('Este QR Code já foi escaneado.');
                return;
            }

            scannedData.push(qrCodeMessage);
            localStorage.setItem('scannedData', JSON.stringify(scannedData));
            updateScannedList();
        }

        function updateScannedList() {
            let scannedData = JSON.parse(localStorage.getItem('scannedData')) || [];
            const qrList = document.getElementById('qr-list');
            qrList.innerHTML = ''; // Clear the list

            scannedData.forEach((data, index) => {
                const li = document.createElement('li');
                li.textContent = `QR Code ${index + 1}: ${data}`;
                qrList.appendChild(li);
            });

            const scannedQrCodes = scannedData.length;
            document.getElementById('progress').textContent = `${scannedQrCodes}/${totalQrCodes} QR Codes scanned`;

            if (scannedQrCodes === totalQrCodes) {
                sendDataToServer();
            }
        }

        function clearScannedData() {
            localStorage.removeItem('scannedData');
            updateScannedList(); // Ensure the list and progress are updated after clearing
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

        // Atualiza a lista ao carregar a página
        updateScannedList();
    </script>
</body>
</html>
