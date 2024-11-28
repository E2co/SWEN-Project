document.getElementById('fetch-data').addEventListener('click', function() {
    const studentID = document.getElementById('student-id').value;
    const grade = document.getElementById('grade').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    //send to PHP script
    const form_data=new FormData();
    if(studentID) form_data.append('student_id', studentID);
    if(grade) form_data.append('grade', grade);
    if(startDate) form_data.append('start-date', startDate);
    if(endDate) form_data.append('end-date', endDate);

    //send to PHP
    fetch('viewattendance.php', {
        method:'POST',
        body:form_data
    })
    .then(response=>response.json())
    .then(data=> {
        const tableBody= document.getElementById('attendance-table').getElementsByTagName('tbody')[0];
        tableBody.innerHTML= '';
        if(data.length>0){
            data.forEach(row=> {
                let tr=document.createElement('tr');
                let tdstudentID=document.createElement('td');
                tdstudentID.textContent=row.student_id
                tr.appendChild(tdstudentID);

                let tdDate=document.createElement('td');
                tdDate.textContent=row.date;
                tr.appendChild(tdDate);

                let tdstatus=document.createElement('td');
                tdstatus.textContent=row.status;
                tr.appendChild(tdstatus);

                let tdgrade=document.createElement('td');
                tdgrade.textContent=row.grade;
                tr.appendChild(tdgrade);
                
                tableBody.appendChild(tr);
            });
        }else{
            let tr=document.createElement('tr');
            tr.innerHTML=`<td colspan="4">No records were found</td>`;
            tableBody.appendChild(tr);
        }
    })
    .catch(error=> console.error('Error:',error));
});