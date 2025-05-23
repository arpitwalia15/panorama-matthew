<?php



    /* Widgets */



	// TODO: Update widget creation method



    class psppan_project_list_widget extends WP_Widget {



        function __construct() {



            //Constructor

            $widget_ops = array(

                'classname' => 'psppan_project_list_widget',

                'description' => 'List of Panorama Projects'

                );

            parent::__construct(

                'psppan_project_list_widget',

                'Panorama Project List',

                $widget_ops

                );

        }



        function widget($args, $instance) {



            //outputs the widget

            extract($args, EXTR_SKIP);



            $pan_project_type = apply_filters('pan_project_type', $instance['project_type']);

            $pan_project_status = apply_filters('pan_project_status', $instance['project_status']);

            $pan_project_access = apply_filters('pan_project_access', $instance['project_access']);



            $widget_shortcode = '[project_list type="'.$pan_project_type.'" status="'.$pan_project_status.'" access="'.$pan_project_access.'"]';



            echo do_shortcode($widget_shortcode);



        }



        function update ($new_instance, $old_instance) {



            $instance = $old_instance;



            // Save the new fields



            $instance['project_type'] = wp_strip_all_tags($new_instance['project_type']);

            $instance['project_status'] = wp_strip_all_tags($new_instance['project_status']);

            $instance['project_access'] = wp_strip_all_tags($new_instance['project_access']);



            return $instance;



        }



        function form($instance) {



            $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'entry_title' => '', 'comments_title' => '' ) );



            $pan_project_type = wp_strip_all_tags($instance['project_type']);

            $pan_project_status = wp_strip_all_tags($instance['project_status']);

            $pan_project_access = wp_strip_all_tags($instance['project_access']);



            // Set defaults

            if(empty($pan_project_type)) { $pan_project_type = 'all'; }

            if(empty($pan_project_status)) { $pan_project_status = 'all'; }

            if(empty($pan_project_access)) { $pan_project_access = 'user'; }



            $pan_project_types = get_terms('psp_tax');



            ?>



            <p>

                <label for="<?php echo esc_attr($this->get_field_id('pan_project_type')); ?>">Type</label>

                <select id="<?php echo esc_attr($this->get_field_id('pan_project_type')); ?>" name="<?php echo esc_attr($this->get_field_name('pan_project_type')); ?>">

                        <option value="all">All</option>

                    <?php foreach($pan_project_types as $type) { ?>

                        <option value="<?php echo esc_attr($type->slug); ?>" <?php if($pan_project_type == $type->slug) { echo 'selected'; } ?>><?php echo esc_html($type->name); ?></option>

                    <?php } ?>

                </select>

            </p>



            <p><label for="<?php echo esc_attr($this->get_field_id('pan_project_status')); ?>">Status</label>

                <select id="<?php echo esc_attr($this->get_field_id('pan_project_status')); ?>" name="<?php echo esc_attr($this->get_field_name('pan_project_status')); ?>">

                    <option value="all" <?php if($pan_project_status == 'all') { echo 'selected'; } ?>>All</option>

                    <option value="active" <?php if($pan_project_status == 'active') { echo 'selected'; } ?>>Active</option>

                    <option value="complete" <?php if($pan_project_status == 'complete') { echo 'selected'; } ?>>Complete</option>

                </select>

            </p>



            <p><input type="checkbox" name="<?php echo esc_attr($this->get_field_name('pan_project_access')); ?>" id="<?php echo esc_attr($this->get_field_id('pan_project_access')); ?>" value="user" <?php if($pan_project_access == 'user') { echo 'checked'; } else { echo 'unchecked'; } ?>> <label for="<?php echo esc_attr($this->get_field_id('pan_project_access')); ?>">Only display projects current user has permission to access</label></p>





        <?php

        }



}



add_action('widgets_init','projpan_register_widgets');

function projpan_register_widgets() {

    register_widget('psppan_project_list_widget');

}

