<?php

function bookcomplaints_table_create(){
    global $wpdb;
    $table_document = $wpdb->prefix.'bc_documento';
    $table_area = $wpdb->prefix.'bc_area';
    $table_claims = $wpdb->prefix.'bc_reclamo';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_document(
          id_tipo INT NOT NULL AUTO_INCREMENT,
          nombre VARCHAR(45) NULL,
          UNIQUE KEY (id_tipo)) $charset_collate;
    ";

    $sql.= "CREATE TABLE $table_area(
        id_area INT NOT NULL AUTO_INCREMENT,
        code_area VARCHAR (6) NULL ,
        nombre VARCHAR(45) NULL,
        UNIQUE KEY (id_area)) $charset_collate;
    ";

    $sql.= "
        CREATE TABLE $table_claims(
          id_reclamo INT NOT NULL AUTO_INCREMENT,
          nombres VARCHAR(255) NULL DEFAULT '',
          tipo_documento VARCHAR(3) NOT NULL ,
          num_documento VARCHAR(45) NULL DEFAULT '',
          email VARCHAR(255) NULL DEFAULT '',
          telefono VARCHAR(45) NULL DEFAULT '',
          departamento VARCHAR(2) NULL,
          provincia VARCHAR(4)NULL,
          distrito VARCHAR (6) NULL,
          direccion TEXT NULL DEFAULT '',
          area VARCHAR (6) NOT NULL,
          asunto TINYTEXT NULL DEFAULT '',
          descripcion TEXT NULL DEFAULT '',
          medio_respuesta INT NOT NULL,
          fecha_incidencia DATE NOT NULL DEFAULT '0000-00-00',
          estado INT NOT NULL,
          terminos INT NOT NULL,
          fecha_registro datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          PRIMARY KEY (id_reclamo)) $charset_collate;
    ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta($sql);
}

function bookcomplaints_table_remove(){
    global $wpdb;
    $table_document = $wpdb->prefix.'bc_documento';
    $table_area = $wpdb->prefix.'bc_area';
    $table_claims = $wpdb->prefix.'bc_reclamo';
    $sql = "DROP TABLE IF EXISTS $table_document";
    $wpdb->query($sql);
    $sql = "DROP TABLE IF EXISTS $table_area";
    $wpdb->query($sql);
    $sql = "DROP TABLE IF EXISTS $table_claims";
    $wpdb->query($sql);
}

function bookcomplaints_add_tables(){
    global $wpdb;
    /*date_default_timezone_set('America/Bogota');
    $current_date = date('d/m/Y', time());*/

    $table_document = $wpdb->prefix.'bc_documento';

    $dataDocument = array(
        [ 'id_tipo' => '1', 'nombre' => 'DNI' ],
        [ 'id_tipo' => '2', 'nombre' => 'RUC' ],
        [ 'id_tipo' => '3', 'nombre' => 'Carnet de Extranjeria' ],
        [ 'id_tipo' => '4', 'nombre' => 'Pasaporte' ]
    );

    foreach ($dataDocument as $data){
        $wpdb->insert($table_document,$data);
    }

}