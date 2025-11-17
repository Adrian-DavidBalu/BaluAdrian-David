document.addEventListener('DOMContentLoaded', () => {
    const inputActivitate = document.getElementById('inputActivitate');
    const btnAdauga = document.getElementById('btnAdauga');
    const listaActivitati = document.getElementById('listaActivitati');
    const numeLuni = [
        "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
        "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
    ];
    const adaugaActivitate = () => {
        const textActivitate = inputActivitate.value.trim();

        if (textActivitate !== "") {
            const elementNou = document.createElement('li');
            const dataCurenta = new Date();
            const ziua = dataCurenta.getDate();
            const luna = numeLuni[dataCurenta.getMonth()];
            const anul = dataCurenta.getFullYear();
            const dataFormatata = `${ziua} ${luna} ${anul}`;
            elementNou.textContent = `${textActivitate} - adăugată la: ${dataFormatata};`;
            listaActivitati.appendChild(elementNou);
            inputActivitate.value = '';
        } else {
            alert("Vă rugăm introduceți o activitate!");
        }
    };
    btnAdauga.addEventListener('click', adaugaActivitate);
    inputActivitate.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            adaugaActivitate();
        }
    });
});