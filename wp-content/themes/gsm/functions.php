<?php

$themename = "Anthology";
$shortname = "anthology";

$functions_path= TEMPLATEPATH . '/functions/';
add_action('admin_menu', 'mytheme_add_admin');

if(is_admin()){
	require('update-notifier.php');
}

/* INCLUDE THE FUNCTIONS FILES */
require_once ($functions_path.'general.php');  //some main common functions
require_once ($functions_path.'sidebars.php');  //the sidebar functionality
require_once ($functions_path.'options.php');  //the theme options functionality
require_once ($functions_path.'portfolio.php');  //portfolio functionality
require_once ($functions_path.'comments.php');  //the comments functionality
require_once ($functions_path.'meta.php');  //adds the custom meta fields to the posts and pages
require_once ($functions_path.'shortcodes.php');  //the shortcodes functionality

/* DEFINE CONSTANTS */
define("PEXETO_FUNCTIONS_URL", get_template_directory_uri().'/functions/');
$uploadsdir=wp_upload_dir();
define("PEXETO_UPLOADS_URL", $uploadsdir[url]);



add_action('admin_enqueue_scripts', 'pexeto_admin_init');

/**
 * Load the CSS and JavaScript files needed for formatting the elements.
 */
function pexeto_admin_init(){
	global $current_screen;
	
	//enqueue the script and CSS files for the TinyMCE editor formatting buttons
	if($current_screen->base=='post'){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-dialog');
		add_editor_style('functions/formatting-buttons/custom-editor-style.css');
	}
}


function head_add(){

  echo '<script type="text/javascript" src="/wp-content/themes/gsm/script/jquery.tweet.js"></script>';
      ?>
      <script type="text/javascript">
        jQuery(function($) {
          $(".tweet").tweet({
            username: "grafstress",
            join_text: "auto",
            avatar_size: 32,
            count: 2,
            auto_join_text_default: "",
            auto_join_text_ed: "",
            auto_join_text_ing: "",
            auto_join_text_reply: "we replied to",
            auto_join_text_url: "",
            loading_text: ""
          });
        });
      </script>
    <?php
}
add_action('wp_head', 'head_add');

function admin_head_add()
{
	$path= get_template_directory_uri().'/functions/';
	if(isset($_GET['page']) && $_GET['page']=='functions.php'){
		
	 
		echo '<script type="text/javascript" src="'.$path.'script/jquery-1.4.js"></script>';
		echo '<script type="text/javascript" src="'.$path.'script/jquery-ui.js"></script>';
		echo '<script type="text/javascript" src="'.$path.'script/ajaxupload.js"></script>';
		echo '<script type="text/javascript" src="'.$path.'script/options.js"></script>';
		echo '<script type="text/javascript" src="'.$path.'script/colorpicker.js"></script>';

		$uploadsdir=wp_upload_dir();
		$uploadsurl=$uploadsdir[url];
		
		echo '<script type="text/javascript">jQuery(document).ready(function($){
			pexetoOptions.init();
		
			pexetoOptions.loadUploader(jQuery("#thumbUpload"), "'.$path.'upload-handler.php", "'.$uploadsurl.'");
			pexetoOptions.loadUploader(jQuery("#thumUploadBig"), "'.$path.'upload-handler.php", "'.$uploadsurl.'");
			pexetoOptions.loadUploader(jQuery("#nivoUpload"), "'.$path.'upload-handler.php", "'.$uploadsurl.'");
		});</script>';
	}
	
	
	global $current_screen;
	
	//call the scripts needed for the add/edit Portfolio post
	if($current_screen->id=='portfolio'){
		echo '<script type="text/javascript" src="'.$path.'script/ajaxupload.js"></script>';
		echo '<script type="text/javascript" src="'.$path.'script/options.js"></script>';
	}

	if($current_screen->id=='portfolio'||$current_screen->id=='post'||$current_screen->id=='page'||(isset($_GET['page']) && $_GET['page']=='functions.php')){
		echo '<link rel="stylesheet" href="'.$path.'css/admin_style.css" type="text/css" charset="utf-8" />';
	}

	echo '<link rel="stylesheet" href="'.$path.'css/colorpicker.css" type="text/css" charset="utf-8" />';

}


	add_action('admin_head', 'admin_head_add');


/**
 * Add the Theme Options Page
 */
function mytheme_add_admin() {

	global $themename, $shortname, $options;

	foreach ($options as $value) {
		if(get_option($value['id'])=='' && isset($value['std'])){
			update_option( $value['id'], $value['std']);
		}
	}

	if ( $_GET['page'] == basename(__FILE__) ) {
		if ( 'save' == $_REQUEST['action'] ) {
			if ( empty($_POST) || !wp_verify_nonce($_POST['pexeto-theme-options'],'pexeto-theme-update-options') )
			{
				   print 'Sorry, your nonce did not verify.';
				   exit;
			}else{
				foreach ($options as $value) {
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
	
					foreach ($options as $value) {
						if( isset( $_REQUEST[ $value['id'] ] ) ) { 
							update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); 
						} else { 
							delete_option( $value['id'] ); 
						} 
					}
						header("Location: themes.php?page=functions.php&saved=true");
						die;
			}
		} else if( 'reset' == $_REQUEST['action'] ) {

			foreach ($options as $value) {
				delete_option( $value['id'] ); }
				header("Location: themes.php?page=functions.php&reset=true");
				die;

		}
	}


	add_theme_page($themename." Options", "".$themename." Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}


?>