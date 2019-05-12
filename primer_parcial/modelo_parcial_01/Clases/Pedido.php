<?php
class Pedido{
    public $id;
    public $producto;
    public $cantidad;

    public static function GetPedido($id, $producto, $cantidad) {
        $pedido = new Pedido();
        if (isset($producto)) {
            $pedido->producto = strtolower($producto);
        } 
        if (isset($id)) {
            $pedido->id = $id;
        } 
        if (isset($cantidad)) {
            $pedido->cantidad = $cantidad;
        }         
        return $pedido;
    }

    public function CreateProperty($name, $value){
        $this->{$name} = $value;
    }

    public static function StdClassToPedido($stdClass)
    {
        $pedido = new Pedido();
        $propertiesArray = get_object_vars($stdClass);                         
        foreach ($propertiesArray as $nombre => $valor) {
            if(isset($valor))
                $pedido->CreateProperty($nombre, $valor);
        }
        return $pedido;
    }

    //--------------------- TXT FILE

    public static function InsertPedidoInTxtFile($fileTxtPedidos, $pedido, $fileProv)
    {        
        $result = null;
        $arrayTxtInFile = Pedido::TextToPedidosArray($fileTxtPedidos,';');
        
        if(!is_null($arrayTxtInFile) && count($arrayTxtInFile) > 0)
        { 
            $existeProv = Proveedor::GetProveedorByIdFromTxt($pedido->id, $fileProv);
            if($existeProv!=null)
            {
                array_push($arrayTxtInFile, $pedido);
            } 
            $result = Pedido::PedidosArrayToTxtFile($fileTxtPedidos, $arrayTxtInFile);
        }
        else
        {
            var_dump($pedido->id);
            $existeProv = Proveedor::GetProveedorByIdFromTxt($pedido->id, $fileProv);
            if($existeProv!=null)
            {                
                $result = Pedido::PedidosArrayToTxtFile($fileTxtPedidos, array($pedido));
            }            
        }        
        return $result;       
    }

    public static function TextToPedidosArray($txtFile, $separador)
    {
        $arrayPedidos = array();
        $arrayTxt = $txtFile->TextToArray();
        foreach($arrayTxt as $row)
        {
            $dataArray = explode($separador,$row);
            if(strtolower(trim($dataArray[0])) == 'id')
            {
                continue;
            }
            else
            {
                $idAux = trim($dataArray[0]);
                $productoAux = trim($dataArray[1]);
                $cantidadAux = trim($dataArray[2]);                
                $fotoAux = trim($dataArray[3]);
                $pedido = Pedido::GetPedido($idAux, $productoAux, $cantidadAux, $fotoAux);
                array_push($arrayPedidos, $pedido);
            }
        }        
        return $arrayPedidos;
    }

    public function GetPedidoById($array)
    {
        $returnAux = null;
        foreach($array as $pedido)
        {
            if($pedido->id == $this->id)
            {
                $returnAux = $pedido;
                break;
            }
        }
        return $returnAux;
    }

    public static function GetPedidoByNombreFromTxt($producto, $txtFile)
    {
        $returnAux = null;
        $array = Pedido::TextToPedidosArray($txtFile, ';');

        foreach($array as $pedido)
        {
            if($pedido->producto == $producto)
            {
                $returnAux = $pedido;
                break;
            }
        }
        if($returnAux!=null){
            
            $returnAux = $pedido;
        }                
        return $returnAux;
    }

    public static function GetPedidosByIdFromTxt($id, $txtFile)
    {        
        $returnAux = null;
        $array = Pedido::TextToPedidosArray($txtFile, ';');
        $arrayPedidos = array();
        foreach($array as $pedido)
        {
            if($pedido->id == $id)
            {
                array_push($arrayPedidos, $pedido);
            }
        }              
        return $arrayPedidos;
    }    

    public function PedidosArrayToTxtFile($fileTxtPedidos, $array)
    {
        $string = 'Id;Nombre;Email;Foto'.PHP_EOL;
        $arrayInserts = array();
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        $result = $fileTxtPedidos->WriteInTxtFile($string);
        return $result;
    }

    private function ToString()
    {
        return "{$this->id};{$this->producto};{$this->cantidad};" . PHP_EOL;
    } 

    public function ShowPedido($prov)
    {
        $format = sprintf("%-15s;%-8s;%-5s;%-20s;" . PHP_EOL,
            $this->producto,$this->cantidad,$this->id,$prov->nombre);
        return $format;
    }

    public function ShowPedidoProps()
    {
        $format = sprintf("%-15s;%-8s;%-5s;" . PHP_EOL,
            $this->producto,$this->cantidad,$this->id);
        return $format;
    }

    public function ShowPedidoWithHeader()
    {        
        $format = sprintf("%-15s;%-8s;%-5s;%-20s;" . PHP_EOL,
            "Producto","Cantidad","Id","Proveedor");
        echo $format;
        echo $this->ShowPedido();
    } 

    public static function ShowTextList($path, $txtFile)
    {
        $format = sprintf("%-15s;%-8s;%-5s;%-20s;" . PHP_EOL,
        "Producto","Cantidad","Id","Proveedor");
        echo $format;

        $array = Pedido::TextToPedidosArray($path, ';');
        
        if($array != null)
        {
            foreach($array as $pedido)
            {
                $prov = Proveedor::GetProveedorByIdFromTxt($pedido->id, $txtFile);
                echo $pedido->ShowPedido($prov);
            }
        }
        else
        {
            echo "Archivo vacío." . PHP_EOL;
        }
    }

    public static function ShowPedidoProveedor($fileTxtPedido, $id){
        $pedidos = Pedido::GetPedidosByIdFromTxt($id, $fileTxtPedido);
        $format = sprintf("%-15s;%-8s;%-5s;" . PHP_EOL,
            "Producto","Cantidad","Id");
        echo $format;
        foreach($pedidos as $pedido){
            echo $pedido->ShowPedidoProps();
        }
    }
}
?>