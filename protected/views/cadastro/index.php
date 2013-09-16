<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Cadastros';
$this->breadcrumbs=array(
	'Cadastros',
);
?>

<h1>Cadastros</h1>


<div class="form">
    <div style="width: 130px; position: absolute;">
    <?php 
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'list',
        'items' => array(
                array('label'=>'Cadastros', 'itemOptions'=>array('class'=>'nav-header')),
                array('label'=>'Fornecedor', 'url'=>Yii::app()->createUrl('cadastro/fornecedor'), 'itemOptions'=>$itemOptions['Fornecedor']),
                array('label'=>'Colaborador', 'url'=>Yii::app()->createUrl('cadastro/colaborador'), 'itemOptions'=>$itemOptions['Colaborador']),
                array('label'=>'Usuário', 'url'=>Yii::app()->createUrl('cadastro/usuario'), 'itemOptions'=> $itemOptions['Usuario']),
        )
    ));
    ?>
    </div>
    <div style="left:150px; position: relative; width: 87%">
        
        <?php 
        if (!empty($titleBox))
        {
            $this->widget('bootstrap.widgets.TbBox', array(
                'title' => $titleBox,
                'headerIcon' => 'icon-file',
                'content' => $viewForm,
                'headerButtons' => array(
                	array(
                        'class' => 'bootstrap.widgets.TbButtonGroup',
                        'buttons'=>array(
                            array('label'=>'Adicionar', 'url'=>'#modal-cadastro', 'htmlOptions' => array(
                                'data-toggle' => 'modal',)),
                        )
                    )
                )
            )); 
        }
        ?>
    </div>
</div><!-- form -->