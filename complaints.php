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

register_activation_hook( __FILE__, 'bookcomplaints_table_create' );
register_activation_hook( __FILE__, 'bookcomplaints_add_tables' );

register_deactivation_hook(__FILE__,'bookcomplaints_table_remove');


add_action( 'admin_menu', 'bookcomplaints_panel_menu' );

