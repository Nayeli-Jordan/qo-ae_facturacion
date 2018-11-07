<?php get_header(); 

	/* Email Header, haya o no facturaciones */
	/* to do cambiar a correo final alto empleo contacto */
   	/*$to 				= "nayeli@queonda.com.mx";*/
    $subject 		 = "Alertas de facturación Alto Empleo";

	$messageHeader 	 = '<html style="font-family: Arial, sans-serif;"><body>';
	$messageHeader 	.= '<div style="text-align: center; background-color: #00B4EF; margin-bottom: 20px;"><img style="display: inline-block; margin: auto;" src="' . THEMEPATH . 'images/email/logo-light.png"></div>';
	$messageHeader 	.= '<h1 style="display: block; margin-bottom: 30px; text-align: center;  font-size: 20px; font-weight: 700; color: #00B4EF; text-transform: uppercase;">Pr&oacute;ximas Facturaciones</h1>';

	$messageFooter	 = '<br><p style="text-align: center;"><a style="color: #00B4EF; font-weight: 700; text-decoration: none; text-transform: uppercase;" href="' . SITEURL . 'ae_alertas/">Ver todas</a></p>';
	$messageFooter  .= '<div style="text-align: center; margin-bottom: 20px;"><a style="color: #000; text-align: center; display: block;" href="' . SITEURL . '"><img style="display: inline-block; margin: auto;" src="' . THEMEPATH . 'images/email/logoae.png"></a></div>';
	$messageFooter  .= '</body></html>';

?>
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
	$terms = get_terms('tax_ejecutivo');
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach($terms as $term){
			/*Obtener header por ejecutivo */
			$to 			 = $term->description;
			$from 			 = 'nayeli@queonda.com.mx';
			$headers 		 = '';
			$headers 		.= "From:" . strip_tags($from) . "\r\n";
		    $headers   		.= "Reply-To: " . strip_tags($from) . "\r\n";
		    $headers   		.= "MIME-Version: 1.0\r\n";
			$headers   		.= "Content-Type: text/html; charset=ISO-8859-1\r\n";

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

					/*Si falta menos de dos días para la Facturación visualizar: */
					if ($proxFacturacion >= $today && $proxFacturacion <= $FacturaAlert) : 

						/* Conocer si al menos hay una facturación próxima (al día siguiente) para enviar el mail */
						$i++;

						$body 			.= '<p style="color: #00B4EF; text-transform: uppercase; font-size: 18px;"><strong>' . $post_title . '</strong></p>';
						$body 			.= '<p><strong>Inicio Contrato: </strong>' . $dateContratoEsp . '</p>';
						$body 			.= '<p><strong>Periodo de Facturaci&oacute;n: </strong>' . $periodoFacturacion  . '</p>';
						$body 			.= '<p style="margin-bottom: 20px;"><strong>Pr&oacute;xima Facturaci&oacute;n: </strong>' . $proxFacturacionEsp  . '</p>';
						/*$message 			.= '<hr style="height: 1px; border: 0px; background-color: #00b4ef;">';*/

					endif;
			    endwhile; 

			    if ($i != 0) {
			    	$message = $messageHeader . $body . $messageFooter;
					echo $to . '<br>' . $message . '<br><br><br>';
				    //mail($to, utf8_decode($subject), utf8_decode($message), $headers);
				    wp_mail($to, $subject, $message);
				}
				
		   	} 
		    wp_reset_postdata();

		} /*End foreach*/
	} ?>
<?php get_footer(); ?>