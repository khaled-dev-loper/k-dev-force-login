<?php 
/*
Plugin Name:    K-Dev Force Login
Description:    GO to 'settings > K-dev Force' and set your Login Url 
Author:         Khaled Developer
Author URI:     https://aparat.com/khaledsss
Version:        1.0
Text Domain:    k-dev-force-login
Domain Path:    /lang
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/ 
if ( ! defined('ABSPATH')) exit;  // if direct access
add_action('admin_menu', 'kdfl_add_submenu');
function kdfl_add_submenu(){
	add_submenu_page('options-general.php',__('K-dev Force Login','k-dev-force-login'),__('K-dev Force Login','k-dev-force-login'),'manage_options','k-dev-force-login','kdfl_settings_of_plugin');
}
function kdfl_settings_of_plugin(){
	if(isset($_POST) && isset($_POST['kdfl-link'])){
		$link = sanitize_text_field($_POST['kdfl-link']);
		if(get_option("kdfl-link","Na/N?$%") == "Na/N?$%"){
			add_option("kdfl-link",$link);
			echo "<div class='kd-notice' style='margin: 10px; background: #fff; padding: 10px; border: solid 1px #e0e0e0; border-right: solid 3px #8bc34a;'><label>".__('changes saved.','k-dev-force-login')."</label></div>";
		}else{
			update_option("kdfl-link",$link);
		}
	}
	?>	
	
		<form method='post'>
			<div class='kdfl-settings' style='display:flex;margin-top: 1rem;'>
				<div class='kdfl-title' style='color: #242424;
											  font-size: 14px;
											  font-family: sans-serif;
											  padding: 10px;
											  display: inline-block;
											  display: flex;
											  align-items: center;'>
					<?php echo __('Login URL:','k-dev-force-login'); ?>
				</div>
				<div class='kdfl-des' style='width: 80%;'>
					<input type='text' name='kdfl-link' placeholder='<?php echo __('example: http://example.com/login','k-dev-force-login'); ?>' style='width: 100%;' value='<?php 
						if(get_option("kdfl-link","Na/N") !== "Na/N"){
							esc_html_e(get_option("kdfl-link"));
						}
					?>'>
				</div>
			</div>
			<?php submit_button(); ?>
		</form>
	<?php 
}
function kdfl_currect_url()
{
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

add_action( 'init', 'kdfl_head' );
function kdfl_head(){
	global $wp;
	if(get_option("kdfl-link","Na/N") !== "Na/N"){
		$url = maybe_unserialize(get_option("kdfl-link"));
		if((admin_url() !== home_url($wp->request)) && ($url !== kdfl_currect_url()) && !is_user_logged_in()){
			header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");
			header("refresh:0;url=".maybe_unserialize(get_option("kdfl-link")),true,301);
		}
	}
}
?>