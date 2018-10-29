<?php get_header(); ?>
	<!-- Se envian alertas al email Automaticamente con CRON sólo si hay facturaciónes con Alerta Activa, es decir, un día antes de su próxima fecha de facturación. En el caso de que no haya ningúna NO se envía el email  -->
	<div class="title-ae">
		<div class="container">			
			<?php include (TEMPLATEPATH . '/templates/template-nav.php'); ?>
			<h2 class="text-center">Enviar Alertas</h2>
		</div>
	</div>
	<section class="container">
		<div class="row">
			<div class="col s12 sm10 offset-sm1 m8 offset-m2 l6 offset-l3 text-center margin-bottom">
				<p>Al cargar esta página <strong>se enviaron automáticamente</strong> las facturaciones de los próximos dos días sólo en el caso de que las haya.</p>				
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
	        while ( $loop->have_posts() ) : $loop->the_post();
				$post_title		= get_the_title();

				include (TEMPLATEPATH . '/templates/ae-alertas/ae-alertas-function.php');	

				/*Si falta menos de dos días para la Facturación visualizar: */
				if ($proxFacturacion >= $today && $proxFacturacion <= $FacturaAlert) : 

					/* Conocer si al menos hay una facturación próxima (al día siguiente) para enviar el mail */
					$i++;
					if ($i === 1) {
						/* Sí hay al menos una alerta, entonces enviar email (Email Header) */
						/* to do cambiar a correo final alto empleo contacto */
					   	$to 				= "nayeli@queonda.com.mx";
					    $subject 			= "Alertas de facturación Alto Empleo";	

					    $headers 			= "From:" . strip_tags($to) . "\r\n";
					    $headers 		   .= "Reply-To: " . strip_tags($to) . "\r\n";
					    $headers 		   .= "MIME-Version: 1.0\r\n";
						$headers 		   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

						$message 			 = '<html style="font-family: Arial, sans-serif;"><body>';
						$message 			.= '<h1 style="display: block; margin-bottom: 20px; padding: 10px; color: #fff; text-align: center;  font-size: 20px; background-color: #00B4EF;">Pr&oacute;ximas Facturaciones</h1>';
						//echo "HEADER EMAIL <br>";
					}

					$message 			.= '<p style="color: #00B4EF; text-transform: uppercase;"><strong>' . $post_title . '</strong></p>';
					$message 			.= '<p><strong>Inicio Contrato: </strong>' . $dateContratoEsp . '</p>';
					$message 			.= '<p><strong>Periodo de Facturaci&oacute;n: </strong>' . $periodoFacturacion  . '</p>';
					$message 			.= '<p style="margin-bottom: 20px;"><strong>Pr&oacute;xima Facturaci&oacute;n: </strong>' . $proxFacturacionEsp  . '</p>';
					$message 			.= '<hr style="height: 1px; border: 0px; background-color: #00b4ef;">';
					//echo $post_title . ' <br>';

				endif;
		    endwhile; 

		    if ($i >= 1) {
				/* Sí hubo al menos una alerta, entonces enviar email (Email Footer) */
				$message 			.= '<br><p style="text-align: center; background-color: #00b4ef; display: inline-block; line-height: 35px; padding: 0 20px; border-radius: 5px;"><a style=" color: #fff; text-decoration: none;" href="' . SITEURL . 'ae_alertas/">Ver todas</a></p>';
				$message 			.= '<a style="color: #000; text-align: center; display: block;" href="' . SITEURL . '"><img style="display: inline-block; width: 200px; margin: auto;" src="' . THEMEPATH . 'images/identidad/logo.png"></a>';
				$message 			.= '</body></html>';

			    mail($to, utf8_decode($subject), utf8_decode($message), $headers);
			    //echo "FOOTER EMAIL  <br>";
			}

	   	} 
	    wp_reset_postdata();
	?>
<?php get_footer(); ?>