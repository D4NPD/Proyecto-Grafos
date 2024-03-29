<?php
  include("grafo.php");
  session_start();
  if(!isset($_SESSION["grafo"])){
    $_SESSION["grafo"] = new Grafo();
  }

$accion = (isset($_POST["accion"]))?$_POST["accion"]:"";
$ruta = null;
$respuesta="";
switch ($accion) {
    case 'Guardar Vertice':
      if (empty($_POST["Vertice"])) {
        $Message = 'El campo de texto no puede estar vacio';
      }else {
        if($_SESSION["grafo"]->agregarVertice(new vertice($_POST["Vertice"]))){
          $Message = null;
        }else{
          $Message = 'El nodo ya existe';
        }
      }
      if($Message!=null){
        echo "<script type='text/javascript'>alert('$Message');</script>";
      }
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
      break;

      case 'Anchura':
      $nodo = $_POST["nodoI"];
      $respuesta = $_SESSION['grafo']->recorrerAnchura($nodo);
        break;

      case 'Profundidad':
      $nodo = $_POST["nodoI"];
      $respuesta = $_SESSION['grafo']->recorrerProfundidad($nodo);
        break;

      case 'Camino más corto':
      $nodoOrigen = $_POST['verticeOrigen'];
      $nodoDestino = $_POST['verticeDestino'];
      $ruta = $_SESSION['grafo']->caminoMasCorto($nodoOrigen,$nodoDestino);
      if(!is_array($ruta) && $ruta!=null) echo "<script type='text/javascript'>alert('$ruta');</script>";
        break;
}

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script type="text/javascript" src="vis/dist/vis.js"></script>
    <link rel="stylesheet" href="vis/dist/vis.css" type="text/css">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Proyecto de Grafos</title>
  </head>
  <body>
    <h1>Proyecto de Grafos</h1>
    <div class="container -color">
      <form action="index.php" method="post" id="vertice">
        <h2> Vertices</h2>
        <input type="text" name="Vertice" placeholder="ID del verice" required>
        <input class="verde" type="submit" name="accion" value="Guardar Vertice">
        <input class="azul" type="submit" name="accion" value="Buscar Vertice">
        <input class="azul" type="submit" name="accion" value="Buscar Adyacentes">
        <input class="rojo" type="submit" name="accion" value="Eliminar Vertice">
        <input class="info" type="submit" name="accion" value="Mostrar Grado">
      </form>
    </div>
    <div class="container -color">
      <form action="index.php" method="post" id="aristas">
        <h2> Aristas </h2>
        <input type="text" name="Origen" placeholder="Vertice de origen" required>
        <input type="text" name="Destino" placeholder="Vertice de destino" required>
        <input type="text" name="Peso" placeholder="Peso (Opcional)">
        <input class="verde" type="submit" name="accion" value="Guardar Adyacencia">
        <input class="rojo" type="submit" name="accion" value="Eliminar Arista">
      </form>
    </div>

    <div class="container" id="Grafo1">

    </div>
    <div class="container" id="Adyacente">

    </div>
    <div class="container -color -posicion">
      <form action="index.php" method="post">
        <h2>Recorridos del grafo</h2>
        <input type="text" name="nodoI" placeholder="ID del vertice" required>
        <input class="info" type="submit" name="accion" value="Anchura">
        <input class="info" type="submit" name="accion" value="Profundidad">
      </form>
      <div class="mostrar">
        <?php echo "".($respuesta? $respuesta:""); ?>
      </div>
    </div>
    <div class="container -color -posicion -margen">
      <form action="index.php" method="post">
        <h2>Buscar el camino más corto</h2>
        <input type="text" name="verticeOrigen"placeholder="Origen" required>
        <input type="text" name="verticeDestino" placeholder="Destino" required>
        <input class="verde" type="submit" name="accion" value="Camino más corto">
      </form>
    </div>

  <?php
    if($accion == 'Buscar Adyacentes'){
      if (empty($_POST['Vertice'])) {
        $Message = 'Ingrese el vertice que desea buscar';
      }else{
        $nodo = $_POST['Vertice'];
        $vector = $_SESSION['grafo']->getAdyacentes($nodo);
        if($vector!=null){
          $Message = null;
          echo "<script type='text/javascript'> var adya = new vis.DataSet([";
          foreach ($vector as $key => $value) {
            echo "{id:'$key', label: '$key'},";
          };
          if(!isset($vector[$nodo])){
            echo "{id:'$nodo', label:'$nodo'}";
          };
          echo "]);";
          echo "var aris = new vis.DataSet([";
          foreach ($vector as $key => $value) {
            echo "{from: '$nodo', to: '$key', label: '$value' },";
          };
          echo "]);";
          echo "var contenedor = document.getElementById('Adyacente');";
          echo "var opc = { edges: { arrows:{ to:{ enabled: true }}}};";
          echo "var dat = {nodes: adya, edges: aris}; var nuevo = new vis.Network(contenedor, dat, opc);";
          echo "</script>";
        }else{
          if(!isset(($_SESSION['grafo']->getVertices())[$nodo])){
            $Message="El vertice no existe";
          }else{
            $Message="El nodo ".$nodo." no tiene adyacentes";
          }
        }
      }
      if ($Message!=null) {
        echo "<script type='text/javascript'>alert('$Message');</script>";
      }
    }
?>
  <script type="text/javascript">

    var nodos = new vis.DataSet([
      <?php
        $matriz = $_SESSION["grafo"]->getAristas();
        foreach ($matriz as $nodos => $vector) {
            echo "{id:'$nodos', label: '$nodos'";
            if(is_array($ruta) && !empty($ruta)){
              for ($i=0; $i<count($ruta) ; $i++) {
                if($ruta[$i]==$nodos){
                  echo ",font: {color: 'white'},color:{ background: 'red' }";
                }
              }
            }
            echo "},";
        };
      ?>
    ]);

    var aristas = new vis.DataSet([
      <?php
        $matriz = $_SESSION["grafo"]->getAristas();

        foreach ($matriz as $nodos => $vector) {
          if ($vector!=null) {
              foreach ($vector as $destino => $peso) {
                echo "{from: '$nodos', to: '$destino', label: '$peso' ";
                    if(is_array($ruta) && !empty($ruta)){
                      if(in_array($nodos,$ruta) && in_array($destino,$ruta)){
                          if(array_search($nodos,$ruta) == array_search($destino,$ruta)-1){
                            if(end($ruta)!=$nodos){
                              echo ",color: {color: 'red'}";
                            }
                          }
                        }              
                      }
                    echo "},";
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
</body>
</html>
