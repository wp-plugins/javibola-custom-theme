<?php
/*
Plugin Name: JaviBola Custom Theme Test
Plugin URI: http://javibola.com/javibola-custom-theme.zip
Description: Enabled a Custom Theme if admin is logged for a safely testing.
Version: 2.0.3
Author: JaviBola.com
Author URI: http://javibola.com/
License: GPL2
*/

function javibola_custom_theme_install(){
	$theme = wp_get_theme();
	$theme = $theme->Template;
	add_option( 'jbct_theme', $theme);
	update_option( 'jbct_theme', $theme); 
}
register_activation_hook(__FILE__,'javibola_custom_theme_install');

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
	if(get_option("jbct_theme")!= "" && get_option("jbct_theme") != "no-theme"){
		add_filter('template', 'jbct');
		add_filter('option_template', 'jbct');
		add_filter('option_stylesheet', 'jbct');
	}
	add_options_page( 'JaviBola Custom Theme Test', 'JaviBola Custom Theme Test', 'manage_options', 'jbct', 'jbct_options' );
	wp_enqueue_style( 'jbct_stylesheet', plugins_url('stylesheet.css', __FILE__) );
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
	$themes = wp_get_themes();
	
	$result .= "<form action='options-general.php?page=jbct' method='GET'>";
	$result .= "<h3 class='title'>Select a theme</h3>";
	$result .= "<p>Select a theme for display when admin is logged.</p>";
	$result .= "<div>";
	$result .= "<select name='jbct_theme'>";
	foreach ($themes as $th){
		$template = $th->Template;
		$name = $th->Name ? $th->Name : $th->Template;
		$result .= "<option ".($template == get_option("jbct_theme") ? "selected='selected'" : "")." value='$template'>$name</option>";
	}
	$result .= "</select>";
	$result .= '&nbsp;<input type="submit" name="submit" id="submit" class="button button-primary" value="Select theme">';
	$result .= "</div>";
	$result .= '<br/>';
	$theme = wp_get_theme($theme->template);
	
	// Display the selected THEME
	$result .= "<div class='theme-preview selected'>";
		$img = get_theme_root_uri().$theme->ThemeURI.'/'. $theme->template."/screenshot.png";
		$result .= '<img width="350"  src="'.$img.'" alt="Template preview">';
		$result .= '<div class="theme-name">';
			$actual = $theme->Name ? $theme->Name : $theme->Template;
			$result .= $actual;
			$result .= '<span class="theme-active">Active</span>';
		$result .= '</div>';
	$result .= '</div>';
	
	// Display others THEMES
	foreach ($themes as $th){
		$thName = $th->Name ? $th->Name : $th->Template;
		if($thName != $actual){
			$template = $th->Template;
			$name = $th->Name;
			$result.= '<a href="'. admin_url('options-general.php?page=jbct&jbct_theme='.$th->template).'">';
				$result .= "<div class='theme-preview'>";
					$img = get_theme_root_uri().$th->ThemeURI.'/'. $th->template."/screenshot.png";
					$result .= '<img width="350"  src="'.$img.'" alt="Template preview">';
					$result .= '<div class="theme-name">';
						$result .= $thName;
					$result .= '</div>';
				$result .= '</div>';
			$result .= '</a>';
		}
	}
	$result .= '<input type="hidden" name="jbct_theme_2" value="'.$theme->template.'">';
	$result .= '<input type="hidden" name="page" value="jbct">';
	$result .= '</form>';
	$result .= '</div>';
	echo $result;
}
?>