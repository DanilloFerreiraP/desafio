
const apiUrlPatients = '/api/patients';
const apiUrlHealthUnit = '/api/health-units';

let patients = [];

async function loadPatients() {
    try {
        const response = await fetch(apiUrlPatients);

        if (!response.ok) {
            throw new Error('Erro ao carregar pacientes.');
        }

        patients = await response.json();
        filterPatients();

    } catch (error) {
        alert("Falha ao carregar pacientes: " + error.message);
    }
}

function filterPatients() {
    const search = document.getElementById('search').value.toLowerCase();

    const filtered = patients.filter(patient =>
        patient.name.toLowerCase().includes(search)
    );

    const table = document.getElementById('patientsTable');
    table.innerHTML = '';

    if (filtered.length === 0) {
        table.innerHTML = '<tr><td colspan="4">Nenhum paciente encontrado.</td></tr>';
        return;
    }

    filtered.forEach(patient => {
        const row = document.createElement('tr');

        const values = [
            patient.name,
            patient.cpf,
            patient.health_unit?.name || ''
        ];

        values.forEach(value => {
            const cell = document.createElement('td');
            cell.textContent = value;
            row.appendChild(cell);
        });

        const actions = document.createElement('td');

        const edit = document.createElement('button');
        edit.textContent = 'Editar';
        edit.className = 'btn btn-sm btn-outline-primary me-2';
        edit.onclick = () => editPatient(patient.id);

        const remove = document.createElement('button');
        remove.textContent = 'Excluir';
        remove.className = 'btn btn-sm btn-outline-danger';
        remove.onclick = () => deletePatient(patient.id);

        actions.append(edit, remove);
        row.appendChild(actions);
        table.appendChild(row);
    });
}

async function loadHealthUnits() {
    try {
        const response = await fetch(apiUrlHealthUnit);

        if (!response.ok) {
            throw new Error('Erro ao carregar unidades de saúde.');
        }

        const units = await response.json();
        const select = document.getElementById('health_unit_id');

        select.innerHTML = '<option value="">Selecione uma unidade</option>';

        units.forEach(unit => {
            const option = document.createElement('option');
            option.value = unit.id;
            option.textContent = unit.name;
            select.appendChild(option);
        });

    } catch (error) {
        alert("Falha ao carregar unidades de saúde: " + error.message);
    }
}

function newPatient() {
    document.getElementById('patientForm').reset();
    document.getElementById('patientId').value = '';
    document.getElementById('formTitle').textContent = 'Novo paciente';
    clearErrors();
    document.getElementById('patientFormCard').hidden = false;
}

async function editPatient(id) {
    try {
        const response = await fetch(`${apiUrlPatients}/${id}`);

        if (!response.ok) {
            throw new Error('Erro ao consultar paciente.');
        }

        const patient = await response.json();

        document.getElementById('patientId').value = patient.id;
        document.getElementById('name').value = patient.name;
        document.getElementById('cpf').value = patient.cpf;
        document.getElementById('birth_date').value = patient.birth_date;
        document.getElementById('phone').value = patient.phone || '';
        document.getElementById('health_unit_id').value = patient.health_unit_id;

        document.getElementById('formTitle').textContent = 'Editar paciente';
        clearErrors();
        document.getElementById('patientFormCard').hidden = false;

    } catch (error) {
        alert("Falha ao editar paciente: " + error.message);
    }
}

function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(input => {
        input.classList.remove('is-invalid');
    });

    document.querySelectorAll('.invalid-feedback').forEach(error => {
        error.textContent = '';
    });
}

async function savePatient(event) {
    event.preventDefault();
    clearErrors();

    const id = document.getElementById('patientId').value;

    const data = {
        name: document.getElementById('name').value,
        cpf: document.getElementById('cpf').value.replace(/\D/g, ''),
        birth_date: document.getElementById('birth_date').value,
        phone: document.getElementById('phone').value.replace(/\D/g, ''),
        health_unit_id: document.getElementById('health_unit_id').value
    };

    const url = id ? `${apiUrlPatients}/${id}` : apiUrlPatients;
    const method = id ? 'PUT' : 'POST';
    const button = document.getElementById('savePatient');

    button.disabled = true;

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.status === 422) {
            alert("Verifique os campos informados.");
            for (const field in result.errors) {
                const input = document.getElementById(field);
                const error = document.getElementById(`error-${field}`);

                if (input && error) {
                    input.classList.add('is-invalid');
                    error.textContent = result.errors[field][0];
                }
            }

            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Erro ao salvar paciente.');
        }

        cancelPatient();
        alert("Paciente salvo com sucesso.");
        await loadPatients();

    } catch (error) {
        alert("Falha ao salvar paciente: " + error.message);
    } finally {
        button.disabled = false;
    }
}

async function deletePatient(id) {
    if (confirm('Deseja realmente excluir este paciente?')) {
        try {
            const response = await fetch(`${apiUrlPatients}/${id}`, {
                method: 'DELETE'
            });

            if (!response.ok) {
                throw new Error('Erro ao excluir paciente.');
            }

            cancelPatient();
            alert("Paciente excluído com sucesso.");
            await loadPatients();

        } catch (error) {
            alert("Falha ao excluir paciente: " + error.message);
        }
    }
}

function cancelPatient() {
    document.getElementById('patientForm').reset();
    document.getElementById('patientId').value = '';
    document.getElementById('patientFormCard').hidden = true;
    clearErrors();
}

// Eventos da página
document.getElementById('newPatient').onclick = newPatient;
document.getElementById('cancelPatient').onclick = cancelPatient;
document.getElementById('search').oninput = filterPatients;
document.getElementById('patientForm').onsubmit = savePatient;

// Carregamento inicial
loadHealthUnits();
loadPatients();
