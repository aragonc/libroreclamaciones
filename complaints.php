<?php
/*
Plugin Name: Libro de Reclamaciones - INDECOPI
Plugin URI: http://www.tunqui.pe
Description: Genera un formulario y almacena reclamos en la base de datos en orden correlativo, según la normativa de Indecopi
Version: 1.0.0
Author: Alex Aragón Calixto
Author URI: http://www.tunqui.pe
License: GPL2

  	Copyright 2019 Tunqui Agencia Creativa  (email : alex.aragon@tunqui.pe)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


include __DIR__.'/includes/complaints-init.php';
include __DIR__.'/includes/complaints-admin.php';
include __DIR__.'/includes/complaints-front.php';

register_activation_hook( __FILE__, 'bookcomplaints_table_create' );
register_activation_hook( __FILE__, 'bookcomplaints_add_tables' );

register_deactivation_hook(__FILE__,'bookcomplaints_table_remove');


add_action( 'admin_menu', 'bookcomplaints_panel_menu' );

/**
 * Add "Custom" template to page attirbute template section.
 */
function bookcomplaints_add_template_to_select( $post_templates, $wp_theme, $post, $post_type ) {

    // Add custom template named template-custom.php to select dropdown
    $post_templates['template-custom.php'] = __('Libro reclamaciones');

    return $post_templates;
}

add_filter( 'theme_page_templates', 'bookcomplaints_add_template_to_select', 10, 4 );


/**
 * Check if current page has our custom template. Try to load
 * template from theme directory and if not exist load it
 * from root plugin directory.
 */
function bookcomplaints_load_plugin_template( $template ) {

    if(  get_page_template_slug() === 'template-custom.php' ) {

        if ( $theme_file = locate_template( array( 'template-custom.php' ) ) ) {
            $template = $theme_file;
        } else {
            $template = plugin_dir_path( __FILE__ ) . 'templates/page-complaints.php';
        }
    }

    if($template == '') {
        throw new \Exception('No template found');
    }

    return $template;
}

add_filter( 'template_include', 'bookcomplaints_load_plugin_template' );