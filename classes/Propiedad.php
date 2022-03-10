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
        $this->vendedorId = $args['vendedorId'] ?? 1; // necesario de momento antes de crear la clase vendedor.
    }

    public function guardar(){
        if(isset($this->id)){
            //actualizar
            $this->actualizar();
        } else {
            //creando una nueva clase
            $this->crear();
        }
    }
    public function crear(){
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

    public function actualizar(){
        //sanitizamos los datos
        $atributos = $this->sanitizar();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE propiedades SET ";
        $query.= join(', ', $valores);
        $query.= " WHERE id = '" .self::$db->escape_string($this->id). "' ";
        $query.= " LIMIT 1 ";

        $resultado = self::$db->query($query);
        
        if($resultado){
            //redireccionar a la usuario
            header('Location: /admin?resultado=2');
        }
    }


    //Identificar y unir los atributos de la base de datos
    public function atributos(){ 
        $atributos = [];
        foreach(self::$columnasBD as $columna ) {
            if($columna === 'id') continue; // para ignorar 'id'.
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

    // Funcion validacion formato de imagen
    public static function FormatoImagen(){ // Manera rudimentaria de comprobar tanto el formato correcto o envia mensaje de error en funcion validar().

        if($_FILES['propiedad']['type']['imagen']==='image/png'){
            return '.png'; 
        }elseif($_FILES['propiedad']['type']['imagen']==='image/jpeg'){
            return '.jpeg'; 
        }else{
            return false;
        }
    }

    public function setImagen($imagen) {
        // Eliminar imagen previa
        if ($this->imagen) {
            $existeArchivo = file_exists(CARPETAS_IMAGENES . $this->imagen);

            if($existeArchivo) {
                unlink(CARPETAS_IMAGENES . $this->imagen);
            }
        }
        // asignar al atributo imagen el nombre generado para carpeta
        if($imagen) {
            $this->imagen = $imagen;
        }
    }

    //Lista todas las propiedades
    public static function all(){
    
    $query = "SELECT * FROM propiedades";
    $resultado = self::consultarSQL($query);
    return $resultado;
    }
    //busca un registro por el Id
    public static function find($id) {
    $query = "SELECT * FROM propiedades WHERE id = ${id}";
    $resultado = self::consultarSQL($query);
    return array_shift($resultado);
    }

    public static function consultarSQL($query){
    //consultar la base de datos
    $resultado = self::$db->query($query);
    //iterar la base de datos
    $array =[];
    while($registro = $resultado->fetch_assoc()) {
        $array[] = self::crearObjeto($registro);
        }
        //liberar la memoria
    $resultado->free();
        //retornar los resultados
    return $array;
        
    }
    public static function crearObjeto($registro){
    //creamos un nuevo objeto de la clase actual
    $objeto = new self; 
        foreach($registro as $key => $value)
            if(property_exists( $objeto, $key )){
            $objeto->$key = $value;
        } 

        return $objeto;
    }

    //Sincronizar
     public function sincronizar( $args = [] ) {
        foreach ($args as $key => $value) {
            if(property_exists( $this, $key ) && !is_null($value)){
                $this->$key = $value;
            }
        }
     }

}