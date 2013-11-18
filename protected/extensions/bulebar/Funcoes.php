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
    
    public function trocaTimestampViewParaModel($data)
    {
        $timestamp = CDateTimeParser::parse($data,'dd/MM/yyyy hh:mm:ss');
        
        if($timestamp)
        {
            return date('Y-m-d h:i:s', $timestamp);
        }
        return $timestamp;
    }
    
    public function trocaTimestampModelParaView($data)
    {
        $timestamp = CDateTimeParser::parse($data,'yyyy-MM-dd hh:mm:ss');
        
        if($timestamp)
        {
            return date('d/m/Y h:i:s', $timestamp);
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
    
    public function criptografaSenha($senha)
    {
        return md5($senha);
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
   
    /**
     * Verifica se o CNPJ é válido
     * @param string $cnpj cnpj a ser validado
     * @return boolean
     */
    public function validarCNPJ($cnpj)
    {
        //Etapa 1: Cria um array com apenas os digitos numéricos, isso permite receber o cnpj em diferentes formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
        $j=0;
        for($i=0; $i<(strlen($cnpj)); $i++)
        {
            if(is_numeric($cnpj[$i]))
            {
                $num[$j]=$cnpj[$i];
                $j++;
            }
        }
        //Etapa 2: Conta os dígitos, um Cnpj válido possui 14 dígitos numéricos.
        if(count($num)!=14)
        {
            $isCnpjValid=false;
        }
        //Etapa 3: O número 00000000000 embora não seja um cnpj real resultaria um cnpj válido após o calculo dos dígitos verificares e por isso precisa ser filtradas nesta etapa.
        if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
        {
            $isCnpjValid=false;
        }
        //Etapa 4: Calcula e compara o primeiro dígito verificador.
        else
        {
            $j=5;
            for($i=0; $i<4; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j=9;
            for($i=4; $i<12; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }
            $soma = array_sum($multiplica);	
            $resto = $soma%11;			
            if($resto<2)
            {
                $dg=0;
            }
            else
            {
                $dg=11-$resto;
            }
            if($dg!=$num[12])
            {
                $isCnpjValid=false;
            } 
        }
        
        //Etapa 5: Calcula e compara o segundo dígito verificador.
        if(!isset($isCnpjValid))
        {
            $j=6;
            for($i=0; $i<5; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j=9;
            for($i=5; $i<13; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }
            $soma = array_sum($multiplica);	
            $resto = $soma%11;			
            if($resto<2)
            {
                $dg=0;
            }
            else
            {
                $dg=11-$resto;
            }
            if($dg!=$num[13])
            {
                $isCnpjValid=false;
            }
            else
            {
                $isCnpjValid=true;
            }
        }

        //Etapa 6: Retorna o Resultado em um valor booleano.
        return $isCnpjValid;
    }
    
    public function trocaDecimalModelParaView($num)
    {
        return Yii::app()->numberFormatter->format('###,###,###,##0.00',$num);
    }
    
    public function trocaDecimalViewParaModel($num)
    {
        if(strrpos($num,','))
        {
            $num = str_replace('.', '', $num);
            $num = str_replace(',', '.', $num);
        }
        
        return $num;
    }
}
?>