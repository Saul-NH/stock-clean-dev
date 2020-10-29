$(document).ready(function() {
    var producto_id;

    $(".search").keyup(function () {
        var searchTerm = $(".search").val();
        var listItem = $('.results tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

        $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
                return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
            $(this).attr('visible','false');
        });

        $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
            $(this).attr('visible','true');
        });

        var jobCount = $('.results tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' producto(s)');

        if(jobCount == '0') {$('.no-result').show();}
        else {$('.no-result').hide();}
    });

    $(".eliminar").on('click', function () {
        producto_id = $(this).attr('data-id');

        console.log(producto_id);
    });

    $('#deleteProduct').on('shown.bs.modal', function(e) {
        $('#botonEliminar').click(function () {

            // $.get("productos./"+producto_id, function (result) {
            //     $('#mensaje').text(result);
            // });
            $("#deleteProduct").modal("hide");
            $('#form'+producto_id).submit();
            producto_id=null;
            //$("#fila_"+producto_id).remove();
        });
    });

});
