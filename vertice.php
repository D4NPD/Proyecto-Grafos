<?php

class Vertice{
    private $id;
    private $visitado;

    public function __construct($i){
      $this->id = $i;
      $this->visitado = false;
    }

    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }

    public function getVisitado(){
      return $this->visitado;
    }

    public function setVisitado($visitado){
      $this->visitado = $visitado;
    }

}

?>
