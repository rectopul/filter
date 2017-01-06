<?php get_header(); ?>
	<div class="container">
		<div class="col-md-2 bar-lateral">
			<?php 
				$args = array(
				    'number'     => $number,
				    'orderby'    => 'date',
				    'order'      => 'DESC',
				    'hide_empty' => $hide_empty,
				    'include'    => $ids
				);

				$categories = get_terms( 'product_cat', $args ); //var_dump($categories);

				//SHOW CAT PAI
				foreach ($categories as $value_cat) { 

					if ( $value_cat->parent == 0 ) {

						echo '<h1>'.$value_cat->name.'</h1>';
						echo ' <div class="bodyFiltroHQuest">'.str_replace('-', ' ', $value_cat->slug).'</div>';

						//SHOW CAT FILHO
						foreach ($categories as $value_cat_2) {
							echo ($value_cat_2->parent == $value_cat->term_id ? '<input type="checkbox" name="filter" custom-id="'.$value_cat_2->term_id.'"> '.
							$value_cat_2->name.' <br>' : '' );
						}
					}
				}
			?>
		</div>
		<div class="col-md-10 contador"> <h5><i class="count">0</i> Resultados para <i class="pesquisa">Teste</i> </h5> </div>
		<div class="col-md-10 filtronavegacao">
			<!-- NO PADDING-LEFT-RIGHT -->
			<div class="row"> </div>
		</div>
		<div class="col-md-10 produtos">
			
			<?php

			$args = array(
			   'posts_per_page' => -1, // -1 Mostrar todos
			   'post_type' 		=> 'product'
			);
			$query = new WP_Query($args);
			// Checar resultado
			if ( $query->have_posts() ) {
			   while ( $query->have_posts() ) {
			      $query->the_post();
			      $produto = wc_get_product(get_the_id());
			      // Metodos da WC_Product
			      // Must be inside a loop.
			       ?>
					<div class="col-md-4 produtoLista">
						<?php 
							if ( has_post_thumbnail() ) {

								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'obras_home' );

								$thumbnail_id = get_post_thumbnail_id( $post->ID );

								$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

							    echo '<img src="'.$thumb['0'].'" alt="'.$alt .'">';
							}
							else {
							    echo '<div class="thumb-obras">';
							}
						?>



						<a href=""><div class="title"><?php echo $produto->get_title(); ?></div> </a>
						<?php echo $produto->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', sizeof( get_the_terms( $post->ID, 'product_cat' ) ), 'woocommerce' ) . ' ', '.</span>' ); ?>
					</div>
			      <?php
			   }
			}
			// Restore original post data.
			wp_reset_postdata();
			?>
		</div>
	</div>
<?php get_footer(); ?>