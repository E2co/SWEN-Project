    function populateStudentSelect(students) {
       
        const studentSelect = document.getElementById("studentId");
        studentSelect.innerHTML = '<option value="">Select Student</option>';  

        students.forEach(student => {
            const option = document.createElement("option");
            option.value = student.id;  
            option.textContent = student.name;  
            studentSelect.appendChild(option);  
        });}

            window.onload=fetchStudents;
    function fetchStudents() {
        fetch('get_student.php')  
            .then(response => response.json()) 
            .then(students => populateStudentSelect(students))  
            .catch(error => console.error('Error fetching students:', error));  
}
window.onload=fetchStudents;
    function fetchAuditLogs() {
        const selectedgrade=document.getElementById('grade');
        const selectedstudentId=document.getElementById('studentId');
        const selectedmonth=document.getElementById('month');
        const selectedyear=document.getElementById('year');

        const selectedgradeval= selectedgrade.value;
        const selectedgradestudentIdval= selectedstudentId.value;
        const selectedmonthval= selectedmonth.value;
        const selectedyearval = selectedyear.value;

        if (selectedgradeval || selectedgradestudentIdval || selectedmonthval|| selectedyearval) {
            const formData = new FormData();
            formData.append('selectedgradeval', selectedgradeval);
            formData.append('selectedgradestudentIdval', selectedgradestudentIdval);
            formData.append('selectedmonthval', selectedmonthval);
            formData.append('selectedyearval', selectedyearval);
            

            
            fetch('generate_report.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('auditLogsBody');
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9">No audit records available.</td></tr>';
                } else {
                    data.forEach(log => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${log.name}</td>
                            <td>${log.grade}</td>
                            <td>${log.totalPresent}</td>
                            <td>${log.totalAbsent}</td>
                            <td>${log.totalLate}</td>
                            <td>${log.attendancePercentage}</td>       
                            <td>${log.absenceDates}</td>
                            <td>${log.lateDates}</td>
                            
                        `;
                        tbody.appendChild(row);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            alert('Please select an item')
        }}
    
    

       
    

