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
        $this->obj_viaje = null;
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

    public function insertar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
            $res = false;
        }

        if (!$this->buscar($this->getDocumento())) {
            $query = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) 
            VALUES ('{$this->getDocumento()}', '{$this->getNombre()}', '{$this->getApellido()}', '{$this->getTelefono()}', '{$this->getObjViaje()->getIdViaje()}')";


            if ($conexion->consultar($query)) {
                $conexion->desconectar();
                $res = true;
            } else {
                echo "Error al insertar el registro: " . $conexion->getError();
                $conexion->desconectar();
            }
            $res = true;
        }
        return $res;
    }

    public function buscar($id) {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "SELECT * FROM pasajero WHERE pdocumento = '$id'";

            if ($conexion->consultar($query)) {
                $registro = $conexion->respuesta();
                $newviaje = new Viaje();
                $newviaje->buscar($registro['idviaje']);
                $this->cargar($registro['pdocumento'], $registro['pnombre'], $registro['papellido'], $registro['ptelefono'], $newviaje);
                $newviaje->
                $conexion->desconectar();
                $res =  true;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }

    public static function listar() {
        $conexion = new Viajes_db();
        $col_pasajeros = [];

        if ($conexion->conectar()) {
            $query = "SELECT * FROM pasajero";

            if ($conexion->consultar($query)) {
                while ($registro = $conexion->respuesta()) {
                    $pasajero = new Pasajero();
                    $pasajero->buscar($registro['pdocumento']);
                    $col_pasajeros[] = $pasajero;
                }
                $conexion->desconectar();
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $col_pasajeros;
    }

    public function actualizar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "UPDATE pasajero SET pnombre = '$this->nombre', 
                      papellido = '$this->apellido', ptelefono = '$this->telefono', idviaje = '{$this->getObjViaje()->getIdViaje()}' 
                      WHERE pdocumento = '$this->documento'";

            if ($conexion->consultar($query)) {
                $conexion->desconectar();
                $res = true;
            } else {
                echo "Error al actualizar el registro: " . $conexion->getError();
                $conexion->desconectar();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }

    //elimina pasajero del viaje
    public function eliminar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "DELETE FROM pasajero WHERE idviaje = " . $this->getObjViaje()->getIdViaje() . " AND pdocumento = " . $this->getDocumento();

            if ($conexion->consultar($query)) {
                $conexion->desconectar();
                $res = true;
            } else {
                echo "Error al eliminar el registro: " . $conexion->getError();
                $conexion->desconectar();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }
}