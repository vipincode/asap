	$product_categories = get_terms(array(
		'taxonomy' => 'product_cat',
        'orderby' => 'name',
        'field' => 'name',
        'order' => 'ASC',
		'feed_image' => '',
        'hide_empty' => false
	));

	if ($product_categories && !is_wp_error($product_categories)) {	?>
		<div class="srn-main">
			<div class="block-menu">
				<div class="block-menu-level-1">
					<ul class="main">
						<?php foreach ($product_categories as $product_category) { 
							if ($product_category->name !== 'All Products') { ?>
								<li class="memu-item">
									<a attr="menu-<?php echo $product_category->term_id ?>" href="#">
										<?php echo $product_category->name; ?>
									</a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
				<div class="menu-next">
					<button class="menu-colse-icon">
						<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/05/Clear.svg" alt="close icon" />
					</button>
					<?php foreach ($product_categories as $product_category) { 
					 	$category_url = get_term_link($product_category);
        				$thumbnail_id  = get_term_meta($product_category->term_id, 'thumbnail_id', true);
		 				$category_image_url = wp_get_attachment_url( $thumbnail_id ); 
					?>
						<div id="menu-<?php echo $product_category->term_id ?>" class="memu-item-content">
							<ul class="menu-item-inner">
								<li class="serie-product--box">
									<div class="serie-product--image">
									<a href="http://new.cl.de.dedi7317.your-server.de/serie?category=<?php echo $product_category->slug;?>">
											<img src="<?php echo esc_url($category_image_url); ?>" alt="<?php echo esc_attr($product_category->name); ?>" />
										</a>
									</div>
									<h3><?php echo $product_category->name; ?> Collection</h3>
									<a href="http://new.cl.de.dedi7317.your-server.de/serie?category=<?php echo $product_category->slug;?>">
										Disciver the collection&nbsp;&nbsp;<i class="fa-solid fa-angle-right"></i>
									</a>
								</li>
								
							</ul>
						</div>
					<?php } ?>
				</div>
			 </div>
		</div>
	<?php } 