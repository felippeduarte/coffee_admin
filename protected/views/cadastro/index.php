<script type="text/javascript">
    $('#adicionar').live('click',function()
    {
        _resetForm();
    });
</script>

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
                array('label'=>'Pessoas', 'itemOptions'=>array('class'=>'nav-header')),
                array('label'=>'Fornecedores', 'url'=>Yii::app()->createUrl('cadastro/fornecedor'), 'itemOptions'=>$itemOptions['Fornecedor']),
                array('label'=>'Colaboradores', 'url'=>Yii::app()->createUrl('cadastro/colaborador'), 'itemOptions'=>$itemOptions['Colaborador']),
                array('label'=>'Cargos de Colaborador', 'url'=>Yii::app()->createUrl('cadastro/cargocolaborador'), 'itemOptions'=>$itemOptions['CargoColaborador']),
                array('label'=>'Usuários', 'url'=>Yii::app()->createUrl('cadastro/usuario'), 'itemOptions'=> $itemOptions['Usuario']),
                array('label'=>'Estabelecimentos', 'itemOptions'=>array('class'=>'nav-header')),
                array('label'=>'Estabelecimentos', 'url'=>Yii::app()->createUrl('cadastro/estabelecimento'), 'itemOptions'=> $itemOptions['Estabelecimento']),
                array('label'=>'Grupos de Estabelecimentos', 'url'=>Yii::app()->createUrl('cadastro/grupoestabelecimento'), 'itemOptions'=> $itemOptions['GrupoEstabelecimento']),
                array('label'=>'Categorias e taxas', 'itemOptions'=>array('class'=>'nav-header')),
                array('label'=>'Categorias', 'url'=>Yii::app()->createUrl('cadastro/categorialancamento'), 'itemOptions'=> $itemOptions['CategoriaLancamento']),
                array('label'=>'Formas de Pagamento', 'url'=>Yii::app()->createUrl('cadastro/formapagamento'), 'itemOptions'=> $itemOptions['FormaPagamento']),
                array('label'=>'Tarifas', 'url'=>Yii::app()->createUrl('cadastro/estabelecimentoformapagamento'), 'itemOptions'=> $itemOptions['EstabelecimentoFormaPagamento']),
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
                                'data-toggle' => 'modal', 'id'=>'adicionar')),
                        )
                    )
                )
            )); 
        }
        ?>
    </div>
</div><!-- form -->