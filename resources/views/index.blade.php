<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apurador de Votos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon"/>
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/index.css')
</head>

<body>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <!-- Biblioteca para leitura de QR Code -->
    <div class="center-container">
        <div class="buttons-container">
            <button id="start-scan" class="green-button">Escanear QR Codes</button>
            <!-- Botão para iniciar a leitura dos QR Codes -->
            <button id="clear-data" class="green-button">Limpar QR Codes Armazenados</button>
            <!-- Botão para limpar os QR Codes armazenados -->
        </div>
        <div id="reader" class="qr-reader"></div> <!-- Contêiner para o leitor de QR Code -->
        <div id="show" style="display: none;">
            <h4>Resultado Escaneado</h4>
            <pre id="result" style="color: blue;"></pre> <!-- Exibição do resultado escaneado -->
        </div>
        <p id="progress">0 QR Codes Escaneados</p> <!-- Exibição do progresso de QR Codes escaneados -->
        <div id="scanned-list" style="text-align: left; margin: 20px;">
            <h4>QR Codes Escaneados:</h4>
            <ul id="qr-list"></ul> <!-- Lista de QR Codes escaneados -->
        </div>
        <p>OU</p>
        <div id="writer" class="digitar">
            <div class="container">
                <label for="codigo"> Digite o código do candidato abaixo ⤵</label>
                <textarea name="código-pref" placeholder="Digite o código do candidato" minlength="10" maxlength="20" id="codigo-candidato"></textarea>
                <button type="submit">Enviar</button> 
            </div>
        </div>
    </div>
    <script>
        document.getElementById('start-scan').addEventListener('click', function () {
            const html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                qrCodeMessage => {
                    storeQrCodeData(qrCodeMessage); // Armazena o QR Code escaneado
                },
                errorMessage => {
                    console.log(`QR Code no longer in front of camera.`);
                }
            ).catch(err => {
                console.error(`Unable to start scanning, error: ${err}`);
            });
        });

        document.getElementById('clear-data').addEventListener('click', function () {
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
                li.textContent = `QR Code ${index + 1}: ${data}`;
                qrList.appendChild(li);
            });

            const scannedQrCodes = scannedData.length;
            document.getElementById('progress').textContent = `${scannedQrCodes} QR Codes Escaneados`;

            if (scannedQrCodes === totalQrCodes) {
                sendDataToServer(); // Envia os dados para o servidor se todos os QR Codes foram escaneados
            }
        }

        function clearScannedData() {
            localStorage.removeItem('scannedData');
            updateScannedList(); // Atualiza a lista e o progresso após limpar
        }

        function sendDataToServer() {
            let scannedData = JSON.parse(localStorage.getItem('scannedData')) || [];

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
        }

        // Atualiza a lista ao carregar a página
        updateScannedList();
    </script>

</body>

</html>