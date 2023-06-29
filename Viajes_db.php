<?php
class Viajes_db {
    private string $HOSTNAME;
    private string $BASEDATOS;
    private string $USUARIO;
    private string $CLAVE;
    private ?mysqli $CONEXION;
    private ?mysqli_result $RESULT;
    private ?string $ERROR;
    private ?string $QUERY;


    public function __construct() {
        $this->HOSTNAME = "127.0.0.1";
        $this->BASEDATOS = "bdviajes";
        $this->USUARIO = "root";
        $this->CLAVE = "";
        $this->RESULT = null;
        $this->ERROR = null;
        $this->QUERY = "";
    }

    public function getError(): ?string {
        return $this->ERROR;
    }

    public function conectar(): bool {
        $resp = false;
        $conexion = new mysqli($this->HOSTNAME, $this->USUARIO, $this->CLAVE, $this->BASEDATOS);
        if ($conexion->connect_errno) {
            $this->ERROR = $conexion->connect_errno . ": " . $conexion->connect_error;
        } else {
            $this->CONEXION = $conexion;
            unset($this->QUERY);
            unset($this->ERROR);
            $resp = true;
        }
        return $resp;
    }

    public function consultar(string $consulta): bool {
        $resp = false;
        unset($this->ERROR);
        $this->QUERY = $consulta;
        $result = $this->CONEXION->query($consulta);
        if ($result !== false) {
            $this->RESULT = $result;
            $resp = true;
        } else {
            $this->ERROR = $this->CONEXION->errno . ": " . $this->CONEXION->error;
        }
        return $resp;
    }

    public function respuesta(): ?array {
        $resp = null;
        if ($this->RESULT !== null) {
            unset($this->ERROR);
            if ($temp = $this->RESULT->fetch_assoc()) {
                $resp = $temp;
            } else {
                $this->RESULT->free_result();
            }
        } else {
            $this->ERROR = $this->CONEXION->errno . ": " . $this->CONEXION->error;
        }
        return $resp;
    }

    public function devuelveIDInsercion(string $consulta): ?int {
        $resp = null;
        unset($this->ERROR);
        $this->QUERY = $consulta;
        $result = $this->CONEXION->query($consulta);
        if ($result !== false) {
            $id = $this->CONEXION->insert_id;
            $resp = $id;
        } else {
            $this->ERROR = $this->CONEXION->errno . ": " . $this->CONEXION->error;
        }
        return $resp;
    }

    public function desconectar(): bool {
        $resp = false;
        if ($this->CONEXION !== null && $this->CONEXION->close()) {
            $resp = true;
            unset($this->CONEXION);
            unset($this->QUERY);
            unset($this->RESULT);
            unset($this->ERROR);
        }
        return $resp;
    }
}
