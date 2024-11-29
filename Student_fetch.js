document.addEventListener('DOMContentLoaded', () => {
    const studentIdInput = document.getElementById('student-id');
    const submitBtn = document.getElementById('submit-btn');
    const studentInfoDiv = document.getElementById('student-info');

    submitBtn.addEventListener('click', fetchStudentInfo);

    function fetchStudentInfo() {
        const studentId = studentIdInput.value.trim();

        if (!studentId) {
            alert('Please enter a Student ID');
            return;
        }

        fetch('Student_fetch.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `student_id=${studentId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Create form for student and parent information
            studentInfoDiv.innerHTML = `
                <form id="student-update-form">
                    <h3>Student Information</h3>
                    <div>
                        <input type="hidden" name="student_id" class="search-field" value="${data.student.id}">
                        <label>Name: </label> 
                        <input type="text" name="student_name" value="${data.student.name}"><br>
                        <label>Grade: </label> 
                        <input type="number" name="student_grade" value="${data.student.grade}">

                        <h3>Parent Information</h3>
                        <input type="hidden" name="parent_student_id" value="${data.parent.student_id}">
                        <label>Parent Name: </label>
                        <input type="text" name="parent_name" value="${data.parent.parent_name}"><br>
                        <label>Email: </label>
                        <input type="email" name="parent_email" value="${data.parent.email}">
                        <label>Telephone: </label> 
                        <input type="tel" name="parent_telephone" value="${data.parent.telephone_number}">

                        <button type="button" id="update-btn">Update Information</button>
                    </div>
                </form>
            `;

            // Add event listener for update button
            document.getElementById('update-btn').addEventListener('click', updateStudentInfo);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching student information');
        });
    }

    function updateStudentInfo() {
        const form = document.getElementById('student-update-form');
        const formData = new FormData(form);

        fetch('Student_update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Student information updated successfully');
            } else {
                alert(data.error || 'Failed to update student information');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating student information');
        });
    }
    
});