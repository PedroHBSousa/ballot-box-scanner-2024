// document.addEventListener("DOMContentLoaded", function () {
//     const html5Qrcode = new Html5Qrcode("reader");
//     const qrCodeSuccessCallback = (decodedText, decodedResult) => {
//         if (decodedText) {
//             document.getElementById("show").style.display = "block";
//             document.getElementById("result").textContent =
//                 returnExtractedData(decodedText);
//             // html5Qrcode.stop();
//         }
//     };
//     const config = {
//         fps: 10,
//         qrbox: {
//             width: 250,
//             height: 250,
//         },
//     };
//     html5Qrcode.start(
//         {
//             facingMode: "environment",
//         },
//         config,
//         qrCodeSuccessCallback
//     );
// });
