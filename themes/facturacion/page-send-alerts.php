<?php get_header(); ?>
	<!-- Al cargar la página se envía un mensaje con las alertas de facturación de hoy,mañana y pasado mañana, si no hay alertas aún así se envía el email indicando que no hay facturaciones próximas -->
	<div class="title-ae">
		<div class="container">			
			<?php include (TEMPLATEPATH . '/templates/template-nav.php'); ?>
			<h2 class="text-center">Enviar Alertas</h2>
		</div>
	</div>
	<section class="container">
		<div class="row">
			<div class="col s12 sm10 offset-sm1 m8 offset-m2 l6 offset-l3 text-center margin-bottom">
				<p>Al cargar esta página <strong>ya se han enviado</strong> automáticamente las facturaciones de los próximos dos días al correo electrónico designado.</p>				
			</div>
			<div class="text-center clear"><a href="<?php echo SITEURL; ?>ae_alertas" class="btn">Regresar</a></div>		
		</div>
	</section>
	<?php 
	    $args = array(
	        'post_type' 		=> 'ae_alertas',
	        'posts_per_page' 	=> -1,
	        'orderby' 			=> 'title',
	        'order' 			=> 'DESC',
	    );
	    $loop = new WP_Query( $args );
	    if ( $loop->have_posts() ) {
	    	$i = 0;

			/* Email Header, haya o no facturaciones */
			/* to do cambiar a correo final alto empleo contacto */
		   	$to 				= "nayeli@queonda.com.mx";
		    $subject 			= "Alertas de facturación Alto Empleo";	

		    $headers 			= "From:" . strip_tags($to) . "\r\n";
		    $headers 		   .= "Reply-To: " . strip_tags($to) . "\r\n";
		    $headers 		   .= "MIME-Version: 1.0\r\n";
			$headers 		   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$message 			 = '<html style="font-family: Arial, sans-serif;"><body>';
			$message 			 = '<div style="text-align: center; background-color: #00B4EF; margin-bottom: 20px;"><img style="display: inline-block; margin: auto;" src="' . THEMEPATH . 'images/email/logo-light.png"></div>';
			$message 			.= '<h1 style="display: block; margin-bottom: 30px; text-align: center;  font-size: 20px; font-weight: 700; color: #00B4EF; text-transform: uppercase;">Pr&oacute;ximas Facturaciones</h1>';
			//echo "HEADER EMAIL <br>";

	        while ( $loop->have_posts() ) : $loop->the_post(); 
				$post_title		= get_the_title();
				include (TEMPLATEPATH . '/templates/ae-alertas/ae-alertas-function.php');	

				/*Si falta menos de dos días para la Facturación visualizar: */
				if ($proxFacturacion >= $today && $proxFacturacion <= $FacturaAlert) : 

					/* Conocer si al menos hay una facturación próxima (al día siguiente) para enviar el mail */
					$i++;

					$message 			.= '<p style="color: #00B4EF; text-transform: uppercase; font-size: 20px;"><strong>' . $post_title . '</strong></p>';
					$message 			.= '<p><strong>Inicio Contrato: </strong>' . $dateContratoEsp . '</p>';
					$message 			.= '<p><strong>Periodo de Facturaci&oacute;n: </strong>' . $periodoFacturacion  . '</p>';
					$message 			.= '<p style="margin-bottom: 20px;"><strong>Pr&oacute;xima Facturaci&oacute;n: </strong>' . $proxFacturacionEsp  . '</p>';
					/*$message 			.= '<hr style="height: 1px; border: 0px; background-color: #00b4ef;">';*/
					//echo $post_title . ' <br>';

				endif;
		    endwhile; 

		    if ($i === 0) {
		    	$message 			.= '<p style="text-align: center"><strong>No hay facturaciones pendientes para hoy o mañana</strong></p>';
		    	//echo "NO HAY FACTURACIONES PARA HOY O MAÑANA";
			}

			/* Sí hubo al menos una alerta, entonces enviar email (Email Footer) */
			$message 			.= '<br><p style="text-align: center;"><a style="color: #00B4EF; font-weight: 700; text-decoration: none; text-transform: uppercase;" href="' . SITEURL . 'ae_alertas/">Ver todas</a></p>';
			$message	        .= '<div style="text-align: center; margin-bottom: 20px;"><a style="color: #000; text-align: center; display: block;" href="' . SITEURL . '"><img style="display: inline-block; margin: auto;" src="' . THEMEPATH . 'images/email/logoae.png"></a></div>';
			$message	        .= '</body></html>';

		    mail($to, utf8_decode($subject), utf8_decode($message), $headers);
	   	} 
	    wp_reset_postdata();
	?>
<?php get_footer(); ?>