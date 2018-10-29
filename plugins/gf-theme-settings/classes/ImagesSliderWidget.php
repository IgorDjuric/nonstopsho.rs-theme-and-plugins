<?php

class ImagesSliderWidget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'ImagesSliderWidget', // Base ID
            esc_html__('GF Image Slider Widget', 'gf_widgets_domain'), // Name
            array('description' => esc_html__('Image Slider', 'gf-image-slider'),) // Args
        );
//        add_action('init',array('ImageSliderWidget','update'));
        add_action('init', array($this, 'update'));
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
        echo do_shortcode('[gfImageSlider]');
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
        $menuLink = get_bloginfo('url') . '/wp-admin/admin.php?page=image_slider_options'; ?>
        <p>Da bi ste pristupili podešavanjima ovog widgeta kiknite
            <a href="<?= $menuLink ?>" target="_blank">ovde</a>
        </p>
        <?php
        $this->update('','');
    }
    public function update($new_instance = '', $old_instance = '')
    {
        $instance = array();
        return $instance;
    }
}