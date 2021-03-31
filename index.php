<script type="text/javascript"> 
      function adyacentes(nodo){

      }

</script>
<?php
  include("grafo.php");
  session_start();
  if(!isset($_SESSION["grafo"])){
    $_SESSION["grafo"] = new Grafo();
  }

$accion = (isset($_POST["accion"]))?$_POST["accion"]:"";

switch ($accion) {
  case 'Guardar Vertice':
    if (empty($_POST["Vertice"])) {
      $Message = 'El campo de texto no puede estar vacio';
    }else {
      if($_SESSION["grafo"]->agregarVertice(new vertice($_POST["Vertice"]))){
        $Message = 'Guardado Correctamente';
      }else{
        $Message = 'El elemento ya existe';
      }
    }
    echo "<script type='text/javascript'>alert('$Message');</script>";
    break;

    case 'Buscar Vertice':
      if (empty($_POST['Vertice'])) {
        $Message = 'Ingrese el vertice que desea buscar';
      }else{
        $valor = $_SESSION['grafo']->getVertice($_POST['Vertice']);
        if($valor!=null){
          $Message = ($valor->getVisitado())? "Visitado":"No visitado";
          $Message = "Id: ".$valor->getId()." Vertice ".$Message;
        }else{
          $Message = "No existe el vertice ";
        }

      }
      echo "<script type='text/javascript'>alert('$Message');</script>";
      break;

    case 'Buscar Adyacentes':
      if (empty($_POST['Vertice'])) {
        $Message = 'Ingrese el vertice que desea buscar';
      }else{
        $vector = $_SESSION['grafo']->getAdyacentes($_POST['Vertice']);
        if($vector!=null){
          $Message = "Encontrado";
          print_r($vector);
        }else{
          $Message = "No existe el vertice";
        }
      }
      echo "<script type='text/javascript'>alert('$Message');</script>";
      break;

  case 'Guardar Adyacencia':
    if (empty($_POST["Origen"]) && empty($_POST["Destino"])) {
        $Message = 'El campo de texto no puede estar vacio';
      }else{
        if($_SESSION["grafo"]->agregarArista($_POST["Origen"],$_POST["Destino"],$_POST["Peso"])){
          $Message = 'Vertices Enlazados correctamente';
        }else{
          $Message = 'No existe nodo de origen o destino';
        }
      }
      echo "<script type='text/javascript'>alert('$Message');</script>";
    break;

 case 'Eliminar Arista':
    if(empty($_POST["Origen"]) && empty($_POST["Destino"])){
      $Message = 'Los campos de texto no pueden estar vacios';
    }else{
      if($_SESSION["grafo"]->eliminarArista($_POST["Origen"], $_POST["Destino"])){
        $Message = 'Vertices Deselanzados Correctamente';
      }else{
        $Message = 'Vertices no enlazados o inexistentes';
      }
    }
    echo "<script type='text/javascript'>alert('$Message');</script>";
    break;
    
    case 'Eliminar Vertice':
      if(empty($_POST["Vertice"])){
        $Message = 'Ingrese el vertice que desea eliminar';
      }else{
        if($_SESSION["grafo"]->eliminarVertice($_POST["Vertice"])){
          $Message = 'Vertice eliminado correctamente';
        }else{
          $Message = 'Verice inexistente';
        }
      }
    echo "<script type='text/javascript'>alert('$Message');</script>";
    break;
    
    case 'Mostrar Grado':
      if(empty($_POST["Vertice"])){
        $Message = 'Ingrese el vertice para ver su grado';
      }else{
        if($_SESSION["grafo"]->getVertice($_POST["Vertice"]) == null){
          $Message = 'Vertice inexistente';
        }else{
          $grado = $_SESSION["grafo"]->grado($_POST["Vertice"]);
          $Message = 'Grado: '.$grado;
        }
      }
    echo "<script type='text/javascript'>alert('$Message');</script>";
      
       

}

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script type="text/javascript" src="vis/dist/vis.js"></script>
    <link rel="stylesheet" href="vis/dist/vis.css" type="text/css">
    <title>Mi grafo</title>
  </head>
  <body>
    <h1>Mi Grafo</h1>
    <div>
      <form action="index.php" method="post">
        <h3>Agregar Vertices</h3>
        <label> Incerte el vertice </label>
        <input type="text" name="Vertice">
        <input type="submit" name="accion" value="Guardar Vertice">
        <input type="submit" name="accion" value="Buscar Vertice">
        <input type="submit" name="accion" value="Buscar Adyacentes">
        <input type="submit" name="accion" value="Eliminar Vertice">
        <input type="submit" name="accion" value="Mostrar Grado">
      </form>
    </div>
    <div>
      <form action="index.php" method="post">
        <h3> Agregar Aristas </h3>
        <label>Ingrese vertice de origen</label>
        <input type="text" name="Origen">
        <label>Ingrese vertice de destino</label>
        <input type="text" name="Destino">
        <label>Ingrese el peso (Opcional)</label>
        <input type="text" name="Peso">
        <input type="submit" name="accion" value="Guardar Adyacencia">
        <input type="submit" name="accion" value="Eliminar Arista">
      </form>
    </div>
    <hr>
    <div id="Grafo1" style=" float: left; width: 400px; height: 300px; border: 1px solid lightgray;" >

    </div>
    <div id="Adyacente" style=" float: left; width: 400px; height: 300px; border: 1px solid lightgray;">

    </div>
  </body>
  <br>
  <script type="text/javascript">

    var nodos = new vis.DataSet([
      <?php
        $matriz = $_SESSION["grafo"]->getAristas();
        foreach ($matriz as $nodos => $vector) {
            echo "{id:'$nodos', label: '$nodos'},";
        };
      ?>
    ]);

    var aristas = new vis.DataSet([
      <?php
        $matriz = $_SESSION["grafo"]->getAristas();
        foreach ($matriz as $nodos => $vector) {
          if ($vector!=null) {
            foreach ($vector as $destino => $peso) {
              echo "{from: '$nodos', to: '$destino', label: '$peso' },";
            }
          }
        }
      ?>
    ]);

    var contenedor = document.getElementById('Grafo1');
    var opciones = {
      edges:{
        arrows:{
          to:{
            enabled: true
          }
        }
      }
    };
    var datos = {
      nodes: nodos,
      edges: aristas
    };
    var eldato = new vis.Network(contenedor, datos, opciones);
  </script>

</html>