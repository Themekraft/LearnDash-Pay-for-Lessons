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
	    $id         =   get_the_ID(); 
	    $lesson_ar =   unserialize(get_post_meta($id ,'_lesson_id',true) );
	    //print_r($lesson_ar);
	?>
	<h4><?php _e("Lessons to buy", "learndash_pfl"); ?></h4>
	<table style="border:1px;">
		<?php
			foreach( $lesson_ar as $lesson ) {
				$lesson_data = get_post( $lesson );
		?>
		<tr>
			<!-- <td><?php echo $lesson; ?></td> -->
			<td style="padding:5px"><?php _e( $lesson_data->post_title, "learndash_pfl" ); ?></td>
		</tr>
		<?php
			}
		?>
	</table>

	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php _e(esc_html($product->single_add_to_cart_text()), "learndash_pfl"); ?></button>
</form>

<?php do_action( 'lesson_type_after_add_to_cart_form' ); ?>