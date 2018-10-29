<?php
class gf_contact_phone_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'gf_contact_phone_widget', // Base ID
            esc_html__( 'GF Custom Contact Phone Widget', 'gf_widgets_domain' ), // Name
            array( 'description' => esc_html__( 'Custom Contact Phone Widget', 'gf_widgets_domain' ), ) // Args
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

        require(realpath(__DIR__ . '/../template-parts/gf-contact-phone.php'));

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
        $contact_phone = ! empty( $instance['contact_phone'] ) ? $instance['contact_phone'] : esc_html__( 'Type phone number', 'gf_custom_contact_phone_domain' );
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'contact_phone' ) ); ?>">
                <?php esc_attr_e( 'Contact Phone:', 'gf_custom_contact_phone_domain' ); ?>
            </label>

            <input
                class="widefat"
                id="<?php echo esc_attr( $this->get_field_id( 'contact_phone' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'contact_phone' ) ); ?>"
                type="text"
                value="<?php echo esc_attr( $contact_phone ); ?>">
        </p>
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
        $instance['contact_phone'] = ( ! empty( $new_instance['contact_phone'] ) ) ? sanitize_text_field( $new_instance['contact_phone'] ) : '';

        return $instance;
    }

}