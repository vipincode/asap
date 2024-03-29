<?php
// Get the product category slug from the URL using $_GET
$product_category_slug = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

// Check if a product category slug is present
if ($product_category_slug) {
    // Get the product category object based on the slug
    $product_category = get_term_by('slug', $product_category_slug, 'product_cat');

    // Check if the product category is found
    if ($product_category) {
        // Output product category details
        echo "Product Category ID: {$product_category->term_id}<br>";
        echo "Product Category Name: {$product_category->name}<br>";
        echo "Product Category Description: {$product_category->description}<br>";
        $category_image_id = get_term_meta($product_category->term_id, 'thumbnail_id', true);
        $category_image_url = wp_get_attachment_url($category_image_id);

        if ($category_image_url) {
            echo '<img src="' . $category_image_url . '" alt="' . $product_category->name . '">';
        } else {
            echo 'No category image for this product category.';
        }

        // Now, you can use the product category ID to fetch products in that category
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $product_category->term_id,
                ),
            ),
        );

        $product_query = new WP_Query($args);

        // Check if there are products in the category
        if ($product_query->have_posts()) {
            echo '<h2>Products in this category:</h2>';
            while ($product_query->have_posts()) {
                $product_query->the_post();
                $product = wc_get_product(get_the_ID());
                $product_key=get_post_meta(get_the_ID(),'product_key',true);
                $quantity_price=get_post_meta(get_the_ID(),'quantity_&_price',true);
                
                echo '<h3>' . get_the_title() . '</h3>';
                echo '<div>' . get_the_content() . '</div>';
                
                echo '<p>Price: ' . wc_price($product->get_price()) . '</p>';
                echo '<p>' . $product->get_short_description() . '</p>';
                echo '<p>' . get_post_meta(get_the_ID(),'product_key',true) . '</p>';
                echo '<p>' . get_post_meta(get_the_ID(),'quantity_&_price',true) . '</p>';
                
                $product_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

                if ($product_image_url) {
                    echo '<img style="height:200px" src="' . $product_image_url . '" alt="' . get_the_title() . '">';
                } else {
                    echo 'No featured image for this product.';
                }
            }
        } else {
            echo 'No products found in this category.';
        }

        // Restore original post data
        wp_reset_postdata();
    } else {
        echo "Product Category not found";
    }
} else {
    echo "Product Category Slug not found in the URL";
}