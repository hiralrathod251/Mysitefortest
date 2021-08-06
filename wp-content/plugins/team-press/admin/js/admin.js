;(function($){
	$(document).ready(function() {
		/*-ready-*/
		$("#toplevel_page_extp_options").appendTo("#menu-posts-ex_team > ul");
		$("#toplevel_page_extp_options").removeClass();
		if($("#toplevel_page_extp_options > a").hasClass('current')  || $("#toplevel_page_extp_custom_code_options > a").hasClass('current') || $("#toplevel_page_extp_js_css_file_options > a").hasClass('current') ){
			$("#adminmenu > li#menu-posts-ex_team").addClass('wp-has-current-submenu');
			$("#toplevel_page_extp_options").addClass('current');
		}
		if(jQuery('.post-type-team_scbd .cmb2-metabox select[name="sc_type"]').length>0){
			$val = jQuery('.post-type-team_scbd .cmb2-metabox select[name="sc_type"]').val();
			if($val==''){
				$val ='grid';
			}
			if($val =='carousel'){
				jQuery('.post-type-team_scbd select#style option').removeAttr("disabled");
				jQuery('.post-type-team_scbd select#fullcontent_in option[value="collapse"]').attr('disabled','disabled');
			}else if($val =='list'){
				jQuery('.post-type-team_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-team_scbd select#style option[value="1"], .post-type-team_scbd select#style option[value="2"], .post-type-team_scbd select#style option[value="3"]').removeAttr("disabled");
				jQuery('.post-type-team_scbd select#fullcontent_in option[value="collapse"]').attr('disabled','disabled');
			}else if($val =='table'){
				jQuery('.post-type-team_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-team_scbd select#style option[value="1"], .post-type-team_scbd select#style option[value="2"]').removeAttr("disabled");
				jQuery('.post-type-team_scbd select#fullcontent_in option[value="collapse"]').attr('disabled','disabled');
			}else{
				jQuery('.post-type-team_scbd select#style option').removeAttr('disabled','disabled');
				jQuery('.post-type-team_scbd select#fullcontent_in option').removeAttr('disabled','disabled');
			}
			$('body').removeClass (function (index, className) {
				return (className.match (/(^|\s)extp-layout\S+/g) || []).join(' ');
			});
			$('body').addClass('extp-layout-'+$val);
			//$('.show-in'+$val).fadeIn();
			//$('.hide-in'+$val).fadeOut();
		}
		/*-on change-*/
		jQuery('.post-type-team_scbd .cmb2-metabox select[name="sc_type"]').on('change',function() {
			var $this = $(this);
			var $val = $this.val();
			if($val==''){
				$val ='grid';
			}
			if($val =='carousel'){
				jQuery('.post-type-team_scbd select#style option').removeAttr("disabled");
				jQuery('.post-type-team_scbd select#fullcontent_in option[value="collapse"]').attr('disabled','disabled');
			}else if($val =='list'){
				jQuery('.post-type-team_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-team_scbd select#style option[value="1"], .post-type-team_scbd select#style option[value="2"], .post-type-team_scbd select#style option[value="3"]').removeAttr("disabled");
				jQuery('.post-type-team_scbd select#fullcontent_in option[value="collapse"]').attr('disabled','disabled');
			}else if($val =='table'){
				jQuery('.post-type-team_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-team_scbd select#style option[value="1"], .post-type-team_scbd select#style option[value="2"]').removeAttr("disabled");
				jQuery('.post-type-team_scbd select#fullcontent_in option[value="collapse"]').attr('disabled','disabled');
			}else{
				jQuery('.post-type-team_scbd select#style option').removeAttr('disabled','disabled');
				jQuery('.post-type-team_scbd select#fullcontent_in option').removeAttr('disabled','disabled');
			}
			$('body').removeClass (function (index, className) {
				return (className.match (/(^|\s)extp-layout\S+/g) || []).join(' ');
			});
			$('body').addClass('extp-layout-'+$val);
			//$('.show-in'+$val).fadeIn();
			//$('.hide-in'+$val).fadeOut();
			
		});
		/*-ajax save meta-*/
		jQuery('input[name="extp_sort"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var valu = $this.val();
           	var param = {
	   			action: 'extp_change_sort_mb',
	   			post_id: post_id,
				value: valu
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: extp_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		jQuery('input[name="extp_position"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var valu = $this.val();
           	var param = {
	   			action: 'extp_change_position',
	   			post_id: post_id,
				value: valu
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: extp_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});

		jQuery('input[name="extp_sort_category"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var value = $this.val();
           	var param = {
	   			action: 'extp_change_sort_category',
	   			post_id: post_id,
				value: value
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: extp_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		jQuery('input[name="extp_sort_location"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var value = $this.val();
           	var param = {
	   			action: 'extp_change_sort_location',
	   			post_id: post_id,
				value: value
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: extp_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		
	});
}(jQuery));
