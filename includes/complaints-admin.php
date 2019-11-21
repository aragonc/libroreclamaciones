<?php

function bookcomplaints_get_complaints(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_reclamo';
    $results = $wpdb->get_results("SELECT * FROM " . $table_name . " ORDER BY id_reclamo DESC");

    return $results;
}

function bookcomplaints_panel_menu(){
    add_menu_page(
        __('Libro de reclamaciones'),
        __('Reclamos'),
        'activate_plugins',
        'complaints_options',
        'bookcomplaints_panel_content',
        'dashicons-media-spreadsheet'
    );

    add_submenu_page(
        'complaints_options',
        __('Áreas de reclamos'),
        __('Áreas'),
        'activate_plugins',
        'areas_complaints',
        'bookcomplaints_panel_area'
    );

    // Add menu link to the admin bar
    function bookcomplaints_add_admin_menu() {
        global $wp_admin_bar;
        $wp_admin_bar->add_menu( array(
            'parent'	=>	false,
            'id'		=>	'80',
            'title'		=>	__( 'Reclamaciones' ),
            'meta'		=>	false
        ));
    }
    add_action( 'wp_before_admin_bar_render', 'bookcomplaints_add_admin_menu' );

}

function bookcomplaints_panel_area(){
    ?>

    <div class="wrap">
        <h2><?php _e('Agregar áreas'); ?></h2>
    </div>
    <?php
}

function bookcomplaints_panel_content(){
    ?>

    <div class="wrap">
        <h2><?php _e('Libro de Reclamaciones'); ?></h2>
    <div id="pw_warp">
        <div class="panel-content">
            <div id="message-append">
                <div id="top-message" class="updated saved">
                    <p><?php printf( __('Copie y pegue el siguiente %1s en cualquier página donde quiera que aparezca el formulario de reclamaciones: %2s'), '<strong>shortcode</strong>', '<code>[formulario-reclamaciones]</code>' ); ?></p>
                </div>
            </div>

            <h3 class="title"><?php _e('Reclamos guardados'); ?></h3>
            <p><?php _e('A continuación de mostrará una lista de reclamos, desde el más reciente a los más antiguos.'); ?></p>
        </div>
        <div class="panel-content">

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Usuario'); ?></th>
                        <th><?php _e('Domicilio'); ?></th>
                        <th><?php _e('Contacto'); ?></th>
                        <th><?php _e('Reclamo'); ?></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
    </div>
<?php }