<?php
require_once('Viaje.php');
require_once('./Pasajero.php');
require_once('./ResponsableV.php');

$viaje = new Viaje();

function cargarPasajero($viaje) {
    $nombre = "";
    $apellido = "";
    $numero_documento = "";
    $telefono = "";
    $res = false;

    echo "Ingrese el nombre del pasajero: ";
    $nombre = fgets(STDIN);
    echo "Ingrese el apellido del pasajero: ";
    $apellido = fgets(STDIN);
    echo "Ingrese el número de documento del pasajero: ";
    $numero_documento = fgets(STDIN);
    echo "Ingrese el teléfono del pasajero: ";
    $telefono = fgets(STDIN);

    $pasajero = new Pasajero();

    if ($viaje->agregarPasajero($pasajero)) {
        if($pasajero->insertar()){
            $res = true;
        }
    }
    return $res;
}

function cargarResponsable($viaje) {
    $numero_empleado = "";
    $numero_licencia = "";
    $nombre = "";
    $apellido = "";

    echo "Ingrese el nombre del Responsable: ";
    $nombre = fgets(STDIN);
    echo "Ingrese el apellido: ";
    $apellido = fgets(STDIN);
    echo "Ingrese el número de documento de licencia: ";
    $numero_licencia = fgets(STDIN);
    echo "Ingrese el teléfono: ";
    $numero_empleado = fgets(STDIN);

    $responsable = new ResponsableV($numero_empleado, $numero_licencia, $nombre, $apellido);

    $responsable->insertar();

    $viaje->setResponbleV($responsable);
}



do {
    // Mostramos el menú
    echo "======= MENÚ =======\n";
    echo "1. Cargar información del viaje\n";
    echo "2. Modificar información del viaje\n";
    echo "3. Ver información del viaje\n";
    echo "4. Cargar Pasajero\n";
    echo "5. Salir\n";
    echo "Seleccione una opción: ";

    // Leemos la opción seleccionada por el usuario
    $opcion = trim(fgets(STDIN));

    switch ($opcion) {
        case 1:
            // Cargamos la información del viaje
            echo "Ingrese el destino del viaje: ";
            $destino = trim(fgets(STDIN));
            echo "Ingrese la cantidad máxima de pasajeros: ";
            $maxPasajeros = trim(fgets(STDIN));
            $viaje->setDestino($destino);
            $viaje->setMaxPasajeros($maxPasajeros);
            cargarResponsable($viaje);
            $cargaDePasajero = "Y";
            while ($cargaDePasajero == "Y" || $cargaDePasajero == "y") {
                echo "Deseada cargar Pasajeros? Y o N";
                $cargaDePasajero = trim(fgets(STDIN));
                if ($cargaDePasajero == "Y" || $cargaDePasajero == "y") {
                    cargarPasajero($viaje);
                }
            }
            break;
        case 2:
            // Modificamos la información del viaje
            echo "¿Qué desea modificar?\n";
            echo "1. Código del viaje\n";
            echo "2. Destino del viaje\n";
            echo "3. Cantidad máxima de pasajeros\n";
            echo "4. Cargar Pasajero\n";
            echo "Seleccione una opción: ";
            $opcionModificar = trim(fgets(STDIN));
            switch ($opcionModificar) {
                case 1:
                    echo "Ingrese el nuevo código del viaje: ";
                    $codigo = trim(fgets(STDIN));
                    $viaje->buscar($codigo);
                    break;
                case 2:
                    echo "Ingrese el nuevo destino del viaje: ";
                    $destino = trim(fgets(STDIN));
                    $viaje->setDestino($destino);
                    break;
                case 3:
                    echo "Ingrese la nueva cantidad máxima de pasajeros: ";
                    $maxPasajeros = trim(fgets(STDIN));
                    $viaje->setMaxPasajeros($maxPasajeros);
                    break;
                case 4:
                    if ($viaje) {
                        cargarPasajero($viaje);
                    } else {
                        echo "Cree un viaje";
                    }

                    break;
                default:
                    echo "Opción inválida\n";
                    break;
            }
            break;
        case 3:
            // Mostramos la información del viaje
            echo $viaje->__toString();
            break;
        case 4:
            // Salimos del programa
            echo "¡Hasta luego!\n";
            break;
        default:
            echo "Opción inválida\n";
            break;
    }
} while ($opcion != 4);
