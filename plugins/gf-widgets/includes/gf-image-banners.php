<?php
$category_term_1 = get_term_by('slug', $instance['category_select_1'], 'product_cat');
$category_link_1 = get_term_link($category_term_1->term_id);

$category_term_2 = get_term_by('slug', $instance['category_select_2'], 'product_cat');
$category_link_2 = get_term_link($category_term_2->term_id);

$category_term_3 = get_term_by('slug', $instance['category_select_3'], 'product_cat');
$category_link_3 = get_term_link($category_term_3->term_id);

$category_term_4 = get_term_by('slug', $instance['category_select_4'], 'product_cat');
$category_link_4 = get_term_link($category_term_4->term_id);
?>

<div class="row gf-image-banners">
    <div class="col-6 gf-image-banners__item">
        <a href="<?=$category_link_1?>" target="_self">
            <?php list($width_1, $height_1) = getimagesize($instance['image_banner_1_value']); ?>
            <img src="<?= $instance['image_banner_1_value'] ?>" width="<?=$width_1?>" height="auto" >
        </a>
    </div>
    <div class="col-6 gf-image-banners__item">
        <a href="<?=$category_link_2?>" target="_blank">
            <?php list($width_2, $height_2) = getimagesize($instance['image_banner_2_value']); ?>
            <img src="<?= $instance['image_banner_2_value'] ?>" width="<?=$width_2?>" height="auto">
        </a>
    </div>
    <div class="col-6 gf-image-banners__item">
        <a href="<?=$category_link_3?>" target="_blank">
            <?php list($width_3, $height_3) = getimagesize($instance['image_banner_3_value']); ?>
            <img src="<?= $instance['image_banner_3_value'] ?>" width="<?=$width_3?>" height="auto">
        </a>
    </div>
    <div class="col-6 gf-image-banners__item">
        <a href="<?=$category_link_4?>" target="_blank">
            <?php list($width_4, $height_4) = getimagesize($instance['image_banner_2_value']); ?>
            <img src="<?= $instance['image_banner_4_value'] ?>" width="<?=$width_4?>" height="auto">
        </a>
    </div>
</div>
