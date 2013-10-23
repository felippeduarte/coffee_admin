<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jQuery-Mask-Plugin-master/jquery.mask.min.js');
Yii::app()->clientScript->registerScript(1,"
$('.btn-group a.btn').live('click',function() {
    $('#Lancamento_nm_turno').val($(this).attr('value'));
});

$('#Lancamento_vl_lancamento').mask('000.000.000.000,00', {reverse: true});

",CClientScript::POS_END );

$this->pageTitle=Yii::app()->name . ' - Lançamentos';
$this->breadcrumbs=array('Lançamentos',);

$listaEstabelecimentos = CHtml::listData(Estabelecimento::model()->getComboEstabelecimento(), 'id_estabelecimento', 'nm_estabelecimento');
$listaCategorias = CHtml::listData(Categorialancamento::model()->getComboCategoriaLancamento(), 'id_categoriaLancamento', 'nm_categoriaLancamento');
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
        $listaEstabelecimentos,
        array(
            'prompt' => '-- Estabelecimento --',
            'options'=> array($estabelecimento=>array('selected'=>true))
        ));
?>

<?php
echo CHtml::dropDownlist(
        'categoriaLancamento',
        null,
        $listaCategorias,
        array(
            'prompt' => '-- Categoria --',
            'options'=> array($categoria=>array('selected'=>true))
        ));
?>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Pesquisar')
);
 
$this->endWidget();
unset($form);
?>

<?php
$this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>true, // use transitions?
    'closeText'=>'×', // close link text - if set to false, no close link is displayed
    'alerts'=>array( // configurations per alert type
	    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
    ),
));
?>

<?php
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
        'id' => 'lancamento',
        'type' => 'horizontal',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>false,
        ),
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

            <?php echo $form->maskedTextFieldRow($modelLancamento,'dt_lancamento','99/99/9999',
                        array('prepend'=>'<i class="icon-calendar"></i>',
                              'class' => 'input-small',
                              'value' => date('d/m/Y'),
                        )
                    ); ?>
            <?php echo $form->textFieldRow($modelLancamento,'vl_lancamento',
                        array('prepend'=>'R$',
                              'class' => 'input-medium',
                        )
                    ); ?>
            <?php echo $form->select2Row($modelLancamento,'id_estabelecimento',array(
                    'data' => $listaEstabelecimentos,
                    'asDropDownList' => true,
                    'options' => array('allowClear' => true,
                                'placeholder' => '-- Escolha o estabelecimento --',
                                'width'=>'40%'))); ?>
            <?php echo $form->select2Row($modelLancamento,'id_categoriaLancamento',array(
                    'data' => null,
                    'asDropDownList' => true,
                    'options' => array('allowClear' => true,
                                'placeholder' => '-- Escolha a categoria --',
                                'width'=>'40%'))); ?>
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
            
            <?php echo $form->select2Row($modelLancamento,'id_pessoaLancamento',array(
                    'data' => CHtml::listData(Pessoa::model()->getComboFavorecido(),'id_pessoa','nm_comboFavorecido'),
                    'asDropDownList' => true,
                    'options' => array('allowClear' => true,
                                'placeholder' => '-- Escolha o favorecido --',
                                'width'=>'40%'))); ?>
            <?php echo $form->select2Row($modelLancamento,'id_formaPagamento',array(
                    'data' => CHtml::listData(Formapagamento::model()->getComboFormaPagamento(),'id_formaPagamento','nm_formaPagamento'),
                    'asDropDownList' => true,
                    'options' => array('allowClear' => true,
                                'placeholder' => '-- Escolha a forma de pagamento --',
                                'width'=>'40%'))); ?>
            <?php echo $form->textAreaRow($modelLancamento, 'de_observacao', array('class'=>'span4', 'rows'=>1)); ?>
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