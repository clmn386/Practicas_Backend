<?php
namespace App;

class Vendedor extends ActiveRecord {
   
    protected static $tabla = "vendedores";

    protected static $columnasBD = [ 'id','nombre', 'apellido', 'telefono' ];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    
    //Todos estos datos se mapean con $columnaBD en ActiveRecord.
    public function __construct($args = []){
        $this->id = $args['id'] ?? NULL;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? ''; 
    }
    public function validar($ignore_img=false){

        if(!$this->nombre){
            self::$errores[] = 'falta colocar nombre';
        }if(!$this->apellido){
            self::$errores[] = 'falta colocar apellido';
        }if(!$this->telefono){
            self::$errores[] = 'falta colocar telefono';
        }
        if(!preg_match('/(6|7)[ -]*([0-9][ -]*){8}/', $this->telefono)){
            self::$errores[] = 'telefono - inicia con (6 o 7) seguido de 8 digitos';
        }
        return self::$errores;
    }
}

