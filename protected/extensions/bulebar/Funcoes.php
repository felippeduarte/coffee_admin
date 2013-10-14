<?php

class Funcoes extends CApplicationComponent
{
    public function trocaDataViewParaModel($data)
    {
        $timestamp = CDateTimeParser::parse($data,'dd/MM/yyyy');
        
        if($timestamp)
        {
            return date('Y-m-d', $timestamp);
        }
        return $timestamp;
    }
    
    public function trocaDataModelParaView($data)
    {
        $timestamp = CDateTimeParser::parse($data,'yyyy-MM-dd');
        
        if($timestamp)
        {
            return date('d/m/Y', $timestamp);
        }
        return $timestamp;
    }
    
    public function validaData($data,$formato='dd/MM/yyyy')
    {
        return CDateTimeParser::parse($data,$formato) ? true:false;
    }
    
    public function removeMascaraApenasNumeros($str)
    {
        return preg_replace("/[^0-9]/","",$str);
    }
    
    public function adicionaMascaraCPF($str)
    {
        return $this->adicionaMascaraString($str, '###.###.###-##');
    }
    
    public function adicionaMascaraCNPJ($str)
    {
        return $this->adicionaMascaraString($str, '##.###.###/####-##');
    }
    
    public function adicionaMascaraIdentificador($str)
    {
        return strlen($str) == 11 ? $this->adicionaMascaraCPF($str) : $this->adicionaMascaraCNPJ($str);
    }
    
    public function adicionaMascaraString($str, $mascara)
    {
        $str = str_replace(" ","",$str);
        for($i=0;$i<strlen($str);$i++)
        {
           $mascara[strpos($mascara,"#")] = $str[$i];
        }
        
        return $mascara;
    }
    
    public function adicionaMascaraTaxaPercentual($taxa)
    {
        $this->adicionaMascaraString($taxa, '##,##');
    }
    
    public function criptografaSenha($senha)
    {
        return md5($senha);
    }
    
    public function getTipoCategoria($abreviacao)
    {
        if ($abreviacao == 'D') return "Despesa";
        else if ($abreviacao == 'R') return "Receita";
    }
}

?>