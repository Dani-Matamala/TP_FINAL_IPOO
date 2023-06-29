<?php
require_once 'Viajes_db.php';

class TestViaje
{
    public static function cargarEmpresa()
    {
        // Crear instancia de Viajes_db
        $conexion = new Viajes_db();

        // Conectar a la base de datos
        $conexion->conectar();

        // Crear una empresa
        $empresa = new Empresa();
        $empresa->setNombre("Empresa XYZ");
        $empresa->setDireccion("Calle Principal 123");

        // Insertar la empresa en la base de datos
        $empresa->insertar($empresa);

        // Cerrar la conexión
        $conexion->desconectar();
    }

    public static function insertarViaje()
    {
        // Crear instancia de Viajes_db
        $conexion = new Viajes_db();

        // Conectar a la base de datos
        $conexion->conectar();

        // Crear un viaje
        $viaje = new Viaje();
        $viaje->setIdViaje(1);
        $viaje->setOrigen("Ciudad A");
        $viaje->setDestino("Ciudad B");
        $viaje->setFecha("2023-05-15");
        $viaje->setHoraPartida("10:00");
        $viaje->setCantidadAsientos(10);
        $viaje->setImporte(100);

        // Insertar el viaje en la base de datos
        $conexion->insertarViaje($viaje);

        // Cerrar la conexión
        $conexion->cerrarConexion();
    }

    public static function cargarPasajeros()
    {
        // Crear instancia de Viajes_db
        $conexion = new Viajes_db();

        // Conectar a la base de datos
        $conexion->conectar();

        // Crear tres pasajeros
        $pasajero1 = new Pasajero();
        $pasajero1->setIdPasajero(1);
        $pasajero1->setNombre("Juan");
        $pasajero1->setApellido("Pérez");

        $pasajero2 = new Pasajero();
        $pasajero2->setIdPasajero(2);
        $pasajero2->setNombre("María");
        $pasajero2->setApellido("Gómez");

        $pasajero3 = new Pasajero();
        $pasajero3->setIdPasajero(3);
        $pasajero3->setNombre("Carlos");
        $pasajero3->setApellido("López");

        // Insertar los pasajeros en la base de datos
        $conexion->insertarPasajero($pasajero1);
        $conexion->insertarPasajero($pasajero2);
        $conexion->insertarPasajero($pasajero3);

        // Cerrar la conexión
        $conexion->cerrarConexion();
    }
}

// Ejemplo de uso
TestViaje::cargarEmpresa();
TestViaje::insertarViaje();
TestViaje::cargarPasajeros();
