<?php
/**
 * Simple custom product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
do_action( 'lesson_type_before_add_to_cart_form' );  ?>

<form class="lesson_type_cart" method="post" enctype='multipart/form-data'>	
	<?php
	    $id        =   get_the_ID(); 
	    $lesson_ar =   unserialize( get_post_meta( $id, '_lesson_id', true ) );
	    $lessons_list = isset( $lesson_ar[0] ) ? learndash_get_lesson_list( $lesson_ar[0] ) : '';
	?>
	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php _e(esc_html($product->single_add_to_cart_text()), "learndash_pfl"); ?></button>
</form>

<?php do_action( 'lesson_type_after_add_to_cart_form' ); ?>