document.addEventListener('DOMContentLoaded', function () {
    const savedPageLength = parseInt(localStorage.getItem('pageLength')) || 25;

    $('#kopUzmTilpumsTable').DataTable({
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
            url: '/kopejais-uzmeritais-tilpums/data',
        },
        columns: [
            { data: 'veids' },
            { data: 'bruto' },
            { data: 'neto' },
            { data: 'brakis' },
        ]
    });
});