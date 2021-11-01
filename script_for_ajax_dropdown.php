<style type="text/css">
	.lesson_form_select {
		width: 50% !important;
	}
</style>
<script style="text/javascript">
jQuery(document).ready(function (){
	prod_course_lessons();
	jQuery("#_course_id").on('change' ,function() {
		prod_course_lessons();		
	});

	jQuery("#_lesson_id").on('change' ,function() {
		//jQuery('#_lesson_id option').prop('selected', true);
		if (jQuery("#_all_lessons").prop('checked') == true) 
		{
			var s = 0;
			jQuery("#_lesson_id option").each(function() {
				if(s > 0){
					jQuery(this).attr('selected', 'selected');
				}
				s++;
			});
			alert('You cannot Unselect Lesson when All lesson is selected');
		}
	});
});

function prod_course_lessons() {
	var courses = jQuery('#_course_id').val();        
	jQuery.ajax({
		type: "POST",
		url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
		//dataType:"json",
		data: { 
		    action: 'get_course_lessons',
		    courses: courses,
		    productID: '<?php echo $_GET['post']; ?>'
		},
		cache: false,
		success: function(data)
		{
			jQuery('#_lesson_id').html(data);
			if (jQuery("#_all_lessons").prop('checked') == true) 
			{
				setTimeout(function(){
					var s = 0;
					jQuery("#_lesson_id option").each(function() {
						if(s > 0){
							jQuery(this).attr('selected', 'selected');
						}
						s++;
					});
					//jQuery('#_lesson_id option').prop('selected', true);
				}, 2000);
			}        
		}
	});
}
</script>