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
	<h4>Lessons to buy</h4>
	<table border="1">
		<?php
		foreach($lesson_ar as $lesson) {
			$lesson_data = get_post($lesson);
			?>
		<tr>
			<!-- <td><?php echo $lesson; ?></td> -->
			<td style="padding:5px"><?php echo $lesson_data->post_title; ?></td>
		</tr>
			<?php
		}
		?>
	</table>

	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
</form>

<?php do_action( 'lesson_type_after_add_to_cart_form' ); ?>