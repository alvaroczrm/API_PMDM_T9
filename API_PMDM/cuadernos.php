<?php
include "config.php";
include "utils.php";
$dbConn = connect($db);
/*
  Listar todos los registros o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['idCuaderno']))
    {
      //Mostrar un cuaderno
      $sql = $dbConn->prepare("SELECT * FROM cuadernos WHERE idCuaderno=:idCuaderno");
      $sql->bindValue(':idCuaderno', $_GET['idCuaderno']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
      exit();
    }
    else 
	{
      //Mostrar lista de cuadernos
      $sql = $dbConn->prepare("SELECT * FROM cuadernos");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetchAll());
      exit();
  }
}
// Crear un nuevo cuaderno
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO cuadernos VALUES (null,'".$_POST['nombreCuaderno']."')";
    $statement = $dbConn->prepare($sql);
  
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      $input['idCuaderno'] = $postId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
   }
}
// Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
  $idCuaderno = $_GET['idCuaderno'];
  $statement = $dbConn->prepare("DELETE FROM cuadernos WHERE idCuaderno=:idCuaderno");
  $statement->bindValue(':idCuaderno', $idCuaderno);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}
// Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
	$sql = "UPDATE cuadernos SET nombreCuaderno='".$input['nombreCuaderno']."' WHERE idCuaderno=".$input['idCuaderno'];
    $statement = $dbConn->prepare($sql);
    
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}
// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
