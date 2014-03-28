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
        
        $receitas = $modelLancamento->getLancamentosSumarizado($dataInicio, $dataFim, $idEstabelecimento, null, 'R', 'idFormaPagamento.id_formaPagamento');
             
        $s  = "";
        $s .= "<h2>Lan&ccedil;amentos para o estabelecimento ".htmlentities($modelEstabelecimento->nm_estabelecimento)."</h2>";
        $s .= "<table class='table table-striped'>
                <tr>
                    <td rowspan='".(count($receitas)+2)."' style='vertical-align:middle'>Receitas</td>
            ";
        
        $subtotal = 0;
        
        foreach($receitas as $receita)
        {
            $subtotal += $receita->soma;
            
            $s .="<tr>
                    <td>".$receita->idFormaPagamento->nm_formaPagamento."</td>
                    <td>".$receita->soma."</td>
                </tr>";
        }
        
        $s .= "<td><b>Subtotal</b></td><td><b>$subtotal</b></td>";
        $s .= "</tr>";
        $s .= "</table>";
        
        $despesas = $modelLancamento->getLancamentosSumarizado($dataInicio, $dataFim, $idEstabelecimento, null, 'D');
             
        $s .= "<br>";
        $s .= "<table class='table table-striped'>
                <tr>
                    <td rowspan='".(count($receitas)+2)."' style='vertical-align:middle'>Despesas</td>
            ";
        
        $subtotal = 0;
        
        foreach($despesas as $despesa)
        {
            $subtotal += $despesa->soma;
            
            $s .="<tr>
                    <td>".$despesa->idCategoriaLancamento->nm_categoriaLancamento."</td>
                    <td>".$despesa->soma."</td>
                </tr>";
        }
        
        $s .= "<td><b>Subtotal</b></td><td><b>$subtotal</b></td>";
        $s .= "</tr>";
        $s .= "</table>";
        
        $resultados = $modelLancamento->getLancamentosSumarizado($dataInicio, $dataFim, $idEstabelecimento, null, null, 'idFormaPagamento.id_formaPagamento');
             
        $s .= "<br>";
        $s .= "<table class='table table-striped'>
                <tr>
                    <td rowspan='".(count($receitas)+2)."' style='vertical-align:middle'>Resultado</td>
            ";
        
        $subtotal = 0;
        
        foreach($resultados as $resultado)
        {
            $subtotal += $resultado->soma;
            
            $s .="<tr>
                    <td>".$resultado->idFormaPagamento->nm_formaPagamento."</td>
                    <td>".$resultado->soma."</td>
                </tr>";
        }
        
        $s .= "</tr>";
        $s .= "</table>";
        
        $s .= "<table>";
        $s .= "<tr>";
        $s .= "<td><b>Lucro Total</b></td><td><b>$subtotal</b></td>";
        $s .= "</tr>";
        $s .= "</table>";
        
        return $s;
    }
    
}