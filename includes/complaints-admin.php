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
        'export_complaints',
        'bookcomplaints_panel_area'
    );

    add_submenu_page(
        'complaints_options',
        __('Exportar registros de reclamos'),
        __('Exportar registros'),
        'activate_plugins',
        'areas_complaints',
        'bookcomplaints_panel_export'
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

function getAreaName($id){
    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_area';
    $name = $wpdb->get_results("SELECT nombre FROM " . $table_name . " WHERE id_area = $id");

    return $name[0];
}

function getAreaNameCode($code){
    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_area';
    $name = $wpdb->get_results("SELECT nombre FROM " . $table_name . " WHERE code_area = '".$code."'");
    return $name[0];
}

function bookcomplaints_panel_area(){
    global $reg_errors;
    $reg_errors = new WP_Error;
    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_area';
    $name = null;
    $siteURL = get_site_url().'/wp-admin/admin.php?page=areas_complaints';

    if (isset($_GET['action']) && isset($_GET['idarea'])) {
        $action = $_GET['action'];
        $idArea = $_GET['idarea'];
        if ($action){
            $wpdb->delete($table_name, [ 'id_area' => $idArea ]);
            echo "<script>location.replace('".$siteURL."');</script>";
        }
    }

    if (isset($_POST['update'])) {
        if (empty($_POST['area'])) {
            $reg_errors->add('area_empty', 'El campo esta vacio');
        } else {
            $nameArea = $_POST['area'];
            $idArea = $_POST['code_area'];
            $wpdb->update(
                $table_name,
                array(
                    'nombre' => $nameArea,
                ),
                array(
                    'id_area' => $idArea,
                )
            );
        }

    }

    if ( isset( $_POST['save'] ) ) {
        if(empty($_POST['area']) && empty($_POST['code_short'])){
            $reg_errors->add('area_empty','El campo esta vacio');
        } else {
            $area = $_POST['area'];
            $codeArea = strtoupper($_POST['code_short']);
            $wpdb->insert($table_name,array(
                'code_area' => $codeArea,
                'nombre' => $area
            ));
        }

        if ( is_wp_error( $reg_errors ) ) {
            foreach ( $reg_errors->get_error_messages() as $error ) {
                echo '<div>';
                echo '<strong>ERROR</strong>:';
                echo $error . '<br/>';
                echo '</div>';
            }
        }
    }

    ?>

    <div class="wrap">
        <h2><?php _e('Agregar áreas'); ?></h2>
        <p>Introduce las áreas de tu empresa, que prestan servicios, donde se podria hacer un reclamo</p>
        <form method="post" id="add-area" class="validate" >
            <?php
                settings_fields( '' );
            ?>
            <table class="form-table">
                <tbody>
                    <tr class="form-field form-required">
                        <th>
                            <label for="namearea">Nombre del área</label>
                        </th>
                        <th>
                            <input name="area" type="text" id="namearea"  value="">
                        </th>
                    </tr>
                    <tr class="form-field form-required">
                        <th>
                            <label for="code_short">Código corto</label>
                        </th>
                        <th>
                            <input name="code_short" type="text" id="code_short"  value="">
                        </th>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" id="code_area" name="code_area" value="">
            <?php

            submit_button('Crear área', 'primary', 'save', false);
            submit_button('Actualizar área', 'large', 'update', false, ['disabled' => '']);

            ?>
        </form>
    </div>

    <div class="wrap">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('ID'); ?></th>
                    <th><?php _e('Código corto'); ?></th>
                    <th><?php _e('Nombre del área'); ?></th>
                    <th><?php _e('Acciones'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'bc_area';
                    $list = $wpdb->get_results("SELECT * FROM " . $table_name . " ORDER BY id_area ASC");
                    $urlCurrent = esc_url( $_SERVER['REQUEST_URI'] );
                    foreach ( $list as $item ) :
				?>
                    <tr>
                        <td>
                            <?php echo $item->id_area; ?>
                        </td>
                        <td>
                            <span id="code_short_<?php echo $item->id_area; ?>" data-name="<?php echo $item->code_area; ?>"><?php echo $item->code_area; ?></span>
                        </td>
                        <td>
                            <span id="area_name_<?php echo $item->id_area; ?>" data-name="<?php echo $item->nombre; ?>"><?php echo $item->nombre; ?></span>
                        </td>
                        <td>
                            <a href="#" class="modify" data-id="<?php echo $item->id_area; ?>" href="#">Modificar</a> |
                            <a onclick="javascript: if (!confirm('Por favor, confirme su elección')) return false;" href="<?php echo $urlCurrent; ?>&action=delete&idarea=<?php echo $item->id_area; ?>">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        (function($) {
            $('.modify').click(function(){
                $('#update').prop('disabled', false);
                $('#save').prop('disabled', true);
                var fieldID = $(this).attr('data-id');
                var fieldName = $('#area_name_' + fieldID).attr('data-name');
                var fieldCode = $('#code_short_' + fieldID).attr('data-name');
                //console.log(fieldName);
                $('#code_area').val(fieldID);
                $('#namearea').val(fieldName);
                //$('#namearea').val(fieldName);
                $('#code_short').val(fieldCode);
                $('#code_short').prop('disabled', true);
            });
        })( jQuery );
    </script>

    <?php
}

function bookcomplaints_panel_export(){
    ?>

    <div class="wrap">
        <h2><?php _e('Exportar registros de reclamos'); ?></h2>
        <p>Introduce la fecha de inicio y fin para exportar los registros en formato Excel</p>
        <form method="post" id="add-complaints" class="validate" >
            <?php
            settings_fields( '' );
            ?>
            <table class="form-table" style="width: 400px;">
                <tbody>
                <tr class="form-field form-required">
                    <th>
                        <label for="date_start">Fecha inicial</label>
                    </th>
                    <th>
                        <input name="date_start" type="date" id="date_start"  value="">
                    </th>
                </tr>
                <tr class="form-field form-required">
                    <th>
                        <label for="date_end">Fecha final</label>
                    </th>
                    <th>
                        <input name="date_end" type="date" id="date_end"  value="">
                    </th>
                </tr>
                </tbody>
            </table>

            <?php

            submit_button('Exportar reclamos', 'primary', 'export', false);

            ?>
        </form>
    </div>

    <?php
}

function bookcomplaints_panel_content(){
    wp_enqueue_script( 'jquery-ui-dialog' ); // jquery and jquery-ui should be dependencies, didn't check though...
    wp_enqueue_style( 'wp-jquery-ui-dialog' );

    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_reclamo';
    $siteURL = get_site_url().'/wp-admin/admin.php?page=complaints_options';
    $urlPlugin = plugin_dir_url( __DIR__ );

    wp_enqueue_script('complaints-js', $urlPlugin.'js/complaints.js');

    $list = $wpdb->get_results("SELECT * FROM " . $table_name . " ORDER BY id_reclamo DESC");
    $urlCurrent = esc_url( $_SERVER['REQUEST_URI'] );

    //Eliminar registro

    if (isset($_GET['action']) && isset($_GET['id_complaints'])) {
        $action = $_GET['action'];
        $idComplaints = $_GET['id_complaints'];
        $result = null;
        switch ($action){
            case 'delete':
                $result = $wpdb->delete($table_name, [ 'id_reclamo' => $idComplaints ]);
                break;
            case 'change_status_check':
                $result = $wpdb->update($table_name,
                    ['estado' => 2],
                    ['id_reclamo' => $idComplaints]
                );
            case 'change_status_deny':
                $result = $wpdb->update($table_name,
                    ['estado' => 1],
                    ['id_reclamo' => $idComplaints]
                );
                break;
        }

        if($result) {
            echo "<script>location.replace('".$siteURL."');</script>";
        }
    }

    ?>

    <div class="wrap">
        <h2><?php _e('Libro de Reclamaciones'); ?></h2>
    <div id="pw_warp">
        <div class="panel-content">
            <h3 class="title"><?php _e('Reclamos guardados'); ?></h3>
            <p><?php _e('A continuación de mostrará una lista de reclamos, desde el más reciente a los más antiguos.'); ?></p>
        </div>
        <div class="panel-content">

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 7%"><?php _e('Codigo'); ?></th>
                        <th style="width: 10%;"><?php _e('Nombres y Apellidos'); ?></th>
                        <th><?php _e('Documento Identidad'); ?></th>
                        <th style="width: 18%;"><?php _e('Domicilio'); ?></th>
                        <th><?php _e('Email'); ?></th>
                        <th><?php _e('Celular/Wathsapp'); ?></th>
                        <th><?php _e('Área/Code'); ?></th>
                        <th><?php _e('Asunto'); ?></th>
                        <th><?php _e('Acciones'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $imageCheck = '<img width="22px" src="'.$urlPlugin.'images/check.svg'.'"/>';
                    $imageForbidden = '<img width="22px" src="'.$urlPlugin.'images/forbidden.svg'.'"/>';
                    $link = null;
                    $dialog = null;
                    foreach ( $list as $item ) :
                        if($item->estado == '1'){
                            $link = '<a title="Cambiar estado de atención" href="'.$urlCurrent.'&action=change_status_check&id_complaints='.$item->id_reclamo.'">'.$imageForbidden.'</a>';
                        } else {
                            $link = '<a title="Cambiar estado de atención" href="'.$urlCurrent.'&action=change_status_deny&id_complaints='.$item->id_reclamo.'">'.$imageCheck.'</a>';
                        }
                        $title = getTypeofRequest($item->asunto);
                        $dialog = '<div class="dialog" id="dialog_'.$item->id_reclamo.'" title="'.$title.'">'.$item->descripcion.'</div>';
                    ?>
                    <tr>
                        <td>
                            <?php echo $item->id_reclamo; ?>
                        </td>
                        <td>
                            <?php echo $item->nombres; ?>
                        </td>
                        <td>
                            <?php echo  $item->tipo_documento; ?>
                            <?php echo  $item->num_documento; ?>
                        </td>
                        <td>
                            <?php
                                echo $item->direccion.'<br>';
                                echo '<strong>'.getUbigeo($item->distrito).'</strong>';
                            ?>
                        </td>
                        <td>
                            <?php echo $item->email; ?>
                        </td>
                        <td>
                            <?php echo $item->telefono; ?>
                        </td>
                        <td>
                            <?php echo $item->area; ?>
                        </td>
                        <td>
                            <?php echo $title . $dialog ;  ?>
                        </td>
                        <td>
                            <a class="open_view" data-id="<?php echo $item->id_reclamo; ?>" title="Ver contenido de mensaje" href="#"><img width="24px" src="<?php echo $urlPlugin.'images/information.svg'; ?>" /></a>
                            <?php echo $link; ?>
                            <a title="Eliminar registro" onclick="javascript: if (!confirm('Por favor, confirme su elección')) return false;"
                               href="<?php echo $urlCurrent; ?>&action=delete&id_complaints=<?php echo $item->id_reclamo; ?>">
                                <img width="22px" src="<?php echo $urlPlugin.'images/trash.svg'; ?>">
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
<?php }