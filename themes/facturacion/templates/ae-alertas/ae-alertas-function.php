<?php 
	$custom_fields 	= get_post_custom();
	$post_id 		= get_the_ID();

	$inicioContrato     	= get_post_meta( $post_id, 'ae_alertas_inicioContrato', true );
	$periodoFacturacion  	= get_post_meta( $post_id, 'ae_alertas_periodoFacturacion', true );
	$periodoQuincenal1  	= get_post_meta( $post_id, 'ae_alertas_periodoQuincenal1', true ); 
	$periodoQuincenal2  	= get_post_meta( $post_id, 'ae_alertas_periodoQuincenal2', true ); 
	$periodoPersonalizada  	= get_post_meta( $post_id, 'ae_alertas_periodoPersonalizada', true );
	$periodoPreestablecida1  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida1', true );
	$periodoPreestablecida2  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida2', true );
	$periodoPreestablecida3  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida3', true );
	$periodoPreestablecida4  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida4', true );
	$periodoPreestablecida5  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida5', true );
	$periodoPreestablecida6  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida6', true );
	$periodoPreestablecida7  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida7', true );
	$periodoPreestablecida8  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida8', true );
	$periodoPreestablecida9  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida9', true );
	$periodoPreestablecida10  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida10', true );
	$periodoPreestablecida11  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida11', true );
	$periodoPreestablecida12  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida12', true );
	$periodoPreestablecida13  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida13', true );
	$periodoPreestablecida14  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida14', true );
	$periodoPreestablecida15  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida15', true );
	$periodoPreestablecida16  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida16', true );
	$periodoPreestablecida17  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida17', true );
	$periodoPreestablecida18  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida18', true );
	$periodoPreestablecida19  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida19', true );
	$periodoPreestablecida20  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida20', true );
	$periodoPreestablecida21  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida21', true );
	$periodoPreestablecida22  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida22', true );
	$periodoPreestablecida23  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida23', true );
	$periodoPreestablecida24  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida24', true );
	$periodoPreestablecida25  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida25', true );
	$periodoPreestablecida26  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida26', true );
	$periodoPreestablecida27  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida27', true );
	$periodoPreestablecida28  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida28', true );
	$periodoPreestablecida29  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida29', true );
	$periodoPreestablecida30  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida30', true );
	$periodoPreestablecida31  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida31', true );
	$periodoPreestablecida32  = get_post_meta( $post_id, 'ae_alertas_periodoPreestablecida32', true );

	setlocale(LC_ALL,"es_ES"); /* Convertir meses a español*/
	$today      		= date('Y-m-d'); /* Hoy */
	/* Activar alerta si es cercana pero no es el día de la alerta por correo. Sólo se obtiene la suma de hoy + 2 días y se compara con la fecha de facturación */
	$FacturaDanger    	= date('Y-m-d', strtotime($today . '+ 2 day'));

	$dateContrato 		= date('d-M-Y', strtotime($inicioContrato));
	$dateContratoEsp 	= strftime("%d de %B del %Y", strtotime($inicioContrato)); 

	/* Obtener Fecha de próxima facturación */
	if( $periodoFacturacion === "Semanal" ) : 

		$proxFacturacion = date('Y-m-d', strtotime($dateContrato));
		while( $today > $proxFacturacion) {
			$proxFacturacion = date('Y-m-d', strtotime($proxFacturacion . '+ 1 week'));						
		}

	elseif( $periodoFacturacion === "Quincenal" ) :
		$introContrato 	= date('Y-m-', strtotime($inicioContrato));

		/* Determinar la fecha máxima del mes */
		$maxDayMonth	= date("t", strtotime($today));
		//echo "Días del mes " . $maxDayMonth . '<br><br>';

		/* Formato de día con 2 digitos */
		if ($periodoQuincenal1 < 10) {
			$periodoQuincenal1 = '0' . $periodoQuincenal1;
		}
		if ($periodoQuincenal2 < 10) {
			$periodoQuincenal2 = '0' . $periodoQuincenal2;
		}

		/* Determinar si el día es mayor al numéro de días del mes - corregirlo*/
		if ($periodoQuincenal1 > $maxDayMonth) {
			$periodoQuincenal1 = $maxDayMonth;
		}
		if ($periodoQuincenal2 > $maxDayMonth) {
			$periodoQuincenal2 = $maxDayMonth;
		}

		/* Estableces las proximas quincenas inmediatas */
		$proxQuincena1 = $introContrato . $periodoQuincenal1;
		$proxQuincena2 = $introContrato . $periodoQuincenal2;

		/* Si la fecha de hoy es mayor a la fecha de facturación quincenal inmediata se saca la siguiente fecha*/
		while( $today > $proxQuincena1) {
			$proxQuincena1 = date('Y-m-d', strtotime($proxQuincena1 . '+ 1 month'));			
		}
		while( $today > $proxQuincena2) {
			$proxQuincena2 = date('Y-m-d', strtotime($proxQuincena2 . '+ 1 month'));	
		}
		/*echo "Quincena1 " . $proxQuincena1 . '<br>';
		echo "Quincena2 " . $proxQuincena2 . '<br><br>';*/

		/* Determinar qué quincena está más próxima*/
		if ($proxQuincena1 < $proxQuincena2) {
			$proxFacturacion = $proxQuincena1;
		} else {
			$proxFacturacion = $proxQuincena2;
		}

	elseif( $periodoFacturacion === "Mensual" ) :

		$proxFacturacion = date('Y-m-d', strtotime($dateContrato));
		while( $today > $proxFacturacion) {
			$proxFacturacion = date('Y-m-d', strtotime($proxFacturacion . '+ 1 month'));
		}

	elseif( $periodoFacturacion === "Personalizada" ) :

		$proxFacturacion = date('Y-m-d', strtotime($dateContrato . '+' . $periodoPersonalizada . ' day'));
		while( $today > $proxFacturacion) {
			$proxFacturacion = date('Y-m-d', strtotime($proxFacturacion . '+' . $periodoPersonalizada . ' day'));					
		}

	elseif( $periodoFacturacion === "Preestablecida" ) :

		$proxFacturacion = date('Y-m-d', strtotime($periodoPreestablecida1));
		$count = 1;
		while( $today > $proxFacturacion && $count < 33 ) {
			$periodoPreestablecida = ${'periodoPreestablecida' . $count};
			if( $periodoPreestablecida != "" ) :
				$proxFacturacion = date('Y-m-d', strtotime($periodoPreestablecida));
			endif;
			$count++;
		}

	endif; 

	/* Activar alerta dos días antes */
	$FacturaAlert    	= date('Y-m-d', strtotime($proxFacturacion . '- 2 day'));
	/* Cambiar formato $proxFacturacion*/
	$proxFacturacionEsp 	= strftime("%d de %B del %Y", strtotime($proxFacturacion));
?>