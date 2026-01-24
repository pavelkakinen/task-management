// === STATE ===
let csrfToken = null;
let isAuthenticated = false;
let currentUser = null;

// === INITIALIZATION ===
async function init() {
    await checkAuthStatus();
    route();
}

async function checkAuthStatus() {
    try {
        const response = await fetch('/api/auth.php?action=status', {
            credentials: 'include'
        });
        const data = await response.json();
        csrfToken = data.csrfToken;
        isAuthenticated = data.authenticated;
        currentUser = data.user;
        updateAuthUI();
    } catch (error) {
        console.error('Failed to check auth status:', error);
    }
}

function updateAuthUI() {
    const authLink = document.getElementById('auth-link');
    if (authLink) {
        if (isAuthenticated) {
            authLink.textContent = `Logout (${currentUser.username})`;
            authLink.href = '#/logout';
        } else {
            authLink.textContent = 'Login';
            authLink.href = '#/login';
        }
    }
}

// === API HELPERS ===
async function apiGet(url) {
    const response = await fetch(url, {
        credentials: 'include'
    });
    return response.json();
}

async function apiPost(url, data) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken || ''
        },
        credentials: 'include',
        body: JSON.stringify(data)
    });
    return { response, data: await response.json() };
}

async function apiDelete(url, data) {
    const response = await fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken || ''
        },
        credentials: 'include',
        body: JSON.stringify(data)
    });
    return { response, data: await response.json() };
}

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
    } else if (path === '/positions') {
        showPositionList();
    } else if (path === '/positions/new') {
        showPositionForm();
    } else if (path.startsWith('/positions/edit/')) {
        const id = path.split('/')[3];
        showPositionForm(id);
    } else if (path === '/tasks') {
        showTaskList();
    } else if (path === '/tasks/new') {
        showTaskForm();
    } else if (path.startsWith('/tasks/edit/')) {
        const id = path.split('/')[3];
        showTaskForm(id);
    } else if (path === '/login') {
        showLoginForm();
    } else if (path === '/logout') {
        handleLogout();
    }
}

window.addEventListener('hashchange', route);
window.addEventListener('load', init);

// === DASHBOARD ===
function showDashboard() {
    document.getElementById('app').innerHTML = '<p>Loading...</p>';

    apiGet('/api/dashboard.php')
        .then(employees => {
            renderDashboard(employees);
        })
        .catch(error => {
            document.getElementById('app').innerHTML = '<p>Error loading dashboard</p>';
        });
}

function renderDashboard(employees) {
    const html = `
        <h1>Dashboard</h1>
        <div class="dashboard-list">
            ${employees.map(emp => `
                <div class="dashboard-card">
                    <div class="dashboard-picture">
                        ${emp.picture
                            ? `<img src="/uploads/${emp.picture}" alt="${escapeHtml(emp.firstName)}">`
                            : `<div class="no-picture dashboard">${escapeHtml(emp.firstName.charAt(0))}${escapeHtml(emp.lastName.charAt(0))}</div>`
                        }
                    </div>
                    <div class="dashboard-info">
                        <div class="dashboard-header">
                            <div class="dashboard-name-section">
                                <h3 class="dashboard-name">${escapeHtml(emp.firstName)} ${escapeHtml(emp.lastName)}</h3>
                                <p class="dashboard-position">${emp.positionTitle ? escapeHtml(emp.positionTitle) : '<em>No position</em>'}</p>
                            </div>
                            <div class="dashboard-tasks">
                                ${emp.tasks.length > 0 ? `
                                    <ul class="dashboard-task-list">
                                        ${emp.tasks.map(task => `
                                            <li class="${task.isCompleted ? 'completed' : 'pending'}">
                                                <span class="task-icon">${task.isCompleted ? '&#10003;' : '&#9675;'}</span>
                                                ${escapeHtml(task.description)}
                                            </li>
                                        `).join('')}
                                    </ul>
                                ` : '<p class="no-tasks">No tasks assigned</p>'}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    document.getElementById('app').innerHTML = html;
}

// === LOGIN ===
function showLoginForm() {
    if (isAuthenticated) {
        window.location.hash = '#/';
        return;
    }

    document.getElementById('app').innerHTML = `
        <h2>Login</h2>

        <div id="error-block" class="error" style="display: none;"></div>

        <form id="login-form">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <button type="submit" id="submitButton">Login</button>
            </div>
        </form>
    `;

    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        await handleLogin();
    });
}

async function handleLogin() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;

    try {
        const { response, data } = await apiPost('/api/auth.php?action=login', {
            username,
            password
        });

        if (data.success) {
            csrfToken = data.csrfToken;
            isAuthenticated = true;
            currentUser = data.user;
            updateAuthUI();
            window.location.hash = '#/';
        } else {
            const errorBlock = document.getElementById('error-block');
            errorBlock.textContent = data.error || 'Login failed';
            errorBlock.style.display = 'block';
        }
    } catch (error) {
        alert('Error during login');
    }
}

async function handleLogout() {
    try {
        await apiPost('/api/auth.php?action=logout', {});
        isAuthenticated = false;
        currentUser = null;
        csrfToken = null;
        await checkAuthStatus();
        window.location.hash = '#/';
    } catch (error) {
        console.error('Logout failed:', error);
    }
}

// === POSITION LIST ===
function showPositionList() {
    document.getElementById('app').innerHTML = '<p>Loading...</p>';

    apiGet('/api/positions.php')
        .then(positions => {
            renderPositionList(positions);
        })
        .catch(error => {
            document.getElementById('app').innerHTML = '<p>Error loading positions</p>';
        });
}

function renderPositionList(positions) {
    const html = `
        <h2>Positions (${positions.length})</h2>
        <ul class="position-list">
            ${positions.map(pos => `
                <li>
                    <span class="col-name" data-position-id="${pos.id}">${escapeHtml(pos.title)}</span>
                    <span class="col-count">${pos.employeeCount} employee${pos.employeeCount !== 1 ? 's' : ''}</span>
                    <div class="col-actions">
                        <a href="#/positions/edit/${pos.id}" id="position-edit-link-${pos.id}" class="edit-link">Edit</a>
                        ${pos.employeeCount === 0
                            ? `<button type="button" class="delete-btn" data-id="${pos.id}">Delete</button>`
                            : `<button type="button" class="delete-btn disabled" disabled title="Cannot delete: has employees">Delete</button>`
                        }
                    </div>
                </li>
            `).join('')}
        </ul>
        <p><a href="#/positions/new">Add Position</a></p>
    `;

    document.getElementById('app').innerHTML = html;

    // Attach delete handlers
    document.querySelectorAll('.delete-btn:not(.disabled)').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = parseInt(btn.dataset.id);
            if (confirm('Are you sure you want to delete this position?')) {
                await deletePosition(id);
            }
        });
    });
}

// === POSITION FORM ===
function showPositionForm(id) {
    if (id) {
        apiGet(`/api/positions.php?id=${id}`)
            .then(position => {
                renderPositionForm(position);
            });
    } else {
        renderPositionForm(null);
    }
}

function renderPositionForm(position) {
    const isEdit = position && position.id;
    const hasEmployees = position && position.employeeCount > 0;

    const html = `
        <h2>${isEdit ? 'Edit Position' : 'Add Position'}</h2>

        <div id="error-block" class="error" style="display: none;"></div>

        <form id="position-form">
            <div>
                <label for="title">Position Title:</label>
                <input type="text" id="title" name="title"
                       value="${position ? escapeHtml(position.title) : ''}"
                       minlength="2" maxlength="100" required>
            </div>

            ${isEdit ? `<p class="info-text">Employees in this position: <strong>${position.employeeCount}</strong></p>` : ''}

            <div>
                <button type="submit" name="submitButton" id="submitButton">
                    ${isEdit ? 'Update Position' : 'Add Position'}
                </button>

                ${isEdit && !hasEmployees ? '<button type="button" id="deleteButton" name="deleteButton" class="delete">Delete Position</button>' : ''}
                ${isEdit && hasEmployees ? '<button type="button" class="delete" disabled title="Cannot delete: has employees">Delete Position</button>' : ''}
            </div>
        </form>

        <p><a href="#/positions">Back to Position List</a></p>
    `;

    document.getElementById('app').innerHTML = html;

    document.getElementById('position-form').addEventListener('submit', (e) => {
        e.preventDefault();
        savePosition(position ? position.id : null);
    });

    if (isEdit && !hasEmployees) {
        document.getElementById('deleteButton').addEventListener('click', async () => {
            if (confirm('Are you sure you want to delete this position?')) {
                await deletePosition(position.id);
            }
        });
    }
}

// === SAVE POSITION ===
async function savePosition(id) {
    const title = document.getElementById('title').value.trim();

    const data = { id, title };

    try {
        const { response, data: result } = await apiPost('/api/positions.php', data);

        if (result.error) {
            const errorBlock = document.getElementById('error-block');
            errorBlock.textContent = result.error;
            errorBlock.style.display = 'block';
        } else {
            window.location.hash = '#/positions';
        }
    } catch (error) {
        alert('Error saving position');
    }
}

// === DELETE POSITION ===
async function deletePosition(id) {
    try {
        const { response, data: result } = await apiDelete('/api/positions.php', { id });

        if (result.success) {
            window.location.hash = '#/positions';
        }
    } catch (error) {
        alert('Error deleting position');
    }
}

// === EMPLOYEE LIST ===
function showEmployeeList() {
    document.getElementById('app').innerHTML = '<p>Loading...</p>';

    apiGet('/api/employees.php')
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
                    <div class="col-picture">
                        ${emp.picture
                            ? `<img src="/uploads/${emp.picture}" alt="${escapeHtml(emp.firstName)}">`
                            : `<div class="no-picture">${escapeHtml(emp.firstName.charAt(0))}${escapeHtml(emp.lastName.charAt(0))}</div>`
                        }
                    </div>
                    <span class="col-name" data-employee-id="${emp.id}">${escapeHtml(emp.firstName)} ${escapeHtml(emp.lastName)}</span>
                    <span class="col-position">${emp.positionTitle ? escapeHtml(emp.positionTitle) : '<em>No position</em>'}</span>
                    <span class="col-task-count">${emp.taskCount} task${emp.taskCount !== 1 ? 's' : ''}</span>
                    <div class="col-actions">
                        <a href="#/employees/edit/${emp.id}" id="employee-edit-link-${emp.id}" class="edit-link">Edit</a>
                    </div>
                </li>
            `).join('')}
        </ul>
        <p><a href="#/employees/new">Add New Employee</a></p>
    `;

    document.getElementById('app').innerHTML = html;
}

// === EMPLOYEE FORM ===
function showEmployeeForm(id) {
    document.getElementById('app').innerHTML = '<p>Loading...</p>';

    // Load positions for dropdown
    apiGet('/api/employees.php?positions=1')
        .then(positions => {
            if (id) {
                apiGet(`/api/employees.php?id=${id}`)
                    .then(employee => {
                        renderEmployeeForm(employee, positions);
                    });
            } else {
                renderEmployeeForm(null, positions);
            }
        })
        .catch(error => {
            document.getElementById('app').innerHTML = '<p>Error loading form</p>';
        });
}

function renderEmployeeForm(employee, positions) {
    const isEdit = employee && employee.id;

    const html = `
        <h2>${isEdit ? 'Edit Employee' : 'Add Employee'}</h2>

        <div id="error-block" class="error" style="display: none;"></div>
        <div id="message-block" class="success" style="display: none;"></div>

        ${isEdit ? `
        <div class="picture-section">
            <div class="picture-preview">
                ${employee.picture
                    ? `<img src="/uploads/${employee.picture}" alt="${escapeHtml(employee.firstName)}" id="picturePreview">`
                    : `<div class="no-picture large" id="picturePreview">${escapeHtml(employee.firstName.charAt(0))}${escapeHtml(employee.lastName.charAt(0))}</div>`
                }
            </div>
            <div class="picture-upload">
                <input type="file" id="pictureInput" accept="image/jpeg,image/png,image/gif,image/webp" style="display: none;">
                <button type="button" id="uploadButton" class="upload-btn">Upload Picture</button>
                <p class="upload-hint">JPG, PNG, GIF or WebP. Max 2MB.</p>
            </div>
        </div>
        ` : ''}

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
                <label for="positionId">Position:</label>
                <select id="positionId" name="positionId">
                    <option value="">-- No Position --</option>
                    ${positions.map(pos => `
                        <option value="${pos.id}" ${employee && employee.positionId == pos.id ? 'selected' : ''}>
                            ${escapeHtml(pos.title)}
                        </option>
                    `).join('')}
                </select>
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

    document.getElementById('employee-form').addEventListener('submit', (e) => {
        e.preventDefault();
        saveEmployee(employee ? employee.id : null);
    });

    if (isEdit) {
        document.getElementById('deleteButton').addEventListener('click', () => {
            deleteEmployee(employee.id);
        });

        // Picture upload handling
        const uploadButton = document.getElementById('uploadButton');
        const pictureInput = document.getElementById('pictureInput');

        uploadButton.addEventListener('click', () => {
            pictureInput.click();
        });

        pictureInput.addEventListener('change', async () => {
            const file = pictureInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('picture', file);
            formData.append('employeeId', employee.id);
            formData.append('csrf_token', csrfToken || '');

            try {
                uploadButton.disabled = true;
                uploadButton.textContent = 'Uploading...';

                const response = await fetch('/api/upload.php', {
                    method: 'POST',
                    credentials: 'include',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Update preview
                    const preview = document.getElementById('picturePreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = '/uploads/' + result.picture + '?t=' + Date.now();
                    } else {
                        preview.outerHTML = `<img src="/uploads/${result.picture}" alt="Employee" id="picturePreview">`;
                    }
                    const messageBlock = document.getElementById('message-block');
                    messageBlock.textContent = 'Picture uploaded successfully';
                    messageBlock.style.display = 'block';
                    setTimeout(() => { messageBlock.style.display = 'none'; }, 3000);
                } else {
                    const errorBlock = document.getElementById('error-block');
                    errorBlock.textContent = result.error || 'Upload failed';
                    errorBlock.style.display = 'block';
                }
            } catch (error) {
                alert('Error uploading picture');
            } finally {
                uploadButton.disabled = false;
                uploadButton.textContent = 'Upload Picture';
                pictureInput.value = '';
            }
        });
    }
}

// === SAVE EMPLOYEE ===
async function saveEmployee(id) {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const positionIdValue = document.getElementById('positionId').value;
    const positionId = positionIdValue ? parseInt(positionIdValue) : null;

    const data = { id, firstName, lastName, positionId };

    try {
        const { response, data: result } = await apiPost('/api/employees.php', data);

        if (result.error) {
            const errorBlock = document.getElementById('error-block');
            errorBlock.textContent = result.error;
            errorBlock.style.display = 'block';
        } else {
            window.location.hash = '#/employees';
        }
    } catch (error) {
        alert('Error saving employee');
    }
}

// === DELETE EMPLOYEE ===
async function deleteEmployee(id) {
    try {
        const { response, data: result } = await apiDelete('/api/employees.php', { id });

        if (result.success) {
            window.location.hash = '#/employees';
        }
    } catch (error) {
        alert('Error deleting employee');
    }
}

// === TASK LIST ===
function showTaskList() {
    document.getElementById('app').innerHTML = '<p>Loading...</p>';

    apiGet('/api/tasks.php')
        .then(tasks => {
            renderTaskList(tasks);
        })
        .catch(error => {
            document.getElementById('app').innerHTML = '<p>Error loading tasks</p>';
        });
}

function renderTaskList(tasks) {
    const html = `
        <h2>Tasks (${tasks.length})</h2>
        <ul class="task-list">
            ${tasks.map(task => `
                <li>
                    <span class="col-description" data-task-id="${task.id}">
                        <span class="task-status ${task.isCompleted ? 'completed' : 'pending'}">
                            ${task.isCompleted ? '&#10003;' : '&#9675;'}
                        </span>
                        ${escapeHtml(task.description)}
                    </span>
                    <span class="col-assigned">
                        ${task.employeeFirstName ?
                            `${escapeHtml(task.employeeFirstName)} ${escapeHtml(task.employeeLastName)}` :
                            '<em>Unassigned</em>'}
                    </span>
                    <div class="col-actions">
                        <a href="#/tasks/edit/${task.id}" id="task-edit-link-${task.id}" class="edit-link">Edit</a>
                    </div>
                </li>
            `).join('')}
        </ul>
        <p><a href="#/tasks/new">Add New Task</a></p>
    `;

    document.getElementById('app').innerHTML = html;
}

// === TASK FORM ===
function showTaskForm(id) {
    document.getElementById('app').innerHTML = '<p>Loading...</p>';

    apiGet('/api/tasks.php?employees=1')
        .then(employees => {
            if (id) {
                apiGet(`/api/tasks.php?id=${id}`)
                    .then(task => {
                        renderTaskForm(task, employees);
                    });
            } else {
                renderTaskForm(null, employees);
            }
        })
        .catch(error => {
            document.getElementById('app').innerHTML = '<p>Error loading form</p>';
        });
}

function renderTaskForm(task, employees) {
    const isEdit = task && task.id;

    const html = `
        <h2>${isEdit ? 'Edit Task' : 'Add Task'}</h2>

        <div id="error-block" class="error" style="display: none;"></div>

        <form id="task-form">
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description"
                          minlength="5" maxlength="200" required>${task ? escapeHtml(task.description) : ''}</textarea>
            </div>

            <div>
                <label for="employeeId">Assign to:</label>
                <select id="employeeId" name="employeeId">
                    <option value="">-- Unassigned --</option>
                    ${employees.map(emp => `
                        <option value="${emp.id}" ${task && task.employeeId == emp.id ? 'selected' : ''}>
                            ${escapeHtml(emp.firstName)} ${escapeHtml(emp.lastName)}
                        </option>
                    `).join('')}
                </select>
            </div>

            <div>
                <label>
                    <input type="checkbox" id="isCompleted" name="isCompleted"
                           ${task && task.isCompleted ? 'checked' : ''}>
                    Completed
                </label>
            </div>

            <div>
                <button type="submit" name="submitButton" id="submitButton">
                    ${isEdit ? 'Update Task' : 'Add Task'}
                </button>

                ${isEdit ? '<button type="button" id="deleteButton" name="deleteButton" class="delete">Delete Task</button>' : ''}
            </div>
        </form>

        <p><a href="#/tasks">Back to Task List</a></p>
    `;

    document.getElementById('app').innerHTML = html;

    document.getElementById('task-form').addEventListener('submit', (e) => {
        e.preventDefault();
        saveTask(task ? task.id : null);
    });

    if (isEdit) {
        document.getElementById('deleteButton').addEventListener('click', () => {
            deleteTask(task.id);
        });
    }
}

// === SAVE TASK ===
async function saveTask(id) {
    const description = document.getElementById('description').value.trim();
    const employeeIdValue = document.getElementById('employeeId').value;
    const employeeId = employeeIdValue ? parseInt(employeeIdValue) : null;
    const isCompleted = document.getElementById('isCompleted').checked;

    const data = { id, description, employeeId, isCompleted };

    try {
        const { response, data: result } = await apiPost('/api/tasks.php', data);

        if (result.error) {
            const errorBlock = document.getElementById('error-block');
            errorBlock.textContent = result.error;
            errorBlock.style.display = 'block';
        } else {
            window.location.hash = '#/tasks';
        }
    } catch (error) {
        alert('Error saving task');
    }
}

// === DELETE TASK ===
async function deleteTask(id) {
    try {
        const { response, data: result } = await apiDelete('/api/tasks.php', { id });

        if (result.success) {
            window.location.hash = '#/tasks';
        }
    } catch (error) {
        alert('Error deleting task');
    }
}

// === HELPER ===
function escapeHtml(text) {
    if (text === null || text === undefined) {
        return '';
    }
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
