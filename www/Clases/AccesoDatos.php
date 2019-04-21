<?php
class AccesoDatos
{
    private static $ObjetoAccesoDatos;
    private $objetoPDO;
 
    private function __construct()
    {
        //try { 
            $this->objetoPDO = new PDO('mysql:host=localhost;dbname=utn_prog_III;charset=utf8', 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        // } 
        // catch (PDOException $e) { 
        //     print "Error en ctor AD!: " . $e->getMessage(); 
        //     die();
        // }
    }
 
    public function GetQuery($sql)
    { 
        return $this->objetoPDO->prepare($sql); 
    }
     public function GetLastInsertedId()
    { 
        return $this->objetoPDO->lastInsertId(); 
    }
 
    public static function GetObjectAccesoDatos()
    { 
        if (!isset(self::$ObjetoAccesoDatos)) {          
            self::$ObjetoAccesoDatos = new AccesoDatos(); 
        } 
        return self::$ObjetoAccesoDatos;        
    }
 
 
     // Evita que el objeto se pueda clonar
    public function __clone()
    { 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }
}
?>