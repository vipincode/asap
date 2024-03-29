<?php
try{
	$product_id = get_the_ID();

	$section3_background_color = get_field("section3_background_color", $product_id);

	$section3_image = get_field("section3_image", $product_id);

	$section3_title = get_field("section3_title", $product_id);

	$section3_subtitle = get_field("section3_subtitle", $product_id);

	$section3_text = get_field("section3_text", $product_id);

	$section3_link = get_field("section3_link", $product_id);

	// Getting product categories
	$product_info = wp_get_post_terms( $product_id, 'product_cat' );

	$product_categories;

	$product_category_slug;

	if(is_array($product_info) == 1 ){
		if(count($product_info) > 1){
			$product_categories = $product_info[1];
		}else{
			$product_categories = $product_info[0];
		}
	} else{
		$product_categories = $product_info;
	}
	
	$product_category_slug = $product_categories->slug;

	// Fetching other products of this category using categoryslug
	$product_args = array(
		'post_status' => 'publish',
		'limit' => 4,
// 		'category' => [$product_category_slug],
		'meta_key'      => 'section3_background_color',
		'meta_value'    => $section3_background_color
	);
	
	$products = wc_get_products($product_args);
}
catch(\Throwable $e){
	var_dump($e);
}
?>
<div class="sl_pr_details_main">
	<div class="sl_gpr_details">
		<div class="flex-center sl_bg_red quick-cart-box-main" style="background-color: <?php echo $section3_background_color?>">
			<div class="quick-cart-box">
				<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/05/Clear.svg" alt="" class="quick-cart-box-close" />
				<div class="quick-cart-item">
					<div class="quick-cart-item-image">
						<img id="quick-cart-box-image" src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/04/cl-med-care-product-8.png" alt="" />
					</div>
					<span id="quick-cart-box-title"></span>
					<h3 id="quick-cart-box-description"></h3>
					<strong id="quick-cart-box-price"></strong>
					<div id="quick-cart-add-cart-div">
						
					</div>
					<a id="quick-cart-box-link" href="#">VIEW FULL DETAILS</a>
				</div>
			</div>
			<div class="sl_gpr_details_text">
				<span><?php echo $section3_subtitle?></span>
				<h3><?php echo $section3_title?></h3>
				<p>
					<?php echo $section3_text?>
				</p>
				<button><?php echo $section3_link['title']?> </button>
			</div>
		</div>
		<div class="sl_pr_details_right sl_bg_white image_item slc-product-main-warpper">
			<div class="slc-product-main">
					<?php
						$counter = 1;
						foreach($products as $key => $product){ ?>
							<div class="sl-product-imge<?php echo $counter; ?>">
								<img class="prdc-image" 
										src="<?php echo wp_get_attachment_image_src($product->image_id,'adv-pos-a-default')[0]; ?>" alt="" />
								<img class="plus-icon" 
									 src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/07/white-bg-plus-icon.png" data-id="<?php echo $product->id ?>" alt="" />
							</div>
						<?php
							$counter = $counter + 1;
						}
					?>
			</div>
			
		</div>
	</div>	
</div>