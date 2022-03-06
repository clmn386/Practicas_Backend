<?php
namespace App;

class Propiedad {
    //Base de datos
    protected static $db; 
    protected static $columnasBD = [ 'id','titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamientos', 'creado', 'vendedorId' ]; //creo el arreglo de columnas para poder iterarlo y por mapear el objeto.

    //Errores o validacion
    protected static $errores = [];


    //FORMA DE DECLARAR ATRIBUTOS ANTES DE PHP-v8
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
    
    //definir la conexion a la base de datos
    public static function setBD($database) {
        self::$db = $database;
    }
    //Todos estos datos se mapean con $columnaBD arriba.
    public function __construct($args = []){
        $this->id = $args['id'] ?? NULL;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? ''; // modificacion se pasa valos proviconal de imagen, pendiente sanitizar.
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamientos = $args['estacionamientos'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public function guardar(){
        //Sanitizar datos
        $atributos = $this->sanitizar(); // aqui tenemos la referencia en atributos

        //Insertar en la BD
        $query = " INSERT INTO propiedades ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        //Hacemos consulta a la BD para subir archivos.
        $resultado = self::$db->query($query);
        return $resultado;
    }
    //Identificar y unir los atributos de la base de datos
    public function atributos(){ 
        $atributos = [];
        foreach(self::$columnasBD as $columna ) {
            if($columna === 'id') continue; // para ignarar 'id'.
            //$atributos: va ir formateando y le da forma que tenemos en las columnas y hacemos referencia a el objeto memoria y de esta forma se crea un nuevo arreglo
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizar(){ //al tener los atributos se puede sanitizar antes de pasarlo a la BD.
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value){ //arreglo asociativo
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return($sanitizado);
    }
    //validacion
    public static function getErrores(){
        return self::$errores;
    }

    public function validar(){

        if(!$this->titulo){
            self::$errores[] = 'falta colocar titutlo';
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
        if(self::FormatoImagen()===false){
            self::$errores[] = 'falta imagen o tiene error de formato subido';
        }
        return self::$errores;
    }

    // Funcion validacion formato de imagen
    public static function FormatoImagen(){ // Manera rudimentaria de comprobar tanto el formato correcto o envia mensaje de error en funcion validar().
        if($_FILES['imagen']['type']==='image/png'){
            return '.png'; 
        }elseif($_FILES['imagen']['type']==='image/jpeg'){
            return '.jpeg'; 
        }else{
            return false;
        }
    }

    public function setImagen($imagen) {
        // asignar al atributo imagen el nombre generado para carpeta
        if($imagen) {
            $this->imagen = $imagen;
        }
    }

}