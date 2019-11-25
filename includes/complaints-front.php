<?php

function complaintsForm(){
    ?>
    <main id="main" class="site-main" role="main">
        <div class="container">
            <form>
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
                            <option></option>
                            <option>DNI</option>
                            <option>RUC</option>
                            <option>Carnet de extranjeria</option>
                            <option>Pasaporte</option>
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
                            <option></option>
                            <option>DNI</option>
                            <option>RUC</option>
                            <option>Carnet de extranjeria</option>
                            <option>Pasaporte</option>
                        </select>
                    </div>
                    <label for="client_provinces" class="col-sm-1 col-form-label">Provincia</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="client_provinces" id="client_provinces">
                            <option></option>
                            <option>DNI</option>
                            <option>RUC</option>
                            <option>Carnet de extranjeria</option>
                            <option>Pasaporte</option>
                        </select>
                    </div>
                    <label for="client_districts" class="col-sm-1 col-form-label">Distrito</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="client_districts" id="client_districts">
                            <option></option>
                            <option>DNI</option>
                            <option>RUC</option>
                            <option>Carnet de extranjeria</option>
                            <option>Pasaporte</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="client_area" class="col-sm-2 col-form-label">Área de formación</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="client_area" id="client_area">
                            <option></option>
                            <option>DNI</option>
                            <option>RUC</option>
                            <option>Carnet de extranjeria</option>
                            <option>Pasaporte</option>
                        </select>
                    </div>
                    <label for="client_request" class="col-sm-2 col-form-label">Tipo de solicitud</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="client_request" id="client_request">
                            <option></option>
                            <option>DNI</option>
                            <option>RUC</option>
                            <option>Carnet de extranjeria</option>
                            <option>Pasaporte</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="client_description" class="col-sm-2 col-form-label">Descripción</label>
                    <div class="col-sm-10">
                        <textarea type="text" class="form-control" rows="5" name="client_description" id="client_description"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Medio de respuesta</label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="client_answer" id="client_answer1" value="option1">
                            <label class="form-check-label" for="client_answer1">Correo</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="client_answer" id="client_answer2" value="option2">
                            <label class="form-check-label" for="client_answer2">Celular</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="client_answer" id="client_answer3" value="option3" >
                            <label class="form-check-label" for="client_answer3">Telefono</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="client_check">
                            <label class="form-check-label" for="client_check">
                                Mediante el mensaje enviado manifiesto mi conformidad de solicitud ingresada
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <button type="submit" class="btn btn-primary">Registrar reclamo</button>
                </div>
            </form>
        </div>
    </main>
    <?php
}