<div id="carouselExample" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExample" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExample" data-slide-to="1"></li>
        <li data-target="#carouselExample" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <?php if (isset($instance['image_1_value']) && !empty($instance['image_1_value']) ):?>
        <div class="carousel-item active">
            <a href="<?= $instance['image_1_link'] ?>" >
                <?php list($width_slider_1, $height_slider_1) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_1_value'])); ?>
                <img class="d-block w-100" src="<?= $instance['image_1_value'] ?>" alt="Nonstopshop" width="<?=$width_slider_1?>" height="auto">
            </a>
        </div>
        <?php endif;?>
        <?php if (isset($instance['image_2_value']) && !empty($instance['image_2_value']) ):?>
        <div class="carousel-item">
            <a href="<?= $instance['image_2_link'] ?>" >
                <?php list($width_slider_2, $height_slider_2) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_2_value'])); ?>
                <img class="d-block w-100" src="<?= $instance['image_2_value'] ?>" alt="" width="<?=$width_slider_2?>" height="auto">
            </a>
        </div>
        <?php endif;?>
        <?php if (isset($instance['image_3_value']) && !empty($instance['image_3_value']) ):?>
        <div class="carousel-item">
            <a href="<?= $instance['image_3_link'] ?>">
                <?php list($width_slider_3, $height_slider_3) = getimagesize(ABSPATH . str_replace(home_url(), '', $instance['image_3_value'])); ?>
                <img class="d-block w-100" src="<?= $instance['image_3_value'] ?>" alt="" width="<?=$width_slider_2?>" height="auto">
            </a>
        </div>
        <?php endif;?>
    </div>
    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Prethodni</span>
    </a>
    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Sledeći</span>
    </a>
</div>
