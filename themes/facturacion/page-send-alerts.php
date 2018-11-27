<?php get_header(); 

	/* Email Header, haya o no facturaciones */
    $subject 		 = "Alertas de facturación Alto Empleo";

	$messageHeader 	 = '<html style="font-family: Arial, sans-serif;"><body>';
	$messageHeader 	.= '<div style="text-align: center; background-color: #00B4EF; margin-bottom: 20px;"><img style="display: inline-block; margin: auto;" src="https://queonda.com.mx/ae_facturacion/wp-content/themes/alto-empleo/images/email/logo-light.png" alt="Logo Alto Empleo"></div>';
	$messageHeader 	.= '<h1 style="display: block; margin-bottom: 30px; text-align: center;  font-size: 20px; font-weight: 700; color: #00B4EF; text-transform: uppercase;">Pr&oacute;ximas Facturaciones</h1>';
	
	$messageFooter	  = '<div style="text-align: center; margin-bottom: 10px;"><p><small>Este email ha sido enviado desde el sistema de alertas de facturación de Alto Empleo. <br>Para visualizar correctamente los correos agrega este email a tu lista de remitentes seguros.</small></p></div>';
	$messageFooter  .= '<div style="text-align: center; margin-bottom: 20px;"><a style="color: #000; text-align: center; display: block;" href="' . SITEURL . '"><img style="display: inline-block; margin: auto;" src="https://queonda.com.mx/ae_facturacion/wp-content/themes/alto-empleo/images/email/logoae.png"></a></div>';
	$messageFooter  .= '</body></html>';

?>
	<!-- Al cargar la página se envía un mensaje con las alertas de facturación de hoy,mañana y pasado mañana, si no hay alertas aún así se envía el email indicando que no hay facturaciones próximas -->
	<div class="title-ae">
		<div class="container">			
			<?php include (TEMPLATEPATH . '/templates/template-nav.php'); ?>
			<h2 class="text-center">Enviar Alertas</h2>
		</div>
	</div>
	<?php if (!is_user_logged_in()) : ?> 
		<?php include (TEMPLATEPATH . '/templates/template-ae-logout.php'); ?>
	<?php endif; ?>
	<section id="enviar-alertas" class="container">
		<div class="row">
			<div class="col s12 sm10 offset-sm1 m8 offset-m2 l6 offset-l3 text-center margin-bottom">
				<h2 class="color-primary">¡Listo!</h2>
				<p>Se han enviado automáticamente las próximas facturaciones (dentro de dos días) al correo electrónico designado. <br><br><strong class="color-primary">Si recargas está página se enviarán de nuevo las alertas.</strong><br>Cierra la página o regresa al inicio.</p><br>
				<a href="<?php echo SITEURL; ?>" class="btn margin-bottom-xlarge">Regresar</a>	
			</div>		
		</div>
	
		<?php 
		$terms = get_terms('tax_ejecutivo');
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$countAlerts = 0;
			foreach($terms as $term){
				/*Obtener header por ejecutivo */
				$to 			 = $term->description;

				/* Obtener facturaciones de este Ejecutivo*/
				$body = '';

			    $args = array(
			        'post_type' 		=> 'ae_alertas',
			        'posts_per_page' 	=> -1,
			        'orderby' 			=> 'title',
			        'order' 			=> 'DESC',
					'tax_query' 		=> array(
						array(
							'taxonomy' 	=> 'tax_ejecutivo',
							'field'	   	=> 'slug',
							'terms'	 	=> $term->slug,
						)
					)
			    );
			    $loop = new WP_Query( $args );
			    if ( $loop->have_posts() ) {
			    	$i = 0;
			        while ( $loop->have_posts() ) : $loop->the_post(); 
						$post_title		= get_the_title();
						include (TEMPLATEPATH . '/templates/ae-alertas/ae-alertas-function.php');	

						/*Si falta dos días para la Facturación visualizar. Sólo se envía un correo dos días antes, NO cada día hasta la facturación */
						if ($today === $FacturaAlert) : 

							$countAlerts++;

							/* Conocer si al menos hay una facturación próxima (al día siguiente) para enviar el mail */
							$i++;

							$body 			.= '<div style="text-align: center; margin-bottom: 10px;"><p style="color: #00B4EF; text-transform: uppercase; font-size: 18px;"><strong>' . $post_title . '</strong></p>';
							/*$body 			.= '<p><strong>Inicio Contrato: </strong>' . $dateContratoEsp . '</p>';*/
							$body 			.= '<p><strong>Ejecutivo: </strong>' . $term->name . ' <small>(' . $term->description . ')</small>'  . '</p>';
							$body 			.= '<p><strong>Periodo de Facturaci&oacute;n: </strong>' . $periodoFacturacion  . '</p>';
							$body 			.= '<p style="margin-bottom: 20px;"><strong>Pr&oacute;xima Facturaci&oacute;n: </strong><strong style="color: #00B4EF">' . $proxFacturacionEsp  . '</strong></p></div>';

						endif;
				    endwhile; 

				    if ($i != 0) {
				    	$to 	 = $to . ', pruebas@altoempleo.com.mx';
				    	$message = $messageHeader . $body . $messageFooter;
						echo '<p class="color-primary"><strong>*' . $to . '</strong></p><br>' . $body . '<br>';
					    /* Enviar mail al Ejecutivo en el caso de que tenga alertas activas*/
					    wp_mail($to, $subject, $message);
					}
					
			   	} wp_reset_postdata();

			} /*End foreach*/
			if ($countAlerts === 0) {
				echo "<p class='text-center margin-bottom'><span class='color-red'>¡IMPORTANTE! </span><br>No se envió ningún correo ya que no hay alertas programadas para dentro de dos días.</p>";
			}
		} ?>
	</section>
<?php get_footer(); ?>