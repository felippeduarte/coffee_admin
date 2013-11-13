<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Relatórios';
$this->breadcrumbs=array(
	'Relatórios',
);
?>

<h1>Relatórios</h1>


<div class="form">
    <div style="width: auto; position: absolute;">
    <?php 
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'list',
        'items' => array(
                array('label'=>'Lançamentos/Estabelecimento', 'url'=>Yii::app()->createUrl('relatorio/lancamentoestabelecimento'), 'itemOptions'=>$itemOptions['LancamentoEstabelecimento']),
                array('label'=>'Lançamentos/Fornecedor', 'url'=>Yii::app()->createUrl('relatorio/lancamentofornecedor'), 'itemOptions'=>$itemOptions['LancamentoFornecedor']),
                array('label'=>'Folha de Pagamento/Mês', 'url'=>Yii::app()->createUrl('relatorio/folhapagamentomensal'), 'itemOptions'=>$itemOptions['FolhaPagamentoMensal']),
        )
    ));
    ?>
    </div>
    <div style="left:250px; position: relative; width: 80%">
        
        <?php 
        if (!empty($titleBox))
        {
            $this->widget('bootstrap.widgets.TbBox', array(
                'title' => $titleBox,
                'headerIcon' => 'icon-file',
                'content' => $viewForm,
            )); 
        }
        ?>
    </div>
</div><!-- form -->