<?php
$kristall = get_term_by( 'slug', 'kristall', 'product_cat' );
$refresh = get_term_by( 'slug', 'refresh', 'product_cat' );
$kristall_id = $kristall->slug;
$refresh_id = $refresh->slug;
$kristall_product_args = array(
    'post_status' => 'publish',
    'limit' => -1,
    'category' => $kristall_id,
);

$refresh_product_args = array(
    'post_status' => 'publish',
    'limit' => -1,
    'category' => $refresh_id,
);
$kristall_products = wc_get_products($kristall_product_args);
$refresh_products = wc_get_products($refresh_product_args);
?>
<ul class="mobile_menu">
	<li><a href="/produkte">Produkte</a></li>
	<li><a href="#Bedürfnisse">Bedürfnisse</a>
		<ul class="submenu">
			<li><a href="#">Trockene Haut</a>
				<ul class="submenu">
					<li class="submenu-items">
						<div class="row p-3">
							<div class="col-6">
								<a href="#">
									<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2024/01/invisible_fresh.webp" alt="" />
									<p>refresh Stick</p>
								</a>
							</div>
							<div class="col-6">
								<a href="#">
									<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2024/01/invisible_fresh.webp" alt="" />
									<p>invisible fresh Spray</p>
								</a>
							</div>
							<div class="col-6">
								<a href="#">
									<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2024/01/refresh-_stick.webp" alt="" />
									<p>medcare roll-on</p>
								</a>
							</div>
							<div class="col-6">
								<a href="#">
									<img src="http://new.cl.de.dedi7317.your-server.de/wp-content/uploads/2024/01/invisible_fresh.webp" alt="" />
									<p>medcare Serie</p>
								</a>
							</div>
						</div>
					</li>
				</ul>
			</li>
			<li><a href="#">Ölige Haut</a>
				<ul class="submenu">
					<li  class="submenu-items">
						<div class="row">
							<div class="col-6"><a href="#"><p>Ölige Haut 1</p></a></div>
							<div class="col-6"><a href="#"><p>Ölige Haut 2</p></a></div>
							<div class="col-6"><a href="#"><p>Ölige Haut 3</p></a></div>
							<div class="col-6"><a href="#"><p>Ölige Haut 4</p></a></div>
						</div>
					</li>
				</ul>
			</li>
			<li><a href="#">Empfindliche Haut</a>
				<ul class="submenu">
					<li  class="submenu-items">
						<div class="row">
							<div class="col-6"><a href="#"><p>Empfindliche Haut 1</p></a></div>
							<div class="col-6"><a href="#"><p>Empfindliche Haut 2</p></a></div>
							<div class="col-6"><a href="#"><p>Empfindliche Haut 3</p></a></div>
							<div class="col-6"><a href="#"><p>Empfindliche Haut 4</p></a></div>
						</div>
					</li>
				</ul>
			</li>
			<li><a href="#">Gegen starkes schwitzen</a>
				<ul class="submenu">
					<li  class="submenu-items">
						<div class="row">
							<div class="col-6"><a href="#"><p>Gegen starkes schwitzen1</p></a></div>
							<div class="col-6"><a href="#"><p>Gegen starkes schwitzen 2</p></a></div>
							<div class="col-6"><a href="#"><p>Gegen starkes schwitzen 3</p></a></div>
							<div class="col-6"><a href="#"><p>Gegen starkes schwitzen 4</p></a></div>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</li>
	<li><a href="#Serien">Serien</a>
		<ul class="submenu srien-data">
			<li><a href="#">Kristal</a>
				<ul class="submenu">
					<li class="submenu-items">
						<div class="row p-3">
							<?php
							foreach($kristall_products as $kristall_product){
							?>
							<div class="col-6">
								<a href="<?php echo get_permalink($kristall_product->get_id()); ?>">
									<div class="srn-prd-image">
										<?php echo $kristall_product->get_image(); ?>
									</div>
									<p><?php echo $kristall_product->get_title(); ?></p>
								</a>
							</div>
							<?php } ?>
						</div>
					</li>
				</ul>
			</li>
			<li><a href="#">Refresh</a>
				<ul class="submenu">
					<li class="submenu-items">
						<div class="row p-3">
							<?php
							foreach($refresh_products as $refresh_product){
							?>
							<div class="col-6">
								<a href="<?php echo get_permalink($refresh_product->get_id()); ?>">
									<div class="srn-prd-image"><?php echo $refresh_product->get_image(); ?></div>
									<p><?php echo $refresh_product->get_title(); ?></p>
								</a>
							</div>
							<?php } ?>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</li>
  	<li><a href="/cl-inside">CL inside</a></li>
  	<li><a href="/about-us">About us</a></li>
</ul>

?>