<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="hero-unit">
  <div class="thumbnail span3" style="margin-right: 20px;">
    <img src="<?php echo Yii::app()->request->baseUrl . '/images/logo_bulebar_200px.png' ?>" alt="logo"/>
  </div>
  <h1>Controle Financeiro</h1>
  <p>@TODO: Inserir gráficos, avisos, etc. </p>
  
  
<?php

echo "Gastos/Estabelecimento";

$estabelecimentos = Estabelecimento::model()->findAll();

$labels = null;
$data = null;

foreach($estabelecimentos as $estabelecimento)
{
    $labels[] = $estabelecimento->nm_estabelecimento;
    
    $lancamentos = Lancamento::model()->getLancamentosSumarizado(null,null,$estabelecimento->id_estabelecimento,null,'D');
    
    $data[] = empty($lancamentos) ? 0 : $lancamentos[0]->soma * -1;
}

$this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 600,
                'height' => 300,
                'htmlOptions' => array(),
                'labels' => $labels,
                'datasets' => array(
                    array(
                        "fillColor" => "rgba(151,187,205,0.5)",
                        "strokeColor" => "rgba(151,187,205,1)",
                        "data" => $data
                        
                    )       
                ),
                'options' => array(
                        "scaleOverride" => true,
                        "scaleSteps" => 20,
                        "scaleStepWidth"=> ceil(max($data) / 20),
                        "scaleStartValue" => min($data)-(min($data) * 0.9)
                )
            )
        ); 
    ?>
  
  
<?php

echo "<hr>";

echo "Receita/Estabelecimento<br>";

$labels = null;
$data = null;

foreach($estabelecimentos as $estabelecimento)
{
    $labels[] = $estabelecimento->nm_estabelecimento;
    
    $lancamentos = Lancamento::model()->getLancamentosSumarizado(null,null,$estabelecimento->id_estabelecimento,null,'R');
    
    $data[] = empty($lancamentos) ? 0 : $lancamentos[0]->soma;
}

$this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 600,
                'height' => 300,
                'htmlOptions' => array(),
                'labels' => $labels,
                'datasets' => array(
                    array(
                        "fillColor" => "rgba(151,187,205,0.5)",
                        "strokeColor" => "rgba(151,187,205,1)",
                        "data" => $data
                        
                    )       
                ),
                'options' => array(
                        "scaleOverride" => true,
                        "scaleSteps" => 20,
                        "scaleStepWidth"=> ceil(max($data) / 20),
                        "scaleStartValue" => min($data)-(min($data) * 0.9)
                )
            )
        ); 
    ?>
  
<?php

echo "<hr>";

echo "Custo/Retorno<br>";

$labels = null;
$data = null;

foreach($estabelecimentos as $estabelecimento)
{   
    $lancamentos = Lancamento::model()->getLancamentosSumarizado(null,null,$estabelecimento->id_estabelecimento,null,'R');    
    $receita = empty($lancamentos) ? 0 : $lancamentos[0]->soma;
    $lancamentos = Lancamento::model()->getLancamentosSumarizado(null,null,$estabelecimento->id_estabelecimento,null,'D');
    $despesa = empty($lancamentos) ? 0 : $lancamentos[0]->soma * -1;
    
    echo $estabelecimento->nm_estabelecimento;
    echo "<br>";
    $this->widget(
        'chartjs.widgets.ChPie', 
        array(
            'width' => 600,
            'height' => 300,
            'htmlOptions' => array(),
            'drawLabels' => true,
            'datasets' => array(
                array(
                    "value" => round($receita),
                    "label" => "Rceita: ".Yii::app()->bulebar->trocaDecimalModelParaView($receita),
                    "color" => "#06D422",
                ),
                array(
                    "value" => round($despesa),
                    "label" => "Despesa: ".Yii::app()->bulebar->trocaDecimalModelParaView($despesa),
                    "color" => "#D40622",
                ),
            ),
            'options' => array()
        )
    );
    
} ?>  
</div>
