$(document).ready(function() {
    const savedPageLength = parseInt(localStorage.getItem('pageLength')) || 25;

    // Initialize DataTable
    const table = $('#uzmBalkuSkaitsTable').DataTable({
        processing: true,
        serverSide: false,
        pageLength: savedPageLength,
        language: {
            lengthMenu: 'Rādīt _MENU_ ierakstus lapā',
            info: 'Rāda no _START_ līdz _END_ no _TOTAL_ ierakstiem',
            search: 'Meklēt',
            emptyTable: 'Nav pieejamu datu',
            zeroRecords: 'Nav ierakstu atbilstoši filtriem'
        },
        ajax: {
            url: '/uzmerito-balku-skaits/data',
            data: function(d) {
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
            }
        },
        columns: [
            { data: 'datums' },
            { data: 'vieta' },
            { data: 'skaits' }
        ]
    });

    function validateDate(value) {
        if (!value) return ''; // Empty is valid
        const date = new Date(value);
        return value.match(/^\d{4}-\d{2}-\d{2}$/) && !isNaN(date.getTime()) ? '' : 'Nepareizs datuma formāts!';
    }

    function clearErrors() {
        $('#startDate, #endDate, #dateError').toggleClass('invalid', false).text('');
    }

    $('#filterButton').click(function() {
        clearErrors();

        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        let error = validateDate(startDate) || validateDate(endDate);
        if (!error && startDate && endDate && new Date(startDate) > new Date(endDate)) {
            error = 'Nepareizs datuma formāts!';
        }

        if (error) {
            $('#startDate, #endDate').toggleClass('invalid', true);
            $('#dateError').text(error);
        } else {
            table.ajax.reload();
        }
    });

    $('#startDate, #endDate').on('input', clearErrors);

    table.on('length.dt', function(e, settings, len) {
        localStorage.setItem('pageLength', len);
    });
});