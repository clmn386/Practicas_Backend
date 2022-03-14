<?php 

namespace App;

class ActiveRecord {
         //Base de datos
    protected static $db; 
    protected static $columnasBD = []; //creo el arreglo de columnas para poder iterarlo y por mapear el objeto.
    protected static $tabla = '';
    //Errores o validacion
    protected static $errores = [];
     
    //definir la conexion a la base de datos
    public static function setBD($database) {
        self::$db = $database;
    }

    public function guardar(){
        if(!is_null($this->id)){
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
        $query = " INSERT INTO ". static::$tabla ." ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        //Hacemos consulta a la BD para subir archivos.
        $resultado = self::$db->query($query);
        //Redireccionar al usuario.
        if($resultado){
            header('Location: /admin?resultado=1');
        }
    }

    public function actualizar(){
        //sanitizamos los datos
        $atributos = $this->sanitizar();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE ". static::$tabla ." SET ";
        $query.= join(', ', $valores);
        $query.= " WHERE id = '" .self::$db->escape_string($this->id). "' ";
        $query.= " LIMIT 1 ";

        $resultado = self::$db->query($query);
        
        if($resultado){
            //redireccionar a la usuario
            header('Location: /admin?resultado=2');
        }
    }
    //Eliminar un registro
    public function eliminar() {
        //Elimina propiedad
        $query = "DELETE FROM ". static::$tabla ." WHERE id = ".self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        
        if($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    //Identificar y unir los atributos de la base de datos
    public function atributos(){ 
        $atributos = [];
        foreach(static::$columnasBD as $columna ) {
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
        return static::$errores;
    }

    public function validar($ignore_img=false){
        static::$errores = [];
        return static::$errores;

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
        if (is_null($this->id)) {
            $this->borrarImagen();
        }
        // asignar al atributo imagen el nombre generado para carpeta
        if($imagen) {
            $this->imagen = $imagen;
        }
    }
    
    //Eliminar archivo
    public function borrarImagen() {
        $existeArchivo = file_exists(CARPETAS_IMAGENES . $this->imagen);

        if($existeArchivo) {
            unlink(CARPETAS_IMAGENES . $this->imagen);
        }
    }

    //Lista todas las propiedades
    public static function all(){
    
    $query = "SELECT * FROM ". static::$tabla;

    $resultado = self::consultarSQL($query);
    return $resultado;
    }
    //busca un registro por el Id
    public static function find($id) {
    $query = "SELECT * FROM ". static::$tabla ." WHERE id = ${id}";
    $resultado = self::consultarSQL($query);
    return array_shift($resultado);
    }

    public static function consultarSQL($query){
    //consultar la base de datos
    $resultado = self::$db->query($query);
    //iterar la base de datos
    $array =[];
    while($registro = $resultado->fetch_assoc()) {
        $array[] = static::crearObjeto($registro);
        }
        //liberar la memoria
    $resultado->free();
        //retornar los resultados
    return $array;
        
    }
    public static function crearObjeto($registro){
    //creamos un nuevo objeto de la clase desde el constructor en su archivo dentro de la carpeta classes
    $objeto = new static; 
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