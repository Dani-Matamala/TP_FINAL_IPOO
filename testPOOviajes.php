<?php
include_once './Pasajero.php';
include_once './Viaje.php';
include_once './ResponsableV.php';
include_once './Empresa.php';
include_once './Pasajero.php';
include_once "./Viajes_db.php";


//coleccion de los datos cargados en la base de datos

$col_responsables = ResponsableV::listar();
$col_empresas = Empresa::listar();
$col_pasajeros = Pasajero::listar();

function mostrarViajes() {
    $col_viajes = [];
    $col_viajes = Viaje::listar();
    foreach ($col_viajes as $viaje) {
        $viaje->__toString();
        echo "\n";
    }
}

function mostrarResponsables() {
    $col_responsables = [];
    $col_responsables = ResponsableV::listar();
    foreach ($col_responsables as $responsable) {
        $responsable->__toString();
        echo "\n";
    }
}

function mostrarEmpresas() {
    $col_empresas = [];
    $col_empresas = Empresa::listar();
    foreach ($col_empresas as $empresa) {
        $empresa->__toString();
        echo "\n";
    }
}

function mostrarPasajeros() {
    $col_pasajeros = [];
    $col_pasajeros = Pasajero::listar();
    foreach ($col_pasajeros as $pasajero) {
        $pasajero->__toString();
        echo "\n";
    }
}



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
            }
            else{
                echo "\nEl viaje no pudo ser cargado\n";
            }
        }else{
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
    $viaje = null; // la carga de viaje se realizara mediante otro metodo

    echo "Ingrese el número de documento del pasajero: ";
    $numero_documento = fgets(STDIN);
    if($pasajero->buscar($numero_documento) !== true){
        echo "Ingrese el nombre del pasajero: ";
        $nombre = fgets(STDIN);
        echo "Ingrese el apellido del pasajero: ";
        $apellido = fgets(STDIN);
        echo "Ingrese el teléfono del pasajero: ";
        $telefono = fgets(STDIN);
        $pasajero->cargar($numero_documento, $nombre, $apellido, $telefono, $viaje);
        $res=$pasajero->insertar();
        if($res == true){
            echo "Pasajero ingresado correctamente\n";
        } else {
            echo "No fue posible cargar el pasajero\n";
        }
    } else{
        echo "El pasajero ya existe\n";
    }

   return $res;
}

function cargarResponsable() {
    $res = false;
    $responsable= new ResponsableV();
    $numero_empleado = null;

    echo "Ingrese num de licencia del responsable\n";
    $numerolicencia= trim(fgets(STDIN));
    echo "Ingrese nombre del responsable:\n";
    $nombreResp= trim(fgets(STDIN));
    echo "Ingrese apellido del responsable:\n";
    $apellidoResp= trim(fgets(STDIN));
    $responsable->cargar($numero_empleado,$numerolicencia, $nombreResp, $apellidoResp);
    $res=$responsable->insertar();
    if( $res == true){
        echo"Responsable ingresado correctamente\n";
    }else {
        echo"No fue posible cargar el responsable o ya existe\n";
    }
    return $res;
}
