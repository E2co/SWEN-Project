document.getElementById('submit-btn').addEventListener('click', function(event) {
    // Prevent any default form submission
    event.preventDefault();

    const studentId = document.getElementById('student-id').value;
    
    if (!studentId) {
        alert('Please enter a student ID.');
        return;
    }

    const data = new FormData();
    data.append('id', studentId);

    fetch('Student_fetch.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.text())
    .then(responseText => {
        document.getElementById('student-info').innerHTML = responseText;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching the student information.');
    });
});