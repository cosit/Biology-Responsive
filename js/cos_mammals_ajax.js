jQuery(document).ready(function ($) {

	$('#infraclass_dropdown').change(function(){
		$.post(document.location.protocol+'//'+document.location.host+'/files/wp-admin/admin-ajax.php', 
			{
				action: 'cos_mammal_code', 
				parentTaxTitle: 'cos_mammal_infraclass',
				parentTax: $('select[name=mammalinfraclass').val(),
				childTax: 'cos_mammal_order'
			}, 
			function(response) {				
				console.log(response);
				$('#order_dropdown').html(response);
				$('#cos_mammal_order').prop('disabled', false);	
				if($('#cos_mammal_order').hasClass('disabled'))
					$('#cos_mammal_order').prop('disabled', true);
			});
	});
	$('#order_dropdown').change(function(){
		$.post(document.location.protocol+'//'+document.location.host+'/files/wp-admin/admin-ajax.php', 
			{
				action: 'cos_mammal_code', 
				parentTaxTitle: 'cos_mammal_order',
				parentTax: $('select[name=mammalorder').val(),
				childTax: 'cos_mammal_family'
			}, 
			function(response) {
				$('#family_dropdown').html(response);
				$('#cos_mammal_family').prop('disabled', false);
				if($('#cos_mammal_family').hasClass('disabled'))
					$('#cos_mammal_family').prop('disabled', true);		
			});
	});	
	$('#family_dropdown').change(function(){
		$.post(document.location.protocol+'//'+document.location.host+'/files/wp-admin/admin-ajax.php', 
			{
				action: 'cos_mammal_code', 
				parentTaxTitle: 'cos_mammal_family',
				parentTax: $('select[name=mammalfamily').val(),
				childTax: 'cos_mammal_genus'
			}, 
			function(response) {
				$('#genus_dropdown').html(response);
				$('#cos_mammal_genus').prop('disabled', false);	
				if($('#cos_mammal_genus').hasClass('disabled'))
					$('#cos_mammal_genus').prop('disabled', true);		
			});
	});	

});

