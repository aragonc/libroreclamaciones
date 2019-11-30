<?php

function getAreasList(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_area';
    $list = $wpdb->get_results("SELECT * FROM " . $table_name . " ORDER BY id_area ASC");
    return $list;
}

function getUbigeo($id_districts){
    $text = null;
    $urlJSONDistricts = file_get_contents(plugin_dir_url( __DIR__ )."ubigeo/json/districts.json");
    $urlJSONProvinces = file_get_contents(plugin_dir_url( __DIR__ )."ubigeo/json/provinces.json");
    $urlJSONDepartments = file_get_contents(plugin_dir_url( __DIR__ )."ubigeo/json/departments.json");
    $districts = json_decode($urlJSONDistricts);
    $provinces = json_decode($urlJSONProvinces);
    $departments = json_decode($urlJSONDepartments);

    foreach ($districts as $district){
        if($district->id == $id_districts){

            $text.= $district->name;

            foreach ($provinces as $province){
                if($province->id == $district->province_id){

                    $text.= ', '.$province->name;

                    foreach ($departments as $department){
                        if($department->id == $district->department_id){

                            $text.= ' - '.$department->name;
                        }
                    }
                }
            }
        }
    }

    return $text;
}

function getTypeofRequest($id_type){
    $text = null;
    switch($id_type){
        case 1:
            $text = 'Reclamo';
            break;
        case 2:
            $text = 'Queja';
            break;
        case 3:
            $text = 'Consulta';
            break;
        case 4:
            $text = 'Sugerencia';
            break;   
    }
    return $text;
}

function getTypeMedium($id_type){
    $text = null;
    switch($id_type){
        case 1:
            $text = 'Correo';
            break;
        case 2:
            $text = 'Celular';
            break;
        case 3:
            $text = 'Teléfono';
            break;
    }
    return $text;
}


function complaintsForm(){
    global $reg_errors;
    $reg_errors = new WP_Error;
    $list = getDepartments();
    $areas = getAreasList();
    global $wpdb;
    $table_name = $wpdb->prefix . 'bc_reclamo';
    $dateCurrent = date("Y-m-d H:i:s");

    if (isset($_POST['save'])) {
        $nombre = $_POST['client_name'];
        $tipo_doc = $_POST['type_doc'];
        $nro_doc = $_POST['num_doc'];
        $email = $_POST['client_email'];
        $phone = $_POST['client_phone'];
        $direction = $_POST['client_direction'];
        $departments = $_POST['client_departments'];
        $provinces = $_POST['client_provinces'];
        $districts = $_POST['client_districts'];
        $area = $_POST['client_area'];
        $request = $_POST['client_request'];
        $description = $_POST['client_description'];
        $medio = $_POST['client_answer'];
        $check = $_POST['client_check'];
        $incidentDate = $_POST['client_date'];


        $result = $wpdb->insert($table_name,[
               'nombres' => $nombre,
                'tipo_documento' => $tipo_doc,
                'num_documento' => $nro_doc,
                'email' => $email,
                'telefono' => $phone,
                'departamento' => $departments,
                'provincia' => $provinces,
                'distrito' => $districts,
                'direccion' => $direction,
                'area' => $area,
                'asunto' => $request,
                'descripcion' => $description,
                'medio_respuesta' => $medio,
                'fecha_incidencia' => $incidentDate,
                'terminos' => $check,
                'estado' => 1,
                'fecha_registro' => $dateCurrent
        ]);

        $typeMedio = getTypeMedium($medio);
        $ubication = getUbigeo($districts);
        //Main Details.
        $to = get_option( 'admin_email' );
        $subject =  getTypeofRequest($request) .__( ' enviado desde ' ) . get_option( 'blogname' );

        $message = __('Usuario:') . ' ' . $nombre . ' con ' . $tipo_doc . ': ' . $nro_doc . "\n";
        $message .= __('Email:') . ' ' . $email . "\n";
        $message .= __('Celular o Teléfono:') . ' ' . $phone . "\n";
        $message .= __('Dirección:').' '. $direction . "\n";
        $message .= __('Distrito/Pronvincia/Región:').' '. $ubication . "\n";
        $message .= __('Aréa/Code:').' '. $area . "\n";
        $message .= __('Tipo de solicitud:').' '. getTypeofRequest($request) . "\n";
        $message .= __('Fecha de incidencia:') . ' ' . $incidentDate . "\n";
        $message .= __('Medio de respuesta:'). ' ' . $typeMedio;
        $message .= __('Comentario:') . " \n\n";
        $message .= $description;

        $headers = 'From: ' . $nombre . ' <' . $email . '>' . "\r\n";

        // Email para el administrador
        wp_mail( $to, $subject, $message, $headers );

        // Email para el usuario
        $message_user = "Estimado $nombre ,\r\n\r\nMuchas gracias por dejarnos su opinión sobre nuestros servicios. Su reclamo ha sido recepcionado correctamente.";
        $headers = 'From: ' . get_option( 'blogname' ) . ' <informes@grupoexcelencia.org>' . "\r\n";

        wp_mail( $email, __('Hemos recibido su reclamo'), $message_user, $headers );

        if($result){
            $alert = '<div class="alert alert-primary" role="alert">'.__('Gracias por registrar su mensaje, se le envió un correo de confirmación, favor de revisar su bandeja de entrada o spam y no olvides agregarnos a tus contactos.').'</div>';
            echo $alert;
        }

    }

    ?>

        <div class="container">
            <form method="post" id="form-complaints">
                <div class="form-group row">
                    <label for="client_name" class="col-sm-2 col-form-label">Nombres y apellidos</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="client_name" id="client_name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type_doc" class="col-sm-2 col-form-label">Tipo de documento</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="type_doc" id="type_doc">
                            <option value="-1"></option>
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="CE">Carnet de extranjeria</option>
                            <option value="PAS">Pasaporte</option>
                        </select>
                    </div>
                    <label for="num_doc" class="col-sm-2 col-form-label">Número de documento</label>
                    <div class="col-sm-4">
                        <input class="form-control" name="num_doc" id="num_doc">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="client_email" class="col-sm-2 col-form-label">Correo electronico</label>
                    <div class="col-sm-4">
                        <input type="email" class="form-control" name="client_email" id="client_email">
                    </div>
                    <label for="client_phone" class="col-sm-2 col-form-label">Celular/Wathsapp</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="client_phone" id="client_phone">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="client_direction" class="col-sm-2 col-form-label">Dirección</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="client_direction" id="client_direction">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="client_departments" class="col-sm-2 col-form-label">Departamento</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="client_departments" id="client_departments">
                            <?php foreach ($list as $item):
                                echo '<option value="'.$item["id"].'">'.$item["name"].'</option>';
                            endforeach; ?>
                        </select>
                    </div>
                    <label for="client_provinces" class="col-sm-1 col-form-label">Provincia</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="client_provinces" id="client_provinces" disabled>
                        </select>
                    </div>
                    <label for="client_districts" class="col-sm-1 col-form-label">Distrito</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="client_districts" id="client_districts" disabled>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="client_area" class="col-sm-2 col-form-label">Área de formación</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="client_area" id="client_area">
                            <option value="-1"></option>
                            <?php foreach ($areas as $item):
                                echo '<option value="'.$item->code_area.'">'.$item->nombre.'</option>';
                            endforeach; ?>
                        </select>
                    </div>
                    <label for="client_request" class="col-sm-2 col-form-label">Tipo de solicitud</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="client_request" id="client_request">
                            <option value="-1"></option>
                            <option value="1">Reclamo</option>
                            <option value="2">Queja</option>
                            <option value="3">Consulta</option>
                            <option value="4">Sugerencia</option>
                        </select>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li><strong>Reclamo:</strong> Disconformidad sobre un producto o servicio prestado por la empresa.</li>
                                    <li><strong>Consulta:</strong> Duda sobre alguna operación o información sobre los productos o servicios que le brinda
                                        la empresa.</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li>
                                        <strong>Queja:</strong> Malestar o descontento respecto a la atención en público.
                                    </li>
                                    <li>
                                        <strong>Sugerencia:</strong> Recomendaciones de mejora que nos desean brindar nuestros clientes.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="client_description" class="col-sm-2 col-form-label">Descripción</label>
                    <div class="col-sm-10">
                        <textarea type="text" class="form-control" rows="5" name="client_description" id="client_description"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="client_date" class="col-sm-2 col-form-label">Fecha de indicencia</label>
                    <div class="col-sm-4">
                        <input type="date" class="form-control" name="client_date" id="client_date">
                    </div>
                    <label class="col-sm-2 col-form-label">Medio de respuesta</label>
                    <div class="col-sm-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="client_answer" id="client_answer1" value="1">
                            <label class="form-check-label" for="client_answer1">Correo</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="client_answer" id="client_answer2" value="2">
                            <label class="form-check-label" for="client_answer2">Celular</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="client_answer" id="client_answer3" value="3" >
                            <label class="form-check-label" for="client_answer3">Teléfono</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="client_check" id="client_check">
                            <label class="form-check-label" for="client_check">
                                Mediante el mensaje enviado manifiesto mi conformidad de solicitud ingresada
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <button type="submit" name="save" id="save" class="btn btn-primary" disabled>REGISTRAR</button>
                </div>
            </form>
        </div>

    <script>
        (function($) {

            $('#client_check').click(function(){
                var save = $('#save');
                if( $('#client_check').prop('checked') ) {
                    save.prop('disabled', false);
                } else {
                    save.prop('disabled', true);
                }
            });

            $('#client_departments').click(function(){
                var provinces = $('#client_provinces');
                var districts = $('#client_districts');
                var idDepartament = $('#client_departments').val();
                provinces.prop('disabled', false);
                provinces.empty();
                districts.empty();
                districts.prop('disabled', true);
                var urlProvinces = "<?php echo plugin_dir_url( __DIR__ ) ?>ubigeo/json/provinces.json";
                $.getJSON(urlProvinces, function (data) {
                    data.forEach(function(province, index) {
                        if(province.department_id == idDepartament ){
                            var content = '<option value="'+province.id+'">' + province.name + '</option>';
                            provinces.append(content);
                        }

                    });
                });
            });


            $('#client_provinces').click(function(){
                var districts = $('#client_districts');
                var idProvince = $('#client_provinces').val();
                districts.prop('disabled', false);
                districts.empty();
                var urlDistricts = "<?php echo plugin_dir_url( __DIR__ ) ?>ubigeo/json/districts.json";
                $.getJSON(urlDistricts, function (data) {
                    data.forEach(function(district) {
                        if(district.province_id == idProvince ){
                            var content = '<option value="'+district.id+'">' + district.name + '</option>';
                            districts.append(content);
                        }

                    });
                });
            });


        })( jQuery );
    </script>
    <?php
}