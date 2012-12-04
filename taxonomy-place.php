<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/starkers-utilities.php for info on get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php get_template_parts( array( 'parts/shared/html-header', 'parts/shared/slide-header' ) ); ?>

<div id="primary">
<form>
<div id="slider">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<!--h2><?php  //the_title(); ?></h2-->
<?php// the_content(); ?>


<?php
	
		 $post_id = $post->ID;

		
		 $time_mode = get_field('time_settings_choise',$post_id);


		 $from = "";
		 $to = "";



		if($time_mode == "time_of_day_interval")
		{
			$to = get_post_meta( $post_id, 'to_time_of_day', true );
			$from = get_post_meta( $post_id, 'from_time_of_day', true );
		}
		else 
		{
			$from = get_field('from',$post_id);
			$to = get_field('to',$post_id);
		}

		
		if(!isValidTime($to,$from) && $time_mode != "no")
		{
			continue;
		}
		
		$mode = get_field('type_of_slide',$post_id);

		
		if($mode == 'group')
		{
			echo add_multiple_slides($post_id,$to,$from);
		}
		else if($mode == 'normal')
		{
			echo add_single_slide($post_id,$to,$from);
		}
		?>


<?php //comments_template( '', true ); ?>
<?php endwhile; ?>
</div>
</form>
</div>
<?php get_template_parts( array( 'parts/shared/slide-footer','parts/shared/html-footer' ) ); ?>