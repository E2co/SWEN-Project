document.addEventListener('DOMContentLoaded', function() {
    const classbutton = document.getElementById("classButton");
    const submitbutton = document.getElementById("submitButton");
    const attendanceTable = document.getElementById("attendanceRegister");
    const studentList = document.getElementById("studentList");
    
    classbutton.addEventListener('click', function() {
        classbutton.style.display = "none";
        submitbutton.style.display = "inline-block";
        attendanceTable.style.display = 'table';
        submitbutton.disabled = true;

        fetch('get_student.php')
            .then(response => {
                if(!response.ok){
                    throw new Error("Response was not ok");
                }
                return response.json();
            })
            .then((students) => {

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
                        if(presentCheckbox.checked){
                            absentCheckbox.checked = false;
                            checkSubmitButtonState();
                        } 
                    });

                    absentCheckbox.addEventListener("change", () => {
                        if(absentCheckbox.checked){
                            presentCheckbox.checked = false;
                            checkSubmitButtonState();
                        } 
                    });
                });
            });
    });

    function checkSubmitButtonState(){
        const checkboxes = document.querySelectorAll('.present-checkbox, .absent-checkbox');
        const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
        submitbutton.disabled = checkedBoxes.length === 0;
    }

    submitbutton.addEventListener('click', function() {
        const attendanceData = {};
        const checkboxes = document.querySelectorAll('.present-checkbox, .absent-checkbox');

        checkboxes.forEach(checkbox => {
            const studentId = checkbox.name.split('_')[1];
            if(checkbox.checked){
                attendanceData[studentId] = checkbox.classList.contains('present-checkbox') ? 'present' : 'absent';
            }
        });

        fetch('updt_stdnt_attendance.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(attendanceData)
        })
        .then(response => {
            if(!response.ok){
                throw new Error("Failed to update attendance");
            }
            return response.json();
        })
        .then(data => {
            alert('Attendance updated successfully!')

            classbutton.style.display = 'inline-block';
            submitbutton.style.display = 'none';
            attendanceTable.style.display = 'none'
            studentList.innerHTML = '';
            submitbutton.disabled = true;
        })
        .catch(error => {
            console.error('Error updating attendance: ', error);
            alert('Failed to update attendance');
        });
    }); 
});