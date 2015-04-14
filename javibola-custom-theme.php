<?php
/*
Plugin Name: JaviBola Custom Theme Test
Plugin URI: http://javibola.com/javibola-custom-theme.zip
Description: Enabled a Custom Theme if admin is logged for a safely testing.
Version: 1.3
Author: JaviBola.com
Author URI: http://javibola.com
License: GPL2
*/

function javibola_custom_theme_install(){
	//Actualizamos la variable en la instalación al tema actual.
	add_option( 'jbct_theme', get_current_theme());
	update_option( 'jbct_theme', get_current_theme());
}
register_activation_hook(__FILE__,'javibola_custom_theme_install');


add_filter('template', 'jbct');
add_filter('option_template', 'jbct');
add_filter('option_stylesheet', 'jbct');
function jbct($theme) {
	if ( current_user_can('manage_options') ) {
		$theme = get_option("jbct_theme");
	}
	return $theme;
}
	
add_action( 'admin_menu', 'jbct_menu' );

function jbct_menu() {
	add_options_page( 'JaviBola Custom Theme', 'JaviBola Custom Theme', 'manage_options', 'jbct', 'jbct_options' );
}

if(isset($_GET["jbct_theme"])){
	update_option( 'jbct_theme', $_GET["jbct_theme"]);
}

function jbct_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$result = '<div class="wrap">';
	$result .= '<h2>JaviBola Custom Theme Options</h2>';
	if(isset($_GET["jbct_theme"])){
		$result .= '<div class="updated" style="padding:20px;">Tema actualizado correctamente!</div>';
	}
	$result .= '<div class="">Tema actual para el admin: <b>'.get_option("jbct_theme").'</b></div>';

	$directorio = get_template_directory();
	
	$directorio = explode("/", $directorio);
	
	array_pop($directorio);
	$directorio = implode("/", $directorio);
	
	
	if ($gestor = opendir($directorio)) {
		//$result .= "Gestor de directorio: $directorio<br/>";
		$result .= "<form action='options-general.php?page=jbct' method='GET'>";
		$result .= "<h3 class='title'>Selecciona un tema</h3>";
		$result .= "<p>Selecciona uno de los temas para que sea visualizado cuando el admin esté registrado.</p>";
		$result .= "<div>";
		$result .= "<select name='jbct_theme'>";
		$result .= "<option value='no-theme'>Sin tema</option>";
		while (false !== ($entrada = readdir($gestor))) {
			if(is_dir($directorio."/".$entrada) && $entrada != "." && $entrada != ".."){
				$result .= "<option ".($entrada == get_option("jbct_theme") ? "selected='selected'" : "")." value='$entrada'>$entrada</option>";
			}
		}
		$result .= "</select>";
		$result .= "</div>";
	}
	$result .= '<br/>';
	$result .= '<input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios">';
	$result .= '<input type="hidden" name="page" value="jbct">';
	$result .= '</form>';
	$result .= '</div>';
	
	echo $result;
}
?>