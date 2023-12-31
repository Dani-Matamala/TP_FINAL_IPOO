<?php
include_once 'Viajes_db.php';
include_once 'Viaje.php';
include_once 'Empresa.php';

class Pasajero {
    private $documento;
    private $nombre;
    private $apellido;
    private $telefono;
    private ?Viaje $obj_viaje;

    public function __construct() {
        $this->documento = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->telefono = "";
        $this->obj_viaje = new Viaje();
    }

    public function getDocumento() {
        return $this->documento;
    }

    public function setDocumento($documento) {
        $this->documento = $documento;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getObjViaje() {
        return $this->obj_viaje;
    }

    public function setIdViaje($obj_viaje) {
        $this->obj_viaje = $obj_viaje;
    }

    public function cargar($documento, $nombre, $apellido, $telefono, $obj_viaje) {
        $this->setDocumento($documento);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setTelefono($telefono);
        $this->setIdViaje($obj_viaje);
    }

    public function __toString() {
        return "Documento: " . $this->getDocumento() . "\n" .
            "Nombre: " . $this->getNombre() . "\n" .
            "Apellido: " . $this->getApellido() . "\n" .
            "Teléfono: " . $this->getTelefono() . "\n" .
            "ID Viaje: " . $this->getObjViaje()->getIdViaje() . "\n";
    }

    /**
     * CRUD para la clase Pasajero
     */

    /**
     * Inserta los datos del pasajero en la base de datos.
     * @return bool
     */
    public function insertar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {

            $query = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) 
            VALUES ('{$this->getDocumento()}', '{$this->getNombre()}', '{$this->getApellido()}', '{$this->getTelefono()}', '{$this->getObjViaje()->getIdViaje()}')";

            if ($conexion->consultar($query)) {
                $res = true;
            } else {
                echo "Error al insertar el registro: " . $conexion->getError();
            }
        } else {
            echo "Fallón la conexión a MySQL: " . $conexion->getError();
        }
        return $res;
    }
    /**
     * Busca los datos del pasajero en la base de datos.
     * @return bool
     */
    public function buscar($id) {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "SELECT * FROM pasajero WHERE pdocumento = '$id'";

            if ($conexion->consultar($query)) {
                if ($registro = $conexion->respuesta()) {
                    $newviaje = new Viaje();
                    $newviaje->buscar($registro['idviaje']);
                    $this->cargar($registro['pdocumento'], $registro['pnombre'], $registro['papellido'], $registro['ptelefono'], $newviaje);
                    $res =  true;
                }
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }

    
     /**
     * Lista los datos de los pasajeros eexistentes en la base de datos.
     * @return Array
     */
    public static function listar($condicion) {
        $conexion = new Viajes_db();
        $col_pasajeros = [];
        $condicion = $condicion != "" ? "where ".$condicion : "";

        if ($conexion->conectar()) {
            $query = "SELECT * FROM pasajero ".$condicion;
            if ($conexion->consultar($query)) {
                while ($registro = $conexion->respuesta()) {
                    $pasajero = new Pasajero();
                    $pasajero->buscar($registro['pdocumento']);
                    $col_pasajeros[] = $pasajero;
                }
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $col_pasajeros;
    }


    /**
     * Actualiza los datos del pasajero en la base de datos.
     * @return bool
     */
    public function actualizar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "UPDATE pasajero SET pnombre = '{$this->getNombre()}', papellido = '{$this->getApellido()}', ptelefono = '{$this->getTelefono()}', idviaje = '{$this->getObjViaje()->getIdViaje()}'
                      WHERE pdocumento = '{$this->getDocumento()}'";

            if ($conexion->consultar($query)) {

                $res = true;
            } else {
                echo "Error al actualizar el registro: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }

    /**
     * Elimina los datos del pasajero en la base de datos.
     * //@return bool
     */
    public function eliminar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {

            $query = "DELETE FROM pasajero WHERE pdocumento = " . $this->getDocumento();

            if ($conexion->consultar($query)) {

                $res = true;
            } else {
                echo "Error al eliminar el registro: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }
}
