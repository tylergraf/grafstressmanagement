<?php
/*
Plugin Name: Portfolio posts loader
Description: Loads the posts from a defined category
Version: 1.0
Author: Pexeto
Author URI: http://pexeto.com
*/

add_action('init', 'portfolio_posts_register');
function portfolio_posts_register() {
	
	$prefix = 'portfolio-posts-multi'; // $id prefix
	$name = __("Pexeto's portfolio posts loader");
	$widget_ops = array('classname' => 'posts_multi', 'description' => __('Loads the latest portfolio posts'));
	$control_ops = array('width' => 200, 'height' => 200, 'id_base' => $prefix);
	
	$options = get_option('portfolio_posts_multi');
	if(isset($options[0])) unset($options[0]);
	
	if(!empty($options)){
		foreach(array_keys($options) as $widget_number){
			wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'portfolio_posts_multi', $widget_ops, array( 'number' => $widget_number ));
			wp_register_widget_control($prefix.'-'.$widget_number, $name, 'portfolio_posts_multi_control', $control_ops, array( 'number' => $widget_number ));
		}
	} else{
		$options = array();
		$widget_number = 1;
		wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'portfolio_posts_multi', $widget_ops, array( 'number' => $widget_number ));
		wp_register_widget_control($prefix.'-'.$widget_number, $name, 'portfolio_posts_multi_control', $control_ops, array( 'number' => $widget_number ));
	}
}

function portfolio_posts_multi($args, $vars = array()) {
    extract($args);
    $widget_number = (int)str_replace('portfolio-posts-multi-', '', @$widget_id);
    $options = get_option('portfolio_posts_multi');
    if(!empty($options[$widget_number])){
    	$vars = $options[$widget_number];
    }
    // widget open tags
		echo $before_widget;
		
	// print title from admin 
	echo $before_title . $vars['title'] . $after_title;
		
		
	// print content and widget end tags
    
	$catId=$vars['catId'];
	$number=$vars['postNumber'];
	$title=$vars['title'];

	printPortfolioPosts($catId,$number,$title);
		
    echo $after_widget;
}

function portfolio_posts_multi_control($args) {

	$prefix = 'portfolio-posts-multi'; // $id prefix
	
	$options = get_option('portfolio_posts_multi');
	if(empty($options)) $options = array();
	if(isset($options[0])) unset($options[0]);
		
	// update options array
	if(!empty($_POST[$prefix]) && is_array($_POST)){
		foreach($_POST[$prefix] as $widget_number => $values){
			if(empty($values) && isset($options[$widget_number])) // user clicked cancel
				continue;
			
			if(!isset($options[$widget_number]) && $args['number'] == -1){
				$args['number'] = $widget_number;
				$options['last_number'] = $widget_number;
			}
			$options[$widget_number] = $values;
		}
		
		// update number
		if($args['number'] == -1 && !empty($options['last_number'])){
			$args['number'] = $options['last_number'];
		}

		// clear unused options and update options in DB. return actual options array
		$options = bf_smart_multiwidget_update($prefix, $options, $_POST[$prefix], $_POST['sidebar'], 'portfolio_posts_multi');
	}
	
	// $number - is dynamic number for Authors Loader, gived by WP
	// by default $number = -1 (if no widgets activated). In this case we should use %i% for inputs
	//   to allow WP generate number automatically
	$number = ($args['number'] == -1)? '%i%' : $args['number'];

	// now we can output control
	$opts = @$options[$number];
	
	$title = @$opts['title'];
	$catId=@$opts['catId'];
	$postNumber=@$opts['postNumber'];
	
	 
	?>
    Title<br />
		<input type="text" name="<?php echo $prefix; ?>[<?php echo $number; ?>][title]" value="<?php echo $title; ?>" />
		
		  <p><label>Number of posts to show</label><br />
      <input name="<?php echo $prefix; ?>[<?php echo $number; ?>][postNumber]" value="<?php echo $postNumber; ?>"/>
     
    </p>
    
   <p><label>Post category<br /><select name="<?php echo $prefix; ?>[<?php echo $number; ?>][catId]"> 
      <option value="0">ALL</option>
     <?php 
		$pexeto_portfolio_cats=pexeto_get_taxonomies('portfolio_category');
		
      foreach ($pexeto_portfolio_cats as $cat) {
        $option = '<option';
        if($catId==$cat->term_id){
            $option.=' selected';   
        }
        $option.=' value="'.$cat->term_id.'">'; 
        $option .= $cat->name;
        $option .= '</option>';
        echo $option;
      }
     ?>
    </select>
    </label></p>
    
    
	<?php
	
}

// helper function can be defined in another plugin
if(!function_exists('bf_smart_multiwidget_update')){
	function bf_smart_multiwidget_update($id_prefix, $options, $post, $sidebar, $option_name = ''){
		global $wp_registered_widgets;
		static $updated = false;

		// get active sidebar
		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();
		
		// search unused options
		foreach ( $this_sidebar as $_widget_id ) {
			if(preg_match('/'.$id_prefix.'-([0-9]+)/i', $_widget_id, $match)){
				$widget_number = $match[1];
				
				// $_POST['widget-id'] contain current widgets set for current sidebar
				// $this_sidebar is not updated yet, so we can determine which was deleted
				if(!in_array($match[0], $_POST['widget-id'])){
					unset($options[$widget_number]);
				}
			}
		}
		
		// update database
		if(!empty($option_name)){
			update_option($option_name, $options);
			$updated = true;
		}
		
		// return updated array
		return $options;
	}
}

function printPortfolioPosts($catId,$number,$title){
	if($number==''){
	$number=4;
	}
	
	$args= array(
         'posts_per_page' =>$number, 
		 'post_type' => 'Portfolio'	
	);
	

	if($catId!='0'){
		$slug=pexeto_get_taxonomy_slug($catId);
		$args['portfolio_category']=$slug;
	}
	
query_posts($args);
	?>
 <ul id="sidebar-projects">
<?php 
		 if ( have_posts() ) {
		     while ( have_posts() ){
		          the_post();
		          global $more;
				  $more = 0;
				  global $post;
		          ?> 
		          
		         <?php 
		       
		         	$thumbnail=get_post_meta($post->ID, 'thumbnail_value', true);
		         	if(!$thumbnail || $thumbnail==''){
		         		$thumbnail=get_post_meta($post->ID, 'preview_value', true);
		         	}
		        
		        
		         ?>    
		         <li>
		           <a href="<?php the_permalink();?>" ><img src="<?php bloginfo("template_directory"); ?>/functions/timthumb.php?src=<?php echo($thumbnail); ?>&amp;h=100&amp;w=110&amp;zc=1&amp;q=80" class="shadow-frame" alt=""/></a>
		         </li>
		         <?php 
			}
		}
	?></ul><?php 
}
?>