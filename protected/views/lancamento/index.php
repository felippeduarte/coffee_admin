<?php
$this->pageTitle=Yii::app()->name . ' - Lançamentos';
$this->breadcrumbs=array(
	'Lançamentos',
);
?>

<h1>Lançamentos</h1>

<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'form',
        'htmlOptions' => array('class' => 'form-inline well'), // for inset effect
    )
);
?>
<i class="icon-calendar"></i>
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker',array(
    'name'=>'dataInicio',
    'id'=>'dataInicio',
    'language'=>'pt-BR',
    'value'=> $dataInicio,
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
    ),
    'htmlOptions'=>array(
        'class'=>'input-small search-query',
    ),
));
?>

<span class="">&nbsp;&nbsp;até&nbsp;&nbsp;</span>

<i class="icon-calendar"></i>
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker',array(
    'name'=>'dataFim',
    'id'=>'dataFim',
    'language'=>'pt-BR',
    'value'=> $dataFim,
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
    ),
    'htmlOptions'=>array(
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
$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Pesquisar')
);
 
$this->endWidget();
unset($form);

$this->widget('bootstrap.widgets.TbBox', array(
                'title' => 'Lançamentos',
                'headerIcon' => 'icon-file',
                'content' => $grid,
                'headerButtons' => array(
                	array(
                        'class' => 'bootstrap.widgets.TbButtonGroup',
                        'buttons'=>array(
                            array(
                                'label'=>'+ Lançar Receita',
                                'url'=>'#modal-cadastro',
                                'htmlOptions' => array(
                                    'data-toggle' => 'modal',
                                    'class'=>'btn btn-success',
                                )
                            ),
                            array(
                                'label'=>'- Lançar Despesa',
                                'url'=>'#modal-cadastro',
                                'htmlOptions' => array(
                                    'data-toggle' => 'modal',
                                    'class'=>'btn btn-danger',
                                )
                            ),
                        )
                    )
                )
            ));
?>