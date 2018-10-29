<?php
$category_term_1 = get_term_by('slug', $instance['category_select_1'], 'product_cat');
$category_link_1 = get_term_link($category_term_1->term_id);

$category_term_2 = get_term_by('slug', $instance['category_select_2'], 'product_cat');
$category_link_2 = get_term_link($category_term_2->term_id);

$category_term_3 = get_term_by('slug', $instance['category_select_3'], 'product_cat');
$category_link_3 = get_term_link($category_term_3->term_id);

$category_term_4 = get_term_by('slug', $instance['category_select_4'], 'product_cat');
$category_link_4 = get_term_link($category_term_4->term_id);

$category_term_5 = get_term_by('slug', $instance['category_select_5'], 'product_cat');
$category_link_5 = get_term_link($category_term_5->term_id);

$category_term_6 = get_term_by('slug', $instance['category_select_6'], 'product_cat');
$category_link_6 = get_term_link($category_term_6->term_id);
?>

<div class="row gf-image-banners">
    <div class="row gf-image-banners-wider">
        <a href="<?= $category_link_1 ?>" target="_self">
            <?php
            if (!empty($instance['image_banner_1_value'])) {
                list($width_1, $height_1) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_banner_1_value']));
            } ?>
            <img src="<?= $instance['image_banner_1_value'] ?>" width="<?= $width_1 ?>" height="auto">
        </a>
    </div>

    <div class="col-6 gf-image-banners__item">
        <a href="<?= $category_link_2 ?>">
            <?php
            if (!empty($instance['image_banner_2_value'])) {
                list($width_2, $height_2) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_banner_2_value']));
            } ?>
            <img src="<?= $instance['image_banner_2_value'] ?>" width="<?= $width_2 ?>" height="auto">
        </a>
    </div>
    <div class="col-6 gf-image-banners__item">
        <a href="<?= $category_link_3 ?>">
            <?php
            if (!empty($instance['image_banner_3_value'])) {
                list($width_3, $height_3) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_banner_3_value']));
            } ?>
            <img src="<?= $instance['image_banner_3_value'] ?>" width="<?= $width_3 ?>" height="auto">
        </a>
    </div>

    <div class="row gf-image-banners-wider">
        <a href="<?= $category_link_4 ?>">
            <?php
            if (!empty($instance['image_banner_4_value'])) {
                list($width_4, $height_4) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_banner_4_value']));
            } ?>
            <img src="<?= $instance['image_banner_4_value'] ?>" width="<?= $width_4 ?>" height="auto">
        </a>
    </div>

    <div class="col-6 gf-image-banners__item">
        <a href="<?= $category_link_5 ?>">
            <?php
            if (!empty($instance['image_banner_5_value'])) {
                list($width_5, $height_5) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_banner_5_value']));
            } ?>
            <img src="<?= $instance['image_banner_5_value'] ?>" width="<?= $width_5 ?>" height="auto">
        </a>
    </div>
    <div class="col-6 gf-image-banners__item">
        <a href="<?= $category_link_6 ?>">
            <?php
            if (!empty($instance['image_banner_6_value'])) {
                list($width_6, $height_6) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_banner_6_value']));
            } ?>
            <img src="<?= $instance['image_banner_6_value'] ?>" width="<?= $width_6 ?>" height="auto">
        </a>
    </div>
</div>
