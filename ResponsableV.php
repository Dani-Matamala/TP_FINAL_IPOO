<?php
class ResponsableV {
    private $numero_empleado;
    private $numero_licencia;
    private $nombre;
    private $apellido;

    public function __construct() {
        $this->nombre = "";
        $this->apellido = "";
        $this->numero_empleado = 0;
        $this->numero_licencia = 0;
    }

    public function cargar($numero_empleado, $numero_licencia, $nombre, $apellido) {
        $this->setNumeroEmpleado($numero_empleado);
        $this->setNumeroLicencia($numero_licencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }


    public function getNumeroEmpleado() {
        return $this->numero_empleado;
    }

    private function setNumeroEmpleado($numero_empleado) {
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

    /**
     * Inserta los datos del responsable en la base de datos.
     * @return bool
     */
    public function insertar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $numero_empleado = $this->getNumeroEmpleado();
            $numero_licencia = $this->getNumeroLicencia();
            $nombre = $this->getNombre();
            $apellido = $this->getApellido();

            if (!$this->buscar($this->getNumeroEmpleado())) {
                $query = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) 
                VALUES ('$numero_licencia', '$nombre', '$apellido')";

                if ($numero_empleado = $conexion->devuelveIDInsercion($query)) {
                    $this->setNumeroEmpleado($numero_empleado);
                    $res = true;
                } else {
                    echo "Error al insertar el registro: " . $conexion->getError();
                }
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }
        return $res;
    }


    /**
     * Busca un determinado responsable en la base de datos.
     * @return bool
     */
    public function buscar($numero_empleado) {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "SELECT * FROM responsable WHERE rnumeroempleado = '$numero_empleado'";

            if ($conexion->consultar($query)) {
                if ($registro = $conexion->respuesta()) {
                    $this->cargar($registro['rnumeroempleado'], $registro['rnumerolicencia'], $registro['rnombre'], $registro['rapellido']);
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
     * lista los datos de la tabla responsable en la base de datos segun una condicion.
     * @return Array
     */
    public static function listar($condicion) {
        $conexion = new Viajes_db();
        $col_responsable = [];
        $condicion = $condicion != "" ? "where ".$condicion : "";

        if ($conexion->conectar()) {
            $query = "SELECT * FROM responsable". $condicion;

            if ($conexion->consultar($query)) {
                while ($registro = $conexion->respuesta()) {
                    $responsable = new ResponsableV();
                    $responsable->buscar($registro['rnumeroempleado']);
                    $col_responsable[] = $responsable;
                }
            } else {
                echo "Error al ejecutar la consulta: " . $conexion->getError();
            }
        } else {
            echo "Falló la conexión a MySQL: " . $conexion->getError();
        }

        return $col_responsable;
    }

    /**
     * Actualiza los datos de un determinado responsable en la base de datos.
     * @return bool
     */
    public function actualizar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "UPDATE responsable SET rnumerolicencia = '$this->numero_licencia', 
                      rnombre = '$this->nombre', rapellido = '$this->apellido' 
                      WHERE rnumeroempleado = '$this->numero_empleado'";

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
     * Elimina los datos del responsable en la base de datos.
     * @return bool
     */
    public function eliminar() {
        $conexion = new Viajes_db();
        $res = false;

        if ($conexion->conectar()) {
            $query = "DELETE FROM responsable WHERE rnumeroempleado = '{$this->getNumeroEmpleado()}'";

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
