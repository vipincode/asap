<?php

?>
<div class="sdr-overlay">
	<div class="sdr-cart">
		<div class="sdr-cart-content">
			<div class="sdr-cart-close">
				<a class="btn-close">
					<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/05/Clear.svg"  alt="close" />
				</a>
			</div>
			<div class="sdr_cart_main xoo-wsc-products">
	<?php
	
	if(WC()->cart->get_cart_contents_count() == 0){
		echo '<div class="isCartEmpty"><h3>My shopping cart</h3><p>Oh, its empty! Lets fill it with joy</p></div>';
	}
	if ( WC()->cart->get_cart_contents_count()) { ?>
	
	<div class="sdr_cart_body">
		<div class="sdr_cart_heading">
			<h2>My shopping cart</h2>
			<span> <?php  echo count( WC()->cart->get_cart() ) ?> products</span>
			<h3 class="added_to_cart">Added to cart</h3>
		</div>
		<div class="sdr_cart_content">
			<?php 
		
			$subtotal = WC()->cart->subtotal;
			$currency_symbol = get_woocommerce_currency_symbol();
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product =  wc_get_product( $cart_item['data']->get_id() );
				$quantity = $cart_item['quantity'];	
				$price = $_product->get_regular_price();
				$sale_price = $_product->get_sale_price();
				$image = wp_get_attachment_url( $_product->get_image_id() ); 
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			?>
			<div class="sdr_cart">
				<div class="sdr_cart_left">
					<div  class="sdr_cart_image">
						<img src="<?php echo $image ?>" alt="product image" />
					</div>
					<div  class="sdr_cart_left_text">
						<h3><a href="<?php echo $product_permalink ?>"><?php echo $_product->get_name() ?></a></h3>
						<p><?php echo $_product->get_short_description() ?></p>
						<p class="pdr-qty">Quantity: <?php echo $quantity ?></p>
					</div>
				</div>	
				<div class="sdr_cart_right">
					<div class="quantity">
						<button class="minus">-</button>
						<input type="text" class="qty" value="<?php echo $quantity; ?>" />
						<button class="plus">+</button>
					</div>
					<div class="total-price">
						<span><?php echo $price * $quantity; ?></span>
					</div>
					<div class="product-remove">
							<?php echo apply_filters('woocommerce_cart_item_remove_link',sprintf('<a href="%s" class="remove crt_cart_remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-times-circle" aria-hidden="true"></i>Remove</a>',esc_url( wc_get_cart_remove_url( $cart_item_key ) ),esc_html__( 'Remove this item', 'woocommerce' ),esc_attr( $product_id ),
esc_attr( $_product->get_sku() )),$cart_item_key); ?>
										</div>
					<?php 
						if($sale_price) { ?>
						<strong class="offer_price">€ <?php echo $sale_price ?></strong>
						<?php } else { ?>
							<strong class="offer_price">€ <?php echo $price  ?></strong>
						<?php }?>
					<?php if($sale_price) { ?>
					<s class="origin_price"><?php echo $price; ?></s>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="sdr_cart_footer">
		<div class="prc_total">
			<p>Order Subtotal</p>
			<strong>€<?php echo $subtotal ?></strong>
		</div>
		<div class="prc_total_buttin">
			<a href="/produkte"class="btn btn_shoping">CONTINUE SHOPPING</a>
			<?php $checkout_url = wc_get_checkout_url(); ?>
<!-- 			<a href="<?php echo $checkout_url?>" class="btn btn_view_cart">Continue to checkout</a> -->
			<a href="/cart-2" class="btn btn_view_cart">View Cart</a>
		</div>
	</div>
	<?php }	?>
</div>

			
			
		</div>
	</div>
</div>


<script>
    jQuery(document).ready(function($) {
        // Function to update price
        function updatePrice($item) {
            var quantity = parseInt($item.find('.qty').val());
            var price = parseFloat($item.find('.offer_price').text().replace('€ ', ''));
            $item.find('.total-price span').text((price * quantity).toFixed(2));
        }

        // Increase quantity
        $('.plus').click(function() {
            var $input = $(this).prev('.qty');
            var val = parseInt($input.val());
            $input.val(val + 1);
            updatePrice($(this).closest('.sdr_cart'));
        });

        // Decrease quantity
        $('.minus').click(function() {
            var $input = $(this).next('.qty');
            var val = parseInt($input.val());
            if (val > 1) {
                $input.val(val - 1);
                updatePrice($(this).closest('.sdr_cart'));
            }
        });

        // Remove item
        $('.crt_cart_remove').click(function() {
            var $item = $(this).closest('.sdr_cart');
            $item.remove();
            // Update total price or do any other necessary actions here
        });
    });
</script>

