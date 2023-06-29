<?php 
    include("Viajes_db.php");

    $conexion =  new Viajes_db();
    

    // if($conexion -> conectar() == false){
    //     echo"fallo la conexion" . $conexion -> getError();
    // } else{
    //     echo"conexion exitosa";
    // }

    // // forma procedimental mysqli_set_charset($conexion, "utf8");
    
    // // $sql = "SELECT * FROM viaje";
    // $sql = "SELECT * FROM empresa WHERE idempresa = 1";

    // //forma procedimental $resultados = mysqli_query($conexion, $sql);

    // if($conexion -> consultar($sql));
    //     $res = $conexion -> respuesta();

    // if($res){
    //     echo "se puede insertar";
    //     echo print_r($res)."\n";
    // } else{
    //     echo "resultados nulos";
    //     // echo "no hay resultados". gettype($res)."\n";
    // }

    // if($conexion -> errno){
    //     die($conexion -> error); 
    // }

    //forma procedimental while($fila = mysqli_fetch_array($resultados, MYSQLI_ASSOC))

    // while($fila = $resultados -> fetch_assoc()){
    //     echo "<table><tr><td>";
    //     echo $fila['name'] . "</td><td>";
    //     echo $fila['id'] . "</td><td>";
    //     echo "</td><td></tr></table>";
    // }
?>