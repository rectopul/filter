( function ( $ ) {

	//REQUISIÇÃO DO FILTRO

	$('.bar-lateral > input:checkbox').change(function(){

		var catergories_filtrered = new Array(),
		notCheck = new Array();


		$('input:checkbox[name=filter]:checked').each(function() {
			catergories_filtrered.push($(this).attr('custom-id'));
		});

		$('input:checkbox[name=filter]:not(:checked)').each(function() {
			notCheck.push( $(this).attr('custom-id') );
			// catergories_filtrered.push( ( $(this).attr('custom-id') - ( $(this).attr('custom-id') * 2 ) ) );
		});

		function unique(array){
		    return array.filter(function(el, index, arr) {
		        return index === arr.indexOf(el);
		    });
		}

		console.log( unique(catergories_filtrered) );

		var dados_envio = {
	        'filter_products_nonce': js_global.filter_products_nonce,
	        'categories_filter': catergories_filtrered,
	        'categories_not_check': unique(notCheck),
	        'action': 'filter_products'
      	}
		$.ajax({
			url: js_global.xhr_url,
			type: 'POST',	
			data: dados_envio,
			success: function( response ) {

				$('.produtos > div').remove();

				$('.produtos').append( response );
			}
		});
	});

} ) ( jQuery );
