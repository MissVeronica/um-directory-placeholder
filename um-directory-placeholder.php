<?php
/**
 * Plugin Name:     Ultimate Member - Directory Search Placeholder
 * Description:     Extension to Ultimate Member for replacing the Members Directory Search Placeholder with Custom Placeholder.
 * Version:         1.0.0
 * Requires PHP:    7.4
 * Author:          Miss Veronica
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Author URI:      https://github.com/MissVeronica
 * Plugin URI:      https://github.com/MissVeronica/um-directory-placeholder
 * Update URI:      https://github.com/MissVeronica/um-directory-placeholder
 * Text Domain:     ultimate-member
 * Domain Path:     /languages
 * UM version:      2.13.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; 
if ( ! class_exists( 'UM' ) ) return;

Class UM_Directory_Placeholder {

    function __construct() {

        add_action( 'um_members_directory_head', array( $this, 'um_members_directory_placeholder' ), 10, 3 );
        add_action( 'add_meta_boxes',            array( $this, 'add_metabox_directory_placeholder_form' ), 1 );
    }

    public function um_members_directory_placeholder( $args, $form_id, $not_searched ) {

        $custom_placeholder = sanitize_text_field( get_post_meta( $form_id, '_um_custom_placeholder', true ) );

        if ( ! empty( $custom_placeholder )) {
            $html = ob_get_contents();
            ob_end_clean();

            $html = str_replace( 'placeholder="Search"', 'placeholder="' . esc_attr( $custom_placeholder ) . '"', $html );

            ob_start();
            echo $html;
        }
    }

    public function add_metabox_directory_placeholder_form() {

        add_meta_box( 'um-search-placeholder-form', __( 'Custom Search Placeholder', 'ultimate-member' ), array( $this, 'load_metabox_directory' ), 'um_directory', 'normal', 'default' );
    }

    function load_metabox_directory( $object, $box ) {

        global $post_id;
?>
        <div class="um-admin-metabox">
<?php
            UM()->admin_forms(
                array(
                    'class'     => 'um-member-directory-placeholder-form um-half-column',
                    'prefix_id' => 'um_metadata',
                    'fields'    => array(
                        array(
                            'id'                  => '_um_custom_placeholder',
                            'type'                => 'text',
                            'label'               => esc_html__( 'Custom Search Placeholder', 'ultimate-member' ),
                            'tooltip'             => esc_html__( 'Enter your Custom Search Placeholder for this Directory.', 'ultimate-member' ),
                            'value'               => get_post_meta( $post_id, '_um_custom_placeholder', true ),
                            'add_text'            => esc_html__( 'Add placeholder', 'ultimate-member' ),
                            'conditional'         => array( '_um_search', '=', 1 ),
                        ),
                    ),
                )
            )->render_form();
?>
            <div class="clear"></div>
        </div>
<?php

    }

}

new UM_Directory_Placeholder();
