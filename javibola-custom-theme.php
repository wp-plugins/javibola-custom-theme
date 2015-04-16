<?php
/*
Plugin Name: JaviBola Custom Theme Test
Plugin URI: http://javibola.com/javibola-custom-theme.zip
Description: Enabled a Custom Theme if admin is logged for a safely testing.
Version: 1.7
Author: JaviBola.com
Author URI: http://javibola.com/
License: GPL2
*/
function javibola_custom_theme_install(){
	$theme = wp_get_theme( $stylesheet, $theme_root );
	$theme = $theme->Template;
	add_option( 'jbct_theme', $theme);
	update_option( 'jbct_theme', $theme);
}
register_activation_hook(__FILE__,'javibola_custom_theme_install');
if(get_option("jbct_theme")!= "" && get_option("jbct_theme") != "no-theme"){
	add_filter('template', 'jbct');
	add_filter('option_template', 'jbct');
	add_filter('option_stylesheet', 'jbct');
}
function jbct($theme) {
	if ( current_user_can('manage_options') ) {
		$theme = get_option("jbct_theme");
	}
	if($theme != "" && $theme != "no-theme"){
		return $theme;
	}
}
add_action( 'admin_menu', 'jbct_menu' );
function jbct_menu() {
	add_options_page( 'JaviBola Custom Theme Test', 'JaviBola Custom Theme Test', 'manage_options', 'jbct', 'jbct_options' );
}
if(isset($_GET["jbct_theme"])){
	update_option( 'jbct_theme', $_GET["jbct_theme"]);
}
function jbct_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	$result = '<div class="wrap">';
	$result .= '<h2>JaviBola Custom Theme Test Options</h2>';
	if(isset($_GET["jbct_theme"])){
		$result .= '<div class="updated" style="padding:20px;">Theme updated!</div>';
	}
	$result .= '<div class="">Current admin theme: <b>';
	if(get_option("jbct_theme")!= "" && get_option("jbct_theme") != "no-theme"){
	$result.=get_option("jbct_theme");
	}else{
		$result .= get_current_theme();
	}
	$result.='</b></div>';
	$themes = wp_get_themes();
	$result .= "<form action='options-general.php?page=jbct' method='GET'>";
	$result .= "<h3 class='title'>Select a theme</h3>";
	$result .= "<p>Select a theme for display when admin is logged.</p>";
	$result .= "<div>";
	$result .= "<select name='jbct_theme'>";
	foreach ($themes as $th){
		$template = $th->Template;
		$name = $th->Name;
		$result .= "<option ".($template == get_option("jbct_theme") ? "selected='selected'" : "")." value='$template'>$name</option>";
	}
	$result .= "</select>";
	$result .= "</div>";
	$result .= '<br/>';
	$result .= '<input type="submit" name="submit" id="submit" class="button button-primary" value="Save">';
	$result .= '<input type="hidden" name="page" value="jbct">';
	$result .= '</form>';
	$result .= '</div>';
	echo $result;
}
?>