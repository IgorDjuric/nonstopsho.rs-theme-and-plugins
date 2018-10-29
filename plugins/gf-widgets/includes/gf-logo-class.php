<?php
class gf_custom_logo_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'gf_custom_logo_widget', // Base ID
            esc_html__( 'GF Custom Logo Widget', 'gf_widgets_domain' ), // Name
            array( 'description' => esc_html__( 'Custom Logo Widget', 'gf_widgets_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        require(realpath(__DIR__ . '/../template-parts/gf-logo.php'));

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $logo_image = ! empty( $instance['logo_image'] ) ? $instance['logo_image'] : esc_html__( 'Upload image', 'gf_custom_logo_domain' );
        $logo_image_value = !empty($instance['logo_image_value']) ? $instance['logo_image_value'] : esc_html__('', 'gf_custom_logo_domain');

        ?>

        <div>
            <input
                class="gf-upload-image-logo"
                id="upload-image"
                name="<?php echo esc_attr($this->get_field_name('logo_image')); ?>"
                type="button"
                value="Select Image">
            <input class="logo-image-value"
                   id=" <?php echo esc_attr($this->get_field_id('logo_image_value')); ?>"
                   type="hidden"
                   name="<?php echo esc_attr($this->get_field_name('logo_image_value')); ?>"
                   value="<?php echo esc_attr($logo_image_value); ?>">
            <div class="gf-image-preview-wrapper"><img src="<?= esc_attr($logo_image_value); ?>"></div>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['logo_image'] = (!empty($new_instance['logo_image'])) ? sanitize_text_field($new_instance['logo_image']) : '';
        $instance['logo_image_value'] = (!empty($new_instance['logo_image_value'])) ? sanitize_text_field($new_instance['logo_image_value']) : '';

        return $instance;
    }

}