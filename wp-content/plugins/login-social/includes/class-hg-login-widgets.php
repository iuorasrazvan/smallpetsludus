<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Adds HG_Login_Widget widget.
 */
class HG_Login_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'hg_login_widget', // Base ID
            __( 'Login WP', 'hg_login' ), // Name
            array( 'description' => __( 'WordPress Login Widget', 'hg_login' ), ) // Args
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
        $text = ! empty( $instance['text'] ) ? $instance['text'] : HG_Login()->settings->login_button_text;
        $show_menu = ! empty( $instance['show_menu'] ) ? $instance['show_menu'] : 'no';

        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $shortcode = do_shortcode('[hg-login-button text="'.strip_tags($text).'" show_menu="'.strip_tags($show_menu).'"]');

        echo '<div class="hg_login_widget_wrapper">'.$shortcode.'</div>';
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return string|void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Login', 'hg_login' );
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        $show_menu = ! empty( $instance['show_menu'] ) ? $instance['show_menu'] : 'no';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label>
            <input class="widefat" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_menu' ) ); ?>"><?php _e( esc_attr( 'Show Menu:' ) ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_menu' ) ); ?>" >
                <option value="yes" <?php selected( $show_menu, 'yes' ); ?>><?php _e('yes','hg_login'); ?></option>
                <option value="no" <?php selected( $show_menu, 'no' ); ?>><?php _e('no','hg_login'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( esc_attr( 'Text:' ) ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" value="<?php echo esc_attr( $text ); ?>" />
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
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
        $instance['show_menu'] = ( ! empty( $new_instance['show_menu'] ) ) ? strip_tags( $new_instance['show_menu'] ) : '';

        return $instance;
    }

}

/**
 * Adds HG_Login_Signup_Widget widget.
 */
class HG_Login_Signup_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'hg_login_signup_widget', // Base ID
            __( 'Sign Up WP', 'hg_login' ), // Name
            array( 'description' => __( 'WordPress Sign Up Widget', 'hg_login' ), ) // Args
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
        $text = ! empty( $instance['text'] ) ? $instance['text'] : HG_Login()->settings->signup_button_text;
        $show_menu = ! empty( $instance['show_menu'] ) ? $instance['show_menu'] : 'no';

        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $shortcode = do_shortcode('[hg-signup-button text="'.strip_tags($text).'" show_menu="'.strip_tags($show_menu).'"]');

        echo '<div class="hg_signup_widget_wrapper">'.$shortcode.'</div>';
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return string|void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Sign Up', 'hg_login' );
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        $show_menu = ! empty( $instance['show_menu'] ) ? $instance['show_menu'] : 'no';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_menu' ) ); ?>"><?php _e( esc_attr( 'Show Menu:' ) ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_menu' ) ); ?>" >
                <option value="yes" <?php selected( $show_menu, 'yes' ); ?>><?php _e('yes','hg_login'); ?></option>
                <option value="no" <?php selected( $show_menu, 'no' ); ?>><?php _e('no','hg_login'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( esc_attr( 'Text:' ) ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" value="<?php echo esc_attr( $text ); ?>" />
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
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
        $instance['show_menu'] = ( ! empty( $new_instance['show_menu'] ) ) ? strip_tags( $new_instance['show_menu'] ) : '';

        return $instance;
    }

}