<?php

include_once("Viajes_db.php");
include_once("ResponsableV.php");

class Viaje {
    private $idviaje;
    private $destino;
    private $maxPasajeros;
    private ?Empresa $obj_empresa;
    private ?ResponsableV $obj_responsable;
    private $importe;

    public function __construct() {
        $this->idviaje = "";
        $this->destino = "";
        $this->maxPasajeros = 0;
        $this->obj_empresa = null;
        $this->obj_responsable = null;
        $this->importe = 0.0;
    }

    public function getIdViaje() {
        return $this->idviaje;
    }

    public function setIdViaje($idviaje) {
        $this->idviaje = $idviaje;
    }

    public function getDestino() {
        return $this->destino;
    }

    public function setDestino($destino) {
        $this->destino = $destino;
    }

    public function getMaxPasajeros() {
        return $this->maxPasajeros;
    }

    public function setMaxPasajeros($maxPasajeros) {
        $this->maxPasajeros = $maxPasajeros;
    }

    public function getEmpresa() {
        return $this->obj_empresa;
    }

    public function setEmpresa($obj_empresa) {
        $this->obj_empresa = $obj_empresa;
    }

    public function getResponsable() {
        return $this->obj_responsable;
    }

    public function setResponsable($responsable) {
        $this->obj_responsable = $responsable;
    }

    public function getImporte() {
        return $this->importe;
    }

    public function setImporte($importe) {
        $this->importe = $importe;
    }

    public function cargar($idViaje, $destino, $maxPasajeros, $obj_empresa, $responsable, $importe) {
        $this->setIdViaje($idViaje);
        $this->setDestino($destino);
        $this->setMaxPasajeros($maxPasajeros);
        $this->setEmpresa($obj_empresa);
        $this->setResponsable($responsable);
        $this->setImporte($importe);
    }

    public function __toString() {
        $empresa = $this->obj_empresa ? $this->obj_empresa->__toString() : "Sin empresa asignada";
        $responsable = $this->obj_responsable ? $this->obj_responsable->__toString() : "Sin responsable asignado";

        return "ID Viaje: " . $this->getIdViaje() . "\n" .
            "Destino: " . $this->getDestino() . "\n" .
            "Máximo de pasajeros: " . $this->getMaxPasajeros() . "\n" .
            "Empresa: " . $empresa . "\n" .
            "Responsable: " . $responsable . "\n" .
            "Importe: " . $this->getImporte() . "\n";
    }

    /**
     * CRUD para la clase Viaje
     */

    public function insertar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
            $res = false;
        }

        if (!$this->buscar($this->getIdViaje())) {
            $empresa = $this->obj_empresa ? $this->obj_empresa->__toString() : null;
            $responsable = $this->obj_responsable ? $this->obj_responsable->__toString() : null;

            $query = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
            VALUES ('{$this->getDestino()}', {$this->getMaxPasajeros()}, '{$this->getEmpresa()->getIdEmpresa()}', 
            {$this->getResponsable()->getNumeroEmpleado()}, {$this->getImporte()})";

            if ($idConsulta = $conexion->devuelveIDInsercion($query)) {
                $this->setIdViaje($idConsulta);
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
            $query = "SELECT * FROM viaje WHERE idviaje = '$id'";

            if ($conexion->consultar($query)) {
                $registro = $conexion->respuesta();
                $newEmpresa = new Empresa();
                $newEmpresa->buscar($registro['idempresa']);
                $newResponsable = new ResponsableV();
                $newResponsable->buscar($registro['rnumeroempleado']);

                $this->cargar($registro['idviaje'], $registro['vdestino'], $registro['vcantmaxpasajeros'], $newEmpresa, $newResponsable, $registro['vimporte']);

                $conexion->desconectar();
                $res = true;
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
        $col_viajes = [];

        if ($conexion->conectar()) {
            $query = "SELECT * FROM viaje";

            if ($conexion->consultar($query)) {
                while ($registro = $conexion->respuesta()) {
                    $viaje = new Viaje();
                    $viaje->buscar($registro['idviaje']); // Llamada al método buscar
                    $col_viajes[] = $viaje;
                }
                $conexion->desconectar();
                return $col_viajes;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $col_viajes;
    }


    public function actualizar() {
        $conexion = new Viajes_db();
        $res = false;
        $destino = $this->getDestino();
        $maxPasajeros = $this->getMaxPasajeros();
        $obj_empresa = $this->getEmpresa()->getIdEmpresa();
        $responsable = $this->getResponsable()->getNumeroEmpleado();
        $importe = $this->getImporte();
        $idviaje = $this->getIdViaje();

        if ($conexion->conectar()) {
            $query = "UPDATE viaje SET vdestino = '$destino', 
                      vcantmaxpasajeros = $maxPasajeros, idempresa = '$obj_empresa', 
                      rnumeroempleado = '$responsable', vimporte = $importe 
                      WHERE idviaje = '$idviaje'";

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

    public function eliminar() {
        $conexion = new Viajes_db();
        $res = false;
        $idviaje = $this->getIdViaje();

        if ($conexion->conectar()) {
            $query = "DELETE FROM viaje WHERE idviaje = '$idviaje'";

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
