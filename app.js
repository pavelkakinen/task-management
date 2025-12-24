// === ROUTING ===
function route() {
    const path = window.location.hash.slice(1) || '/';

    if (path === '/' || path === '/dashboard') {
        showDashboard();
    } else if (path === '/employees') {
        showEmployeeList();
    } else if (path === '/employees/new') {
        showEmployeeForm();
    } else if (path.startsWith('/employees/edit/')) {
        const id = path.split('/')[3];
        showEmployeeForm(id);
    }
}

window.addEventListener('hashchange', route);
window.addEventListener('load', route);

// === DASHBOARD ===
function showDashboard() {
    document.getElementById('app').innerHTML = `
        <h1>Dashboard</h1>
        <p>Welcome to Employee Management</p>
    `;
}

// === EMPLOYEE LIST ===
function showEmployeeList() {
    document.getElementById('app').innerHTML = '<p>Loading...</p>';

    fetch('/api/employees.php')
        .then(response => response.json())
        .then(employees => {
            renderEmployeeList(employees);
        })
        .catch(error => {
            document.getElementById('app').innerHTML = '<p>Error loading employees</p>';
        });
}

function renderEmployeeList(employees) {
    const html = `
        <h2>Employees (${employees.length})</h2>
        <ul class="employee-list">
            ${employees.map(emp => `
                <li>
                    <div data-employee-id="${emp.id}">
                        ${escapeHtml(emp.firstName)} ${escapeHtml(emp.lastName)}
                    </div>
                    - ${escapeHtml(emp.position)}
                    <a href="#/employees/edit/${emp.id}" id="employee-edit-link-${emp.id}" class="edit-link">Edit</a>
                </li>
            `).join('')}
        </ul>
        <p><a href="#/employees/new">Add New Employee</a></p>
    `;

    document.getElementById('app').innerHTML = html;
}

// === EMPLOYEE FORM ===
function showEmployeeForm(id) {
    if (id) {
        // Load employee data
        fetch(`/api/employees.php?id=${id}`)
            .then(response => response.json())
            .then(employee => {
                renderEmployeeForm(employee);
            });
    } else {
        // New employee
        renderEmployeeForm(null);
    }
}

function renderEmployeeForm(employee) {
    const isEdit = employee && employee.id;

    const html = `
        <h2>${isEdit ? 'Edit Employee' : 'Add Employee'}</h2>
        
        <div id="error-block" class="error" style="display: none;"></div>
        
        <form id="employee-form">
            <div>
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" 
                       value="${employee ? escapeHtml(employee.firstName) : ''}" required>
            </div>
            
            <div>
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" 
                       value="${employee ? escapeHtml(employee.lastName) : ''}" required>
            </div>
            
            <div>
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" 
                       value="${employee ? escapeHtml(employee.position) : ''}">
            </div>
            
            <div>
                <button type="submit" name="submitButton" id="submitButton">
                    ${isEdit ? 'Update Employee' : 'Add Employee'}
                </button>
                
                ${isEdit ? '<button type="button" id="deleteButton" name="deleteButton" class="delete">Delete Employee</button>' : ''}
            </div>
        </form>
        
        <p><a href="#/employees">Back to Employee List</a></p>
    `;

    document.getElementById('app').innerHTML = html;

    // Attach event listeners
    document.getElementById('employee-form').addEventListener('submit', (e) => {
        e.preventDefault();
        saveEmployee(employee ? employee.id : null);
    });

    if (isEdit) {
        document.getElementById('deleteButton').addEventListener('click', () => {
            deleteEmployee(employee.id);
        });
    }
}

// === SAVE EMPLOYEE ===
function saveEmployee(id) {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const position = document.getElementById('position').value.trim();

    const data = {
        id: id,
        firstName: firstName,
        lastName: lastName,
        position: position
    };

    fetch('/api/employees.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.error) {
                // Show error
                const errorBlock = document.getElementById('error-block');
                errorBlock.textContent = result.error;
                errorBlock.style.display = 'block';
            } else {
                // Success - redirect to list
                window.location.hash = '#/employees';
            }
        })
        .catch(error => {
            alert('Error saving employee');
        });
}

// === DELETE EMPLOYEE ===
function deleteEmployee(id) {
    fetch('/api/employees.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.hash = '#/employees';
            }
        })
        .catch(error => {
            alert('Error deleting employee');
        });
}

// === HELPER ===
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}