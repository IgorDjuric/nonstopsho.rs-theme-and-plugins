<?php
class ImageBannersWidget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'ImageBannersWidget', // Base ID
            esc_html__('GF Image Banners Widget', 'gf_widgets_domain'), // Name
            array('description' => esc_html__('Image Banners', 'gf-image-banners'),) // Args
        );

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
        echo do_shortcode('[gfImageBanners]');
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
        $menuLink = get_bloginfo('url') . '/wp-admin/admin.php?page=image_benners_options'; ?>
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