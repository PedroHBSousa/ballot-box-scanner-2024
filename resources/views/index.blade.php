<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apurador de Votos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon" />
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

    <div class="center-container">

        <div class="buttons-container">
            <button id="clear-data" class="green-button">Limpar QR Codes Armazenados</button>
        </div>

        <div id="reader" class="qr-reader"></div>

        <div id="scanned-list" style="text-align: left; margin: 20px;">
            <h4>QR Codes Escaneados: <?php echo '0/0'; ?> </h4>
            <ul id="qr-list"></ul>
        </div>

        <form method="post" action="{{ route('store') }}">
            @csrf
            <input type="hidden" id="qrcode-value" name="qrcode_value">
            <div id="writer" class="digitar">
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>
    </div>
    <script>
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: 250
            });

        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('qrcode-value').value = decodedText;
            html5QrcodeScanner.clear();
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>

</html>
