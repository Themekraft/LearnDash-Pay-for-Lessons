<?php

add_action( 'add_meta_boxes', 'learndash_lesson_meta_box' );
function learndash_lesson_meta_box() {
 
    add_meta_box(
        'learndash_lesson',
        __( 'Woocommece Lesson Setting', 'learndash_pfl' ),
        'learndash_lesson_meta_box_callback',
        "sfwd-lessons"
    );
   
}

function learndash_lesson_meta_box_callback( $post ) {

    wp_nonce_field( 'learndash_lesson_nonce', 'learndash_lesson_nonce' );
    $checked ='';


    $active = get_post_meta( $post->ID, '_ld_lesson_active', true );
    if( $active == "1" ) {
		$checked = 'checked';
	}
    
    $price = get_post_meta( $post->ID, '_ld_lesson_price', true );
    
    ?>
    <div id="_ld_lesson_price" class="sfwd_input sfwd_input_type_checkbox ">
	   <span class="sfwd_option_label" style="padding:10px 0px">
	      <a class="sfwd_help_text_link" style="cursor:pointer;" title="<?php _e( 'It will be mark as paid once it will be added in woocommerce product but you can make is as free if you wanted','learndash_pfl' ); ?>" >
			<img alt="" src="<?php echo plugins_url();?>/lesson_buy/images/question.png">
			<label for="_ld_lesson_price" class="sfwd_label">
				<?php _e( 'Make As Paid','learndash_pfl' ); ?>
			</label>
	      </a>
	     
	   </span>
	   <span class="sfwd_option_input">
	      <div class="sfwd_option_div">
	         <fieldset>
	            <legend class="screen-reader-text">
	            	<span><?php _e( 'Setting', 'learndash_pfl' )?></span>
	            </legend>
	            <p class="learndash-section-field-checkbox-p">
	            	<input type="checkbox" id="ld_lesson_active-yes" name="ld_lesson_active" value="1" <?php echo $checked;?> class="learndash-section-field learndash-section-field-checkbox  ld-checkbox-input">
	            	<label class="ld-checkbox-input__label" for="ld_lesson_active-yes">
						<span><?php _e( 'Yes', 'learndash_pfl' )?></span>
					</label>
				</p>
	         </fieldset>
	      </div>
	   </span>
	   <p class="ld-clear"></p>
	</div>



	<div id="sfwd_option_label_2" class="sfwd_input sfwd_input_type_text ">
	   <span class="sfwd_option_label">
	      <a class="sfwd_help_text_link" style="cursor:pointer;" title="Click for Help!" >
	      		<img alt="" src="<?php echo plugins_url();?>/lesson_buy/images/question.png">
	      		<label for="sfwd_option_label_2" class="sfwd_label">
	      			<?php  _e( 'Product(s) Attached', 'learndash_pfl' )?>							
	  			</label>
	      </a>
	   </span>
	   <span class="sfwd_option_input">
	      <div class="sfwd_option_div">
	        <?php
		        $product_ids   = get_post_meta( get_the_ID(), "product_ids", true ) ;
	    		$product_ids   = unserialize( $product_ids );
	    		$content	   = '';
	    		if ( empty( $product_ids ) ){
		    		$content = __( "No Product Attached", "learndash_pfl" );	
	    		} else {
    				foreach( $product_ids as $val22 )	{
	    				$my_id = $val22;
	    				$permalink = get_permalink($my_id);
	    				$content .= ' <a href="'.$permalink.'"  target="_blank">'.__( get_the_title( $my_id ), "learndash_pfl" ).'</a> <br>';
	    				//$content .= get_the_title($my_id).'<br>';
    				}
	    		}
	    		echo $content;
	        ?>		
	      </div>
	   </span>
	   <p class="ld-clear"></p>
	</div>


	<div id="sfwd_option_label_1" class="sfwd_input sfwd_input_type_text ">
	   <span class="sfwd_option_label">
	      <a class="sfwd_help_text_link" style="cursor:pointer;" title="<?php _e('Click for Help!', 'learndash_pfl'); ?>" >
				<img alt="" src="<?php echo plugins_url();?>/lesson_buy/images/question.png">
				<label for="sfwd_option_label_1" class="sfwd_label">
					<?php _e( 'Allowed Users', 'learndash_pfl' ); ?>							
				</label>
	      </a>
	   </span>
	   <span class="sfwd_option_input">
	      <div class="sfwd_option_div">
		    <?php
		    $users       = get_users( array( 'fields' => 'all' ) );
			$access_user = get_post_meta( get_the_ID(), "access_user_id", true );
			

			$access_user   = unserialize( $access_user );

			if( ! is_array( $access_user ) ) {
		        $access_user = array();    
		    }
		   
		    ?>
		    <select multiple="multiple" class="select2" id="subscription_toggle_ids" name="access_user_id[]" data-placeholder="<?php esc_attr_e( 'Select User', 'learndash_pfl' ); ?>"  >
	            <?php
	                
	                foreach ( $users as $user_id ) {
	                   
	                    $ID = $user_id->data->ID;
	                    $selected='';
	                    if( in_array( $ID,$access_user ) ) {
	                    	 $selected = ' selected ';
	                    }

	                   	$user_login =   $user_id->data->user_login;
	                   	$first_name = get_user_meta ( $user_id->data->ID, 'first_name', true );
	                   	$last_name = get_user_meta ( $user_id->data->ID, 'last_name', true );
	                    //echo '<option value="' . esc_attr( $ID ) . '" '  .$selected. ' >' .$first_name." ".$last_name." (". $user_login . ') </option>';
	                    echo '<option value="' . esc_attr( $ID ) . '" '  .$selected. ' >
	                    		'.__( $first_name." ".$last_name." (". $user_login.")", "learndash_pfl").'
	                    	  </option>';
	                }

	               $temp_users = $access_user;
	               foreach ( $temp_users as $temp_user_id ) {
	               	 echo '<option value="' . esc_attr( $temp_user_id ) . '" selected >'.__( "temp ".$temp_user_id, "learndash_pfl" ).'</option>';
	               }
	            ?>
	        </select>					
	      </div>
	   </span>
	   <p class="ld-clear"></p>
	</div>
   
    <?php
}

add_action( 'save_post', 'save_learndash_lesson_meta_box_data' );
function save_learndash_lesson_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['learndash_lesson_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['learndash_lesson_nonce'], 'learndash_lesson_nonce' ) ) {
        return;
    }

   
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

   
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    if( isset( $_POST['ld_lesson_active'] ) and $_POST['ld_lesson_active'] == "1" ) {
    	$active = "1";
    }
    else {
    	$active = "0";
	}
   
   	$price = $_POST['_ld_lesson_price']; 

   	$access_user_id  = $_POST['access_user_id']; 
   	
    update_post_meta( $post_id, '_ld_lesson_active', $active );
    update_post_meta( $post_id, 'access_user_id', maybe_serialize( $access_user_id ) ) ;
   
}

?>