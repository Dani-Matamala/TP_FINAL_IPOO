<?php
class ResponsableV {
    private $numero_empleado;
    private $numero_licencia;
    private $nombre;
    private $apellido;

    public function __construct() {
        $this->numero_empleado = "";
        $this->numero_licencia = "";
        $this->nombre = "";
        $this->apellido = "";
    }

    public function cargar($nombre, $apellido, $numero_empleado, $numero_licencia) {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumeroEmpleado($numero_empleado);
        $this->setNumeroLicencia($numero_licencia);
    }


    public function getNumeroEmpleado() {
        return $this->numero_empleado;
    }

    public function setNumeroEmpleado($numero_empleado) {
        $this->numero_empleado = $numero_empleado;
    }

    public function getNumeroLicencia() {
        return $this->numero_licencia;
    }

    public function setNumeroLicencia($numero_licencia) {
        $this->numero_licencia = $numero_licencia;
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

    public function __toString() {
        return "Nombre: " . $this->getNombre() .
            "\nApellido: " . $this->getApellido() .
            "\nNumero de Empleado: " . $this->getNumeroEmpleado() .
            "\nNúmero de Licencia: " . $this->getNumeroLicencia();
    }

    /**
     * CRUD para la clase ResponsableV
     */

    public function insertar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->getError()) {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
            $res = false;
            $numero_empleado = $this->getNumeroEmpleado();
            $numero_licencia = $this->getNumeroLicencia();
            $nombre = $this->getNombre();
            $apellido = $this->getApellido();
            

            if (!$this->buscar($this->getNumeroEmpleado())) {
                $query = "INSERT INTO responsablev (rnumeroempleado, rnumerolicencia, rnombre, rapellido)";

                if ($conexion->consultar($query)) {
                    $conexion->desconectar();
                    $res = true;
                } else {
                    echo "Error al insertar el registro: " . $conexion->getError();
                    $conexion->desconectar();
                }
            }
        }
        return $res;
    }

    public function buscar($numero_empleado) {
        $conexion = new Viajes_db();

        if ($conexion->conectar()) {
            $query = "SELECT * FROM responsable WHERE rnumeroempleado = '$numero_empleado'";

            if ($conexion->consultar($query)) {
                $registro = $conexion->respuesta();
                $this->cargar($registro['rnombre'], $registro['rapellido'], $registro['rnumeroempleado'], $registro['rnumerolicencia']);
                $conexion->desconectar();
                return true;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return false;
    }

    public static function listar() {
        $conexion = new Viajes_db();
        $col_responsables = [];

        if ($conexion->conectar()) {
            $query = "SELECT * FROM responsablev";

            if ($conexion->consultar($query)) {
                while ($registro = $conexion->respuesta()) {
                    $responsable = new ResponsableV();
                    $responsable->buscar($registro['rnumeroempleado']);
                    $col_responsables[] = $responsable;
                }
                $conexion->desconectar();
                return $col_responsables;
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }
        return $col_responsables;
    }

    public function actualizar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "UPDATE responsablev SET rnumerolicencia = '$this->numero_licencia', 
                      rnombre = '$this->nombre', rapellido = '$this->apellido' 
                      WHERE rnumeroempleado = '$this->numero_empleado'";

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

        if ($conexion->conectar()) {
            $query = "DELETE FROM responsablev WHERE rnumeroempleado = '$this->numero_empleado'";

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