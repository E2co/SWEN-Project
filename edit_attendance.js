document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('classButton').addEventListener('click', function() {
        const classbutton = document.getElementById("classButton");
        classbutton.style.display = "none";

        const submitbutton = document.getElementById("submitButton");
        submitbutton.style.display = "flex";
        
        const attendanceTable = document.getElementById("attendanceRegister");
        attendanceTable.style.display = 'table';

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

    document.getElementById('submitButton').addEventListener('click', function() {
        const attendanceData = {};
        const checkboxes = document.querySelectorAll('.present-checkbox, .absent-checkbox');

        checkboxes.forEach(checkbox => {
            const studentId = checkbox.name.split('_')[1];
            if(checkbox.classList.contains('present-checkbox') && checkbox.checked){
                attendanceData[studentId] = 'present';
            } else if(checkbox.classList.contains('absent-checkbox') && checkbox.checked){
                attendanceData[studentId] = 'absent';
            }
        });

        fetch('updt_stdnt_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(attendanceData)
        })
        .then(response => {
            if(!response.ok){
                throw new Error("Failed to update attendance");
            }
            return response.json();
        })
        .then(data => {
            console.log('Attendance updated successfully: ', data);
        })
        .catch(error => {
            console.error('Error updating attendance: ', error);
        });
    });
});