<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <link rel="icon" href="{{ asset('graphicon.svg') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('graphicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/main-menu.css') <!-- Adapte esse caminho se necessário -->
</head>

<body>
    <header>
        <div class="text-container">
            <h1>APURAÇÃO ELEITORAL<span>SÃO SEBASTIÃO</span></h1>
            <h2 class="text-container-title">2024</h2>
        </div>

        <div class="container-image">
            <img id="felipe" src="{{ Vite::asset('resources/img/Felipe.png') }}">
            <img id="reis" src="{{ Vite::asset('resources/img/Reis.png') }}">
            <img id="reinaldinho" src="{{ Vite::asset('resources/img/Reinaldinho.png') }}">
        </div>
    </header>

    <main>
        <div class="menu-container">
            <a href="{{ route('dashboard') }}" target="_blank">
                <button>Dashboard</button>
            </a>
            <a href="{{ route('insert') }}" target="_blank">
                <button>Insert</button>
            </a>
            <a href="{{ route('qrcodescanner') }}" target="_blank">
                <button>QR Code</button>
            </a>
            
        </div>
    </main>

    <script>
        // Redireciona para o dashboard
        document.getElementById('dashboard-btn').onclick = function () {
            window.location.href = "{{ route('dashboard') }}";
        };

        // Redireciona para inserir dados
        document.getElementById('insert-btn').onclick = function () {
            window.location.href = "{{ route('insert') }}";
        };

        // Redireciona para o scanner QRCode
        document.getElementById('scanner-btn').onclick = function () {
            window.location.href = "{{ route('qrcodescanner') }}";
        };

    </script>

    <footer>
        <h1 class="footer-title">Vai ser ainda melhor.</h1>
    </footer>

</body>

</html>