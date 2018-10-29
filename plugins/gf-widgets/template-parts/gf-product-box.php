<?php
$category_term = get_term_by('slug', $instance['category_select'], 'product_cat');
$link_term = get_term_by('slug', $instance['link_select'], 'product_cat');
if ($category_term) {
    $category_link = get_term_link($link_term->term_id);
    if (isset($instance['slider_title']) and !empty($instance['slider_title'])) {
        $slider_title = $instance['slider_title'];
    } else {
        $slider_title = $category_term->name;
    }

}
$columnCount = (isset($instance['number_of_columns'])) ? (int) $instance['number_of_columns'] : 4;
if ($columnCount === 0) {
    $columnCount = 4;
}
?>
    <div class="gf-category-box">
        <div class="row gf-category-box__header">
            <h3 class="gf-category-box__header__title"><a href="<?= $category_link ?>"><?= $slider_title ?></a></h3>
        </div>
        <div class="row gf-category-box__body">
            <?php
            $args = array('post_type' => 'product', 'posts_per_page' => 6, 'product_cat' => $instance['category_select'],
                'orderby' => 'name',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
                ));
            $loop = new WP_Query($args);
            $i = 1;
            while ($loop->have_posts()) :
                $loop->the_post();
                global $product;
                $i++;

                ?>
                <div class="col-lg col-md col-sm gf-category-box__item">
                    <a href="<?php echo get_permalink($loop->post->ID) ?>"
                       title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                        <?php woocommerce_show_product_sale_flash('', $product); ?>
                        <?php add_stickers_to_products_new(); ?>
                        <?php if (has_post_thumbnail($loop->post->ID)) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300px" height="300px" />'; ?>
                        <h5><?php the_title(); ?></h5>
                        <span class="price"><?php echo $product->get_price_html(); ?></span>
                    </a>
                    <?php woocommerce_template_loop_add_to_cart($loop->post, $product); ?>
                </div>

                <?php if($i > $columnCount){break;}  ?>

            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
        </div><!--gf-category-body-->
    </div>
