<?php
class Empresa {
    private $idempresa;
    private $nombre;
    private $direccion;

    public function __construct() {
        $this->idempresa = 0;
        $this->nombre = "";
        $this->direccion = "";
    }

    public function getIdempresa() {
        return $this->idempresa;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    private function setIdEmpresa($idempresa) {
        $this->idempresa = $idempresa;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function cargar($idempresa, $nombre, $direccion) {
        $this->setIdempresa($idempresa);
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
    }

    public function __toString() {
        return "ID Empresa: " . $this->getIdempresa() . "\n" .
            "Nombre: " . $this->getNombre() . "\n" .
            "Dirección: " . $this->getDireccion() . "\n";
    }

    /**
     * Guarda la empresa en la base de datos.
     * @return bool
     */
    public function insertar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $nombre = $this->getNombre();
            $direccion = $this->getDireccion();
            //setIdEmpresa($id) es privado
            if ($this->getIdempresa() === 0) {
                $query = "INSERT INTO empresa (enombre, edireccion) VALUES ('$nombre', '$direccion')";

                if ($id = $conexion->devuelveIDInsercion($query)) {
                    $this->setIdEmpresa($id);
                    $res = true;
                } else {
                    echo "Error al ejecutar la consulta: " . $conexion->getError();
                }
            }        
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }

    /**
     * Busca una determinada empresa en la base de datos.
     * @return bool
     */
    public function buscar($idempresa) {
        $conexion = new Viajes_db();
        $res = false;
        if ($conexion->conectar()) {
            $query = "SELECT * FROM empresa WHERE idempresa = '$idempresa'";
            $resultado = $conexion->consultar($query);
            if ($resultado) {
                $registro = $conexion->respuesta();
                $this->cargar($registro['idempresa'], $registro['enombre'], $registro['edireccion']);
                $res = true;
            }
        }
        return $res;
    }

    /**
     * Lista los datos de la empresa en la base de datos.
     * @return Array
     */

    public static function listar($condicion) {
        $conexion = new Viajes_db();
        $col_empresa = [];
        $condicion = $condicion != "" ? "where ".$condicion : "";

        if ($conexion->conectar()) {
            $query = "SELECT * FROM empresa".$condicion;

            if ($conexion->consultar($query)) {
                while ($registro = $conexion->respuesta()) {
                    $empresa = new Empresa();
                    $empresa->buscar($registro['idempresa']);
                    $col_empresa[] = $empresa;
                }
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $col_empresa;
    }


    /**
     * Actualiza los datos de la empresa en la base de datos.
     * @return bool
     */
    public function actualizar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $nombre = $this->getNombre();
            $direccion = $this->getDireccion();

            $query = "UPDATE empresa SET enombre = '$nombre', edireccion = '$direccion'  WHERE idempresa = '{$this->getIdempresa()}'";


            if ($conexion->consultar($query)) {
                $res = true;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }

        
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }

    /**
     * Elimina la empresa de la base de datos.
     * @return bool
     */
    public function eliminar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $queryEmpresa = "DELETE FROM empresa WHERE idempresa = '{$this->getIdempresa()}'";

            if ($conexion->consultar($queryEmpresa)) {
                $res = true;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }

        
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }
}
