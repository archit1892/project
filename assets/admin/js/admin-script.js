/**
* Javascript Function to get customer details
*
**/
function getCustomerDetails(nonce)
{
	// Check if there is a user_id
	user_id = parseInt(jQuery('#authordiv  option:selected').val());
	if ( !user_id ) {
		return ;
	}

	// Confirm
	var r = confirm("Do you want to add the customer details");
	if (r == false) {
		return ;
	}

	jQuery.ajax({
		type : "post",
		dataType : "json",
		url : ajaxurl,
		data : {action: "user_address", user_id : user_id, nonce: nonce},
		success: function(response)
		{
			alert(response);
			if(response.type == "success")
			{
				jQuery('#billing_name').val(response.billing_name);
				jQuery('#billing_address').val(response.billing_address);
				jQuery('#billing_street').val(response.billing_street);
				jQuery('#billing_postcode').val(response.billing_postcode);
				jQuery('#billing_city').val(response.billing_city);
				jQuery('#billing_state').val(response.billing_state);
				jQuery('#billing_country').val(response.billing_country);
				jQuery('#billing_email').val(response.billing_email);
				jQuery('#billing_phone').val(response.billing_phone);
			}
			else
			{
				alert("Customer details could not be added")
			}
		}
	});

}

function isEmpty( el )
{
	return !jQuery.trim(el.html());
}
