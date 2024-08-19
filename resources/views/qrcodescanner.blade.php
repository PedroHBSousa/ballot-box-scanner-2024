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

        <div class="buttons-container">
            <button id="clear-data" class="green-button">LIMPAR QR CODES ARMAZENADOS</button>
        </div>

        <div id="reader" class="qr-reader"></div>

        @if (session('error'))
            <div>
                {{ session('error') }}
            </div>
        @endif

        {{-- <div id="scanned-list" style="text-align: left; margin: 20px;">
            <h4>QR CODE ESCANEADOS: <?php echo '0/0'; ?> </h4>
            <ul id="qr-list"></ul>
        </div> --}}

        <form method="post" action="{{ route('store') }}">
            @csrf
            <input type="hidden" id="qrcode-value" name="qrcode_value">
            <div id="writer" class="digitar">
                <button type="submit">ENVIAR</button>
            </div>
        </form>
        <div id="message">
            @if (session('status'))
                <p>{{ session('status') }}</p>
            @endif
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
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>

</html>
