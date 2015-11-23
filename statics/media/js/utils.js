$(document).ready(function(){
    //$('.table').css({display:'none'});
    $('.attendance').css({cursor:'pointer'});
    $('.attendance').each(function(){
        $(this).click(function(){
            $(this).next().find('table').toggle('slow', 'linear');
        });
    });

    var btn = document.getElementById('btnxls');
    btn.addEventListener("click", function(){
        var html = document.getElementById('tblsalas').outerHTML;
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
    });
});