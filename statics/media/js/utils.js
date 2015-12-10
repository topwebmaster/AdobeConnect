$(document).ready(function () {
    $('#fechainicio, #fechafin').datepicker();
    $('.table').css({display: 'none'});
    $('.attendance').css({cursor: 'pointer'});
    $('.attendance').each(function () {
        $(this).click(function () {
            $(this).next().find('table').toggle('slow', 'linear');
        });
    });

    var sessions = document.getElementById('consultar');

    sessions.addEventListener("click", function () {

        var select = document.getElementById('sessions');
        var index = select.selectedIndex;

        var tbegin = select.options[index].title;
        var sco_id = select.value;
        var inicio = document.getElementById('fechainicio').value;
        var finald = document.getElementById('fechafin').value;

        generarReportesGrabacion(sco_id, inicio, finald, tbegin);
    });
});

function generarReportesGrabacion(sco_id, inicio, finald, tbegin) {

    var tablejs = "";

    var tables = $.fn.dataTable.fnTables(true);
    $(tables).each(function () {
        $(this).dataTable().fnClearTable();
        $(this).dataTable().fnDestroy();
    });

    tablejs = $('#recordings').DataTable({
        "ajax": {
            'type': 'POST',
            'url': 'index.php?req=request&mod=getRec',
            'data': {
                'sco_id': sco_id,
                'tbegin': tbegin,
                'inicio': inicio,
                'finald': finald
            },
            'dataType': 'json'
        },
        "columns": [
            {
                'className': 'details-control',
                'orderable': false,
                'data': null,
                'defaultContent': ''
            },
            {'data': function (row) {
                    return (typeof row['date-begin'] == "undefined") ? ''
                            : '<span title="' + row['date-begin'] + '">' + stristr(row['date-begin'], "T", true) + '</span>';
                }},
            {'data': 'name'},
            {'data': function (row) {
                    return (typeof row['duration'] == 'undefined') ? ''
                            : '<span title="' + row['date-end'] + '">' + stristr(row['duration'], ".", true) + '</span>';
                }},
            {'data': function (row) {
                    return (typeof row['url-path'] == "undefined") ? ''
                            : '<a href="https://utp.adobeconnect.com' + row['url-path'] + '" target="_blank">Ir a la sala...</a>'
                }}
        ]
    });
    $('#grabaciones').removeClass('grabaciones');

    $('#recordings tbody').on("click", "td.details-control", function () {
        var tr = $(this).closest("tr");
        var rows = tablejs.row(tr);

        if (rows.child.isShown()) {
            rows.child.hide();
            tr.removeClass('shown');
        } else {
            rows.child('Loading...').show();
            var date_record_ini = $(this).next().find('span').attr('title');
            var date_record_fin = $(this).next().next().next().find('span').attr('title');

            $.post('index.php?req=request&mod=getUserSession', {
                sco_id: sco_id,
                date_record_ini: date_record_ini,
                date_record_fin: date_record_fin
            }, function (e) {
                var json = eval('(' + e + ')');

                var html = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                        '<tr>' +
                        '<th width="300">Usuario</th>' +
                        '<th width="200">Tiempo de conexión</th>' +
                        '</tr>';

                for (var i in json.usuarios) {
                    html += '<tr>' +
                            '<td>' + i + '</td>' +
                            '<td>' + json.usuarios[i]['TCon'] + '</td>' +
                            '</tr>';
                }
                ;
                html += '</table>';

                rows.child(html).show();
                tr.addClass('shown');
            });
        }
    });
}

function stristr(haystack, needle, bool) {
    var pos = 0;

    pos = haystack.toLowerCase().indexOf((needle + '').toLowerCase());

    if (pos == -1) {
        return false;
    } else {
        if (bool) {
            return haystack.substr(0, pos);
        } else {
            return haystack.slice(pos);
        }
    }
}