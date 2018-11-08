<div id="navAE" class="row">
	<div class="col s7 sm8 l4">
		<a href="<?php echo ORIGINALURL; ?>"><img class="responsive-img" src="<?php echo THEMEPATH; ?>images/identidad/logo-light.png"></a>
	</div>
	<div class="col s5 sm4 l8">
		<nav>
			<ul class="hide-on-large-and-up">
				<li><i id="btn-nav-mobile" class="icon-menu"></i></li>
				<?php if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ): ?>
					<li><i class="icon-menu btn-ae-nav"></i></li>
				<?php endif;?>	
			</ul>
			<ul id="nav-mobile" itemscope>
				<?php if (is_user_logged_in()) :
					echo "<li itemprop='actionOption' class='link'><a href='" . wp_logout_url(SITEURL) . "'>Salir</a></li>";
				endif; ?>									
			</ul>
		</nav>						
	</div>
</div>