document.addEventListener('DOMContentLoaded', function() {
    // Call fetchStudents function when the page is loaded
    fetchStudents();

    // Fetch students and populate the student select dropdown
    function fetchStudents() {
        fetch('get_student.php')  // Make an API request to get_student.php
            .then(response => response.json())  // Parse the JSON response
            .then(students => populateStudentSelect(students))  // Call the function to populate the dropdown
            .catch(error => console.error('Error fetching students:', error));  // Error handling
    }

    //function to populate the student dropdown menu
    function populateStudentSelect(students) {
        const studentSelect = document.getElementById("studentId");
        studentSelect.innerHTML = '<option value="">Select Student</option>';  // Reset select options

        students.forEach(student => {
            const option = document.createElement("option");
            option.value = student.id;  // Set the value of the option to student id
            option.textContent = student.name;  // Set the text content of the option to student name
            studentSelect.appendChild(option);  // Append the option to the select dropdown
        });
    }

    // Fetch the attendance report based on selected filters (using POST)
    function fetchAttendanceReport(grade, studentId, month, year) {
        const url = "generate_report.php";  // URL of the PHP file that will generate the report
        const data = {
            grade: grade,
            student_id: studentId,
            month: month,
            year: year
        };

        console.log("Request Data:", data);  // Debugging line to check the data being sent

        fetch(url, {
            method: 'POST',  // Change to POST method
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'  // Set the content type for POST
            },
            body: new URLSearchParams(data).toString()  // Convert the data to a URL-encoded string
        })
        .then(response => response.json())  // Parse the JSON response
        .then(data => displayAttendanceReport(data))  // Call the function to display the report
        .catch(error => console.error('Error fetching attendance report:', error));  // Error handling
    }

    // Display the attendance report in a table
    function displayAttendanceReport(data) {
        const reportTable = document.getElementById("attendanceReportTable");
        const reportBody = document.getElementById("attendanceReportBody");

        // Clear any previous data
        reportBody.innerHTML = '';

        if (data.length === 0) {
            reportBody.innerHTML = '<tr><td colspan="4">No data found.</td></tr>';
        } else {
            data.forEach(student => {
                const row = document.createElement("tr");

                const nameCell = document.createElement("td");
                nameCell.textContent = student.name;
                row.appendChild(nameCell);

                const presentCell = document.createElement("td");
                presentCell.textContent = student.totalp;  // Total Present
                row.appendChild(presentCell);

                const absentCell = document.createElement("td");
                absentCell.textContent = student.totala;  // Total Absent
                row.appendChild(absentCell);

                const percCell = document.createElement("td");
                percCell.textContent = student.perc.toFixed(2) + '%';  // Attendance percentage
                row.appendChild(percCell);

                reportBody.appendChild(row);  // Append the row to the table body
            });
        }

        // Show the report table
        reportTable.style.display = 'table';
    }
    
    // Handle form submission for generating the report
    document.getElementById("generateReportBtn").addEventListener("click", function() {
        const grade = document.getElementById("grade").value;
        const studentId = document.getElementById("studentId").value;
        const month = document.getElementById("month").value;
        const year = document.getElementById("year").value;

        // Call function to fetch attendance report based on selected filters
        fetchAttendanceReport(grade, studentId, month, year);
    });

});





