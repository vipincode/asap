<?php
    $args = array(
      'post_type'=> 'post',
      'orderby'    => 'ID',
      'post_status' => 'publish',
      'order'    => 'DESC',
      'posts_per_page' => -1 // this will retrive all the post that is published 
  );

  $posts = new WP_Query( $args );
  
  $accumulator = 0;

  $isTwoLayout = true;

  if ( $posts-> have_posts() ) : ?>
      <div class="blog-container">
    <div class="blg-wrapper">
            <div class="row">
              <?php while ( $posts->have_posts() ) : $posts->the_post(); 
                  $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );?>
                  <?php if($isTwoLayout == true) { ?>
                      <div class="col-md-6">
                          <div class="blg-card">
                              <a href="<?php echo get_permalink( $post->ID ); ?>">
                                  <img src="<?php echo $url ?>" alt="" />
                              </a>
                              <div class="blg-card-content">
                                  <ul class="blg-posted-by">
                                      <li><i class="fa-solid fa-user"></i> <?php echo get_the_author(); ?></li>
                                      <li><i class="fa-solid fa-calendar-days"></i> <?php echo get_the_date( 'M jS Y' ); ?></li>
                                      <li><i class="fa-solid fa-clock"></i> <?php the_time( 'h:i A' ); ?></li>
                                  </ul>
                                  <a href="<?php echo get_permalink( $post->ID ); ?>" class="blg-heading"><h3><?php the_title(); ?>   </h3></a>

                <p class="blg-description"><?php echo substr(get_the_excerpt(), 0, 100) . '...'; ?>  </p>
                                  <a href="<?php echo get_permalink( $post->ID ); ?>" class="blg-read-more">Read more</a>
                              </div>
                          </div>
                      </div>                
                  <?php
                      $accumulator++;
                      
                      if($accumulator == 2){ $isTwoLayout = false; $accumulator = 0;}
                      
                  }
                  else { 
                  ?>
                      <div class="col-md-4">
                          <div class="blg-card">
                              <a href="<?php echo get_permalink( $post->ID ); ?>">
                                  <img src="<?php echo $url ?>" alt="" />
                              </a>
                              <div class="blg-card-content">
                                  <ul class="blg-posted-by">
                                      <li><i class="fa-solid fa-user"></i> <?php echo get_the_author(); ?></li>
                                      <li><i class="fa-solid fa-calendar-days"></i> <?php echo get_the_date( 'M jS Y' ); ?></li>
                                      <li><i class="fa-solid fa-clock"></i> <?php the_time( 'h:i A' ); ?></li>
                                  </ul>
                                  <a href="<?php echo get_permalink( $post->ID ); ?>" class="blg-heading"><h3><?php the_title(); ?>   </h3></a>
                                  <p class="blg-description"><?php echo substr(get_the_excerpt(), 0, 100) . '...'; ?>   </p>
                                  <a href="<?php echo get_permalink( $post->ID ); ?>" class="blg-read-more">Read more</a>
                              </div>
                          </div>
                      </div>
                  <?php
                      $accumulator++;
                              
                      if($accumulator == 3){ $isTwoLayout = true; $accumulator = 0;}    
                      } 
                  ?>
              <?php endwhile; ?>
          </div>
    </div>
      </div>
  <?php endif; wp_reset_postdata(); ?>

