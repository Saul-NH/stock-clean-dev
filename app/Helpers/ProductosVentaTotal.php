<?php 
	
	/**
	** Funcion para obtener los productos y el total de la venta
	** @param  []  $productos_compra
	** @return  []  $productosCompraArray
 	*/

	function getProductosVentaTotal ( $productos_compra ) {

		 $total_venta = 0;
         $arrlength = count($productos_compra);

         /* 
	     ** for para obtener los productos que llegan en el  
	     ** array($productos_compra) con formato JSON, a un 
	     **	array con los productos.
         */
	         for ( $x = 0; $x < $arrlength; $x++ ) {
	              $productos_compra[$x] = json_decode($productos_compra[$x]);
	            }

		/*
		**	foreach para obtener el total de la venta.
		*/        

            foreach ($productos_compra as $producto) {
                $total_venta += $producto->precio_venta;
            }

        /* Creamos el array con los productos y el total de la venta*/  
          
    		$productosCompraArray = ['productos_compra'=> $productos_compra, 'total_venta' => $total_venta];

    	


    	return $productosCompraArray;
	}
