<?php
add_action("woocommerce_single_product_summary", "lesson_type_template", 60);

function lesson_type_template()
{
    global $product;
    if ("lesson_type" == $product->get_type() && dpflww_fs()->is_paying() ) {
        $template_path = plugin_dir_path(__FILE__) . "templates/";
        wc_get_template(
            "single-product/add-to-cart/lesson-type.php",
            "",
            "",
            trailingslashit($template_path)
        );
    }
}


add_filter(
    "woocommerce_add_cart_item_data",
    "lesson_product_add_on_cart_item_data",
    10,
    2
);
function lesson_product_add_on_cart_item_data($cart_item, $product_id)
{
    $courses_ar = unserialize(get_post_meta($product_id, "_lesson_id", true));
    $cart_item["lesson_to_buy"] = implode(",", $courses_ar);
    return $cart_item;
}

add_filter(
    "woocommerce_email_order_meta_fields",
    "l_product_add_on_display_emails"
);

function l_product_add_on_display_emails($fields)
{
    $fields["lesson_to_buy"] = "lesson_to_buy";

    return $fields;
}


add_action("woocommerce_order_status_changed","woocommerce_order_status_changed_fn",10,3);
function woocommerce_order_status_changed_fn($order_id, $old_status, $new_status)
{
    $order = wc_get_order($order_id);
    $temp_user_id = $order->get_meta('temp_user_id') ;


    if ($new_status == "completed") 
    {
            
        
        $temp_user_id = $order->get_meta('temp_user_id');
        $user_id      = $order->user_id;

        if($user_id>0) 
        {
            foreach ($order->get_items() as $item) 
            {
                $product_id = $item["product_id"];
                $lesson_ar  = unserialize(get_post_meta($product_id,"_lesson_id",true));
                set_lesson_access($lesson_ar, $user_id);
            }
        }
        else if($temp_user_id>0)
        {
            foreach ($order->get_items() as $item) 
            {
                $product_id = $item["product_id"];
                $lesson_ar  = unserialize(get_post_meta($product_id,"_lesson_id",true));
                set_lesson_access($lesson_ar, $temp_user_id);
            }

        }
       
        
    }
}

function add_admin_scripts( $hook ) {

    global $post;
    $product_id = isset( $_REQUEST['post'] ) ? ( int ) $_REQUEST['post'] : '';
    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        $template_path = plugin_dir_url(__FILE__) . "assets/js/admin.js";
        wp_register_script( "myscript", $template_path, array('jquery') );
        wp_localize_script( 'myscript', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'product_id' => $product_id ) );
        wp_enqueue_script( 'myscript' );
    }
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 5, 1 );

/*==============without login user custom session id save================*/

/* add_action('woocommerce_checkout_update_order_meta',function( $order_id, $posted ) {
    
    if (!is_user_logged_in()) {
        $order = wc_get_order( $order_id );
        $temp_user = get_session();
        $order->update_meta_data( 'temp_user_id',$temp_user );
        $order->save();
    }

} , 10, 2); */


?>