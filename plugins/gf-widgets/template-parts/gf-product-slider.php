<?php
$random_id = rand();
$columnCount = (int) $instance['number_of_columns'];
if ($columnCount === 0) {
    $columnCount = 4;
}
$itemLimit = 16;
if (wp_is_mobile()) {
    $itemLimit = 10;
}
$link_term = get_term_by('slug', $instance['link_select'], 'product_cat');
$category_link = get_term_link($link_term->term_id);
?>
  <div id="<?php echo $random_id;?>" class="gf-product-slider" data-slider-item-count="<?=$columnCount?>">
    <div id="tabs">
      <div class="row gf-product-slider__header">
        <h3 class="gf-product-slider__header__title"><a href="<?=$category_link?>"><?= $instance['slider_title'] ?></a></h3>
          <?php if (isset($instance['tab_1']) && !empty(($instance['tab_1'])) ||
                    isset($instance['tab_2']) && !empty(($instance['tab_2'])) ||
                    isset($instance['tab_3']) && !empty(($instance['tab_3'])) ||
                    isset($instance['tab_4']) && !empty(($instance['tab_4'])) ||
                    isset($instance['tab_5']) && !empty(($instance['tab_5']))):?>
          <div class="gf-product-slider__tabs">
          <ul>
            <?php if (isset($instance['tab_1']) and !empty($instance['tab_1'])): ?>
            <li>
                <a href="#tabs-1"><?= get_term_by('slug', $instance['tab_1'], 'product_cat')->name ?></a>|
            </li>
            <?php endif; ?>
            <?php if (isset($instance['tab_2']) and !empty($instance['tab_2'])): ?>
            <li>
                <a href="#tabs-2"><?= get_term_by('slug', $instance['tab_2'], 'product_cat')->name ?></a>|
            </li>
            <?php endif; ?>
            <?php if (isset($instance['tab_3']) and !empty($instance['tab_3'])): ?>
            <li>
                <a href="#tabs-3"><?= get_term_by('slug', $instance['tab_3'], 'product_cat')->name ?></a>|
            </li>
            <?php endif; ?>
            <?php if (isset($instance['tab_4']) and !empty($instance['tab_4'])): ?>
            <li>
                <a href="#tabs-4"><?= get_term_by('slug', $instance['tab_4'], 'product_cat')->name ?></a>|
            </li>
            <?php endif; ?>
            <?php if (isset($instance['tab_5']) and !empty($instance['tab_5'])): ?>
            <li>
                <a href="#tabs-5"><?= get_term_by('slug', $instance['tab_5'], 'product_cat')->name ?></a>|
            </li>
            <?php endif; ?>
          </ul>
        </div>
          <?php endif; ?>
        <div class="gf-product-slider__header__controls">
          <a class="product-slider__control-prev gf-product-slider__header-control" href="#" role="button">
              <i class="fas fa-angle-left product-slider__control-prev-icon"></i>
            </a>
            <a class="product-slider__control-next gf-product-slider__header-control" href="#" role="button">
              <i class="fas fa-angle-right product-slider__control-next-icon"></i>
            </a>
        </div>
      </div>
      <?php if (isset($instance['tab_1']) and !empty($instance['tab_1'])): ?>
      <div id="tabs-1" class="slider-inner">
        <?php
            $args = array('post_type' => 'product', 'posts_per_page' => $itemLimit, 'product_cat' => $instance['tab_1'],
                'orderby' => 'name',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
            ));
            $loop = new WP_Query($args);
            while ($loop->have_posts()) :
                $loop->the_post();
                global $product; ?>
          <div class="slider-item">
            <a href="<?php echo get_permalink($loop->post->ID) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
              <?php woocommerce_show_product_sale_flash('', $product); ?>
              <?php if (has_post_thumbnail($loop->post->ID)) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?>
              <h5><?php the_title(); ?></h5>
              <span class="price"><?php echo $product->get_price_html(); ?></span>
            </a>
            <?php woocommerce_template_loop_add_to_cart($loop->post, $product); ?>
          </div>
          <?php endwhile; ?>
          <?php wp_reset_query(); ?>
      </div>
      <?php endif; ?>

      <?php if (isset($instance['tab_2']) and !empty($instance['tab_2'])): ?>
      <div id="tabs-2" class="slider-inner">
        <?php
            $args = array('post_type' => 'product', 'posts_per_page' => $itemLimit, 'product_cat' => $instance['tab_2'],
                'orderby' => 'name',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
            ));
            $loop = new WP_Query($args);
            while ($loop->have_posts()) :
                $loop->the_post();
                global $product; ?>
          <div class="slider-item">
            <a href="<?php echo get_permalink($loop->post->ID) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
              <?php woocommerce_show_product_sale_flash('', $product); ?>
              <?php if (has_post_thumbnail($loop->post->ID)) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?>
              <h5><?php the_title(); ?></h5>
              <span class="price"><?php echo $product->get_price_html(); ?></span>
            </a>
            <?php woocommerce_template_loop_add_to_cart($loop->post, $product); ?>
          </div>
          <?php endwhile; ?>
          <?php wp_reset_query(); ?>
      </div>
      <?php endif; ?>

      <?php if (isset($instance['tab_3']) and !empty($instance['tab_3'])): ?>
      <div id="tabs-3" class="slider-inner">
        <?php
            $args = array('post_type' => 'product', 'posts_per_page' => $itemLimit, 'product_cat' => $instance['tab_3'],
                'orderby' => 'name',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
            ));
            $loop = new WP_Query($args);
            while ($loop->have_posts()) :
                $loop->the_post();
                global $product; ?>
          <div class="slider-item">
            <a href="<?php echo get_permalink($loop->post->ID) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
              <?php woocommerce_show_product_sale_flash('', $product); ?>
              <?php if (has_post_thumbnail($loop->post->ID)) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?>
              <h5><?php the_title(); ?></h5>
              <span class="price"><?php echo $product->get_price_html(); ?></span>
            </a>
            <?php woocommerce_template_loop_add_to_cart($loop->post, $product); ?>
          </div>
          <?php endwhile; ?>
          <?php wp_reset_query(); ?>
      </div>
      <?php endif; ?>

      <?php if (isset($instance['tab_4']) and !empty($instance['tab_4'])): ?>
      <div id="tabs-4" class="slider-inner">
        <?php
            $args = array('post_type' => 'product', 'posts_per_page' => $itemLimit, 'product_cat' => $instance['tab_4'],
                'orderby' => 'name',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
            ));
            $loop = new WP_Query($args);
            while ($loop->have_posts()) :
                $loop->the_post();
                global $product; ?>
          <div class="slider-item">
            <a href="<?php echo get_permalink($loop->post->ID) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
              <?php woocommerce_show_product_sale_flash('', $product); ?>
              <?php if (has_post_thumbnail($loop->post->ID)) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?>
              <h5><?php the_title(); ?></h5>
              <span class="price"><?php echo $product->get_price_html(); ?></span>
            </a>
            <?php woocommerce_template_loop_add_to_cart($loop->post, $product); ?>
          </div>
          <?php endwhile; ?>
          <?php wp_reset_query(); ?>
      </div>
      <?php endif; ?>

      <?php if (isset($instance['tab_5']) and !empty($instance['tab_5'])): ?>
      <div id="tabs-5" class="slider-inner">
        <?php
            $args = array('post_type' => 'product', 'posts_per_page' => $itemLimit, 'product_cat' => $instance['tab_5'],
                'orderby' => 'name',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
            ));
            $loop = new WP_Query($args);
            while ($loop->have_posts()) :
                $loop->the_post();
                global $product; ?>
          <div class="slider-item">
            <a href="<?php echo get_permalink($loop->post->ID) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
              <?php woocommerce_show_product_sale_flash('', $product); ?>
              <?php if (has_post_thumbnail($loop->post->ID)) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?>
              <h5><?php the_title(); ?></h5>
              <span class="price"><?php echo $product->get_price_html(); ?></span>
            </a>
            <?php woocommerce_template_loop_add_to_cart($loop->post, $product); ?>
          </div>
          <?php endwhile; ?>
          <?php wp_reset_query(); ?>
      </div>
      <?php endif; ?>
    </div>
    <!--    slider-inner-->
  </div>