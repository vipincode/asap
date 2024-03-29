<?php
$product = wc_get_product( get_the_id() );
// global $product;
$gallery_images = $product->get_gallery_image_ids();
$product_cats = wp_get_post_terms( $product->get_id(), 'product_cat' );
$product_key=get_post_meta($product->get_id(),'product_key',true);
$quantity_price=get_post_meta($product->get_id(),'quantity_&_price',true);

$attachment_ids = $product->get_gallery_attachment_ids();

?>

<!-- Section Detail -->
<?php
	$product_id = $product->get_id();
	$section1_background_color = get_field( "section1_background_color", $product_id );
	$section1_image = get_field( "section1_image", $product_id );
	$section1_title1 = get_field( "section1_title1", $product_id );
	$section1_text1 = get_field( "section1_text1", $product_id );
	$section1_title2 = get_field( "section1_title2", $product_id );
	$section1_text2 = get_field( "section1_text2", $product_id );
				
	$section2_image = get_field( "section2_image", $product_id );
	$section2_title = get_field( "section2_title", $product_id );
	$section2_text = get_field( "section2_text", $product_id );			
?>	
<!-- CSS WRITTEN INSIDE ELEMENTOR CUSTOME CODE -->
<div class="sng-container-wrapper">
	<div class="sl_product">
	<div class="sl_product_image">
		<div class="sl_product-carousel_wrap">
			
		<?php 
			if(empty($attachment_ids)){
				

				$f_image = $product->get_image_id(); 
				
				if($f_image) { ?>
					<div class="swiper-slide">
						<img src="<?php echo wp_get_attachment_image_src( $product->get_image_id(), 'medium' )[0]; ?>" alt="" />
					</div>	
				<?php  } else{ ?>
					<img src="<?php echo site_url()?>/wp-content/uploads/woocommerce-placeholder.png">
				<?php }
			}?>
		<?php
		if($attachment_ids > 0 ){ ?>
			<div class="swiper productSlider">
				<div class="swiper-wrapper">
					<?php foreach( $attachment_ids as $attachment_id ) 
					{ ?>
						<div class="swiper-slide">
							<img src="<?php echo wp_get_attachment_url( $attachment_id) ?>" alt="" />
						</div>
					<?php } ?>
				</div>
				<div class="swiper-scrollbar"></div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-pagination"></div>
			</div>
		<?php } ?>
		</div>
		<!-- 	Product description for Mobile layout	 -->
		<div class="sticky-image-section">
			<div class="sticky-image-bxo-c">
				<div class="sticky-image-bxo-content">
					<div class="sl_product_content">
						<div class="sl_product_content-item">
							<p class="sl_product_title">
								<?php
									$product_cat_arr          = array();
									foreach($product_cats as $product_cat){
										if ($product_cat->name !== 'All Products') {
											$product_cat_arr[] = $product_cat->name;
										}
// 										$product_cat_arr[] = $product_cat->name;
									}
									$cat_list = implode( ', ', $product_cat_arr );
									echo $cat_list;
								?></p>
							<p class="sl_product_name"><?php echo $product->get_name() ?></p>
							<p class="sl_product_short"><?php echo $product->get_short_description()?></p>
							<p class="sl_product_desc" style="height: 100px; overflow: hidden;"><?php echo $product->get_description()?>
									<?php
										if (strlen($product->get_description()) > 100){
											echo '.....';
										}
									?>
							</p>
							<p class="sl_product_underline" style="cursor:pointer;" id="showMoreSingleProductPage">
								Mehr anzeigen
							</p>
							<p class="sl_product_price"><?php echo $product->get_price_html(); ?></p>
							<div class="sl_product_qty_price">
								<?php if($product_key){ ?>
										<p><?php echo $product_key; ?></p>
										<?php } ?>
								<?php if($quantity_price){ ?>
										<p><?php echo $quantity_price; ?></p>
										<?php } ?>
							</div>
							<?php
									echo do_shortcode( '[add_to_cart  show_price="false" id="'.get_the_ID().'" ]' );
								?>
							<div class="sl_product_mark">
								<i class="fa fa-check-circle" aria-hidden="true"></i>
								<?php 
									$delivery_time = get_field( "delivery_time",  get_the_ID());
								?>
								<p><?php echo $delivery_time ?></p>
							</div>
						</div>
						<div class="sticky-image-bxo-c-mobile">
							<img class="scrl-prd-image-left scrl-prd-image" src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/08/Ingredient_Lotus_34839626_Square.webp" alt="" />
							<img class="scrl-prd-image-right scrl-prd-image" src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/08/Ingredient_White-tea-plant_11029_Square.webp" alt="" />
							</div>
						<div class="sticky-content-right-text">
							<div class="zmrt_container sl_pr_details_right sl_bg_red" style="background-color: <?php echo $section1_background_color?>">
								<div class="sl_pr_item">
									<h3><?php echo $section1_title1 ?></h3>
									<div class="sl_pr_item--top">
										<?php echo $section1_text1;?>
									</div>
								</div>
								<?php if(isset($section1_title2) && !empty($section1_title2)): ?>
									<div class="sl_pr_item br_top pt_16">
										<h3><?php echo $section1_title2 ?></h3>
										<div class="sl_pr_item--bottom">
											<?php echo $section1_text2 ?>
										</div>
									</div>
								<?php else: ?>
									<div style="display: none;"></div>
								<?php endif; ?>
								<div>
									<a href="#tab-title-inhaltsstoffe" class="tab-title-inhaltsstoffe btn-outline-white">
										ALLE INHALTSSTOFFE
									</a>
								</div>
							</div>
						</div> 
					</div>
				</div>
				<div class="sticky-image-bxo-c-desk">
				<img class="scrl-prd-image-left scrl-prd-image" src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/08/Ingredient_Lotus_34839626_Square.webp" alt="" />
				<img class="scrl-prd-image-right scrl-prd-image" src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/08/Ingredient_White-tea-plant_11029_Square.webp" alt="" />
				</div>	
			</div>
		</div>
	</div>
	<!-- 	Product description for desktop layout	 -->
	<div class="sl_product_content">
		<div class="sl_product_content-item desktop-descr-box">
			<p class="sl_product_title">
				<?php
					$product_cat_arr          = array();
					foreach($product_cats as $product_cat){
						if ($product_cat->name !== 'All Products') {
							$product_cat_arr[] = $product_cat->name;
						}
// 						$product_cat_arr[] = $product_cat->name;
					}
					$cat_list = implode( ', ', $product_cat_arr );
					echo $cat_list;
				?></p>
			<p class="sl_product_name"><?php echo $product->get_name() ?></p>
			<p class="sl_product_short"><?php echo $product->get_short_description()?></p>
			<p class="sl_product_desc" style="height: 100px; overflow: hidden;"><?php echo $product->get_description()?>
					<?php
						if (strlen($product->get_description()) > 100){
							echo '.....';
						}
					?>
			</p>
			<p class="sl_product_underline" id="showMoreSingleProductPage">
				Mehr anzeigen
			</p>
			<p class="sl_product_price"><?php echo $product->get_price_html(); ?></p>
			<div class="sl_product_qty_price">
				<?php if($product_key){ ?>
						<p><?php echo $product_key; ?></p>
						<?php } ?>
				<?php if($quantity_price){ ?>
						<p><?php echo $quantity_price; ?></p>
						<?php } ?>
			</div>
			<?php
					echo do_shortcode( '[add_to_cart  show_price="false" id="'.get_the_ID().'" ]' );
				?>
			<div class="sl_product_mark">
				<i class="fa fa-check-circle" aria-hidden="true"></i>
				<?php 
					$delivery_time = get_field( "delivery_time",  get_the_ID());
				?>
				<p><?php echo $delivery_time ?></p>
			</div>
		</div>
		<div class="sticky-content-right-text">
			<div class="zmrt_container sl_pr_details_right sl_bg_red" style="background-color: <?php echo $section1_background_color?>">
				<div class="sl_pr_item ">
					<h3><?php echo $section1_title1 ?></h3>
					<div class="sl_pr_item--top">
						<?php echo $section1_text1;?>
					</div>
				</div>
				<?php if(isset($section1_title2) && !empty($section1_title2)): ?>
					<div class="sl_pr_item br_top pt_16">
						<h3><?php echo $section1_title2 ?></h3>
						<div class="sl_pr_item--bottom">
							<?php echo $section1_text2 ?>
						</div>					
					</div>
				<?php else: ?>
					<div style="display: none;"></div>
				<?php endif; ?>
				<div>
					<a href="#tab-title-inhaltsstoffe" class="tab-title-inhaltsstoffe btn-outline-white">
						ALLE INHALTSSTOFFE
					</a>
				</div>
			</div>
		</div> 
	</div>
</div>
</div>

<!-- SECTION DETAILS -->

<div class="sl_pr_details_main">
	<div class="sl_pr_details">
		<div class="sl_pr_details_right sl_bg_white image_item">
			<img class="" src="<?php echo $section2_image ?>" alt="" />
		</div>
		<div class="sl_pr_details_left sl_bg_white flex-center">
			<div class="sl_pr_awending">
				<h3><?php echo $section2_title ?></h3>
				<?php echo $section2_text ?>
			</div>
		</div>
	</div>		
</div>
<script>
	jQuery(document).on("click","#showMoreSingleProductPage",function(){
		document.getElementById('tab-title-description').scrollIntoView();
	});
</script>

