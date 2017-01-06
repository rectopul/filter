( function ( $ ) {

	//REQUISIÇÃO DO FILTRO

	$('input:checkbox').change(function(){

		var catergories_filtrered = new Array(),
		notCheck = new Array();


		$('input:checkbox[name=filter]:checked').each(function() {
			catergories_filtrered.push($(this).attr('custom-id'));
		});

		$('input:checkbox[name=filter]:not(:checked)').each(function() {
			notCheck.push( $(this).attr('custom-id') );
		});

		function unique(array){
		    return array.filter(function(el, index, arr) {
		        return index === arr.indexOf(el);
		    });
		}

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
			dataType: 'json',
			success: function( response ) {

				$('.produtoLista').remove();
				console.log( response );
	      		var i;
				
				//LOOP PROCUTOS
				for (i = 0; i < response['product'].length; ++i) {
				    // do something with `substr[i]`
				    var produto = '<div class="col-md-4 produtoLista">'
				    +'<a href=""><div class="produtoListaFoto"><img src="'+response['product'][''+i+''].thumbnail+'" alt=""></div></a>'
				    +'<a href=""><div class="produtoListaTitulo">'+response['product'][''+i+''].title+'</div></a>'
		      		+'</div>'; 	
				    

				    $('.produtos').append( produto );
				} $('.count').html( $('.produtoLista').length );

				//LOOP FILTROS
				for (i = 0; i < response['filter'].length; ++i) {
					//repetição dos filtros
					$("div[data-filter='" + response['filter'][''+i+''].id + "']").each(function(index, el) {
						$(this).remove();
					});

					var filter = '<div class="col-md-3 navegafiltro" data-filter="'+response['filter'][''+i+''].id+'">'
					+'<p>ESTILO DE PILOTAGEM : <span>'+response['filter'][''+i+''].name+'</span>'
					+'<a href="" class="del-filter"><i class="fa fa-trash-o"></i></a></p></div>';

					$('.filtronavegacao > div').append( filter );


				}
				$('.filtroLimpa').remove();
				$('.filtronavegacao > div').append('<div class="col-md-3 filtroLimpa"><a href=""><img src="http://auaha.com.br/jobs/goldfish//wp-content/themes/GoldFish/img/limpar.png"></a></div>');
			}
		});
	});

} ) ( jQuery );
