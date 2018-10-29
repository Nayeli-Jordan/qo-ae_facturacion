<?php get_header(); ?>
	<div class="title-ae">
		<div class="container">			
			<?php include (TEMPLATEPATH . '/templates/template-nav.php'); ?>
			<h2 class="text-center">Página no encontrada</h2>
		</div>
	</div>
	<section class="[ container ] text-center">
		<p class="fz-large margin-bottom-small">¡Lo sentimos!</p> 
		<p class="fz-medium margin-bottom-small">La página que estás buscando no existe. <br></p>
		<p><a href="<?php echo ORIGINALURL; ?>">Volver al inicio</a></p>
	</section>
<?php get_footer(); ?>