<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Catalog de Studenți</title>
</head>
<body>
    <h1>CATALOG DE STUDENȚI</h1>
    <section>
        <h2>Adaugă Student Nou</h2>
        <form id="addStudentForm">
            <label for="nume">Nume:</label><br>
            <input type="text" id="nume" name="nume" required><br><br>
            <label for="an_studiu">An de Studiu (1-4):</label><br>
            <input type="number" id="an_studiu" name="an_studiu" min="1" max="4" required><br><br>
            <label for="medie">Media Generală:</label><br>
            <input type="number" id="medie" name="medie" step="0.01" min="1" max="10" required><br><br>
            <button type="submit">Adaugă Student</button>
        </form>
        <p id="formMessage" style="color: red;"></p>
    </section>
    <section>
        <h2>Lista Studenților</h2>
        <table id="studentTable" border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nume</th>
                    <th>An Studiu</th>
                    <th>Media</th>
                </tr>
            </thead>
            <tbody id="studentList">
                <tr><td colspan="3">Se încarcă lista...</td></tr>
            </tbody>
        </table>
    </section>

    <script>
        const API_URL = 'api_studenti.php';
        const studentList = document.getElementById('studentList');
        const form = document.getElementById('addStudentForm');
        const formMessage = document.getElementById('formMessage');

        // 1. AFISARE LISTĂ (FETCH GET)
        function fetchStudents() {
            fetch(API_URL)
                .then(response => response.json())
                .then(data => {
                    studentList.innerHTML = '';
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(student => {
                            const row = studentList.insertRow();
                            row.insertCell().textContent = student.nume;
                            row.insertCell().textContent = student.an_studiu;
                            row.insertCell().textContent = parseFloat(student.medie).toFixed(2);
                        });
                    } else if (data.success) {
                        studentList.innerHTML = '<tr><td colspan="3">Nu există studenți înregistrați.</td></tr>';
                    } else {
                        studentList.innerHTML = `<tr><td colspan="3">Eroare la încărcarea datelor: ${data.message}</td></tr>`;
                    }
                })
                .catch(error => {
                    studentList.innerHTML = `<tr><td colspan="3">Eroare de rețea: ${error.message}</td></tr>`;
                });
        }

        // 2. ADĂUGARE STUDENT (FETCH POST FĂRĂ REÎMPROSPĂTARE)
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const studentData = Object.fromEntries(formData.entries());
            formMessage.textContent = 'Se adaugă...';
            
            fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(studentData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formMessage.style.color = 'green';
                    formMessage.textContent = 'Succes: ' + data.message;
                    form.reset();
                    
                    // 4. ACTUALIZAREA LISTEI ÎN TIMP REAL
                    fetchStudents(); 
                } else {
                    formMessage.style.color = 'red';
                    formMessage.textContent = 'Eroare: ' + data.message;
                }
            })
            .catch(error => {
                formMessage.style.color = 'red';
                formMessage.textContent = 'Eroare de rețea la adăugare: ' + error.message;
            });
        });

        // ÎNCĂRCARE LISTĂ SPRE INIȚIALIZARE
        document.addEventListener('DOMContentLoaded', fetchStudents);

    </script>
</body>
</html>