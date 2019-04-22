<?php
require_once CLASES . '/AccesoDatos.php';

class AlumnoDatos 
{
    private static $ObjetoAlumnoDatos;
    private $tabla;
    private $ObjetoAccesoDatos;

    public static function GetObjectAlumnoDatos($tabla)
    {
        if (!isset(self::$ObjetoAlumnoDatos)) {          
            self::$ObjetoAlumnoDatos = new AlumnoDatos(); 
            self::$ObjetoAlumnoDatos->ObjetoAccesoDatos = AccesoDatos::GetObjectAccesoDatos();
            self::$ObjetoAlumnoDatos->tabla = $tabla;
        } 
        return self::$ObjetoAlumnoDatos;   
    }

    public function InsertAlumno($alumno, $query)
	 {
        $consulta = $this->ObjetoAccesoDatos->GetQuery($query);
        $consulta->bindValue(':Nombre',$alumno->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':Apellido', $alumno->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':Edad', $alumno->edad, PDO::PARAM_INT);
        $consulta->bindValue(':Dni', $alumno->dni, PDO::PARAM_INT);
        $consulta->bindValue(':Legajo', $alumno->legajo, PDO::PARAM_INT);  
        $consulta->bindValue(':Foto', $alumno->foto, PDO::PARAM_STR);        
        $consulta->execute();
        return $this->ObjetoAccesoDatos->GetLastInsertedId();
     }         

    public function GetAllAlumnos($query)
    {
        $consulta = $this->ObjetoAccesoDatos->GetQuery($query);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Alumno');	
    }
    
    public function GetAlumno($query) 
	{
        $consulta = $this->ObjetoAccesoDatos->GetQuery($query);
        $consulta->execute();
        return $consulta->fetchObject('Alumno');
    }

    public function UpdateAlumno($alumno, $query)
    {        
        $consulta = $this->ObjetoAccesoDatos->GetQuery($query);
        $consulta->bindValue(':Id',$alumno->id, PDO::PARAM_INT);
        $consulta->bindValue(':Nombre',$alumno->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':Apellido', $alumno->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':Edad', $alumno->edad, PDO::PARAM_INT);
        $consulta->bindValue(':Dni', $alumno->dni, PDO::PARAM_INT);
        $consulta->bindValue(':Legajo', $alumno->legajo, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public function DeleteAlumno($alumno, $query)
    {
        $consulta = $this->ObjetoAccesoDatos->GetQuery($query);         	
        $consulta->bindValue(':Id',$alumno->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }
}
?>