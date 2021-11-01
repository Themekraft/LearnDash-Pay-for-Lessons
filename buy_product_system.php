<?php
add_action("woocommerce_single_product_summary", "lesson_type_template", 60);

function lesson_type_template()
{
    global $product;
    if ("lesson_type" == $product->get_type()) {
        $template_path = plugin_dir_path(__FILE__) . "templates/";
        wc_get_template(
            "single-product/add-to-cart/lesson_type.php",
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
    "woocommerce_get_item_data",
    "lesson_product_add_on_display_cart",
    10,
    2
);

function lesson_product_add_on_display_cart($data, $cart_item)
{
    if (isset($cart_item["lesson_to_buy"])) {
        $value = sanitize_text_field($cart_item["lesson_to_buy"]);

        $valur_ar = explode(",", $cart_item["lesson_to_buy"]);
        $content = '<table border="1">';
        foreach ($valur_ar as $value) {
            $lesson_data = get_post($value);
            $content .=
                "<tr><td style='padding:5px'>" .
                $lesson_data->post_title .
                "</td></tr>";
        }
        $content .= "</table>";
        $data[] = [
            "name" => "lesson_to_buy",
            "value" => $content,
        ];
    }

    return $data;
}

add_action(
    "woocommerce_add_order_item_meta",
    "lesson_product_add_on_order_item_meta",
    10,
    2
);

function lesson_product_add_on_order_item_meta($item_id, $values)
{
    if (!empty($values["lesson_to_buy"])) {
        wc_add_order_item_meta(
            $item_id,
            "lesson_to_buy",
            $values["lesson_to_buy"],
            true
        );
    }
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


/*==============without login user custom session id save================*/

add_action('woocommerce_checkout_update_order_meta',function( $order_id, $posted ) {
    
    if (!is_user_logged_in()) {
        $order = wc_get_order( $order_id );
        $temp_user = get_session();
        $order->update_meta_data( 'temp_user_id',$temp_user );
        $order->save();
    }

} , 10, 2);





?>
