<?php
$this->pageTitle=Yii::app()->name . ' - Lançamentos';
$this->breadcrumbs=array('Lançamentos',);
?>

<h1>Lançamentos</h1>

<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'form',
        'htmlOptions' => array('class' => 'form-inline well'),
    )
);
?>
<i class="icon-calendar"></i>
<?php
$form->widget('zii.widgets.jui.CJuiDatePicker',array(
    'name'=>'dataInicio',
    'id'=>'dataInicio',
    'language'=>'pt-BR',
    'value'=> $dataInicio,
    'options'=>array(
        'showAnim'=>'fold',
        'dateFormat'=>'dd/mm/yy',
        'changeMonth'=>'true', 
        'changeYear'=>'true'
    ),
    'htmlOptions'=>array(
        'readonly'=> true,
        'class'=>'input-small search-query',
    ),
));
?>

<span class="">&nbsp;&nbsp;até&nbsp;&nbsp;</span>

<i class="icon-calendar"></i>
<?php
$form->widget('zii.widgets.jui.CJuiDatePicker',array(
    'name'=>'dataFim',
    'id'=>'dataFim',
    'language'=>'pt-BR',
    'value'=> $dataFim,
    'options'=>array(
        'showAnim'=>'fold',
        'changeMonth'=>'true', 
        'changeYear'=>'true'
    ),
    'htmlOptions'=>array(
        'readonly'=> true,
        'class'=>'input-small search-query',
    ),
));
?>

<?php
echo CHtml::dropDownlist(
        'estabelecimento',
        null,
        CHtml::listData(Estabelecimento::model()->getComboEstabelecimento(), 'id_estabelecimento', 'nm_estabelecimento'),
        array(
            'prompt' => '-- Estabelecimento --'
        ));
?>

<?php
echo CHtml::dropDownlist(
        'categoria',
        null,
        CHtml::listData(Categorialancamento::model()->getComboCategoriaLancamento(), 'id_categoriaLancamento', 'nm_categoriaLancamento'),
        array(
            'prompt' => '-- Categoria --'
        ));
?>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Pesquisar')
);
 
$this->endWidget();
unset($form);

$this->widget('bootstrap.widgets.TbBox', array(
                'title' => 'Lançamentos',
                'headerIcon' => 'icon-file',
                'content' => $this->renderPartial('grid',array('dataProvider' => $dataProvider),true),
                'headerButtons' => array(
                	array(
                        'class' => 'bootstrap.widgets.TbButtonGroup',
                        'buttons'=>array(
                            array(
                                'label'=>'+ Lançar Receita',
                                'url'=>'#modal-cadastro',
                                'htmlOptions' => array(
                                    'data-toggle' => 'modal',
                                    'data-id' => 'R',
                                    'class'=>'btn-success btn-lancamento',
                                    'ajax' => array(
                                        'type'=>'POST', 
                                        'url'=>CController::createUrl('carregaCategorias'),
                                        'data'=>array('tp_categoriaLancamento'=>'R'),
                                        'success'=>"js:function(html){ 
                                            jQuery('#modal-cadastro').modal({'show':true});
                                            jQuery('#Lancamento_id_categoriaLancamento').html(html);
                                            $('.modal-header h3').text('Lançar Receita');
                                        }",
                                    ),
                                ), 
                            ),
                            array(
                                'label'=>'- Lançar Despesa',
                                'url'=>'#modal-cadastro',
                                'htmlOptions' => array(
                                    'data-toggle' => 'modal',
                                    'data-id' => 'D',
                                    'class'=>'btn-danger btn-lancamento',
                                    'ajax' => array(
                                        'type'=>'POST', 
                                        'url'=>CController::createUrl('carregaCategorias'),
                                        'data'=>array('tp_categoriaLancamento'=>'D'),
                                        'success'=>"js:function(html){ 
                                            jQuery('#modal-cadastro').modal({'show':true});
                                            jQuery('#Lancamento_id_categoriaLancamento').html(html);
                                            $('.modal-header h3').text('Lançar Despesa');
                                        }",
                                    ),
                                )
                            ),
                        )
                    )
                )
            ));
?>
<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'form_lancamento',
        'type' => 'horizontal'
    )
);
?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
                                            'id' => 'modal-cadastro'
                                             )
          ); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3></h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelLancamento),'Sumário de Erros'); ?>

            <?php echo $form->maskedTextFieldRow($modelLancamento,'vl_lancamento','%9,99',
                        array('prepend'=>'R$',
                              'class' => 'input-small'
                        )
                    ); ?>
            
            <?php echo $form->dropDownListRow($modelLancamento,
                            'id_categoriaLancamento',
                            CHtml::listData(CategoriaLancamento::model()->getComboCategoriaLancamento(),'id_categoriaLancamento','nm_categoriaLancamento'),
                            array('prompt' => '--Escolha a categoria --',
                                'labelOptions' => array('label' => false),
                            )); ?>
            <div class="control-group">
            <?php echo $form->label($modelLancamento, 'nm_turno',array('class'=>'control-label required')); ?>
                <div class="controls">
            <?php echo $form->hiddenField($modelLancamento, 'nm_turno'); ?>
            <?php $form->widget('bootstrap.widgets.TbButtonGroup', array(
                        'type' => 'info',
                        'toggle' => 'radio',
                        'buttons' => Lancamento::model()->getRadioButtonsTurno(),
                    )); ?>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
<?php 
$this->endWidget();
$this->endWidget();
unset($form);
?>