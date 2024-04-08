<?php
?>
<div class="product-filter-button">
    <a>
        <img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2023/04/Fiter-icon.svg"
            alt="filter button" />
        <span>FILTER</span>
    </a>
    <div>
        <select id="sort-filter">
            <option selected value="">SORTIEREN NACH</option>
            <?php
            $options = [
                [
                    "value" => "hightolow",
                    "text" => "BESTSELLERS"
                ],
                [
                    "value" => "lowtohigh",
                    "text" => "PREIS ABSTEIGEND"
                ],
                [
                    "value" => "atoz",
                    "text" => "PREIS AUFSTEIGEND"
                ],
                [
                    "value" => "ztoa",
                    "text" => "Product Name Z - A"
                ],
            ];

            foreach ($options as $option) {
                echo '<option value="' . $option['value'] . '">' . $option['text'] . '</option>';
            }
            ?>
        </select>
    </div>
</div>
<script>
    jQuery(document).on("change", "#sort-filter", function () {
        let sortType = jQuery(this).val();

        jQuery.ajax({
            type: "POST",
            url: "/rest-api-php/src/sort-api.php",
            data: {
                sortType,
            },
            success: function (response) {
			   let parsedData = JSON.parse(response);
         let productData = parsedData.filteredData;
         let tags = parsedData.tags;
         let categories = parsedData.categories;
			   let metadata = 	parsedData.meta_data;
			
          let htmlTags = `<div class="filter-card product_items"><h3>SHOPPEN NACH PRODUKT</h3><div class="filter-card--tag">`;
  
          categories.forEach((element) => {
            htmlTags += `<button class="sidebar-btns" data-id="${element.id}">${element.name}</button>`;
          });
  
          htmlTags += `</div><h3>ANWENDUNG</h3><div class="filter-card--tag">`;
  
          tags.forEach((element) => {
            htmlTags += `<button class="sidebar-btns" data-id="${element.id}">${element.name}</button>`;
          });
  
          htmlTags += `</div></div>`;
  
          jQuery.each(productData, function (index, data) {
			 
			      const productmeta = [];
			      jQuery.each(data.meta_data, function (metaindex, metadata) {	
				
				      if(metadata.key == "product_key" ){
					     productmeta[metadata.key] = metadata.value;
				      }
				      if( metadata.key == "quantity_&_price"){
					     productmeta['product_quantity'] = metadata.value;
				      }
			      });
			       htmlTags += `<div class="product product_items">
                <div class="product-image-box">
                    <a href="${data.permalink}">
						<img src="${data.images[0].src}" alt='' />
					</a>
                </div>
                <div class="product_content">
						<h2 class='product_title'>
							<a href="${data.permalink}">
								${data.name}
							</a>
						</h2>

                    <div class="product_description">${data.short_description}</div>	
                    
						<h3 class="product_price">${data.price}</h3>
                    <div  class="product_qty">
                        <p>${productmeta.product_key}</p>
                        <p>${productmeta.product_quantity} </p>
                    </div>
                </div>
                <div class="product_footer">
						<p class="product woocommerce add_to_cart_inline " style="border:4px solid #ccc; padding: 12px;"><a href="?add-to-cart=${data.id}" data-quantity="1" class="button wp-element-button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="${data.id}" data-product_sku="" aria-label="Add “${data.name}” to your cart" rel="nofollow">Add to cart</a></p>
                    
                    
                    </div>
                </div>`;
          });
  
          jQuery("#product-container").html(htmlTags);
          jQuery("#product-container").masonry("reloadItems");
          jQuery("#product-container").masonry("layout");
        },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    });
</script>