document.getElementById('fetch-data').addEventListener('click', function() {
    const studentName = document.getElementById('stdname').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Validate input
    if (!studentName || !startDate || !endDate) {
        alert('Please fill in all fields.');
        return;
    }

    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'viewattendance.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Define what happens on successful data submission
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const tableBody = document.querySelector('#attendance-table tbody');
            tableBody.innerHTML = ''; // Clear previous results

            // Populate the table with the fetched data
            response.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.student_id}</td>
                    <td>${record.name}</td>
                    <td>${record.date}</td>
                    <td>${record.status}</td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            alert('Error fetching data. Please try again.');
        }
    };

    // Send the request with the data
    xhr.send(`studentName=${encodeURIComponent(studentName)}&startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}`);
});
