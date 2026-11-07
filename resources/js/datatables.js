import DataTable from 'datatables.net-bs5';

window.DataTable = DataTable;

const spanishLanguage = {
    emptyTable: 'No hay datos disponibles en la tabla',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
    infoFiltered: '(filtrado de _MAX_ registros en total)',
    lengthLabels: { '-1': 'Todos' },
    lengthMenu: 'Mostrar _MENU_ registros',
    loadingRecords: 'Cargando...',
    processing: 'Procesando...',
    search: 'Buscar:',
    zeroRecords: 'No se encontraron registros coincidentes',
    paginate: {
        first: 'Primero',
        last: 'Último',
        next: 'Siguiente',
        previous: 'Anterior',
    },
};

function initializeDataTable(selector, columnDefs) {
    const table = document.querySelector(selector);

    if (!table || DataTable.isDataTable(table)) {
        return;
    }

    new DataTable(table, {
        language: spanishLanguage,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100, { label: 'Todos', value: -1 }],
        order: [[0, 'desc']],
        autoWidth: false,
        columnDefs,
    });
}

function initPropertiesDataTable() {
    initializeDataTable('#properties-table', [
        { targets: 1, orderable: false },
        { targets: 8, orderable: false },
    ]);
}

function initPropertyTypesDataTable() {
    initializeDataTable('#property-types-table', [
        { targets: 4, orderable: false },
    ]);
}

function initClientsDataTable() {
    initializeDataTable('#clients-table', [
        { targets: 7, orderable: false },
    ]);
}

function initializeTables() {
    initPropertiesDataTable();
    initPropertyTypesDataTable();
    initClientsDataTable();
}

document.addEventListener('DOMContentLoaded', initializeTables);

export { initPropertiesDataTable, initPropertyTypesDataTable, initClientsDataTable };
