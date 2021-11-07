<?php

add_action("plugins_loaded", function () {
    if (@$_GET["set_lesson22"]) {
        $lesson_ids = "57,60,62,64";
        $lesson_ids_ar = explode(",", $lesson_ids);

        set_lesson_access($lesson_ids_ar, "1");
    }
    if (@$_GET["unset_lesson22"]) {
        $lesson_ids = "57,60,62,64";
        $lesson_ids_ar = explode(",", $lesson_ids);

        foreach ($lesson_ids_ar as $lesson_id) {
            update_post_meta($lesson_id, "access_user_id", "");
        }
    }

    if (@$_GET["get_lesson22"]) {
        $lesson_ids = "57,60,62,64";
        $lesson_ids_ar = explode(",", $lesson_ids);

        foreach ($lesson_ids_ar as $lesson_id) {
            echo "<br>";
            echo $lesson_id;
            echo "<br>";
            print_r(
                unserialize(get_post_meta($lesson_id, "access_user_id", true))
            );
        }
        exit();
    }
    if (@$_GET["set_user_id"]) {
    	get_session();
    	exit;
    }
    if (@$_GET["get_user_id"]) {
    	


    	print_r(get_session());
    	exit;
    }
    if (@$_GET["remove_user_id"]) {
    	if ( ! session_id() ) {
	        session_start();
	    }
    	unset($_SESSION["temp_user"]);
    	print(get_session());
    	exit;
    }
},1);

?>
