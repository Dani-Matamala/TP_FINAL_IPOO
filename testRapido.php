<?php
include_once('Pasajero.php');
include_once('Viajes_db.php');
//creo un objeto pasajero
$pasajero = new Pasajero();


//Busca todas las personas almacenadas en la base de datos
$colPasajeros = $pasajero->listar();


foreach ($colPasajeros as $pas) {
    echo $pas->__toString();
    echo "\n"."-----------------------------------------------------------"."\n";
}


// // Incluir la clase Viaje
// require_once('Viaje.php');
// require_once('ResponsableV.php');
// require_once('Pasajero.php');


// // Crear un responsable de viaje
// $responsable = new ResponsableV('1234', 'licencia1234', 'Juan', 'Perez');

// // Crear un viaje
// $viaje = new Viaje('1234', 'Miami', 5, $responsable);

// // Agregar pasajeros al viaje
// $pasajero1 = new Pasajero('Juan', 'Gonzalez', '123456789', '555-5555');
// $pasajero2 = new Pasajero('Maria', 'Lopez', '987654321', '444-4444');

// $viaje->agregarPasajero($pasajero1);
// $viaje->agregarPasajero($pasajero2);

// // Imprimir los datos del viaje
// echo $viaje->__toString();

// //Quitar pasajeros
// $viaje->quitarPasajero($pasajero1);
// $viaje->quitarPasajero($pasajero2);

// // Imprimir los datos del viaje
// echo $viaje->__toString();
?>
