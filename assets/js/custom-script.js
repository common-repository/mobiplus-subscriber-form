jQuery(document).ready(function () {
	jQuery("#sendbutton").bind("click", function() {
		
		var mp_identityType  = jQuery("#mp_identityType").val();
		if (mp_identityType == "EMAIL") {
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var error = "Invalid Email address";
		} else {
			var regex = /^[0-9-+s()]*$/;
			var error = "Invalid Phone number"
		}
		
		var mp_Identity  = jQuery("#mp_Identity").val();
		if (regex.test(mp_Identity) === false || jQuery.trim(mp_Identity) == '') {
			jQuery(".mp_msg").html(error);
			return false;
		}
	
		var data = jQuery("#mp_subscribeform_settings_form").serialize();  		
		jQuery(".mp_ajaxload").show();
		jQuery(".mp_subscribe").hide();
		jQuery(".mp_ajaxload").html("<img src='"+ajaximage+"' width='50px'>");
		jQuery.ajax({
		  type:"POST",
		  data:data,
		  url: ajaxurl,
		  success: function(value) {
			jQuery(".mp_msg").html(value);
			jQuery(".mp_subscribe").show();
			jQuery(".mp_ajaxload").hide();
			jQuery("#mp_Identity").val("");
		  }
		});
	});
});