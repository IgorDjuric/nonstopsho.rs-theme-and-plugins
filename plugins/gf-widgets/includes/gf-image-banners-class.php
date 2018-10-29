<?php
//@todo Napraviti da moze manje od tri slike i da moze da se removuje slika
class gf_image_banners_widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'gf_image_banners_widget', // Base ID
            esc_html__('GF Image Banners Widget', 'gf_widgets_domain'), // Name
            array('description' => esc_html__('Image banners', 'gf_widgets_domain'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if(
                isset($instance['category_select_1']) and !empty($instance['category_select_1']) and
                isset($instance['category_select_2']) and !empty($instance['category_select_2']) and
                isset($instance['category_select_3']) and !empty($instance['category_select_3']) and
                isset($instance['category_select_4']) and !empty($instance['category_select_4']) and
                isset($instance['category_select_5']) and !empty($instance['category_select_4']) and
                isset($instance['category_select_6']) and !empty($instance['category_select_4']))
            {
                require(realpath(__DIR__ . '/../template-parts/gf-image-banners.php'));
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $cat_args = array(
            'orderby'   => 'name',
            'order'     => 'ASC',
            'hierarchical' => 1,
            'hide_empty'    => '0'

        );

        $product_categories = get_terms('product_cat', $cat_args);
//        var_dump($product_categories);

        $image_1 = !empty($instance['image_banner_1']) ? $instance['image_banner_1'] : esc_html__('Image banner 1', 'gf_image_banners_widget_domain');
        $image_1_value = !empty($instance['image_banner_1_value']) ? $instance['image_banner_1_value'] : esc_html__('', 'gf_image_banners_widget_domain');
        $category_select_1 = !empty($instance['category_select_1']) ? $instance['category_select_1'] : esc_html__('', 'gf_image_banners_widget_domain');

        $image_2 = !empty($instance['image_banner_2']) ? $instance['image_banner_2'] : esc_html__('Image banner 2', 'gf_image_banners_widget_domain');
        $image_2_value = !empty($instance['image_banner_2_value']) ? $instance['image_banner_2_value'] : esc_html__('', 'gf_image_banners_widget_domain');
        $category_select_2 = !empty($instance['category_select_2']) ? $instance['category_select_2'] : esc_html__('', 'gf_image_banners_widget_domain');

        $image_3 = !empty($instance['image_banner_3']) ? $instance['image_banner_3'] : esc_html__('Image banner 3', 'gf_image_banners_widget_domain');
        $image_3_value = !empty($instance['image_banner_3_value']) ? $instance['image_banner_3_value'] : esc_html__('', 'gf_image_banners_widget_domain');
        $category_select_3 = !empty($instance['category_select_1']) ? $instance['category_select_3'] : esc_html__('', 'gf_image_banners_widget_domain');

        $image_4 = !empty($instance['image_banner_4']) ? $instance['image_banner_4'] : esc_html__('Image 4', 'gf_image_banners_widget_domain');
        $image_4_value = !empty($instance['image_banner_4_value']) ? $instance['image_banner_4_value'] : esc_html__('', 'gf_image_banners_widget_domain');
        $category_select_4 = !empty($instance['category_select_4']) ? $instance['category_select_4'] : esc_html__('', 'gf_image_banners_widget_domain');

        $image_5 = !empty($instance['image_banner_5']) ? $instance['image_banner_5'] : esc_html__('Image 5', 'gf_image_banners_widget_domain');
        $image_5_value = !empty($instance['image_banner_5_value']) ? $instance['image_banner_5_value'] : esc_html__('', 'gf_image_banners_widget_domain');
        $category_select_5 = !empty($instance['category_select_5']) ? $instance['category_select_5'] : esc_html__('', 'gf_image_banners_widget_domain');

        $image_6 = !empty($instance['image_banner_6']) ? $instance['image_banner_6'] : esc_html__('Image 6', 'gf_image_banners_widget_domain');
        $image_6_value = !empty($instance['image_banner_6_value']) ? $instance['image_banner_6_value'] : esc_html__('', 'gf_image_banners_widget_domain');
        $category_select_6 = !empty($instance['category_select_6']) ? $instance['category_select_6'] : esc_html__('', 'gf_image_banners_widget_domain');

        ?>

        <div class="gf-image-banner-widget-form">
            <div class="gf-image-banner-block">
                <label for="<?php echo esc_attr($this->get_field_id('image_banner_1')); ?>">
                    <?php esc_attr_e('Banner 1 (veca slika)', 'gf_product_slider_without_tabs_widget_domain'); ?>
                </label>
                <input
                        class="gf-upload-banner-image-1 widefat"
                        id="upload-banner-image-1"
                        name="<?php echo esc_attr($this->get_field_name('image_banner_1')); ?>"
                        type="button"
                        value="Izaberite sliku">
                <input type="button" id="gf-remove-image-banner-1" name="remove-image" value="Obriši sliku">

                <input class="image_banner_1_value"
                       id=" <?php echo esc_attr($this->get_field_id('image_banner_1_value')); ?>"
                       type="hidden"
                       name="<?php echo esc_attr($this->get_field_name('image_banner_1_value')); ?>"
                       value="<?php echo esc_attr($image_1_value); ?>">
                <select
                        class="gf-category-select widefat"
                        id="<?php echo esc_attr($this->get_field_id('category_select_1')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('category_select_1')); ?>">
                    <?php foreach ($product_categories as $category) : ?>
                        <option
                            <?php if (isset($instance['category_select_1'])) {
                                if ($category->slug == $instance['category_select_1']) {
                                    echo 'selected';
                                }
                            } ?> value="<?= $category->slug ?>"><?= $category->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="gf-image-preview-wrapper"><img src="<?= esc_attr($image_1_value); ?>"></div>
            </div>
            <div class="gf-image-banner-block">
                <label for="<?php echo esc_attr($this->get_field_id('image_banner_2')); ?>">
                    <?php esc_attr_e('Banner 2 (manja slika)', 'gf_product_slider_without_tabs_widget_domain'); ?>
                </label>
                <input
                        class="gf-upload-banner-image-2 widefat"
                        id="upload-banner-image-2"
                        name="<?php echo esc_attr($this->get_field_name('image_banner_2')); ?>"
                        type="button"
                        value="Izaberite sliku">
                <input type="button" id="gf-remove-image-banner-2" name="remove-image" value="Obriši sliku">


                <input class="image_banner_2_value"
                       id=" <?php echo esc_attr($this->get_field_id('image_banner_2_value')); ?>"
                       type="hidden"
                       name="<?php echo esc_attr($this->get_field_name('image_banner_2_value')); ?>"
                       value="<?php echo esc_attr($image_2_value); ?>">
                <select
                        class="gf-category-select widefat"
                        id="<?php echo esc_attr($this->get_field_id('category_select_2')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('category_select_2')); ?>">
                    <?php foreach ($product_categories as $category) : ?>
                        <option
                            <?php if (isset($instance['category_select_2'])) {
                                if ($category->slug == $instance['category_select_2']) {
                                    echo 'selected';
                                }
                            } ?> value="<?= $category->slug ?>"><?= $category->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="gf-image-preview-wrapper"><img src="<?= esc_attr($image_2_value); ?>"></div>
            </div>
            <div class="gf-image-banner-block">
                <label for="<?php echo esc_attr($this->get_field_id('image_banner_3')); ?>">
                    <?php esc_attr_e('Banner 3 (manja slika)', 'gf_product_slider_without_tabs_widget_domain'); ?>
                </label>
                <input
                        class="gf-upload-banner-image-3 widefat"
                        id="upload-banner-image-3"
                        name="<?php echo esc_attr($this->get_field_name('image_banner_3')); ?>"
                        type="button"
                        value="Izaberite sliku">
                <input type="button" id="gf-remove-image-banner-3" name="remove-image" value="Obriši sliku">

                <input class="image_banner_3_value"
                       id=" <?php echo esc_attr($this->get_field_id('image_banner_3_value')); ?>"
                       type="hidden"
                       name="<?php echo esc_attr($this->get_field_name('image_banner_3_value')); ?>"
                       value="<?php echo esc_attr($image_3_value); ?>">
                <select
                        class="gf-category-select widefat"
                        id="<?php echo esc_attr($this->get_field_id('category_select_3')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('category_select_3')); ?>">
                    <?php foreach ($product_categories as $category) : ?>
                        <option
                            <?php if (isset($instance['category_select_3'])) {
                                if ($category->slug == $instance['category_select_3']) {
                                    echo 'selected';
                                }
                            } ?> value="<?= $category->slug ?>"><?= $category->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="gf-image-preview-wrapper"><img src="<?= esc_attr($image_3_value); ?>"></div>
            </div>
            <div class="gf-image-banner-block">
                <label for="<?php echo esc_attr($this->get_field_id('image_banner_4')); ?>">
                    <?php esc_attr_e('Banner 4 (veca slika)', 'gf_product_slider_without_tabs_widget_domain'); ?>
                </label>
                <input
                        class="gf-upload-banner-image-4 widefat"
                        id="upload-banner-image-4"
                        name="<?php echo esc_attr($this->get_field_name('image_banner_4')); ?>"
                        type="button"
                        value="Izaberite sliku">
                <input type="button" id="gf-remove-image-banner-4" name="remove-image" value="Obriši sliku">

                <input class="image_banner_4_value"
                       id=" <?php echo esc_attr($this->get_field_id('image_banner_4_value')); ?>"
                       type="hidden"
                       name="<?php echo esc_attr($this->get_field_name('image_banner_4_value')); ?>"
                       value="<?php echo esc_attr($image_4_value); ?>">
                <select
                        class="gf-category-select widefat"
                        id="<?php echo esc_attr($this->get_field_id('category_select_4')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('category_select_4')); ?>">
                    <?php foreach ($product_categories as $category) : ?>
                        <option
                            <?php if (isset($instance['category_select_4'])) {
                                if ($category->slug == $instance['category_select_4']) {
                                    echo 'selected';
                                }
                            } ?> value="<?= $category->slug ?>"><?= $category->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="gf-image-preview-wrapper"><img src="<?= esc_attr($image_4_value); ?>"></div>
            </div>
            <div class="gf-image-banner-block">
                <label for="<?php echo esc_attr($this->get_field_id('image_banner_5')); ?>">
                    <?php esc_attr_e('Banner 5 (manja slika)', 'gf_product_slider_without_tabs_widget_domain'); ?>
                </label>
                <input
                        class="gf-upload-banner-image-5 widefat"
                        id="upload-banner-image-5"
                        name="<?php echo esc_attr($this->get_field_name('image_banner_5')); ?>"
                        type="button"
                        value="Izaberite sliku">
                <input type="button" id="gf-remove-image-banner-5" name="remove-image" value="Obriši sliku">

                <input class="image_banner_5_value"
                       id=" <?php echo esc_attr($this->get_field_id('image_banner_5_value')); ?>"
                       type="hidden"
                       name="<?php echo esc_attr($this->get_field_name('image_banner_5_value')); ?>"
                       value="<?php echo esc_attr($image_5_value); ?>">
                <select
                        class="gf-category-select widefat"
                        id="<?php echo esc_attr($this->get_field_id('category_select_5')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('category_select_5')); ?>">
                    <?php foreach ($product_categories as $category) : ?>
                        <option
                            <?php if (isset($instance['category_select_5'])) {
                                if ($category->slug == $instance['category_select_5']) {
                                    echo 'selected';
                                }
                            } ?> value="<?= $category->slug ?>"><?= $category->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="gf-image-preview-wrapper"><img src="<?= esc_attr($image_5_value); ?>"></div>
            </div>
            <div class="gf-image-banner-block">
                <label for="<?php echo esc_attr($this->get_field_id('image_banner_6')); ?>">
                    <?php esc_attr_e('Banner 6 (manja slika)', 'gf_product_slider_without_tabs_widget_domain'); ?>
                </label>
                <input
                        class="gf-upload-banner-image-6 widefat"
                        id="upload-banner-image-6"
                        name="<?php echo esc_attr($this->get_field_name('image_banner_6')); ?>"
                        type="button"
                        value="Izaberite sliku">
                <input type="button" id="gf-remove-image-banner-6" name="remove-image" value="Obriši sliku">

                <input class="image_banner_6_value"
                       id=" <?php echo esc_attr($this->get_field_id('image_banner_6_value')); ?>"
                       type="hidden"
                       name="<?php echo esc_attr($this->get_field_name('image_banner_6_value')); ?>"
                       value="<?php echo esc_attr($image_6_value); ?>">
                <select
                        class="gf-category-select widefat"
                        id="<?php echo esc_attr($this->get_field_id('category_select_6')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('category_select_6')); ?>">
                    <?php foreach ($product_categories as $category) : ?>
                        <option
                            <?php if (isset($instance['category_select_6'])) {
                                if ($category->slug == $instance['category_select_6']) {
                                    echo 'selected';
                                }
                            } ?> value="<?= $category->slug ?>"><?= $category->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="gf-image-preview-wrapper"><img src="<?= esc_attr($image_6_value); ?>"></div>
            </div>
        </div>

        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['image_banner_1'] = (!empty($new_instance['image_banner_1'])) ? sanitize_text_field($new_instance['image_banner_1']) : '';
        $instance['image_banner_1_value'] = (!empty($new_instance['image_banner_1_value'])) ? sanitize_text_field($new_instance['image_banner_1_value']) : '';
        $instance['category_select_1'] = (!empty($new_instance['category_select_1'])) ? sanitize_text_field($new_instance['category_select_1']) : '';

        $instance['image_banner_2'] = (!empty($new_instance['image_banner_2'])) ? sanitize_text_field($new_instance['image_banner_2']) : '';
        $instance['image_banner_2_value'] = (!empty($new_instance['image_banner_2_value'])) ? sanitize_text_field($new_instance['image_banner_2_value']) : '';
        $instance['category_select_2'] = (!empty($new_instance['category_select_2'])) ? sanitize_text_field($new_instance['category_select_2']) : '';

        $instance['image_banner_3'] = (!empty($new_instance['image_banner_3'])) ? sanitize_text_field($new_instance['image_banner_3']) : '';
        $instance['image_banner_3_value'] = (!empty($new_instance['image_banner_3_value'])) ? sanitize_text_field($new_instance['image_banner_3_value']) : '';
        $instance['category_select_3'] = (!empty($new_instance['category_select_3'])) ? sanitize_text_field($new_instance['category_select_3']) : '';

        $instance['image_banner_4'] = (!empty($new_instance['image_banner_4'])) ? sanitize_text_field($new_instance['image_banner_4']) : '';
        $instance['image_banner_4_value'] = (!empty($new_instance['image_banner_4_value'])) ? sanitize_text_field($new_instance['image_banner_4_value']) : '';
        $instance['category_select_4'] = (!empty($new_instance['category_select_4'])) ? sanitize_text_field($new_instance['category_select_4']) : '';

        $instance['image_banner_5'] = (!empty($new_instance['image_banner_5'])) ? sanitize_text_field($new_instance['image_banner_5']) : '';
        $instance['image_banner_5_value'] = (!empty($new_instance['image_banner_5_value'])) ? sanitize_text_field($new_instance['image_banner_5_value']) : '';
        $instance['category_select_5'] = (!empty($new_instance['category_select_5'])) ? sanitize_text_field($new_instance['category_select_5']) : '';

        $instance['image_banner_6'] = (!empty($new_instance['image_banner_6'])) ? sanitize_text_field($new_instance['image_banner_6']) : '';
        $instance['image_banner_6_value'] = (!empty($new_instance['image_banner_6_value'])) ? sanitize_text_field($new_instance['image_banner_6_value']) : '';
        $instance['category_select_6'] = (!empty($new_instance['category_select_6'])) ? sanitize_text_field($new_instance['category_select_6']) : '';

        return $instance;
    }

}