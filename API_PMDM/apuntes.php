<?php
include "config.php";
include "utils.php";
$dbConn = connect($db);
/*
  Listar todos los apuntes de un cuaderno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['idCuaderno']))
    {
      //Mostrar apuntes de un cuaderno
      $sql = $dbConn->prepare("SELECT * FROM apuntes WHERE idCuadernoFK=:idCuaderno");
      $sql->bindValue(':idCuaderno', $_GET['idCuaderno']);
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetchAll());
      exit();
    }
}
// Crear un nuevo apunte
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO apuntes VALUES (null,'".$_POST['fechaApunte']."', '".$_POST['textoApunte']."', ".$_POST['idCuadernoFK'].")";
    $statement = $dbConn->prepare($sql);
    
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      $input['idApunte'] = $postId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
   }
}
// Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
  $idApunte = $_GET['idApunte'];
  $statement = $dbConn->prepare("DELETE FROM apuntes WHERE idApunte=:idApunte");
  $statement->bindValue(':idApunte', $idApunte);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}
// Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
	$sql = "UPDATE apuntes SET fechaApunte='".$input['fechaApunte']."',textoApunte='".$input['textoApunte']."' WHERE idApunte=".$input['idApunte'];
    $statement = $dbConn->prepare($sql);
    
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}
// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
