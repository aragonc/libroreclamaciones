<?php
/** Sets up WordPress vars and included files. */

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

/*require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' ); require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
if (!$wpdb) {
    $wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
} else {
    global $wpdb;
}*/

if (isset($_POST['export'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_reclamo';
    $results = array();

    if (!empty($_POST['date_start']) && !empty($_POST['date_end'])) {

        $dateStart = date_create($_POST['date_start']);
        $dateStart = date_format($dateStart, 'Y-m-d H:i:s');

        $dateEnd = date_create($_POST['date_end']);
        $dateEnd = date_format($dateEnd, 'Y-m-d H:i:s');

        $results = $wpdb->get_results("SELECT * FROM " . $table_name . "  WHERE fecha_registro BETWEEN  '".$dateStart."' AND '".$dateEnd."' ORDER BY id_reclamo DESC", 'ARRAY_A');
    }

    exportData($results, 'xls', 'export_complaints');

}

function exportData($dataExport = array(), $export_check = 'xls' , $filename = "export_demo"){

    if($export_check === 'xls') {
        /**
         * @param $str
         */
        function cleanData(&$str) {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if(false !== strpos($str, '"')) {
                $str = '"' . str_replace('"', '""', $str) . '"';
            }
        }
        $filenameXLS = $filename . ".xls";
        //output the headers for the XLS file
        header('Content-Encoding: UTF-8');
        header("Content-Type: Application/vnd.ms-excel; charset=utf-8");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-Disposition: Attachment; Filename=\"$filenameXLS\"");
        header("Expires: 0");
        header("Pragma: public");

        $flag = false;
        foreach($dataExport as $data) {
            if(!$flag) {
                echo implode("\t", array_keys($dataExport)) . "\r\n";
                $flag = true;
            }
            array_walk($data, 'cleanData');

            $data_string = implode("\t", array_map('utf8_decode', array_values($data)));

            echo $data_string . "\r\n";
        }
        exit;
    }

}


