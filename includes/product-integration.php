<?php

add_action( "woocommerce_single_product_summary", "lesson_type_template", 60 );
function lesson_type_template() {
    global $product;
    if ( "lesson_type" == $product->get_type() && dpflww_fs()->is_paying() ) {
        $template_path = plugin_dir_path(__FILE__) . "templates/";
        wc_get_template(
            "single-product/add-to-cart/lesson-type.php",
            "",
            "",
            trailingslashit( $template_path )
        );
    }
}

add_filter( "woocommerce_add_cart_item_data", "lesson_product_add_on_cart_item_data", 10, 2 );
function lesson_product_add_on_cart_item_data( $cart_item, $product_id ) {
    $courses_ar = unserialize(get_post_meta($product_id, "_lesson_id", true));
    $cart_item["lesson_to_buy"] = implode(",", $courses_ar);
    return $cart_item;
}

add_filter( "woocommerce_email_order_meta_fields", "l_product_add_on_display_emails" );
function l_product_add_on_display_emails( $fields ) {
    $fields["lesson_to_buy"] = "lesson_to_buy";
    return $fields;
}

add_action( "woocommerce_order_status_changed", "woocommerce_order_status_changed_fn", 10, 3 );
function woocommerce_order_status_changed_fn( $order_id, $old_status, $new_status ) {
    $order = wc_get_order( $order_id );
    if ( $new_status == "completed" ) {
        $user_id = $order->get_user_id();
        if( ! empty( $user_id ) ) {
            foreach ( $order->get_items() as $item ) {
                $product_id = $item["product_id"];
                $lesson_ar  = unserialize( get_post_meta( $product_id, "_lesson_id", true ) );
                if( ! empty( $lesson_ar ) ){
                    $course_id = learndash_get_course_id( $lesson_ar[0] );
                    ld_update_course_access( $user_id, $course_id, );
                    $paid_lesson_access = get_user_meta( $user_id, '_paid_lesson_access', true );
                    if( ! empty( $paid_lesson_access ) ){
                        $paid_lesson_access = array_merge( $paid_lesson_access, $lesson_ar );
                        update_user_meta( $user_id, '_paid_lesson_access', $paid_lesson_access );
                    } else{
                        update_user_meta( $user_id, '_paid_lesson_access', $lesson_ar );
                    }
                    set_lesson_access( $lesson_ar, $user_id );
                }
            }
        }
    }
}

add_action( 'learndash-course-listing-before', 'learndash_pfl_check_access', 5, 2 );
function learndash_pfl_check_access( $course, $user ){

    $courses = get_user_meta( $user, '_learndash_woocommerce_enrolled_courses_access_counter', true );
    $lesson_arr = array();
    if( is_array( $courses ) ){
        foreach( $courses as $enrolled_course => $order ){
            if( $course === $enrolled_course ){
                $lessons = learndash_get_course_lessons_list( $course, $user );
                if( is_array( $lessons ) ){
                    foreach( $lessons as $lesson ){
                        array_push( $lesson_arr, $lesson['id']);
                    }
                }
            }
        }
    }
    if( current_user_can( 'administrator' ) ){
        $lessons = learndash_get_course_lessons_list( $course, $user );
        if( is_array( $lessons ) ){
            foreach( $lessons as $lesson ){
                array_push( $lesson_arr, $lesson['id']);
            }
        }
    }

    if( ! empty( $lesson_arr ) ){
        set_lesson_access( $lesson_arr, $user );
    }
}

add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 5, 1 );
function add_admin_scripts( $hook ) {
    global $post;
    $product_id = isset( $_REQUEST['post'] ) ? ( int ) $_REQUEST['post'] : '';
    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        $template_path = plugin_dir_url(__FILE__) . "assets/js/admin.js";
        wp_register_script( "pfl-admin-js", $template_path, array('jquery') );
        wp_localize_script( 'pfl-admin-js', 'pfl-ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'product_id' => $product_id ) );
        wp_enqueue_script( 'pfl-admin-js' );
    }
}

?>