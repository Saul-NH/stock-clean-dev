    $(document).ready(function() {
        let producto_id;
        let total_venta =0;
        let productos_venta = [];
        let productos_venta_string = [];

        $('#mensaje').hide();
        $('#button_dissmiss').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            $('#mensaje').hide();
        });

        /*
        *   Control para buscar en la tabla
        */
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


        /*
        *   Boton para generar la nueva venta
        */
            $('#nueva_venta').on('click', function(event) {
                event.preventDefault();
                
                nuevaVentaInit();

            });

        /*
        *   Boton para finalizar la venta: #terminar_venta
        */
            $('#terminar_venta').on('click', function(event) {
                event.preventDefault();

                if (productos_venta.length < 1) {
                    /* 
                        No lanza el modal finalizar_venta_modal 
                        cuando no hay productos registrados a la venta
                     */
                }else {

                    let tableContent = "";
                    for (var i = 0; i < productos_venta.length; i++) {
                        tableContent += '<tr>';
                        tableContent += '<td >'+ productos_venta[i].name + '</td>';
                        tableContent += '<td class="text-center">'+ productos_venta[i].cantidad_vendida +' '+productos_venta[i].tipo_medida +'s</td>';
                        tableContent += '<td class="text-center"> $ '+ productos_venta[i].sub_total + '</td>';
                        tableContent += '</tr>';
                    }
                    tableContent += '<tr class="table-success h5">'
                    +'<td colspan="2"> <strong> Total </strong></td>'
                    + '<td class="text-center"><strong>$ '+total_venta
                    +'</strong></td>'+ '</tr>';

                    $('#finalizar_venta_modal').modal('show');
                    $('#modal_body_finalizar').html(tableContent);
                }
            });


        /*
        *   Modal de confirmacion para la terminación de la venta
        */    
            $('#terminar_venta_button').click(function (event) {
                    event.preventDefault();
                    $("#finalizar_venta_modal").modal("hide");
                    finalizaVenta();
                    /*Aqui ira la llamada al metodo getProductosString()*/
                    getProductosString();
                    $.ajax({
                        // la URL para la petición
                        url : 'ventas',
                     
                        // la información a enviar
                        // (también es posible utilizar una cadena de datos)
                        data : {productos_venta : productos_venta_string , total_venta : total_venta} ,
                     
                        // especifica si será una petición POST o GET
                        type : 'post',

                        cache: false,
                     
                        // el tipo de información que se espera de respuesta
                        //dataType : 'json',
                     
                        // código a ejecutar si la petición es satisfactoria;
                        // la respuesta es pasada como argumento a la función
                        success : function(res) {
                            console.log(res);
                           // location.reload();
                            //$('#mensaje').attr('hidden', true);
                            //$('#mensaje').attr('hidden', false);
                            $('#mensaje').show();
                            $('#mensaje_content').html(res);
                            updateStock();
                            cancelarVenta();


                        },
                     
                        // código a ejecutar si la petición falla;
                        // son pasados como argumentos a la función
                        // el objeto jqXHR (extensión de XMLHttpRequest), un texto con el estatus
                        // de la petición y un texto con la descripción del error que haya dado el servidor
                        error : function(jqXHR, status, error) {
                            console.log('Disculpe, existió un problema');
                            console.log(error);
                        },
                     
                        // código a ejecutar sin importar si la petición falló o no
                        complete : function(jqXHR, status) {
                            console.log('Petición realizada');

                        }
                    });

                });


        /*
        *   Boton de la tabla '+' para agregar un producto a la venta
        */    
            $(".agregar-producto").on('click', function (e) {
                e.preventDefault();
                if ($('#cantidad_'+$(this).attr('data-id')).val() == "" || $('#cantidad_'+$(this).attr('data-id')).val() <= 0) {
                    //alert('esta vacio');
                    $('#mensaje_'+$(this).attr('data-id')).attr('hidden', false);
                }else{                    

                    if (checkStock($(this).attr('data-producto'),$('#cantidad_'+$(this).attr('data-id')).val())) {
                        
                        $('#stock_'+$(this).attr('data-id')).attr('hidden', false);
                        $('#mensaje_'+$(this).attr('data-id')).attr('hidden', true);

                    }else{

                        $('#stock_'+$(this).attr('data-id')).attr('hidden', true);
                        $('#mensaje_'+$(this).attr('data-id')).attr('hidden', true);
                        $(this).hide();
                        $('#'+$(this).attr('data-id')).attr("hidden",false);
                        $('#'+$(this).attr('data-id')).show();

                        let producto = $(this).attr('data-producto');
                        producto = JSON.parse(producto);
                        producto.cantidad_vendida = $('#cantidad_'+$(this).attr('data-id')).val();
                        producto.sub_total = getSubTotal (producto);
                        total_venta += producto.sub_total;
                        productos_venta.push(producto);

                        $('#ventas').text(productos_venta.length);
                        console.log(producto);
                        console.log(productos_venta);
                    }                     
                }
                
            });


        /*
        *   Boton de la tabla '-' para borrar un producto de la venta
        */    
            $(".borrar-producto").on('click', function (e) {
                e.preventDefault();
                $(this).hide();

                idProductoEliminar = $(this).attr('data-id');
                index = getIndice(idProductoEliminar);
                total_venta -= productos_venta[index].sub_total;
                producto_eliminado = productos_venta.splice(index,1);
                console.log('Index del producto eliminado: '+index);
                
                $('#ventas').text(productos_venta.length);
                $('#agregar'+$(this).attr('data-id')).show();
                $('#cantidad_'+$(this).attr('data-id')).val('');
                console.log('id producto a eliminar: '+idProductoEliminar);
                console.log('Array de los productos de la venta'+productos_venta);
                console.log('Producto eliminado: '+ producto_eliminado);
            });

         
         /*
        *  Boton para mostrar el modal con el detalle de la venta
        */
            $('#cantidad_productos').on('click', function(event) {
                event.preventDefault();
                
                if ( $(this).hasClass('btn-secondary') || productos_venta.length < 1) {
                     /* 
                        No lanza el modal #detalle_venta 
                        cuando no hay una venta activa 
                     */
                }else {
                    
                    let tableContent = "";
                    for (var i = 0; i < productos_venta.length; i++) {
                        tableContent += '<tr>';
                        tableContent += '<td >'+ productos_venta[i].name + '</td>';
                        tableContent += '<td class="text-center">'+ productos_venta[i].cantidad_vendida +' '+productos_venta[i].tipo_medida +'s</td>';
                        tableContent += '<td class="text-center"> $ '+ productos_venta[i].sub_total + '</td>';
                        tableContent += '</tr>';
                    }
                    tableContent += '<tr class="table-primary h5">'
                    +'<td colspan="2"> <strong> Total </strong></td>'
                    + '<td class="text-center"><strong>$ '+total_venta
                    +'</strong></td>'+ '</tr>';

                    $('#detalle_venta').modal('show');
                    $('#modal_body_detalle').html(tableContent);
                }
                
            });

        /*
        *   Modal de confirmacion para la cancelación de una venta
        */    
            $('#cancelar_venta_modal').on('shown.bs.modal', function(e) {
                $('#boton_cancelar').click(function () {
                    $("#cancelar_venta_modal").modal("hide");
                    cancelarVenta();
                });
            });


        /*
        *   Boton para obtener el id del producto a eliminar
        */
            $(".eliminar").on('click', function () {
                producto_id = $(this).attr('data-id');
                console.log(producto_id);
            });


        /*
        *   Modal de confirmacion para la eliminación de un producto del stock
        */    
            $('#deleteProduct').on('shown.bs.modal', function(e) {
                $('#botonEliminar').click(function () {
                    $("#deleteProduct").modal("hide");
                    $('#form'+producto_id).submit();
                    producto_id=null;
                });
            });


         /*
        *   Configuración de ajax para peticiones post LARAVEL
        */
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
             }
            });


        /*
        *   peticion ajax para guardar productos de la compra BORRADOR
        */
            $('.ajax').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                        // la URL para la petición
                        url : 'test',
                     
                        // la información a enviar
                        // (también es posible utilizar una cadena de datos)
                        data : {productos_venta:productos_venta} ,
                     
                        // especifica si será una petición POST o GET
                        type : 'post',
                     
                        // el tipo de información que se espera de respuesta
                        //dataType : 'json',
                     
                        // código a ejecutar si la petición es satisfactoria;
                        // la respuesta es pasada como argumento a la función
                        success : function(res) {
                            console.log(res);
                        },
                     
                        // código a ejecutar si la petición falla;
                        // son pasados como argumentos a la función
                        // el objeto jqXHR (extensión de XMLHttpRequest), un texto con el estatus
                        // de la petición y un texto con la descripción del error que haya dado el servidor
                        error : function(jqXHR, status, error) {
                            console.log('Disculpe, existió un problema');
                        },
                     
                        // código a ejecutar sin importar si la petición falló o no
                        complete : function(jqXHR, status) {
                            console.log('Petición realizada');
                        }
                    });
            });   

        /*
        *   Funciones generales
        */

            // Funcion para preparar la nueva venta
            function nuevaVentaInit() {
                $("#nueva_venta").hide();
                $("#terminar_venta").attr("hidden",false);
                $("#cancelar_venta").attr("hidden",false);
                $(".agregar-producto").attr("hidden",false);
                $(".agregar-producto").show();
                $('#ventas').text('0');
                $('#cantidad_productos').removeClass("btn-secondary");
                $('#cantidad_productos').addClass('btn-success');
                $('#cantidad_productos_badge').addClass('badge-danger');

            }

            // Función para cancelar la venta, vuelve a los valores iniciales 
            // para iniciar una nueva venta
            function cancelarVenta (){
                
                $("#nueva_venta").show();
                $("#terminar_venta").attr("hidden",true);
                $("#cancelar_venta").attr("hidden",true);
                $(".agregar-producto").attr("hidden",true);
                $(".borrar-producto").attr("hidden",true);
                $('#cantidad_productos').removeClass("btn-success");
                $('#cantidad_productos').addClass('btn-secondary');
                $('#cantidad_productos_badge').removeClass('badge-danger');
                $('#ventas').text('-');
                $('.limpiar-input-cantidad').val('');
                $('.mensaje-cantidad').attr('hidden', true);
                total_venta = 0;
                productos_venta = [];
                productos_venta_string = [];
            }

            // Función para finalizar la venta, restablece todo para iniciar una nueva venta
            function finalizaVenta() {
                $("#nueva_venta").show();
                $("#terminar_venta").attr("hidden",true);
                $("#cancelar_venta").attr("hidden",true);
                $(".agregar-producto").attr("hidden",true);
                $(".borrar-producto").attr("hidden",true);
                $('#cantidad_productos').removeClass("btn-success");
                $('#cantidad_productos').addClass('btn-secondary');
                $('#cantidad_productos_badge').removeClass('badge-danger');
                $('#ventas').text('-');
                $('.limpiar-input-cantidad').val('');
                $('.mensaje-cantidad').attr('hidden', true);
            }


            // Funcion para obtener el subtotal del nuevo producto agregado a la venta
            function getSubTotal (producto){
                 return producto.cantidad_vendida * producto.precio_venta ;
            }

            // Funcion para obtener el indice del producto a ser borrado de una venta
            function getIndice(Productoid) {
                var Indice = -1;
                productos_venta.filter(function (producto, i) {
                    if (producto.id == Productoid) {
                        Indice = i;
                    }
                });
                return Indice;
            }

            function getProductosString(){

                jQuery.each(productos_venta, function(index, val) {
                    productos_venta_string.push(JSON.stringify(val));
                });
            }

            //Función para actualizar la cantidad en stock
            function updateStock(){

                for (var i = 0; i < productos_venta.length; i++) {
                    
                    console.log('Antes'+$('#agregar'+productos_venta[i].id).attr('data-producto'));
                    let productoBoton = $('#agregar'+productos_venta[i].id).attr('data-producto');
                    productoBoton = JSON.parse(productoBoton);
                    productoBoton.cantidad_stock -= productos_venta[i].cantidad_vendida;

                    $('#agregar'+productos_venta[i].id).attr('data-producto', JSON.stringify(productoBoton));
                    console.log('Despues'+$('#agregar'+productos_venta[i].id).attr('data-producto'));
                }
            }

            function checkStock(producto, cantidad){
                productoStock = JSON.parse(producto);
                if (productoStock.cantidad_stock < cantidad) {
                    return true;
                }else{
                    return false;
                }
            }

    });




