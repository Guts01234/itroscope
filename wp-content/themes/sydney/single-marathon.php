<?php
/**
 * @package Sydney
 */
 get_header();
?>

<?php
$disable_title 					= get_post_meta( $post->ID, '_sydney_page_disable_title', true );
$disable_featured 				= get_post_meta( $post->ID, '_sydney_page_disable_post_featured', true );
$single_post_image_placement 	= get_theme_mod( 'single_post_image_placement', 'below' );
$single_post_meta_position		= get_theme_mod( 'single_post_meta_position', 'below-title' );
?>

<?php do_action( 'sydney_before_single_entry' ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action('sydney_inside_top_post'); ?>

	<?php if ( 'above' === $single_post_image_placement ) : ?>
		<?php sydney_single_post_thumbnail( $disable_featured ); ?>
	<?php endif; ?>

	<?php if ( !$disable_title ) : ?>
	<header class="entry-header">
		<?php if ( 'post' === get_post_type() && 'above-title' === $single_post_meta_position ) : ?>
			<?php sydney_single_post_meta( 'entry-meta-above' ); ?>
		<?php endif; ?>

		<h1 class="title-post entry-title"><?php

		if (get_the_author() !='admin' && get_the_author() !=''){
			echo get_the_author() . '. '.  get_the_title();
		}
		else{
			echo get_the_title();
		}
		 ?></h1>



		<?php if ( 'post' === get_post_type() && 'below-title' === $single_post_meta_position ) : ?>
			<?php sydney_single_post_meta( 'entry-meta-below' ); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<?php endif; ?>

	<?php if ( 'below' === $single_post_image_placement ) : ?>
		<!-- <?php sydney_single_post_thumbnail( $disable_featured ); ?> -->
	<?php endif; ?>

	<div class="entry-content" id='data_ajax' data-user_id=<?php echo get_current_user_id(); ?> data-id= <?php the_id(); ?>  data-sfer="<?php
	$terms = wp_get_post_terms( get_the_ID(), 'sfera');
	$c = count($terms);
	if($c){
		foreach ($terms as $term) {
			echo $term->name . ';';
		}
	}
	else{
		echo 'none;';
	}

	 ?>" data-problem="<?php
 	$terms = wp_get_post_terms( get_the_ID(), 'problem');
	$c = count($terms);
	if($c){
		foreach ($terms as $term) {
			echo $term->name . ';';
		}
	}
	else{
		echo 'none;';
	}

 	 ?>" data-navik="<?php
 	$terms = wp_get_post_terms( get_the_ID(), 'navik');
	$c = count($terms);
	if($c){
		foreach ($terms as $term) {
			echo $term->name . ';';
		}
	}
	else{
		echo 'none;';
	}

 	 ?>"

		 <?php sydney_do_schema( 'entry_content' ); ?>>
		<?php do_action('post_before_cont_kw') ?>
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
				'after'  => '</div>',
			) );

			do_action('btn_read');
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
	</footer><!-- .entry-footer -->

	<?php do_action('sydney_inside_bottom_post'); ?>


</article><!-- #post-## -->
<?php do_action( 'sydney_after_single_entry' ); ?>
<div class="entry-content">
	<?php do_action('see_also3');?>
</div>
<style media="screen">
	p{
		margin-bottom: 0!important;
	}
</style>

<?php
get_footer();
 ?>
