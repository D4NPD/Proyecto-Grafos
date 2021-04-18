<?php
include("vertice.php");
/**
 * Clase grafo
 */
class Grafo{
  private $aristas;
  private $vertices;
  private $dirigido;

  public function __construct($dirigido = true){
    $this->aristas = null;
    $this->vertices = null;
    $this->dirigido = $dirigido;
  }

  public function agregarVertice($vertice){
    if (!isset($this->vertices[$vertice->getId()])){
      $this->aristas[$vertice->getId()] = null;
      $this->vertices[$vertice->getId()] = $vertice;
    }else{
      return false;
    }
    return true;
  }

  public function getVertice($vertice){
    if(isset($this->vertices[$vertice])){
      return $this->vertices[$vertice];
    }else{
      return null;
    }
  }

  public function agregarArista($origen, $destino, $peso = null){
    if(isset($this->vertices[$origen]) &&  isset($this->vertices[$destino])){
      if(empty($peso)) $peso=1;
      $this->aristas[$origen][$destino] = $peso;
    }else{
      return false;
    }
    return true;
  }

  public function getAristas(){
    return $this->aristas;
  }

  public function getVertices(){
    return $this->vertices;
  }

  public function getAdyacentes($vertice){
    if(isset($this->aristas[$vertice])){
      return $this->aristas[$vertice];
    }else{
      return null;
    }
  }

  public function eliminarArista($origen, $destino){
    if(isset($this->aristas[$origen][$destino])){
      unset($this->aristas[$origen][$destino]);
    }else{
      return false;
    }
    return true;
  }

  public function eliminarVertice($vertice){
    if(isset($this->vertices[$vertice])){
      foreach($this->aristas as $nodos => $vector){
        if($vector != null){
          foreach($vector as $destino => $peso){
            if($vertice == $destino){
              self::eliminarArista($nodos, $destino);
            }
          }
        }
      }
      unset($this->aristas[$vertice]);
      unset($this->vertices[$vertice]);
    }else{
      return false;
    }
    return true;
  }

  public function gradoSalida($vertice){
    if(isset($this->aristas[$vertice])){
      return count($this->aristas[$vertice]);
    }else{
      return false;
    }
  }

  public function gradoEntrada($vertice){
    $contador = 0;
    if($this->aristas != null){
      foreach($this->aristas as $nodos => $vector){
        if($vector != null){
          foreach($vector as $destino => $peso){
            if($destino == $vertice){
              $contador++;
            }
          }
        }
      }
    }
    return $contador;
  }

  public function grado($vertice){
    return self::gradoSalida($vertice) + self::gradoEntrada($vertice);
  }

  public function resetNodos(){
    $nodos = self::getVertices();
    if($nodos!=null){
      foreach ($nodos as $key => $value) {
        $value->setVisitado(false);
      }
    }
  }

  public function recorrerAnchura($nodoI){
    $cola=[];
    $respuesta = "";
    if(isset($this->vertices[$nodoI])){
      array_push($cola, $nodoI);
      while (!empty($cola)) {
        $nodoActual = reset($cola);
        $id = array_search($nodoActual, $cola);
        unset($cola[$id]);
        $vertice = self::getVertice($nodoActual);
        if(($vertice->getVisitado())==false){
          $vertice->setVisitado(true);
          $respuesta = $respuesta."Nodo: ".$vertice->getId()." - ";
          $llaves = self::getAdyacentes($nodoActual)? array_keys(self::getAdyacentes($nodoActual)):null;
          $cola = ($llaves!=null)?array_merge($cola,$llaves):$cola;
        }
      }
    }else{
      return "No se encontro nodo inicio";
    }
    self::resetNodos();
    return $respuesta;
  }

  public function recorrerProfundidad($nodoI){
    $pila=[];
    $respuesta = "";
    if (isset($this->vertices[$nodoI])) {
      array_push($pila, $nodoI);
      while(!empty($pila)){
        $nodoActual = array_pop($pila);
        $vertice = self::getVertice($nodoActual);
        if($vertice->getVisitado()==false){
          $vertice->setVisitado(true);
          $respuesta = $respuesta."Nodo: ".$vertice->getId()." - ";
          $llaves = self::getAdyacentes($nodoActual)? array_keys(self::getAdyacentes($nodoActual)):null;
          $pila = ($llaves!=null)?array_merge($pila,$llaves):$pila;
        }
      }
    }else{
      return "No se encontro nodo de inicio";
    }
    self::resetNodos();
    return $respuesta;
  }

  public function caminoMasCorto($origen,$destino){
    if(empty($this->vertices[$origen]) || empty($this->vertices[$destino])){
      return "No existe nodo origen o destino ";
    }else{
      $S = array();
      $Q = array();
      foreach(array_keys($this->aristas) as $val) $Q[$val] = 99999;
        $Q[$origen] = 0;
        while(!empty($Q)){
          $min = array_search(min($Q), $Q);
          if($min == $destino) break;
          if(!empty($this->aristas[$min])) foreach($this->aristas[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
            $Q[$key] = $Q[$min] + $val;
            $S[$key] = array($min, $Q[$key]);
          }
          unset($Q[$min]);
        }
      $path = array();
      $pos = $destino;

      if(!empty($S[$destino])){
        while($pos != $origen){
          $path[] = $pos;
          $pos = $S[$pos][0];
        }
      }else{
        return "No se encontro camino de ".$origen." a ".$destino;
      }
      $path[] = $origen;

      $path = array_reverse($path);
      return $path;
    }
  }

}


?>
