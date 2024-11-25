document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('classButton').addEventListener('click', function() {

        //const classbutton = document.getElementById("classButton");
        //classbutton.style.display = "none";
        const attendanceTable = document.getElementById("attendanceRegister");
        attendanceTable.style.display = "table";

        fetch('get_student.php')
            .then(response => {
                if(!response.ok){
                    throw new Error("Response was not ok");
                }
                return response.json();
            })
            .then((students) => {

                const studentList = document.getElementById("studentList");
                studentList.innerHTML = "";

                students.forEach((student) => {
                    const row = document.createElement("tr");

                    const nameCell = document.createElement("td");
                    nameCell.textContent = student.name;
                    row.appendChild(nameCell);

                    const presentCell = document.createElement("td");
                    const presentCheckbox = document.createElement("input");
                    presentCheckbox.type = "checkbox";
                    presentCheckbox.name = `present_${student.id}`;
                    presentCheckbox.classList.add("present-checkbox");
                    presentCell.appendChild(presentCheckbox);
                    row.appendChild(presentCell);

                    const absentCell = document.createElement("td");
                    const absentCheckbox = document.createElement("input");
                    absentCheckbox.type = "checkbox";
                    absentCheckbox.name = `absent_${student.id}`;
                    absentCheckbox.classList.add("absent-checkbox");
                    absentCell.appendChild(absentCheckbox);
                    row.appendChild(absentCell);

                    studentList.appendChild(row);

                    presentCheckbox.addEventListener("change", () => {
                        if(presentCheckbox.checked) absentCheckbox.checked = false;
                    });

                    absentCheckbox.addEventListener("change", () => {
                        if(absentCheckbox.checked) presentCheckbox.checked = false;
                    });
                });
            });
            //.catch(error => {
            //    console.error('Error fetching student data: ', error);
            //});
    });
});
    