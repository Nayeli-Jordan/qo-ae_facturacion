<?php get_header(); ?>
	<div class="title-ae">
		<div class="container">			
			<?php include (TEMPLATEPATH . '/templates/template-nav.php'); ?>
			<h2 class="text-center">Alertas de facturación</h2>
		</div>
	</div>
	<?php if (!is_user_logged_in()) : ?> 
		<?php include (TEMPLATEPATH . '/templates/template-ae-logout.php'); ?>
	<?php else: ?>
		<section class="[ container ] margin-bottom-40">
			<div class="row">
			<?php
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			    $args = array(
			        'post_type' 		=> 'ae_alertas',
			        'posts_per_page' 	=> 30,
			        'orderby' 			=> 'date',
			        'order' 			=> 'DESC',
			        'paged' 			=> $paged,
			        );
			    $loop = new WP_Query( $args );
			    if ( $loop->have_posts() ) {
			        while ( $loop->have_posts() ) : $loop->the_post(); 
						include (TEMPLATEPATH . '/templates/ae-alertas/ae-alertas-function.php'); ?>	
					
					<div class="col s12 m6 l4 content-card-alerta margin-bottom-small <?php  if ($proxFacturacion >= $today && $proxFacturacion <= $FacturaAlert) : echo "weekAlertActive"; endif; ?>">
						<div class="card-carta card-alerta">
							<p class="title-cart"><?php the_title() ?></p>
							<div class="card-body">
								<?php 
								$ejecutivo = '';
								$terms = get_the_terms($post->ID, 'tax_ejecutivo');
								if ( is_array( $terms ) ) {
									foreach($terms as $term){
										$ejecutivo .= $term->name . ' <small>(' . $term->description . ')</small>';
									}
								}
								?>
								<p><strong>Ejecutivo: </strong><?php echo $ejecutivo; ?></p>
								<p><strong>Inicio Contrato: </strong><?php echo $dateContratoEsp; ?></p>
								<p class="margin-bottom-xxsmall"><strong>Periodo facturación: </strong>
									<?php if( $periodoFacturacion === "Personalizada" ) : ?>
										Cada <?php echo $periodoPersonalizada; ?> día(s)
									<?php else: ?>
										<?php echo $periodoFacturacion; ?>
									<?php endif; ?>
								</p>
								<!-- Imprimir Fecha de siguiente Facturación -->
								<p class="text-center"><strong class="width-100p">
									<?php if ( $today > $proxFacturacion) :
										echo "Última facturación: ";
									else:
										echo "Próxima facturación: ";
									endif; ?>
									 <span class='weekAlert'><i class='icon-attention color-red'></i><span>Facturación cercana</span></span></strong><br><?php echo $proxFacturacionEsp; ?></p>			
							</div>						
						</div>
					</div> <!-- End card facturación --> 		

			    <?php  endwhile; ?>


			    <div class="col s12 pagination margin-top-large">
			    <?php 
				    $total_pages = $loop->max_num_pages;
				    if ($total_pages > 1){
				        $current_page = max(1, get_query_var('paged'));
				        echo paginate_links(array(
				            'base' => get_pagenum_link(1) . '%_%',
				            'format' => 'page/%#%',
				            'current' => $current_page,
				            'total' => $total_pages,
				            'prev_text'    => __('<'),
				            'next_text'    => __('>'),
				        ));
				    }
			    ?>		    	
			    </div>
			    <?php } 
			    wp_reset_postdata();
			?>					
			</div>
			<div class="text-center"><a href="<?php echo SITEURL; ?>send-alerts" class="btn">Enviar alerta</a></div>
		</section>
	<?php endif; ?>

<?php get_footer(); ?>