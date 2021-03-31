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

}


?>
