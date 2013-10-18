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
    
    /**
    * Verifica se é um número de CPF válido.
    *
    * @param $cpf O número a ser verificado
    * @return boolean
    */
    public function validarCPF($cpf)
    {
        // remove os caracteres não-numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // verifica se a sequência tem 11 dígitos
        if (strlen($cpf) != 11)
            return false;

        // calcula o primeiro dígito verificador 
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10-$i);
        }
        $mod = $sum % 11;
        $digit = ($mod > 1) ? (11 - $mod) : 0;

        // verifica se o primeiro dígito verificador está correto
        if ($cpf[9] != $digit)
            return false;

        // calcula o segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11-$i);
        }
        $mod = $sum % 11;
        $digit = ($mod > 1) ? (11 - $mod) : 0;

        // verifica se o segundo dígito verificador está correto
        if ($cpf[10] != $digit)
            return false;

        // Repetir 11 vezes o mesmo número não é permitido, pois não existem CPFs com esta formação numérica.
        if (str_repeat($cpf[0],11) == $cpf) {
            return false;
        }
        // está tudo certo
        return true;
    }
   
    public function validarCNPJ($cnpj)
    {
        $b = array(6,5,4,3,2,9,8,7,6,5,4,3,2);
        
        if(strlen($cnpj = preg_replace("/[^\d]/", "", $cnpj)) != 14)
            return false;
        
        for ($i = 0, $n = 0; $i < 12; $n += $cnpj[$i] * $b[++$i])
        {
            if($cnpj[12] != ((($n %= 11) < 2) ? 0 : 11 - $n))
                return false;
        }
        for ($i = 0, $n = 0; $i <= 12; $n += $cnpj[$i] * $b[$i++])
        {
            if($cnpj[13] != ((($n %= 11) < 2) ? 0 : 11 - $n))
                return false;
        }
        return true; 
    }
    
    public function trocaDecimalModel2View($num)
    {
        return str_replace(".", ",", $num);
    }
    
    public function trocaDecimalView2Model($num)
    {
        return str_replace(",", ".", $num);
    }
}
?>