<?php
class Pedido
{
    public $id;
    public $idProveedor;
    public $producto;
    public $cantidad;

    public static function GetPedido($id, $idProveedor, $producto, $cantidad) 
    {
        $pedido = new Pedido();
        if (isset($id)) {
            $pedido->id = $id;
        }
        if (isset($idProveedor)) {
            $pedido->idProveedor = $idProveedor;
        }
        if (isset($producto)) {
            $pedido->producto = strtolower($producto);
        }          
        if (isset($cantidad)) {
            $pedido->cantidad = $cantidad;
        }         
        return $pedido;
    }

    public static function GetLastIdInArray($array)
    {
        return $array[count($array) - 1]->id;
    }

    public static function AssignId($array)
    {
        $id = 1;
        if(!is_null($array) && count($array) > 0) 
        {   
            $lastId = Pedido::GetLastIdInArray($array);
            $id = $lastId + 1;                                                                                                                                                                                                                     
        } 
        return $id;
    }

    public static function InsertPedido($fileTxtPedidos, $fileTxtProveedores, $idProveedor, $producto, $cantidad)
    {
        $result = "No existe proveedor con id " . $idProveedor . PHP_EOL;
        $existeProv = Proveedor::GetProveedorByIdFromTxt($idProveedor, $fileTxtProveedores);
        if(!is_null($existeProv))
        {
            $pedidos = Pedido::TextToPedidosArray($fileTxtPedidos,';');
            $id = Pedido::AssignId($pedidos);
            $pedido = Pedido::GetPedido($id, $idProveedor, $producto, $cantidad);                                           
            array_push($pedidos, $pedido);
            Pedido::PedidosArrayToTxtFile($fileTxtPedidos, $pedidos) != false ?
                $result = "Se cargó pedido." . PHP_EOL :
                $result = "Error al cargar.".PHP_EOL; 
        }
        return $result;
    }

    public static function TextToPedidosArray($txtFile, $separador)
    {
        $arrayPedidos = array();
        $arrayTxt = $txtFile->TextToArray();
        if(!is_null($arrayTxt) && count($arrayTxt) > 0)
        {
            foreach($arrayTxt as $row)
            {
                $dataArray = explode($separador, $row);
                if(strtolower(trim($dataArray[0])) == 'id')
                {
                    continue;
                }
                else
                {
                    $id = trim($dataArray[0]);
                    $idProveedor = trim($dataArray[1]);
                    $producto = trim($dataArray[2]);
                    $cantidad = trim($dataArray[3]);                
                    $pedido = Pedido::GetPedido($id, $idProveedor, $producto, $cantidad);
                    array_push($arrayPedidos, $pedido);
                }
            }
        }                
        return $arrayPedidos;
    }

    public static function GetPedidosByIdProveedorFromTxt($idProveedor, $txtFile)
    {    
        $pedidosProveedor = array();    
        $pedidos = Pedido::TextToPedidosArray($txtFile, ';');
        if(!is_null($pedidos) && count($pedidos) > 0)
        {            
            foreach($pedidos as $pedido)
            {
                if($pedido->idProveedor == $idProveedor)                
                    array_push($pedidosProveedor, $pedido);                
            }
        }                      
        return $pedidosProveedor;
    }    

    public function PedidosArrayToTxtFile($fileTxtPedidos, $array)
    {
        $string = 'Id;Id Proveedor;Producto;Cantidad;'.PHP_EOL;
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
        return "{$this->id};{$this->idProveedor};{$this->producto};{$this->cantidad};" . PHP_EOL;
    }
    
    public static function GetHeader()
    {
        $format = sprintf("|%-15s|%-10s|%-15s|%-30s|" . PHP_EOL,
            "Producto","Cantidad","Id Proveedor","Proveedor");
        return $format;
    }

    public function ShowPedido($prov)
    {
        $format = sprintf("|%-15s|%-10s|%-15s|%-30s|" . PHP_EOL,
            $this->producto,$this->cantidad,$this->idProveedor,$prov->nombre);
        return $format;
    }

    public function ShowPedidoWithHeader()
    {
        $header = Pedido::GetHeader();
        $pedido = $this->ShowPedido();
        return $header . $pedido;
    } 

    public static function ShowPedidos($txtPedidos, $txtProveedores)
    {   
        $result = "Archivo vacío." . PHP_EOL;     
        $array = Pedido::TextToPedidosArray($txtPedidos, ';');        
        if(!is_null($array) && count($array) > 0)
        {
            $result = Pedido::GetHeader();
            foreach($array as $pedido)
            {
                $prov = Proveedor::GetProveedorByIdFromTxt($pedido->idProveedor, $txtProveedores);
                $result .= $pedido->ShowPedido($prov);
            }
        }
        return $result;
    }

    public static function ShowPedidosProveedor($fileTxtPedido, $proveedor)
    {
        $result = "El proveedor no tiene productos cargados".PHP_EOL;
        $pedidos = Pedido::GetPedidosByIdProveedorFromTxt($proveedor->id, $fileTxtPedido);
        if(!is_null($pedidos) && count($pedidos) > 0)
        {
            $result = Pedido::GetHeader();
            foreach($pedidos as $pedido)
            {
                $result .= $pedido->ShowPedido($proveedor);
            }
        }        
        return $result;
    }
}
?>