function fetchAuditLogs() {
    fetch('fetch_audit.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('auditLogsBody');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3">No audit records available.</td></tr>';
            } else {
                data.forEach(log => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${log.student_id}</td>
                        <td>${log.operation}</td>
                        <td>${log.changed_at}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching audit logs:', error);
        });
}

window.onload = fetchAuditLogs;