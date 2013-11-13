<?php

/**
 * Model para relatórios em geral
 */
class Relatorio
{
    public function relatorioLancamentoEstabelecimento($dataInicio, $dataFim, $idEstabelecimento)
    {
        $modelLancamento = new Lancamento();
        $modelEstabelecimento = Estabelecimento::model()->findByPk($idEstabelecimento);
        
        if(empty($modelEstabelecimento)) { return "Erro ao localizar estabelecimento"; }
        
        $lancamentos = $modelLancamento->getLancamentos($dataInicio, $dataFim, $idEstabelecimento);
             
        $s  = "";
        $s .= "Lan&ccedil;amentos para o estabelecimento ".htmlentities($modelEstabelecimento->nm_estabelecimento);
        $s .= "<table>
                <tr>
                    <td>Data Lan&ccedil;amento</td>
                    <td>Categoria</td>
                    <td>Valor</td>
            ";
        
        foreach($lancamentos as $lancamento)
        {            
            $s .="<tr>
                    <td>".$lancamento->dt_lancamento."</td>
                    <td>".$lancamento->idCategoriaLancamento->nm_categoriaLancamento."</td>
                    <td>".$lancamento->vl_lancamento."</td>
                </tr>";
        }
                
        $s .= "</table>";
        
        return $s;
    }
    
}