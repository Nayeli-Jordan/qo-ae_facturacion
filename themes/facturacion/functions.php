<?php 

/**
* Define paths to javascript, styles, theme and site.
**/
define( 'JSPATH', get_stylesheet_directory_uri() . '/js/' );
define( 'THEMEPATH', get_stylesheet_directory_uri() . '/' );
define( 'SITEURL', get_site_url() . '/' );
define( 'ORIGINALURL', 'http://www.altoempleo.com.mx/' );


/*------------------------------------*\
    #SNIPPETS
\*------------------------------------*/
require_once( 'inc/pages.php' );
require_once( 'inc/post-types.php' );
require_once( 'inc/taxonomies.php' );

/*------------------------------------*\
    #GENERAL FUNCTIONS
\*------------------------------------*/

/**
* Enqueue frontend scripts and styles
**/
add_action( 'wp_enqueue_scripts', function(){
 
    wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.2.1.min.js', array(''), '2.1.1', true );
    wp_enqueue_script( 'ae_parsley', JSPATH.'parsley.min.js', array(), '1.0', true );
    wp_enqueue_script( 'ae_functions', JSPATH.'functions.js', array(), '1.0', true );
 
    wp_localize_script( 'ae_functions', 'siteUrl', SITEURL );
    wp_localize_script( 'ae_functions', 'theme_path', THEMEPATH );
 
});

/**
* Configuraciones WP
*/

// Agregar css y js al administrador
function load_custom_files_wp_admin() {
    wp_register_style( 'ae_wp_admin_css', THEMEPATH . '/admin/admin-style.css', false, '1.0.0' );
    wp_enqueue_style( 'ae_wp_admin_css' );

    wp_register_script( 'ae_wp_admin_js', THEMEPATH . 'admin/admin-script.js', false, '1.0.0' );
    wp_enqueue_script( 'ae_wp_admin_js' );  
}
add_action( 'admin_enqueue_scripts', 'load_custom_files_wp_admin' );

//Change style login
add_action( 'login_enqueue_scripts', 'load_custom_files_wp_admin' );
function my_login_logo_url() {
  return home_url();
}//end my_login_logo_url()
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
  return 'Alto Empleo';
}//end my_login_logo_url_title()
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

//Habilitar thumbnail en post
add_theme_support( 'post-thumbnails' ); 

//Habilitar menú (aparece en personalizar)
add_action('after_setup_theme', 'add_top_menu');
function add_top_menu(){
    register_nav_menu('top_menu',__('Menú inicial'));
    register_nav_menu('ae_menu',__('Menú Alto Empleo'));
}

/* Cambiar posición elementos menú */
function ae_custom_menu_order() {
    return array( 'index.php', 'upload.php', 'edit.php?post_type=page', 'themes.php', 'plugins.php', 'tools.php', 'options-general.php', 'users.php');
}
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'ae_custom_menu_order' );


/**
* Optimización
*/

// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );


/**
* SEO y Analitics
**/

//Código Analitics
// function add_google_analytics() {
//     echo '<script src="https://www.google-analytics.com/ga.js" type="text/javascript"></script>';
//     echo '<script type="text/javascript">';
//     echo 'var pageTracker = _gat._getTracker("UA-117075138-1");';
//     echo 'pageTracker._trackPageview();';
//     echo '</script>';
// }
// add_action('wp_footer', 'add_google_analytics');

/* Aplaza el análisis de JavaScript para una carga más rápida */
if(!is_admin()) {
    // Move all JS from header to footer
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
}

/**
* Perfiles - Permisos
*/
//Hide item admin menu for certain user profile
function qo_remove_menu_items() {
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('edit-comments.php'); // Comments
    //Editor (Esther y Verónica)
    if( current_user_can( 'editor' ) ):
        
        remove_menu_page('edit.php?post_type=page'); // Pages  
        remove_menu_page('tools.php'); // Tools

    endif;
}
add_action( 'admin_menu', 'qo_remove_menu_items' );


// Hide Administrator Bar Users, no admin
add_action('after_setup_theme', 'bp_no_admin_bar');

function bp_no_admin_bar() {
    //if (current_user_can('administrator') || !current_user_can('editor')) { /*&& !is_admin() */
    global $current_user;
    wp_get_current_user();
    if ( user_can( $current_user, "subscriber" ) ) {
        add_filter( 'show_admin_bar', '__return_false' );
    }
}

//Login redirección
add_action('wp_login','my_custom_login_redirect');
function my_custom_login_redirect(){
  wp_redirect( home_url() );    
  exit();
}

// Logout redirección
add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

/* Ingresa automaticamente a la cuenta una vez que el usuario se registra*/
function auto_login_new_user( $user_id ) {
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_redirect( home_url('#cuenta-creada') );
    exit;
}
add_action( 'user_register', 'auto_login_new_user' );


/* Send mail by SMTP */
add_action( 'phpmailer_init', 'send_smtp_email' );
function send_smtp_email( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = SMTP_HOST;
    $phpmailer->SMTPAuth   = SMTP_AUTH;
    $phpmailer->Port       = SMTP_PORT;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->Username   = SMTP_USERNAME;
    $phpmailer->Password   = SMTP_PASSWORD;
    $phpmailer->From       = SMTP_FROM;
    $phpmailer->FromName   = SMTP_FROMNAME;
}

/* $message wp_mail in html (not text/plain) */
function transforme_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','transforme_content_type' );


/**
* CUSTOM FUNCTIONS ALERTAS FACTURACIÓN
*/
//Modificar placeholder título ae_alertas
function cambiar_titulo_alertas($title){
    $screen = get_current_screen();
    if ($screen->post_type == 'ae_alertas') {
        $title = 'Cliente';
    }
    return $title;
}
add_filter( 'enter_title_here', 'cambiar_titulo_alertas' );


//Custom fields Facturación
add_action( 'add_meta_boxes', 'ae_alertas_custom_metabox' );
function ae_alertas_custom_metabox(){
    add_meta_box( 'ae_alertas_meta', 'Información Facturación', 'display_ae_alertas_atributos', 'ae_alertas', 'advanced', 'default');
}

function display_ae_alertas_atributos( $ae_alertas ){
    $inicioContrato  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_inicioContrato', true ) );
    $periodoFacturacion  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoFacturacion', true ) );
    $periodoQuincenal1  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoQuincenal1', true ) );
    $periodoQuincenal2  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoQuincenal2', true ) );
    $periodoPersonalizada  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPersonalizada', true ) );
    $periodoPreestablecida1  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida1', true ) );
    $periodoPreestablecida2  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida2', true ) );
    $periodoPreestablecida3  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida3', true ) );
    $periodoPreestablecida4  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida4', true ) );
    $periodoPreestablecida5  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida5', true ) );
    $periodoPreestablecida6  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida6', true ) );
    $periodoPreestablecida7  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida7', true ) );
    $periodoPreestablecida8  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida8', true ) );
    $periodoPreestablecida9  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida9', true ) );
    $periodoPreestablecida10  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida10', true ) );
    $periodoPreestablecida11  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida11', true ) );
    $periodoPreestablecida12  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida12', true ) );
    $periodoPreestablecida13  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida13', true ) );
    $periodoPreestablecida14  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida14', true ) );
    $periodoPreestablecida15  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida15', true ) );
    $periodoPreestablecida16  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida16', true ) );
    $periodoPreestablecida17  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida17', true ) );
    $periodoPreestablecida18  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida18', true ) );
    $periodoPreestablecida19  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida19', true ) );
    $periodoPreestablecida20  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida20', true ) );
    $periodoPreestablecida21  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida21', true ) );
    $periodoPreestablecida22  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida22', true ) );
    $periodoPreestablecida23  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida23', true ) );
    $periodoPreestablecida24  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida24', true ) );
    $periodoPreestablecida25  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida25', true ) );
    $periodoPreestablecida26  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida26', true ) );
    $periodoPreestablecida27  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida27', true ) );
    $periodoPreestablecida28  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida28', true ) );
    $periodoPreestablecida29  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida29', true ) );
    $periodoPreestablecida30  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida30', true ) );
    $periodoPreestablecida31  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida31', true ) );
    $periodoPreestablecida32  = esc_html( get_post_meta( $ae_alertas->ID, 'ae_alertas_periodoPreestablecida32', true ) );
?>
    <table class="ae-custom-fields">
        <tr>
            <th>
                <label>Inicio del contrato:</label>
                <input type="date" name="ae_alertas_inicioContrato" value="<?php echo $inicioContrato; ?>" required>
            </th>
            <th>
                <label>Periodo de facturación cada: <span class="fz-xsmall">(1)</span></label>
                <select name="ae_alertas_periodoFacturacion" id="ae_alertas_periodoFacturacion" required>
                    <option value="" <?php selected($periodoFacturacion, ''); ?>></option>
                    <option value="Semanal" id="Semanal" <?php selected($periodoFacturacion, 'Semanal'); ?>>Semanal</option>
                    <option value="Quincenal" id="Quincenal" <?php selected($periodoFacturacion, 'Quincenal'); ?>>Quincenal</option>
                    <option value="Mensual" id="Mensual" <?php selected($periodoFacturacion, 'Mensual'); ?>>Mensual</option>
                    <option value="Personalizada" id="Personalizada" <?php selected($periodoFacturacion, 'Personalizada'); ?>>Personalizada</option>
                    <option value="Preestablecida" id="Preestablecida" <?php selected($periodoFacturacion, 'Preestablecida'); ?>>Preestablecida</option>
                </select>
            </th>
        </tr>
    </table>
    <table id="facturacionQuincenal" class="ae-custom-fields margin-top-large bg-gray-light">
        <tr><th colspan="2"><label class="text-center uppercase margin-top-small">Sólo Periodo Quincenal</label></th></tr>
        <tr><th colspan="2"><label class="text-center">Selecciona los días de pago:</label></th></tr>
        <tr id="periodoQuincenal">
            <th>
                <label>Primera Quincena del mes:</label>
                <select name="ae_alertas_periodoQuincenal1" id="ae_alertas_periodoQuincenal1" required>
                    <option value="" <?php selected($periodoQuincenal1, ''); ?>></option>
                    <?php $day1 = 1;
                    while ( $day1 < 16) { ?>
                        <option value="<?php echo $day1; ?>" <?php selected($periodoQuincenal1, $day1); ?> id="<?php echo $day1; ?>"><?php echo $day1; ?></option>
                        <?php $day1++;
                    } ?>
                </select>
            </th>
            <th>
                <label>Segunda Quincena del mes:</label>
                <select name="ae_alertas_periodoQuincenal2" id="ae_alertas_periodoQuincenal2" required>
                    <option value="" <?php selected($periodoQuincenal2, ''); ?>></option>
                    <?php $day2 = 16;
                    while ( $day2 < 32) { ?>
                        <option value="<?php echo $day2; ?>" <?php selected($periodoQuincenal2, $day2); ?>><?php echo $day2; ?></option>
                        <?php $day2++;
                    } ?>
                </select>
            </th>
        </tr>
    </table>
    <table id="facturacionPersonalizada" class="ae-custom-fields margin-top-large bg-gray-light">
        <tr><th colspan="2"><label class="text-center uppercase margin-top-small">Sólo Facturación Personalizada</label></th></tr>
        <tr id="periodoQuincenal">
            <th>
                <label class="text-center">¿Cada cuantos días?:</label>
                <select name="ae_alertas_periodoPersonalizada" id="ae_alertas_periodoPersonalizada" required>
                    <option value="" <?php selected($periodoPersonalizada, ''); ?>></option>
                    <?php $day1 = 1;
                    while ( $day1 < 31) { ?>
                        <option value="<?php echo $day1; ?>" <?php selected($periodoPersonalizada, $day1); ?> id="<?php echo $day1; ?>"><?php echo $day1; ?></option>
                        <?php $day1++;
                    } ?>
                </select>
            </th>
        </tr>
    </table>
    <table id="facturacionPreestablecida" class="ae-custom-fields margin-top-large bg-gray-light">
        <tr><th colspan="2"><label class="text-center uppercase margin-top-small">Sólo Facturación Preestablecida</label></th></tr>
        <tr id="periodoPreestablecida">
            <th>
                <label class="text-center">Establece las fechas:</label>
                <?php $count = 1;                
                while ( $count < 33) { 
                    $periodoPreestablecida = ${'periodoPreestablecida' . $count}; ?>
                    <div>
                        <label>Fecha <?php echo $count; ?></label>
                        <input type="date" name="ae_alertas_periodoPreestablecida<?php echo $count; ?>" value="<?php echo $periodoPreestablecida; ?>" <?php if ($count === 1): echo "required"; endif; ?>>
                    </div>

                    <?php $count++;
                } ?>
            </th>
        </tr>
    </table>
    <table class="ae-custom-fields margin-top-large">        
        <tr>
            <th>
                <label>(1)</label>
                <label><strong>Semanal:</strong> Se repite cada semana.</label>
                <label><strong>Quincenal:</strong> Se repite dos veces al mes en las fechas estableciadas.</label>
                <label><strong>Mensual:</strong> Se repite cada mes.</label>
                <label><strong>Personalizada:</strong> Se repite cada que pasan la cantidad de días seleccionados.</label>
                <label><strong>Preestablecida:</strong> Se preestablecen las fechas de facturación al año.</label>
            </th>
        </tr>
    </table>
<?php }

add_action( 'save_post', 'ae_alertas_save_metas', 10, 2 );
function ae_alertas_save_metas( $idae_alertas, $ae_alertas ){
    //Comprobamos que es del tipo que nos interesa
    if ( $ae_alertas->post_type == 'ae_alertas' ){
    //Guardamos los datos que vienen en el POST
        if ( isset( $_POST['ae_alertas_inicioContrato'] ) ){
            update_post_meta( $idae_alertas, 'ae_alertas_inicioContrato', $_POST['ae_alertas_inicioContrato'] );
        }
        if ( isset( $_POST['ae_alertas_periodoFacturacion'] ) ){
            update_post_meta( $idae_alertas, 'ae_alertas_periodoFacturacion', $_POST['ae_alertas_periodoFacturacion'] );
        }
        if ( isset( $_POST['ae_alertas_periodoQuincenal1'] ) ){
            update_post_meta( $idae_alertas, 'ae_alertas_periodoQuincenal1', $_POST['ae_alertas_periodoQuincenal1'] );
        }
        if ( isset( $_POST['ae_alertas_periodoQuincenal2'] ) ){
            update_post_meta( $idae_alertas, 'ae_alertas_periodoQuincenal2', $_POST['ae_alertas_periodoQuincenal2'] );
        }
        if ( isset( $_POST['ae_alertas_periodoPersonalizada'] ) ){
            update_post_meta( $idae_alertas, 'ae_alertas_periodoPersonalizada', $_POST['ae_alertas_periodoPersonalizada'] );
        }
        $count = 1;
        while ( $count < 33) {
            if ( isset( $_POST['ae_alertas_periodoPreestablecida' . $count] ) ){
                update_post_meta( $idae_alertas, 'ae_alertas_periodoPreestablecida' . $count, $_POST['ae_alertas_periodoPreestablecida' . $count] );
            }                      
            $count++;
        } 
    }
}


/*-------------------------------------------------------------------------------
    Custom Columns WP-ADMIN
-------------------------------------------------------------------------------*/
/*
** Promotor
*/
add_filter( 'manage_ae_empleados_posts_columns', 'set_custom_edit_ae_empleados_columns' );
function set_custom_edit_ae_empleados_columns($columns) {
    $columns['ae_sexo'] = __( 'Sexo', 'aempleo' );
    $columns['ae_rfc'] = __( 'RFC', 'aempleo' );
    $columns['ae_ingreso'] = __( 'Fecha Ingreso', 'aempleo' );
    $columns['ae_imss'] = __( 'No. IMSS', 'aempleo' );
    $columns['ae_empresa'] = __( 'Empresa', 'aempleo' );

    return $columns;
}

add_action( 'manage_ae_empleados_posts_custom_column' , 'custom_ae_empleados_column', 10, 2 );
function custom_ae_empleados_column( $column, $post_id ) {
    switch ( $column ) {
        case 'ae_sexo' :
            $sexo  = get_post_meta( $post_id, 'ae_empleados_sexo', true );
            if( $sexo != "" )
                echo $sexo;
            else
                echo "-";
            break;
        case 'ae_rfc' :
            $rfc  = get_post_meta( $post_id, 'ae_empleados_rfc', true );
            if( $rfc != "")
                echo $rfc;
            else
                echo "-";
            break;
        case 'ae_ingreso' :
            $fecha_ingreso  = get_post_meta( $post_id, 'ae_empleados_fecha_ingreso', true );
            if( $fecha_ingreso != "")
                echo $fecha_ingreso;
            else
                echo "-";
            break;
        case 'ae_imss' :
            $no_seguro  = get_post_meta( $post_id, 'ae_empleados_no_seguro', true );
            if( $no_seguro != "")
                echo $no_seguro;
            else
                echo "-";
            break;
        case 'ae_empresa' :
            $empresa  = get_post_meta( $post_id, 'ae_empleados_empresa', true );
            if( $empresa != "")
                echo $empresa;
            else
                echo "-";
            break;
    }
}