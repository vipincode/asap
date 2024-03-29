	$product_tag = get_terms( array(
	   'taxonomy' 		=> 'product_tag',
	   'hide_empty' 	=> false,
	   'meta_key' => 'tag_group',
    	'orderby' => 'tag_group'
	) );
	
	if ($product_tag) {
		$tag_group_val = array();
		$product_tag_by_group = array();
		foreach ($product_tag as $p_tag_val) {
			$tag_group = get_term_meta($p_tag_val->term_id,'tag_group',true);
			if (!in_array($tag_group,$tag_group_val)) {
				$tag_group_val[] = $tag_group;
				$product_tag_by_group[$tag_group][] = $p_tag_val;
			} else {
				$product_tag_by_group[$tag_group][] = $p_tag_val;
			}
		}
	}
	if ($product_tag_by_group) {
	?>
		<div class="srn-main">
			<div class="block-menu">
				<?php foreach ($product_tag_by_group as $tag_name => $p_tag_val) { ?>
					<div class="block-menu-level-1">
						<h3><?php echo $tag_name ?></h3>
						<ul class="main">
							<?php foreach ($p_tag_val as $tags_arr) { ?>
								<li class="memu-item"><a attr="menu-<?php echo $tags_arr->term_id ?>"><?php echo $tag_name." ".$tags_arr->name ?></a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
				<div class="menu-next">
					<button class="menu-colse-icon">
						<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/05/Clear.svg" alt="close icon" />
					</button>
				<?php foreach ($product_tag_by_group as $tag_name => $p_tag_val) { ?>
					<?php  foreach ($p_tag_val as $tags_arr) {  ?>
						<div id="menu-<?php echo $tags_arr->term_id ?>" class="memu-item-content">
							<?php 
							$args = array(
								'post_type' 		=> 'product',
								'tax_query' 		=> array(
									array(
										'taxonomy' 	=> 'product_tag',
										'field' 		=> 'term_id',
										'terms' 		=> $tags_arr->term_id,
									),
								),
							);
							$tags_product = new WP_Query( $args );
							if ($tags_product->have_posts()) {?>
							<ul class="menu-item-inner">
								<li>
									<div class="menu-product">
										<a href="<?php echo get_term_link( $tags_arr->slug, 'product_tag' ) ?>" class="leve2-heading">Shop: <?php echo $tags_arr->name ?> <i class="fa-solid fa-chevron-right"></i></a>
										<div class="menu-product-card">
											<?php while ( $tags_product->have_posts() ) { $tags_product->the_post();
												$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); ?>
											<div class="serian-prd-tabs">
												
											
												<div class="menu-product-card-item" style="background-color:<?php echo get_field("section3_background_color", get_the_ID()); ?>">
													<a href="<?php echo get_permalink() ?>"><img src="<?php echo $featured_img_url; ?>" alt="Product" /></a>
<!-- 													<p><a href="<?php echo get_permalink() ?>"><?php echo esc_html( get_the_title() )  ?></a></p> -->
												</div>
												<p><a href="<?php echo get_permalink() ?>"><?php echo esc_html( get_the_title() )  ?></a></p>
												</div>
											<?php } ?>
										</div>
									</div>
								</li>
							</ul>
							<?php }
							wp_reset_postdata(); ?>
						</div>
					<?php } ?>
				<?php } ?>
				</div>
			</div>
		</div>
	<?php
	}

