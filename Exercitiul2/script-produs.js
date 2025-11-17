document.addEventListener('DOMContentLoaded', () => {
    const detaliiDiv = document.getElementById('detalii');
    const dataSpan = document.getElementById('dataProdus');
    const btnDetalii = document.getElementById('btnDetalii');
    const numeLuni = [
        "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
        "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
    ];
    detaliiDiv.classList.add('ascuns'); 
    const dataCurenta = new Date();
    const ziua = dataCurenta.getDate();
    const luna = numeLuni[dataCurenta.getMonth()]; 
    const anul = dataCurenta.getFullYear();
    const dataFormatata = `${ziua} ${luna} ${anul}`;
    dataSpan.textContent = dataFormatata;
    const comutaDetalii = () => {
        detaliiDiv.classList.toggle('ascuns');
        if (detaliiDiv.classList.contains('ascuns')) {
            btnDetalii.textContent = "Afișează detalii";
        } else {
            btnDetalii.textContent = "Ascunde detalii";
        }
    };
    btnDetalii.addEventListener('click', comutaDetalii);
});