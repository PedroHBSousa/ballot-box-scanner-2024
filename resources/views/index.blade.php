<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="./js/extractedData.js"></script>
    <div style="text-align: center;">
        <div id="reader" style="width: 500px;"></div>
        <div id="show" style="display: none;">
            <h4>Scanned Result</h4>
            <p style="color: blue;" id="result"></p>
        </div>
    </div>
    <script>
        const html5Qrcode = new Html5Qrcode('reader');
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (decodedText) {
                document.getElementById('show').style.display = 'block';
                document.getElementById('result').textContent = decodedText;
                // html5Qrcode.stop();
            }
        }
        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        }
        html5Qrcode.start({
            facingMode: "environment"
        }, config, qrCodeSuccessCallback);
    </script>
</body>

</html>
