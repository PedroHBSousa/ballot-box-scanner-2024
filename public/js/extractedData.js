// function returnExtractedData(qrCodeResult) {
//     const extractValue = (key, text) => {
//         const regex = new RegExp(`${key}:([^\\s]+)`); // Expressão regular para encontrar a chave e capturar o valor
//         const match = text.match(regex); // Executa a busca
//         return match ? match[1] : null; // Retorna o valor capturado ou null se não encontrado
//     };
//     const extractedData = {
//         BRAN: extractValue("BRAN", qrCodeResult),
//     };
//     console.log(extractedData);
//     return extractedData;
// }
