<?php
$category_id = $_POST['category_id'];
if (empty($category_id)) {
  $args = array(
  'post_type' => 'product',
  'posts_per_page' => -1,
);
} else {
  $args= array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'tax_query' => array(
      array(
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => $category_id
      )
    )
  );
}

$cat_args = array(
  'taxonomy' => 'product_cat',
  'hide_empty' => false,
);
$tag_args = array(
  'taxonomy' => 'product_tag',
  'hide_empty' => false,
);
$query = new WP_Query($args);
$product_categories = get_terms( $cat_args );
$product_tags = get_terms( $tag_args );

// ADD TO CART
?>
<div id="product-container">
<!-- <div class="grid-sizer"></div> -->
<div class="filter-card-main product product_items grid-item">
<div class="filter-card">
  <div clss="breadcrumb-main">
<!-- 			<ul class="breadcrumb">
      <li><a href="#">Home</a></li>
      <li><a href="#">Körperpflege</a></li>
      <li>Deodorants</li>
    </ul> -->
    <?php echo woocommerce_breadcrumb()?>
  </div>
  <h3>SHOPPEN NACH PRODUKT</h3>
  <div class="filter-card--tag">
    <?php 
      foreach ($product_categories as $category) {
        if ($category->name !== 'Refresh' && $category->name !== 'Invisible fresh') {
      ?>
          <button class="sidebar-btns" data-id="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></button>
      <?php
        }
      }
      ?>
<!-- 			<?php 
       foreach( $product_categories as $category ) {
    ?>
      <button class="sidebar-btns" data-id="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></button>
     <?php
      }
    ?> -->
  </div>
  <h3 class="mt-20">ANWENDUNG</h3>
  <div class="filter-card--tag">
  <?php 
     foreach( $product_tags as $tag ) {
  ?>
    <button class="sidebar-btns" data-id="<?php echo $tag->term_id; ?>"><?php echo $tag->name; ?></button>
   <?php
    }
  ?>
</div>
  </div>
<!-- 	<div class="extra-card">
  <h2>CRUELTY FREE PRODUCTS</h2>
  <h3>Alle CL Produkte sind PETA Approved - d.h. alle Produkte werden vegan und tierleidfrei produziert.  </h3>
  <div>
    <img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/04/prd-product-1.jpg" alt="" />
  </div>
</div> -->
</div>	

<div class="grid-sizer"></div>
<?php

if ($query->have_posts()) {
  while ($query->have_posts()) {
      $query->the_post();
      global $product;
  $product_key=get_post_meta(get_the_ID(),'product_key',true);
  $quantity_price=get_post_meta(get_the_ID(),'quantity_&_price',true);
      ?>
      <div class="product product_items grid-item">
    <div class="product-image-box">
      <?php if ( has_post_thumbnail() ) { ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
      <?php } ?>
    </div>
    <div class="product_content">
      <h2 class="product_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div class="product_description"><?php the_excerpt(); ?></div>	
    <h3 class="product_price"><?php echo $product->get_price_html(); ?></h3>
      <div  class="product_qty">
<!-- 					<p>inkl. MwSt.</p> -->
        <?php if($product_key){ ?>
        <p><?php echo $product_key; ?></p>
        <?php } ?>
        <?php if($quantity_price){ ?>
        <p><?php echo $quantity_price; ?></p>
        <?php } ?>
<!-- 					<p>150ml (49,90 € / 1 l ) </p> -->
      </div>
    </div>
    <div class="product_footer">
<!-- 				<button class="add-to-cart" id="add-to-cart-button"> -->
<!-- 					IN DEN WARENKORB -->
        <?php
          echo do_shortcode( '[add_to_cart show_price="false" id="'.get_the_ID().'" ]' );
// 						woocommerce_template_loop_add_to_cart();
        ?>
<!-- 				</button> -->
    </div>
      </div>
      <?php
  }
  wp_reset_postdata();
} else {
  echo 'No products found';
}
?></div>