/**
 * @typedef {import('jquery')} $
 * @typedef {import('datatables.net')} DataTables
 */

document.addEventListener('DOMContentLoaded', function () {
    const savedPageLength = parseInt(localStorage.getItem('pageLength')) || 25;

    const table = $('#reportTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: savedPageLength,
        language: {
            lengthMenu: 'Rādīt _MENU_ ierakstus lapā',
            info: 'Rāda no _START_ līdz _END_ no _TOTAL_ ierakstiem',
            search: 'Meklēt',
            emptyTable: 'Nav pieejamu datu',
            zeroRecords: 'Nav ierakstu atbilstoši filtriem'
        },
        ajax: {
            url: '/registrs/data',
            data: function (d) {
                d.uzm_tips_vmf = $('#uzm_tips_vmf').val();
                d.tilp_bruto_min = $('#tilp_bruto_min').val();
                d.tilp_bruto_max = $('#tilp_bruto_max').val();
                d.tilp_neto_min = $('#tilp_neto_min').val();
                d.tilp_neto_max = $('#tilp_neto_max').val();
                d.tilp_brakis_min = $('#tilp_brakis_min').val();
                d.tilp_brakis_max = $('#tilp_brakis_max').val();
            }
        },
        columns: [
            { data: 'id' },
            { data: 'uzm_tips_vmf' },
            { data: 'tilp_bruto' },
            { data: 'tilp_neto' },
            { data: 'tilp_brakis' }
        ],
        order: [[0, 'asc']]
    });

    table.on('length.dt', function (e, settings, len) {
        localStorage.setItem('pageLength', len);
    });

    document.getElementById('filter').addEventListener('click', function () {
        table.ajax.reload();
    });

    document.getElementById('reset').addEventListener('click', function () {
        document.querySelectorAll('#uzm_tips_vmf, input[type=number]').forEach(el => el.value = '');
        table.ajax.reload();
    });

    document.getElementById('export_registrs').addEventListener('click', function () {
        const order = table.order();
        const column = table.column(order[0][0]).dataSrc();

        const params = new URLSearchParams({
            uzm_tips_vmf: $('#uzm_tips_vmf').val(),
            tilp_bruto_min: $('#tilp_bruto_min').val(),
            tilp_bruto_max: $('#tilp_bruto_max').val(),
            tilp_neto_min: $('#tilp_neto_min').val(),
            tilp_neto_max: $('#tilp_neto_max').val(),
            tilp_brakis_min: $('#tilp_brakis_min').val(),
            tilp_brakis_max: $('#tilp_brakis_max').val(),
            'search[value]': table.search(),
            'search[regex]': false,
            orderColumn: column,
            orderDir: order[0][1]
        });

        window.location.href = `/registrs/export?${params.toString()}`;
    });
});



