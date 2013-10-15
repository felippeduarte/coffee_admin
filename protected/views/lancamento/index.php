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
        'id' => 'verticalForm',
        'htmlOptions' => array('class' => 'well'), // for inset effect
    )
);
echo $form->maskedTextFieldRow($modelLancamento,'dt_lancamento','99/99/9999',
                        array('prepend'=>'<i class="icon-calendar"></i>',
                              'class' => 'input-small',
                              'value' => date('d/m/Y')
                        )
                    ); 

$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Pesquisar')
);
 
$this->endWidget();
unset($form);