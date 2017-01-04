<?php 
function theme_enqueue_styles() {

	wp_enqueue_script('jquery', true );
    wp_enqueue_style('style', get_stylesheet_uri(), false, '0.0.10');
    wp_enqueue_style('Normalize', get_template_directory_uri() . '/lib/css/normalize.css', false, '3.3.7');
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/lib/css/bootstrap.min.css', false, '3.3.7');
	wp_enqueue_script('bootstrap_js', get_template_directory_uri() . '/lib/js/bootstrap.min.js', array('jquery'));
	wp_enqueue_style('Fira Sans', "https://fonts.googleapis.com/css?family=Titillium+Web:200,200i,300,300i,400,400i,600,600i,700,700i,900", array(), null);

	/*FONT AWESOME*/
	wp_enqueue_style('FONT AWESOME', get_template_directory_uri() . '/lib/css/font-awesome.min.css', false, '3.3.7');

	/*effects*/
	wp_enqueue_script('effects', get_template_directory_uri() . '/lib/js/effects.js', array('jquery'), '0.0.1', true );
	
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

//load_template( get_template_directory_uri() . '/lib/functions/load-posts.php' );



/********* -------- SCRIPT PARA FILTRAR PRODUTOS -------- *********/

//Adiciona um script para o WordPress
add_action( 'wp_enqueue_scripts', 'secure_enqueue_script' );
function secure_enqueue_script() {

  	wp_register_script( 'secure-ajax-access', esc_url( add_query_arg( array( 'js_global' => 1 ), site_url() ) ) );
  	wp_enqueue_script( 'secure-ajax-access' );
}

//Joga o nonce e a url para as requisições para dentro do Javascript criado acima
add_action( 'template_redirect', 'javascript_variaveis' );
function javascript_variaveis() {
  if ( !isset( $_GET[ 'js_global' ] ) ) return;
 
  $nonce = wp_create_nonce('filter_products_nonce');
 
  $variaveis_javascript = array(
    'filter_products_nonce' => $nonce, //Esta função cria um nonce para nossa requisição para buscar mais notícias, por exemplo.
    'xhr_url'             => admin_url('admin-ajax.php') // Forma para pegar a url para as consultas dinamicamente.
  );

  $new_array = array();
  foreach( $variaveis_javascript as $var => $value ) $new_array[] = esc_js( $var ) . " : '" . esc_js( $value ) . "'";
 
  header("Content-type: application/x-javascript");
  printf('var %s = {%s};', 'js_global', implode( ',', $new_array ) );
  exit;
}

add_action('wp_ajax_nopriv_filter_products', 'filter_products');
add_action('wp_ajax_filter_products', 'filter_products');
 
function filter_products() {
  if( ! wp_verify_nonce( $_POST['filter_products_nonce'], 'filter_products_nonce' ) ) {
    echo '401'; // Caso não seja verificado o nonce enviado, a requisição vai retornar 401
    die();
  }

  	//Busca os dados que queremos

  	//Caso tenha os dados, retorna-os / Caso não tenha retorna 402 para tratarmos no frontend
  	//LOOP ALL POSTS MIN 
	
	if ( isset( $_POST['categories_filter'] ) ) {

		$categoriesProduct = array_unique($_POST['categories_filter']);
		$notCheck = array_unique( $_POST['categories_not_check'] );

		$args = array(
		    'posts_per_page' => -1, // -1 Mostrar todos
			'post_type'   	 => 'product',
		    'order' 		 => 'desc',
			'tax_query' 	 => array(
			    array(
			        'taxonomy'      => 'product_cat',
			        'field' 		=> 'term_id', //This is optional, as it defaults to 'term_id'
			        'terms'         => $categoriesProduct,
			        'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
			    )
			)
		);

		$query = new WP_Query($args);

		if ( $query->have_posts() ) {
		    while ( $query->have_posts() ) {

		       $query->the_post();
		       $produto = wc_get_product(get_the_id());     
		    ?>
		    
	      	<div class="produtoLista"> 
		       <a href=""><div class="produtoListaFoto"> <?php the_post_thumbnail(); ?> </div></a>
		       <a href=""><div class="produtoListaTitulo">  <?php echo $produto->get_title(); ?> </div></a>
      		</div>      
		<?php   
	    	}
	 	}
	}
  exit;
}