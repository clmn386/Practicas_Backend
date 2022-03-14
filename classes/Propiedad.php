<?php
namespace App;

class Propiedad extends ActiveRecord {
   
    protected static $tabla = "propiedades";

    protected static $columnasBD = [ 'id','titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamientos', 'creado', 'vendedorId' ];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamientos;
    public $creado;
    public $vendedorId;

    //Todos estos datos se mapean con $columnaBD en ActiveRecord.
    public function __construct($args = []){
        $this->id = $args['id'] ?? NULL;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? ''; 
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamientos = $args['estacionamientos'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public function validar($ignore_img=false){

        if(!$this->titulo){
            self::$errores[] = 'falta colocar titulo';
        }
 
        if(!$this->precio){            
            self::$errores[] = 'falta colocar precio';
        }
        
        if( strlen($this->descripcion) < 50){
            self::$errores[] = 'necesario mas de 50 caracteres en descripcion obligatoria';
        }

        if(!$this->habitaciones){            
            self::$errores[] = 'faltan numero de habitaciones';
        }

        if(!$this->wc){            
            self::$errores[] = 'faltan numero de baños';
        }
    
        if(!$this->estacionamientos){            
            self::$errores[] = 'faltan numero de estacionamiento';
        }
                
        if(!$this->vendedorId){            
            self::$errores[] = 'elige un vendedor';
        }
        //valida falta de imagen o formato erroneo.
        if(self::FormatoImagen()===false && !$ignore_img){
            self::$errores[] = 'falta imagen o tiene error de formato subido';
        } 
       
        return self::$errores;
    }

}