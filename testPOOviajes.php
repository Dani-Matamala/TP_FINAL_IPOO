<?php
include_once './Pasajero.php';
include_once './Viaje.php';
include_once './ResponsableV.php';
include_once './Empresa.php';
include_once './Pasajero.php';
include_once "./Viajes_db.php";


//coleccion de los datos cargados en la base de datos
function getColPasajeros() {
    $col_pasajeros = [];
    $col_pasajeros = Pasajero::listar();
    return $col_pasajeros;
}

function getColViajes() {
    $col_viajes = [];
    $col_viajes = Viaje::listar();
    return $col_viajes;
}

function getColResponsables() {
    $col_responsables = [];
    $col_responsables = ResponsableV::listar();
    return $col_responsables;
}

function getColEmpresa() {
    $col_empresas = [];
    $col_empresas = Empresa::listar();
    return $col_empresas;
}



//funciones para mostrar los datos(READ)
function mostrarViajes() {
    $col_viajes = getColViajes();
    foreach($col_viajes as $viaje){
        echo $viaje->__toString();
        echo "\n"."-----------------------------------------------------------"."\n";
    }
    unset($col_viajes);
}

function mostrarResponsables() {
    $col_responsables = getColResponsables();
    foreach($col_responsables as $responsable){
        echo $responsable->__toString();
        echo "\n"."-----------------------------------------------------------"."\n";
    }
    unset($col_responsables);
}

function mostrarEmpresas() {
    $col_empresas = getColEmpresa();
    foreach($col_empresas as $empresa){ 
        echo $empresa->__toString();
        echo "\n"."-----------------------------------------------------------"."\n";
    }
    unset($col_empresas);
}

function mostrarPasajeros() {
    $col_pasajeros = getColPasajeros();
    foreach($col_pasajeros as $pasajero){
        echo $pasajero->__toString();
        echo "\n"."-----------------------------------------------------------"."\n";
    }
    unset($col_empresas);
}


//funciones para insertar datos(CREATE)
function cargarEmpresa() {
    $res = false;
    $id = 0; //luego se le asignara el id asignado por la base de datos
    // Crear una empresa
    echo "Ingrese el nombre de la empresa:\n";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese la direccion de la empresa:\n";
    $direccion = trim(fgets(STDIN));
    $obj_empresa = new empresa();
    $obj_empresa->cargar($id, $nombre, $direccion);

    if ($obj_empresa->insertar()) {
        $mensaje = "\nLa empresa fue cargada con exito\n";
    }
    return $res;
}

function cargarViaje() {
    $res = false;
    $id = 0; //luego se le asignara el id asignado por la base de datos
    // Crea un viaje
    $viaje = new Viaje();
    // Crea una empresa
    $empresa = new Empresa();
    // Crea un Reponsable
    $responsable = new ResponsableV();
    //Luego esto valores saran actulizados por el ORM de Viaje
    echo "Ingrese el nombre del destino:\n";
    $destino = trim(fgets(STDIN));
    echo "Ingrese la cantidad maxima de pasajeros:\n";
    $maxPasajeros = trim(fgets(STDIN));
    echo "Ingrese el ID de la empresa a la que pertenece este viaje:\n";
    mostrarEmpresas();
    $id_empresa = trim(fgets(STDIN));
    if ($empresa->buscar($id_empresa)) {
        echo "Ingrese el ID del responsable a cargo del viaje:\n";
        mostrarResponsables();
        $id_responsable = trim(fgets(STDIN));
        if ($responsable->buscar($id_responsable)) {
            echo "ingrese el monto del viaje \n";
            $monto = trim(fgets(STDIN));
            $viaje->cargar($id, $destino, $maxPasajeros, $empresa, $responsable, $monto);
            if ($viaje->insertar() === true) {
                echo "\nEl viaje fue cargado con exito\n";
            } else {
                echo "\nEl viaje no pudo ser cargado\n";
            }
        } else {
            echo "\nEl responsable no pudo ser cargado\n";
        }
    } else {
        echo "\nEl viaje no pudo ser cargado\n";
    }
    return $res;
}

function cargarPasajero() {
    $res = false;
    $pasajero = new Pasajero();
    $viaje = new Viaje(); 

    //El pasajero debe pertenecer a un viaje
    $col_viajes = getColViajes();

    echo "Ingrese el número de documento del pasajero: ";
    $numero_documento = trim(fgets(STDIN));
    if (!$pasajero->buscar($numero_documento)) {
        echo "Ingrese el nombre del pasajero: ";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese el apellido del pasajero: ";
        $apellido = trim(fgets(STDIN));
        echo "Ingrese el teléfono del pasajero: ";
        $telefono = trim(fgets(STDIN));
        echo "Ingrese el ID del viaje al que pertenece este pasajero: ";
        mostrarViajes();
        $id_viaje = trim(fgets(STDIN));
        $corte = false;
        while ($corte == false) {
            if ($viaje->buscar($id_viaje)) {
                $corte = true;
            } else{
                echo "El viaje no existe\n";
                echo "ingrese una opcion valida\n";
                mostrarViajes();
            }
        }
        $pasajero->cargar($numero_documento, $nombre, $apellido, $telefono, $viaje);
        $res = $pasajero->insertar();
        if ($res == true) {
            echo "Pasajero ingresado correctamente\n";
        } else {
            echo "No fue posible cargar el pasajero\n";
        }
    } else {
        echo "El pasajero ya existe\n";
        echo "Desea actualizarlo? (s/n)\n";
        $opcion = trim(fgets(STDIN));
        if ($opcion == 's') {
            $res = actualizarPasajero();
        } 
    }

    return $res;
}

function cargarResponsable() {
    $res = false;
    $responsable = new ResponsableV();
    $numero_empleado = null;

    echo "Ingrese num de licencia del responsable\n";
    $numerolicencia = trim(fgets(STDIN));
    echo "Ingrese nombre del responsable:\n";
    $nombreResp = trim(fgets(STDIN));
    echo "Ingrese apellido del responsable:\n";
    $apellidoResp = trim(fgets(STDIN));
    $responsable->cargar($numero_empleado, $numerolicencia, $nombreResp, $apellidoResp);
    $res = $responsable->insertar();
    if ($res == true) {
        echo "Responsable ingresado correctamente\n";
    } else {
        echo "No fue posible cargar el responsable o ya existe\n";
    }
    return $res;
}

//funciones para actualizar datos(UPDATE)
function actualizarViaje() {
    $res = false;
    $viaje = new Viaje();
    $empresa = new Empresa();
    $id = 0;
    echo "Ingrese el ID del viaje que desea modificar:\n";
    mostrarViajes();
    $id = trim(fgets(STDIN));
    if ($viaje->buscar($id)) {
        echo "Ingrese el nuevo destino:\n";
        $destino = trim(fgets(STDIN));
        echo "Ingrese la nueva cantidad maxima de pasajeros:\n";
        $maxPasajeros = trim(fgets(STDIN));
        echo "Ingrese el nuevo monto del viaje:\n";
        $monto = trim(fgets(STDIN));
        echo "Desea conserva la empresa asociada al viaje? (s/n)\n";
        $conserva = trim(fgets(STDIN));
        while ($conserva != 's') {
            switch ($conserva) {
                case 'n': {
                        echo "Ingresa el ID de la nueva empresa:\n";
                        mostrarEmpresas();
                        $id_empresa = trim(fgets(STDIN));
                        if ($empresa->buscar($id_empresa)) {
                            $viaje->cargar($id, $destino, $maxPasajeros, $empresa, $viaje->getResponsable(), $monto);
                            if ($viaje->actualizar()) {
                                $res = true;
                                echo "El viaje fue actualizado con exito\n";
                            }
                        } else {
                            echo "El la empresa ingresada no existe\n";
                        }
                    }
                    break;
                default: {
                        echo "Ingresa el ID de la nueva empresa o s para continuar:\n";
                    }
            }
        }
    } else {
        echo "El viaje ingresado no existe\n";
    }
}

function actualizarPasajero() {
    $res = false;
    $pasajero = new Pasajero();
    $id = 0;

    echo "Ingrese el ID del pasajero que desea modificar:\n";
    mostrarPasajeros();
    $id = trim(fgets(STDIN));
    if ($pasajero->buscar($id)) {
        echo "Ingrese el nuevo nombre:\n";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese el nuevo apellido:\n";
        $apellido = trim(fgets(STDIN));
        echo "Ingrese el nuevo teléfono:\n";
        $telefono = trim(fgets(STDIN));
        $pasajero->cargar($id, $nombre, $apellido, $telefono, $pasajero->getObjViaje());
        if ($pasajero->actualizar()) {
            $res = true;
            echo "El pasajero fue actualizado con exito\n";
        } else {
            echo "El pasajero no pudo ser actualizado\n";
        }
    } else {
        echo "El pasajero ingresado no existe\n";
    }
    return $res;
}

function actualizarResponsable() {
    $res = false;
    $responsable = new ResponsableV();
    $id = 0;
    echo "Ingrese el ID del responsable que desea modificar:\n";
    mostrarResponsables();
    $id = trim(fgets(STDIN));
    if ($responsable->buscar($id)) {
        echo "Ingrese el nuevo num de licencia:\n";
        $numerolicencia = trim(fgets(STDIN));
        echo "Ingrese el nuevo nombre:\n";
        $nombre = trim(fgets(STDIN));
        $nombreResp = trim(fgets(STDIN));
        echo "Ingrese el nuevo apellido:\n";
        $apellido = trim(fgets(STDIN));
        $responsable->cargar($id, $numerolicencia, $nombre, $apellido);
        if ($responsable->actualizar()) {
            $res = true;
            echo "El responsable fue actualizado con exito\n";
        } else {
            echo "El responsable no pudo ser actualizado\n";
        }
    } else {
        echo "El responsable ingresado no existe\n";
    }
}
    

function actualizarEmpresa() {
    $res = false;
    $empresa = new Empresa();
    $id = 0;
    echo "Ingrese el ID de la empresa que desea modificar:\n";
    mostrarEmpresas();
    $id = trim(fgets(STDIN));
    if ($empresa->buscar($id)) {
        echo "Ingrese el nuevo nombre:\n";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese el nuevo teléfono:\n";
        $telefono = trim(fgets(STDIN));
        $empresa->cargar($id, $nombre, $telefono);
        if ($empresa->actualizar()) {
            $res = true;
            echo "La empresa fue actualizada con exito\n";
        } else {
            echo "La empresa no pudo ser actualizada\n";
        }
    } else {
        echo "La empresa ingresada no existe\n";
    }
}

//funciones para eliminar datos(DELETE)
function eliminarViaje() {
    $res = false;
    $viaje = new Viaje();
    $id = 0;
    echo "Ingrese el ID del viaje que desea eliminar:\n";
    mostrarViajes();
    $id = trim(fgets(STDIN));
    if ($viaje->buscar($id)) {
        //obtengo todos los pasajeros del viaje
        $col_pasajeros= getColPasajeros();
        //realizo un mapeo de todos los pasajeros pertencientes a este viaje
        $col_pasajeros_viaje = array_filter(
            $col_pasajeros,
            function($pasajero) use ($id){
            return $pasajero->getIdViaje() === $id;
            }
        );

        //si el viaje contiene pasajeros, pregunto al usuario si desea eliminarlos
        if(count($col_pasajeros_viaje) > 0){
            echo "Este viaje contiene pasajeros? (s/n)\n".
                "Si decide continuar con la eliminacion estos pasajeros seran eliminados\n".
                "Desea continuar? (s/n)\n";
            $eliminar = trim(fgets(STDIN));
            while ($eliminar != 'n') {
                switch ($eliminar) {
                    case 's': {
                        foreach ($col_pasajeros_viaje as $pasajero) {
                                $pasajero->eliminar();
                            }
                            $res = true;
                        }
                        unset($col_pasajeros_viaje);
                        break;
                    default: {
                        echo "Ingrese 's' para eliminar los pasajeros o 'n' para salir:\n";
                }
            }

            if($viaje->eliminar()){
                echo "El viaje fue eliminado con exito\n";
            } else {
                echo "El viaje no pudo ser eliminado\n";
            }
    
        }
        
        } else {
            echo "El viaje ingresado no existe\n";
        }
    }
}

function eliminarPasajero() {
    $res = false;
    $pasajero = new Pasajero();
    $id = 0;
    echo "Ingrese el ID del pasajero que desea eliminar:\n";
    mostrarPasajeros();
    $id = trim(fgets(STDIN));
    if ($pasajero->buscar($id)) {
        $pasajero->eliminar();
        $res = true;
    } else {
        echo "El pasajero ingresado no existe\n";
    }
}

function eliminarResponsable() {
    $res = false;
    $responsable = new ResponsableV();
    $id = 0;
    echo "Ingrese el ID del responsable que desea eliminar:\n";
    mostrarResponsables();
    $id = trim(fgets(STDIN));
    if ($responsable->buscar($id)) {
        //obtengo todos los viajes del responsable
        $col_viajes= getColViajes();
        //realizo un mapeo de todos los viajes pertencientes a este responsable
        $col_viajes_responsable = array_filter(
            $col_viajes,
            function($viaje) use ($id){
                return $viaje->getIdResponsable() === $id;
                }
            );
        if(count($col_viajes_responsable) > 0){
            echo "Este responsable contiene viajes? (s/n)\n".
                "Si decide continuar con la eliminacion estos viajes seran eliminados\n".
                "Desea continuar? (s/n)\n";
                $eliminar = trim(fgets(STDIN));
                while ($eliminar != 'n') {
                    switch ($eliminar) {
                        case 's': {
                            foreach ($col_viajes_responsable as $viaje) {
                                    $viaje->eliminar();
                                    }
                                }break;
                        default: {
                            echo "Ingrese 's' para eliminar los viajes o 'n' para salir:\n";
                            }
                    }
                }
            if($responsable->eliminar()){
                $res = true;
                echo "El responsable fue eliminado con exito\n";
            } else {
                echo "El responsable no pudo ser eliminado\n";
            }
        }
    } else {
        echo "El responsable ingresado no existe\n";
    }
    return $res;
}

function eliminarEmpresa() {
    $res = false;
    $empresa = new Empresa();
    $id = 0;
    echo "Ingrese el ID de la empresa que desea eliminar:\n";
    mostrarEmpresas();
    $id = trim(fgets(STDIN));
    if ($empresa->buscar($id)) {
        $col_viajes = getColViajes();
        $col_viaje_empresa = array_filter(
            $col_viajes,
            function($responsable) use ($id){
                return $responsable->getIdEmpresa() === $id;
            }
        );

        if(count($col_viaje_empresa) > 0){
            echo "Esta empresa contiene viajes (s/n)\n".
                "Si decide continuar con la eliminacion estos viajes seran eliminados\n".
                "Desea continuar? (s/n)\n";
                $eliminar = trim(fgets(STDIN));
                while ($eliminar != 'n') {
                    switch ($eliminar) {
                        case 's': {
                            foreach ($col_viaje_empresa as $viaje) {
                                    eliminarViajeDeEmpresa($viaje->getId());
                                //debido a la complejidad de la operacion, se debe hace un metodo eliminar viaje
                            }
                        }break;
                        default:{
                            echo "Ingrese 's' para eliminar los viajes o 'n' para salir:\n";
                        }
                    }
                }
                if($empresa->eliminar()){
                    $res = true;
                    echo "La empresa fue eliminada con exito\n";
                } else {
                    echo "La empresa no pudo ser eliminada\n";
                }
        } else {
            echo "La empresa ingresada no existe\n";
        }


    }
}

function eliminarViajeDeEmpresa($id){
    $viaje = new Viaje();
    $viaje->buscar($id);
    if($viaje->buscar($id)){
        $col_viajes = getColViajes();//obtengo todos los viajes
        $col_viajes_empresa = array_filter(
            $col_viajes,
            function($viaje) use ($id){
                return $viaje->getIdEmpresa() === $id;
                });//filtro por los viajes de la empresa
        
        $col_pasajeros = getColPasajeros();//obtengo todos los pasajeros
        $col_pasajeros_viaje = array_map(
            function($pasajero, $viaje){
                if($viaje->getId() === $pasajero->getIdViaje())
                    return $pasajero;
            }, $col_pasajeros, $col_viajes_empresa);//hago un mapeo de todos los pasajeros que pertenecen a los viajes de la empresa

        //lo busco en la base de datos por que no esta determinada si un pasajero puede pertenecer a mas de un viaje
            foreach($col_pasajeros_viaje as $pasajero){
            if($pasajero->buscar($pasajero->getId()) === true)
                $pasajero->eliminar();
            }//por cada pasajero que pertenece a los viajes de la empresa, lo elimino
        $viaje->eliminar();
    }
}


        



