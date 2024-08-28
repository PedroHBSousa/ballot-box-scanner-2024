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
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/index.css')
</head>
<header>
    <h1>APURAÇÃO ELEITORAL</h1>
    <div class="container-image">
        <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
        <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
        <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
    </div>
</header>

<body>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <div class="center-container">
        <div class="instructions">
            <h2>INSTRUÇÕES</h2>
            <h4>- Realize o escaneamento dos QR Codes na ordem crescente e sequencial.</h4>
            <h4>- Se um QR Code for lido <span>incorretamente</span> ou se houver problemas durante o
                escaneamento, clique no botão "Limpar QR Codes" para apagar todos os QR Codes lidos.</h4>
            <h4>- Envio dos Dados: Após cada escaneamento, não se esqueça de clicar no botão "ENVIAR" para registrar as
                informações.</h4>
        </div>

        <div id="reader" class="qr-reader"></div>

        <form method="post" action="{{ route('store') }}" id="qrcode-form">
            @csrf
            <input type="hidden" id="qrcode-value" name="qrcode_value">
        </form>

        <div id="message">
            @if (session('status'))
                <p style="color: blue;">{{ session('status') }}</p>
            @endif
            @if (session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif
            @if (session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif
        </div>

        <div class="container-button">
            <form action="{{ route('qrcodes.clear') }}" method="POST">
                @csrf
                <div id="writer" class="digitar">
                    <button type="submit">Limpar QR Codes</button>
                </div>
            </form>
        </div>

        <div id="filteredData">
            @if (session('data'))
                <h2>Dados Filtrados:</h2>
                <ul>
                    @foreach (session('data') as $key => $value)
                        <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
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

            // Submeter o formulário automaticamente
            document.getElementById('qrcode-form').submit();

            // // Opcional: Ocultar a mensagem após alguns segundos
            // setTimeout(function() {
            //     successMessage.style.display = 'none';
            // }, 3000);
            document.getElementById('qrcode-form').submit();
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>

</html>
