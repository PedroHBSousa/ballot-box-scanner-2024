<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apurador de Votos</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/index.css')
</head>


<body>
    <header>
        <h1>APURAÇÃO ELEITORAL<span>2024</span></h1>
        <div class="container-image">
            <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
            <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
            <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
        </div>
    </header>

    <div class="center-container">
        {{-- <div class="instructions">
            <h2>INSTRUÇÕES</h2>
            <h4>REALIZE O ESCANEAMENTO EM ORDEM CRESCENTE E SEQUENCIAL. SE ESTIVER TENDO <span>PROBLEMAS</span> COM O
                ESCANEAMENTO, CLIQUE NO BOTÃO "LIMPAR QR CODES" PARA
                REINICIAR.</h4>
        </div> --}}

        <div id="message">
            @if (session('status'))
                <div class="alert alert-status">
                    <span class="material-symbols-outlined">
                        upload_file
                    </span>
                    <p>{{ session('status') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    <span class="material-symbols-outlined">
                        error
                    </span>
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    <span class="material-symbols-outlined">
                        check_circle
                    </span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
        </div>

        <div id="reader" class="qr-reader"></div>

        <form method="post" action="{{ route('store') }}" id="qrcode-form">
            @csrf
            <input type="hidden" id="qrcode-value" name="qrcode_value">
        </form>

        <div class="container-button">
            <form action="{{ route('qrcodes.clear') }}" method="POST">
                @csrf
                <div id="writer" class="digitar">
                    <button type="submit">Limpar QR Codes</button>
                </div>
            </form>
        </div>

    </div>
    <script>
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 15, // Aumente a FPS se necessário
                qrbox: {
                    width: 250,
                    height: 250
                },
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true // Habilitar features experimentais, se suportadas
                }
            });

        // Função que será chamada ao encontrar um QR Code
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('qrcode-value').value = decodedText;
            html5QrcodeScanner.clear();
            document.getElementById('qrcode-form').submit();
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>

</html>
