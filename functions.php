<?php
	/**
	 * Starkers functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
 	 * @package 	WordPress
 	 * @subpackage 	Starkers
 	 * @since 		Starkers 4.0
	 */

	/* ========================================================================================================================
	
	Required external files
	
	======================================================================================================================== */


	if(function_exists('register_field')) {
     register_field('acf_time_picker', dirname(__File__) . '/acf_time_picker/acf_time_picker.php');
   }

	require_once( 'external/starkers-utilities.php' );

	


	/* ========================================================================================================================
	
	Theme specific settings

	Uncomment register_nav_menus to enable a single menu with the title of "Primary Navigation" in your theme
	
	======================================================================================================================== */

	add_theme_support('post-thumbnails');
	
	// register_nav_menus(array('primary' => 'Primary Navigation'));

	/* ========================================================================================================================
	
	Actions and Filters
	
	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'script_enqueuer' );

	add_filter( 'body_class', 'add_slug_to_body_class' );

	/* ========================================================================================================================
	
	Custom Post Types - include custom post types and taxonimies here e.g.

	e.g. require_once( 'custom-post-types/your-custom-post-type.php' );
	
	======================================================================================================================== */



	/* ========================================================================================================================
	
	Scripts
	
	======================================================================================================================== */

	/**
	 * Add scripts via wp_head()
	 *
	 * @return void
	 * @author Keir Whitaker
	 */

	function script_enqueuer() {

		wp_register_script( 'transit', get_template_directory_uri().'/js/jquery.transit.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'transit' );

		wp_register_script( 'site', get_template_directory_uri().'/js/site.js', array( 'jquery' ) );
		wp_enqueue_script( 'site' );

		wp_register_script( 'clock', get_template_directory_uri().'/js/date-se-SE.js', array( 'jquery' ) );
		wp_enqueue_script( 'clock' );


		wp_register_style( 'screen', get_template_directory_uri().'/style.css', '', '', 'screen' );
        wp_enqueue_style( 'screen' );
	}	


	/*
		Removes the version on the script and the css files
	*/
	function control_wp_url_versioning($src)
	{
	    // $src is the URL that WP has generated for the script or stlye you added 
	    // with wp_enqueue_script() or wp_enqueue_style(). This function currently 
	    // removes the version string off *all* scripts. If you need to do something 
	    // different, then you should do it here.
	    $src = remove_query_arg( 'ver', $src );
	    return $src;
	}

	// The default script priority is 10. We load these filters with priority 15 to 
	// ensure they are run *after* all the default filters have run. 
	add_filter('script_loader_src', 'control_wp_url_versioning', 15); 
	add_filter('style_loader_src', 'control_wp_url_versioning', 15); 



	/* ========================================================================================================================
	
	Comments
	
	======================================================================================================================== */

	/**
	 * Custom callback for outputting comments 
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	function starkers_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		?>
		<?php if ( $comment->comment_approved == '1' ): ?>	
		<li>
			<article id="comment-<?php comment_ID() ?>">
				<?php echo get_avatar( $comment ); ?>
				<h4><?php comment_author_link() ?></h4>
				<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
				<?php comment_text() ?>
			</article>
		<?php endif;
	}


	function isValidTime($to,$from)
	{
		$from_timestamp = "";
		$to_timestamp = "";

		$isValidTime = false;

		$today_timestamp = time();
				
		if($from != "")
		{
			$from_timestamp = strtotime($from);
		}

		if($to != "")
		{
			$to_timestamp = strtotime($to);
		}

		if(($from_timestamp == ""|| $from_timestamp == null ) && ($to_timestamp == "" || $to_timestamp == null ))
		{
			$isValidTime = true;
		}
		else if($today_timestamp >= $from_timestamp && $today_timestamp <= $to_timestamp)
		{
			$isValidTime = true;
		} 
		else if($today_timestamp >= $from_timestamp && ($to == "" || $to == null ))
		{
			$isValidTime = true;
		}
		else if($today_timestamp <= $to_timestamp && ($from == null || $from == ""))
		{
			$isValidTime = true;
		}

		return $isValidTime;

	}





	add_action( 'init', 'create_finspang_cms_post_types' );

function create_finspang_cms_post_types() {
	register_post_type( 'slide',
		array(
			'labels' => array(
			'name' => __( 'Slides' ),
			'singular_name' => __( 'slide' ),
			'add_new' => __( 'Lägg till ny' ),
			'add_new_item' => __( 'Lägg till ny slide' ),
			'edit' => __( 'Ändra' ),
			'edit_item' => __( 'Ändra slide' ),
			'new_item' => __( 'Ny slide' ),
			'view' => __( 'Se slide' ),
			'view_item' => __( 'Se slide' ),
			'search_items' => __( 'Sök slide' ),
			'not_found' => __( 'Inga slides hittades' ),
			'not_found_in_trash' => __( 'Inga slides i papperskorgen' ),
			'parent' => __( 'Förälder till slide' ),
			),
			'public' => true,
			'query_var' => true,
			'has_archive' => true,
			//'rewrite' => false 
			'rewrite' => array( 'slug' => 'slide', 'with_front' => true )
		)
	);

	// Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Plats för monitor', 'taxonomy general name' ),
    'singular_name' => _x( 'Plats för monitor', 'taxonomy singular name' ),
    'search_items' =>  __( 'Sök efter platser' ),
    'popular_items' => __( 'Populära platser för monitorer' ),
    'all_items' => __( 'Alla monitorplatser' ),
    'parent_item' => __( 'Förälder monitor plats' ),
    'parent_item_colon' => __( 'Förälder monitor plats:' ),
    'edit_item' => __( 'Ändra monitor plats' ), 
    'update_item' => __( 'Uppdatera monitor plats' ),
    'add_new_item' => __( 'Lägg till monitor plats' ),
    'new_item_name' => __( 'Ny monitor plats' ),
    'menu_name' => __( 'Platser för monitorer' ),
  ); 

  register_taxonomy('place','slide',array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'platser' ),
  ));

	}

	add_filter( 'manage_edit-slide_columns', 'my_edit_slide_columns' ) ;

function my_edit_slide_columns( $columns ) {

  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Betäckning' ),
    'place' => __( 'Plats för monitor' ),
    'from' => __('Visas från och med'),
    'to' => __('Visas till och med'),
    'date' => __( 'Date' )
  );

  return $columns;
}


add_action( 'manage_slide_posts_custom_column', 'my_manage_slide_columns', 10, 2 );

function my_manage_slide_columns( $column, $post_id ) {
  global $post;


  $schedule_mode = get_field('time_settings_choise', $post_id);



   if ( empty( $schedule_mode ) )
   {
   	$schedule_mode = "no";
   }

  switch( $column ) {


/* If displaying the 'annr' column. */
    
    /* If displaying the 'raanr' column. */
    case 'from' :

      /* Get the post meta. */
      $from = get_post_meta( $post_id, 'from', true );



      /* If no duration is found, output a default message. */
      if ( empty( $from ) || $schedule_mode == "no" || $schedule_mode == "to"  )
        echo __( '-' );

      /* If there is a duration, append 'minutes' to the text string. */
      else
        echo $from;

      break;

      case 'to' :

      /* Get the post meta. */
      $to = get_post_meta( $post_id, 'to', true );



      /* If no duration is found, output a default message. */
      if ( empty( $to ) || $schedule_mode == "no" || $schedule_mode == "from"  )
        echo __( '-' );

      /* If there is a duration, append 'minutes' to the text string. */
      else
        echo $to;

      break;


      /* If displaying the 'place' column. */
    case 'place' :

      /* Get the genres for the post. */
      $terms = get_the_terms( $post_id, 'place' );

      /* If terms were found. */
      if ( !empty( $terms ) ) {

        $out = array();

        /* Loop through each term, linking to the 'edit posts' page for the specific term. */
        foreach ( $terms as $term ) {
          $out[] = sprintf( '<a href="%s">%s</a>',
            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'place' => $term->slug ), 'edit.php' ) ),
            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'place', 'display' ) )
          );
        }

        /* Join the terms, separating them with a comma. */
        echo join( ', ', $out );
      }

      /* If no terms were found, output a default message. */
      else {
        _e( '-' );
      }

      break;

      

    /* Just break out of the switch statement for everything else. */
    default :
      break;
  }
}

add_filter( 'manage_edit-slide_sortable_columns', 'my_slide_sortable_columns' );

function my_slide_sortable_columns( $columns ) {

  $columns['to'] = 'to';
  $columns['from'] = 'from';

  return $columns;
}

function add_single_slide($post_id,$to,$from)
{
	$slide = "";

	$slide_duraction = get_field('slide_duraction',$post_id);

	$slide_content = get_field('slide_content',$post_id);

	$slide_image_mode = get_field('slide_image_mode',$post_id);

	$slide_image =  get_field('slide_image',$post_id);

	$slide_image_size_name = get_image_size_name($slide_image,$slide_image_mode);

	$slide_content_position_class = get_content_position_class_name($slide_image_size_name,$slide_image_mode);

	$slide .=  '<div class="slider-item">';
	$slide .=  '<input type="hidden" name="slide_duraction" value="'.$slide_duraction.'"/>';
	$slide .=  extract_image_tag($slide_image,$slide_image_mode, $slide_image_size_name);
	$slide .=  '<div class="slider_text'.$slide_content_position_class.'">'.$slide_content.'</div>';
	$slide .=  '<input type="hidden" name="slide_show_from" value="'.$from.'"/>';
	$slide .=  '<input type="hidden" name="slide_show_to" value="'.$to.'"/>';
	$slide .=  '</div>';

	return $slide;
}

function add_multiple_slides($post_id,$to,$from)
{

	$slides = "";

	$rows = get_field('slides',$post_id);

	if($rows)
		{

			foreach($rows as $row)
			{
				$page_slide_duraction = $row['page_slide_duraction'];

				$page_slide_content = $row['page_slide_content'];

				$page_slide_image_mode = $row['page_slide_image_mode'];

				$page_slide_image =  $row['page_slide_image'];

				$page_slide_image_size_name = get_image_size_name($page_slide_image,$page_slide_image_mode);

				$page_slide_content_position_class = get_content_position_class_name($page_slide_image_size_name,$page_slide_image_mode);
	
				$slides .= '<div class="slider-item">';
				$slides .=  '<input type="hidden" name="slide_duraction" value="'.$page_slide_duraction.'"/>';
				$slides .=  extract_image_tag($page_slide_image,$page_slide_image_mode, $page_slide_image_size_name);
				$slides .=  '<div class="slider_text'.$page_slide_content_position_class.'">'.$page_slide_content.'</div>';
				$slides .=  '<input type="hidden" name="slide_show_from" value="'.$from.'"/>';
				$slides .=  '<input type="hidden" name="slide_show_to" value="'.$to.'"/>';
				$slides .=  '</div>';
			}
		 
		}

	return $slides;
}

function extract_image_tag($image_data,$image_mode,$image_size_name)
{
	$image_tag = "";
	$image_class = get_image_class_name($image_size_name,$image_mode);

	
	if($image_data != null)
	{
		$image_tag = '<img class="slide_image'.$image_class.'" alt="'.$image_data['alt'].'" src="'.$image_data['sizes'][$image_size_name].'" />';
	}

	return $image_tag;

}


function get_image_size_name($image_data, $image_mode)
{
	$image_size_name = "thumbnail";

	

	switch ($image_mode) {
		case 'normal-left':
			$image_size_name = "medium";
			break;
		case 'normal-right':
			$image_size_name = "medium";
			break;
		case 'half-left':
			$image_size_name = "whole";
			break;
		case 'half-right':
			$image_size_name = "whole";
			break;
		case 'whole':
			$image_size_name = "whole";
			break;
		default:
			$image_size_name = "thumbnail";
			break;
	}

	//echo "<pre>";
	//print_r($image_size_name);
	//echo "<pre>";

	if($image_data['sizes'][$image_size_name] == null)
	{
		$image_size_name = "large";
	}

	if($image_data['sizes'][$image_size_name] == null)
	{
		$image_size_name = "medium";
	}

	if($image_data['sizes'][$image_size_name] == null)
	{
		$image_size_name = "thumbnail";
	}

	if($image_data['sizes'][$image_size_name] == null)
	{
		$image_size_name = "orginal";
	}

	//echo "<pre>";
	//print_r($image_size_name);
	//echo "<pre>";

	return $image_size_name;
}


function get_image_class_name($modfied_image_size_name,$image_mode)
{
	$image_class_name = "";

	if($modfied_image_size_name == "whole")
	{
		$image_class_name =" bg";
	}
	
	if(strstr($image_mode, 'left'))
	{
		$image_class_name .=" image_to_the_left";
	}
	else if(strstr($image_mode, 'right'))
	{
		$image_class_name .=" image_to_the_right";
	}


	return $image_class_name;

}

function get_content_position_class_name($modfied_image_size_name,$image_mode)
{
	$content_position_class_name = "";

	if($modfied_image_size_name == "whole" && $image_mode == "half-left")
	{
		$content_position_class_name = " text_to_the_right";
	}
	else if($modfied_image_size_name == "whole" && $image_mode == "half-right")
	{
		$content_position_class_name  =" text_to_the_left";
	}


	return $content_position_class_name;
}

function add_style_block_if_there_is_settings()
{
	$footer_background_color =  get_field('footer_background_color', 'options');
	$footer_text_color = get_field('footer_text_color', 'options');

	$header_logo = get_field('header_logo', 'options');

	$style = "";

	if($header_logo != "" || $footer_text_color != "" ||  $footer_background_color != "")
	{
		echo '<style type="text/css">';
	}

	if($header_logo != "")
	{
		echo "body {background-image: url('".$header_logo["url"]."') !important;}";
	}

	if($footer_text_color != "")
	{
		echo "#slide-footer {color: ".$footer_text_color." !important;}";
	}

	if($footer_background_color != "")
	{
		echo "#slide-footer {background-color: ".$footer_background_color." !important;}";
	}

	if($header_logo != "" || $footer_text_color != "" ||  $footer_background_color != "")
	{
		echo "</style>";
	}
}

	function edit_admin_menus() {  
	    global $menu;  
	    global $submenu;  
	    /*$menu[5][0] = 'Recipes'; // Change Posts to Recipes  
	    $submenu['edit.php'][5][0] = 'All Recipes';  
	    $submenu['edit.php'][10][0] = 'Add a Recipe';  
	    $submenu['edit.php'][15][0] = 'Meal Types'; // Rename categories to meal types  
	    $submenu['edit.php'][16][0] = 'Ingredients'; // Rename tags to ingredients  */
	    
		remove_menu_page('edit.php'); // Remove the post menu
		remove_menu_page('link-manager.php'); // Remove the link manager menu
	    remove_menu_page('edit.php?post_type=page'); // Remove the page menu
	    remove_menu_page('edit-comments.php'); // Remove the comments Menu


	}  
	add_action( 'admin_menu', 'edit_admin_menus' );  

	function custom_menu_order($menu_ord) {  
    if (!$menu_ord) return true;  
    return array(  
        'index.php', // Dashboard  
        'separator1', // First separator  
        'edit.php?post_type=slide',
        'edit.php', // Posts  
        'upload.php', // Media  
        'link-manager.php', // Links  
        'edit.php?post_type=page', // Pages  
        'edit-comments.php', // Comments  
        'separator2', // Third separator 
        'themes.php', // Appearance
        
        'plugins.php', // Plugins  
        'users.php', // Users  
        'tools.php', // Tools  
        'options-general.php', // Settings  
        'separator-last', // Last separator  
    );  
} 
add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order  
add_filter('menu_order', 'custom_menu_order');
add_image_size( 'whole',1920,1080,true ); // Full size



