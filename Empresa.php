<?php
class Empresa {
    private $idempresa;
    private $nombre;
    private $direccion;

    public function __construct() {
        $this->idempresa = "";
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
            if (!$this->getIdempresa()=== '') {
                $query = "INSERT INTO empresa (nombre, direccion) VALUES ($nombre, $direccion)";

                if ($id = $conexion->devuelveIDInsercion($query)) {
                    $this->setIdEmpresa($id);
                    $res = true;
                } else {
                    echo "Error al ejecutar la consulta: " . $conexion->getError();
                }
            }

            $conexion->desconectar();
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }

    public static function buscar($idempresa) {
        $conexion = new Viajes_db();
        $res = false;
        $empresa = null;
        if ($conexion->conectar()) {
            $query = "SELECT * FROM empresa WHERE idempresa = '$idempresa'";
            $resultado = $conexion->consultar($query);
            if ($resultado) {
                $registro = $conexion->respuesta();
                $empresa = new Empresa();
                $empresa->cargar($registro['idempresa'], $registro['enombre'], $registro['edireccion']);
            }
        }
    }

    public static function listar() {
        $conexion = new Viajes_db();
        $col_empresa = [];

        if ($conexion->conectar()) {
            $query = "SELECT * FROM empresa";

            if ($conexion->consultar($query)) {
                while ($registro = $conexion->respuesta()) {
                    $empresa = new Viaje();
                    $empresa->buscar($registro['idempresa']); // Llamada al método buscar

                    $col_empresa[] = $empresa;
                }
                $conexion->desconectar();
                return $col_empresa;
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

            $query = "UPDATE empresa SET nombre = '$nombre' WHERE idempresa = '{$this->getIdempresa()}'";


            if ($conexion->consultar($query)) {
                $res = true;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }

            $conexion->desconectar();
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
            $query = "DELETE FROM empresa";

            if ($conexion->consultar($query)) {
                $res = true;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }

            $conexion->desconectar();
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $res;
    }
}
