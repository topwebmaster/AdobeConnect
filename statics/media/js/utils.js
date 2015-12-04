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

    sessions.addEventListener("click", function(){

        var tables = $.fn.dataTable.fnTables(true);
        $(tables).each(function () {
            $(this).dataTable().fnClearTable();
            $(this).dataTable().fnDestroy();
        });

        var select = document.getElementById('sessions');
        var index = select.selectedIndex;

        var tbegin = select.options[index].title;
        var sco_id = select.value;
        var inicio = document.getElementById('fechainicio').value;
        var finald = document.getElementById('fechafin').value;

        generarReportesGrabacion(sco_id, inicio, finald, tbegin);
    });
});

function generarReportesGrabacion(sco_id, inicio, finald, tbegin){
    var tablejs = $('#recordings').DataTable({
        "ajax" : {
            'type' : 'POST',
            'url' : 'index.php?req=request&mod=getRec',
            'data' : {
                'sco_id' : sco_id,
                'tbegin' : tbegin,
                'inicio' : inicio,
                'finald' : finald
            },
            'dataType' : 'json'
        },
        "columns" : [
            {
                'className' : 'details-control',
                'orderable' : false,
                'data' : null,
                'defaultContent' : ''
            },
            {'data' : function(row){
                return (typeof row['date-begin'] == "undefined") ? '' : stristr(row['date-begin'], "T", true);
            }},
            {'data' : 'name'},
            {'data' : function(row){
                return (typeof row['duration'] == 'undefined') ? '' : stristr(row['duration'], ".", true);
            }},
            {'data' : function(row){
                return (typeof row['url-path'] == "undefined") ? ''  
                : '<a href="https://utp.adobeconnect.com' + row['url-path'] + '" target="_blank">Ir a la sala...</a>'
            }}
        ]
    });
    $('#grabaciones').removeClass('grabaciones');

    $('#recordings tbody').on("click", "td.details-control", function(){
        var tr = $(this).closest("tr");
        var rows = tablejs.row(tr);

        if(rows.child.isShown()){
            rows.child.hide();
            tr.removeClass('shown');
        }else{
            rows.child('Loading...').show();
            $.post('index.php?req=request&mod=getUserSession', {
                sco_id : sco_id
            }, function(e){
                rows.child(format(e)).show();
                tr.addClass('shown');
            });
        }
    });
}

function format ( d ) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Full name:</td>'+
            '<td>'+d+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Extension number:</td>'+
            '<td>'+d+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Extra info:</td>'+
            '<td>And any further details here (images etc)...</td>'+
        '</tr>'+
    '</table>';
}

function stristr(haystack, needle, bool){
    var pos = 0;
    
    pos = haystack.toLowerCase().indexOf((needle+'').toLowerCase());
    
    if(pos == -1){
        return false;
    }else{
        if(bool){
            return haystack.substr(0, pos);
        }else{
            return haystack.slice(pos);
        }
    }
}