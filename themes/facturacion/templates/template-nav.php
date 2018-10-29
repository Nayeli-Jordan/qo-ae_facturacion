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
				<div class="close-nav-mobile hide-on-large-and-up"><span><i class="icon-cancel"></i></span></div>			
				<li itemprop="actionOption"><a href="<?php echo ORIGINALURL; ?>">Inicio</a></li>
				<?php if (!is_user_logged_in()) :
					echo "<li itemprop='actionOption' class='link open-modal' id='ingresa'>Ingresa</li>";
				endif; ?>
				<?php if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ): ?>
					<li class="hide-on-med-and-down"><i class="icon-menu btn-ae-nav"></i></li>
				<?php endif;?>										
			</ul>
		</nav>						
	</div>
</div>

<?php if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ): ?>
	<div class="ae-nav">
		<div class="close-ae-nav"><span>x</span></div>
		<nav>
			<ul itemscope>
				<li itemprop="actionOption"><a class="
					<?php if ( is_post_type_archive( 'ae_candidatos' ) ) { echo 'active'; } ?>
					" href="<?php echo ORIGINALURL; ?>ae_candidatos">Base de talento</a></li>		
				<li itemprop="actionOption"><a class="<?php if ( is_post_type_archive( 'ae_cartas' ) ) { echo 'active'; } ?>" href="<?php echo ORIGINALURL; ?>ae_cartas">Cartas</a></li>
				<li itemprop="actionOption" class="subItem"><a class="<?php if ( is_post_type_archive( 'ae_credencial' ) ) { echo 'active'; } ?>" href="<?php echo ORIGINALURL; ?>ae_credencial">Credenciales</a></li>
				<li itemprop="actionOption" class="subItem"><a class="<?php if ( is_post_type_archive( 'ae_rgafete' ) ) { echo 'active'; } ?>" href="<?php echo ORIGINALURL; ?>ae_rgafete">Reglamento gafete</a></li>
				<li itemprop="actionOption" class="subItem"><a class="<?php if ( is_post_type_archive( 'ae_sacceso' ) ) { echo 'active'; } ?>" href="<?php echo ORIGINALURL; ?>ae_sacceso">Solicitud de acceso</a></li>
				<!-- Redirección QO -->
				<li itemprop="actionOption"><a href="<?php echo SITEURL; ?>">Alertas Facturación</a></li>
				<br>
				<li itemprop="actionOption"><a href="<?php echo ORIGINALURL; ?>ae-login/edit.php?post_type=ae_candidatos" target="_blank">Agregar candidato</a></li>	
				<li itemprop="actionOption"><a href="<?php echo ORIGINALURL; ?>ae-login/edit.php?post_type=ae_cartas" target="_blank">Agregar carta</a></li>
				<li itemprop="actionOption" class="subItem"><a href="<?php echo ORIGINALURL; ?>ae-login/edit.php?post_type=ae_credencial" target="_blank">Agregar credencial</a></li>
				<li itemprop="actionOption" class="subItem"><a href="<?php echo ORIGINALURL; ?>ae-login/edit.php?post_type=ae_rgafete" target="_blank">Agregar reglamento</a></li>
				<li itemprop="actionOption" class="subItem"><a href="<?php echo ORIGINALURL; ?>ae-login/edit.php?post_type=ae_sacceso" target="_blank">Agregar solicitud</a></li>
				<!-- Redirección QO -->
				<li itemprop="actionOption"><a href="<?php echo SITEURL; ?>ae-login/edit.php?post_type=ae_alertas" target="_blank">Agregar alerta facturación</a></li>
				<li itemprop="actionOption"><a href="<?php echo SITEURL; ?>send-alerts" target="_blank">Enviar alerta facturación</a></li>
			</ul>		
		</nav>
	</div>
<?php endif;?>	

<?php /* User register validación y envio */
if (isset( $_POST['submit'] )) {
	global $reg_errors;
	$reg_errors = new WP_Error;

	$user = sanitize_text_field($_POST['user']);
	$email = sanitize_email($_POST['email']);
	$password = sanitize_text_field($_POST['password']);
	$confirm_password = sanitize_text_field($_POST['confirm-password']);

	echo "<div class='notice-error'>";

		/*La comprobación inicialmente se está haciendo con parsley, esto es una manera más de verificar */
		//Comprobamos que los campos obligatorios no están vacios
		if ( empty( $user ) ) {
			$reg_errors->add("empty-user", "<p>El campo nombre es obligatorio</p>");
		}
		if ( empty( $email ) ) {
			$reg_errors->add("empty-email", "<p>El campo e-mail es obligatorio</p>");
		}
		if ( empty( $password ) ) {
			$reg_errors->add("empty-password", "<p>El campo contraseña es obligatorio</p>");
		}
		if ( empty( $confirm_password ) ) {
			$reg_errors->add("empty-password2", "<p>El campo confirmar contraseña es obligatorio</p>");
		}

		//Comprobamos que el email tenga un formato de email válido
		if ( !is_email( $email ) ) {
			$reg_errors->add( "invalid-email", "<p>El e-mail no tiene un formato válido</p>" );
		}
		//Comprobamos que las contraseñas sean iguales  
		if ( $password != $confirm_password ) {
			$reg_errors->add("diferent-password", "<p>Las contraseñas no coinciden</p>");
		}

		/* Comprobamos que el email no haya sido registrado antes*/
		if ( email_exists( $email ) ) {
			$reg_errors->add( "exisist-email", "<p>Ya hay una cuenta registrada con el email <strong>" . $email ."</strong>. <span id='ingresa' class='open-modal font-strong color-primary cursor-pointer'>Ingresa</span> o <a class='font-strong color-primary cursor-pointer' href='" .  wp_lostpassword_url() . "'>recupera tu contraseña</a></p>" );
		}

		if ( is_wp_error( $reg_errors ) ) {
			if (count($reg_errors->get_error_messages()) > 0) {
				foreach ( $reg_errors->get_error_messages() as $error ) {
					echo $error;
				}
			}
		}

		if (count($reg_errors->get_error_messages()) == 0) {

			$userdata = array(
				'user_login' => $user,
				'user_email' => $email,
				'user_pass' => $password
			);

			$user_id = wp_insert_user( $userdata );

			//Si todo ha ido bien, agregamos los campos adicionales
			if ( ! is_wp_error( $user_id ) ) { 
				wp_new_user_notification( $user_id, 'both' );
			}
		}
	echo "</div>";
}?>