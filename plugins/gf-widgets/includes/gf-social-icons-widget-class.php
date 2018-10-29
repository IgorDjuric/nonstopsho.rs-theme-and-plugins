<?php
class gf_social_icons_widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'gf_social_icons_widget', // Base ID
            esc_html__('GF Social Icons Widget', 'gf_widgets_domain'), // Name
            array('description' => esc_html__('Social Icons Widget', 'gf_widgets_domain'),) // Args
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
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

//        require(realpath(__DIR__ . '/../template-parts/gf-social-icons.php'));
        require(realpath(__DIR__ . '/../template-parts/gf-social-icons-only-icons.php'));


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
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'gf_social_icons_widget_domain');
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : esc_html__('Instagram link', 'gf_social_icons_widget_domain');
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : esc_html__('Facebook link', 'gf_social_icons_widget_domain');
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : esc_html__('Twitter link', 'gf_social_icons_widget_domain');
        $google_plus = !empty($instance['google_plus']) ? $instance['google_plus'] : esc_html__('Google Plus link', 'gf_social_icons_widget_domain');
        $linked_in = !empty($instance['linked_in']) ? $instance['linked_in'] : esc_html__('Linked In link', 'gf_social_icons_widget_domain');

        ?>

        <!--         TITLE -->
        <div class="input-wrapper">
            <div class="social-icons-widget-admin-input">
                <label class="" for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                    <?php esc_attr_e('Title:', 'gf_social_icons_widget_domain'); ?>
                </label>

                <input
                        class="widefat"
                        id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                        type="text"
                        value="<?php echo esc_attr($title); ?>">
            </div>


            <!--        Social network links form-->
            <!--            Instagram-->
            <div class="social-icons-widget-admin-input">
                <label class="" for="<?php echo esc_attr($this->get_field_id('instagram')); ?>">
                    <?php esc_attr_e('Instagram Profile:', 'gf_social_icons_widget_domain'); ?>
                </label>

                <input
                        class="widefat"
                        id="<?php echo esc_attr($this->get_field_id('instagram')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('instagram')); ?>"
                        type="text"
                        value="<?php echo esc_attr($instagram); ?>">
            </div>
            <!--            Facebook-->
            <div class="social-icons-widget-admin-input">
                <label class="padding-20" for="<?php echo esc_attr($this->get_field_id('facebook')); ?>">
                    <?php esc_attr_e('Facebook Profile:', 'gf_social_icons_widget_domain'); ?>
                </label>

                <input
                        class="widefat"
                        id="<?php echo esc_attr($this->get_field_id('facebook')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('facebook')); ?>"
                        type="text"
                        value="<?php echo esc_attr($facebook); ?>">
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
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['instagram'] = (!empty($new_instance['instagram'])) ? sanitize_text_field($new_instance['instagram']) : '';
        $instance['facebook'] = (!empty($new_instance['facebook'])) ? sanitize_text_field($new_instance['facebook']) : '';
        $instance['twitter'] = (!empty($new_instance['twitter'])) ? sanitize_text_field($new_instance['twitter']) : '';
        $instance['google_plus'] = (!empty($new_instance['google_plus'])) ? sanitize_text_field($new_instance['google_plus']) : '';
        $instance['linked_in'] = (!empty($new_instance['linked_in'])) ? sanitize_text_field($new_instance['linked_in']) : '';

        return $instance;
    }

}