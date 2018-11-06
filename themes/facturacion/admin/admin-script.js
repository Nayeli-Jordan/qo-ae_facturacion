jQuery(document).ready(function ($) {

	if ($('#ae_alertas_periodoFacturacion').length > 0) {
		/* READY */
		/* Deshabilitar las fechas de facturación quincenal si al cargar la página no es periodo Quincenal*/
		if ($('#ae_alertas_periodoFacturacion option:selected').val() != 'Quincenal' ){
			$('select#ae_alertas_periodoQuincenal1, select#ae_alertas_periodoQuincenal2').val('').prop( "disabled", true );
			$('#facturacionQuincenal').css('display', 'none');
		}
		if ($('#ae_alertas_periodoFacturacion option:selected').val() != 'Personalizada' ) {
			$('select#ae_alertas_periodoPersonalizada').val('').prop( "disabled", true );
			$('#facturacionPersonalizada').css('display', 'none');
		}
		if ($('#ae_alertas_periodoFacturacion option:selected').val() != 'Preestablecida' ) {
			$('#facturacionPreestablecida input').val('').prop( "disabled", true );
			$('#facturacionPreestablecida').css('display', 'none');
		}

		/* Seleccionar en la segunda quincena la opción correspondiente a 14 días después de la primera quincena*/
		$('select#ae_alertas_periodoQuincenal1').on('change', function() {
			var idOption 		= $('select#ae_alertas_periodoQuincenal1 option:selected').attr('id');
			/* Transform idOption text in number */
			var idOption 		= parseInt(idOption);
			var optionQuincena2 = idOption + 15;			
			$('select#ae_alertas_periodoQuincenal2').val(optionQuincena2);
		});
		/* Estableces "1 y 15" como fecha de facturación si se selecciona "Quincena" */
		/* Resetear fechas quincena si se cambia el periodo de facturación que no es quincenal*/
		$('#ae_alertas_periodoFacturacion').on('change', function() {			
			if ($('#ae_alertas_periodoFacturacion option:selected').val() == 'Quincenal' ){
				$('#facturacionQuincenal').css('display', '');
				$('#facturacionPersonalizada, #facturacionPreestablecida').css('display', 'none');
				$('select#ae_alertas_periodoQuincenal1, select#ae_alertas_periodoQuincenal2').val('').prop( "disabled", false );
				$('select#ae_alertas_periodoQuincenal1').val('1');
				$('select#ae_alertas_periodoQuincenal2').val('15');				
			} else if ($('#ae_alertas_periodoFacturacion option:selected').val() == 'Personalizada' ) {
				$('select#ae_alertas_periodoPersonalizada').val('').prop( "disabled", false );
				$('#facturacionQuincenal, #facturacionPreestablecida').css('display', 'none');
				$('#facturacionPersonalizada').css('display', '');
			} else if ($('#ae_alertas_periodoFacturacion option:selected').val() == 'Preestablecida' ) {
				$('#facturacionPreestablecida input').prop( "disabled", false );
				$('#facturacionQuincenal, #facturacionPersonalizada').css('display', 'none');
				$('#facturacionPreestablecida').css('display', '');
			} else {
				$('#facturacionQuincenal, #facturacionPersonalizada, #facturacionPreestablecida').css('display', 'none');
				$('select#ae_alertas_periodoQuincenal1, select#ae_alertas_periodoQuincenal2').val('').prop( "disabled", true );
			}
		});
		
	}	

});

