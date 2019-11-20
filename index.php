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



function bookcomplaints_db_create(){
    global $wpdb;

    $table_document = $wpdb->prefix.'documento';
    $table_area = $wpdb->prefix.'area';
    $table_claims = $wpdb->prefix.'reclamo';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_document(
          id_tipo INT NOT NULL AUTO_INCREMENT,
          nombre VARCHAR(45) NULL,
          UNIQUE KEY (id_tipo));
    ";

    $sql.= "CREATE TABLE $table_area(
        id_area INT NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(45) NULL,
        UNIQUE KEY (id_area));
    ";

    $sql.= "
        CREATE TABLE $table_claims(
          id_reclamo INT NOT NULL AUTO_INCREMENT,
          nombres VARCHAR(255) NULL DEFAULT '',
          apaterno VARCHAR(255) NULL DEFAULT '',
          amaterno VARCHAR(255) NULL DEFAULT '',
          tipo_documento INT NOT NULL,
          num_documento VARCHAR(45) NULL DEFAULT '',
          email VARCHAR(255) NULL DEFAULT '',
          telefono VARCHAR(45) NULL DEFAULT '',
          celular VARCHAR(45) NULL DEFAULT '',
          departamento INT NULL,
          provincia INT NULL,
          distrito INT NULL,
          direccion TEXT NULL DEFAULT '',
          area INT NOT NULL,
          asunto TINYTEXT NULL DEFAULT '',
          descripcion TEXT NULL DEFAULT '',
          medio_respuesta INT NOT NULL,
          estado INT NOT NULL,
          PRIMARY KEY (id_reclamo));
    ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta($sql);
}

function bookcomplaints_db_remove(){
    global $wpdb;
    $table_claims = $wpdb->prefix.'reclamo';
    $table_document = $wpdb->prefix.'tipo_documento';
    $table_area = $wpdb->prefix.'area';
    $sql = "
        DROP TABLE IF EXISTS $table_claims;
        DROP TABLE IF EXISTS $table_document;
        DROP TABLE IF EXISTS $table_area;
    ";
    dbDelta($sql);
}

register_activation_hook( __FILE__, 'bookcomplaints_db_create' );
//register_deactivation_hook(__FILE__,'bookcomplaints_db_remove');
