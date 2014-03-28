<script type="text/javascript">

function send()
{
    var data=$("#form").serialize();

    $.ajax({
        type: 'POST',
        url: '<?php echo Yii::app()->createUrl("relatorio/lancamentoestabelecimento"); ?>',
        data:data,
        before:function()
        {
            
        },
        success:function(data){
            if(data.success == true)
            {
                $("#flash-message").hide();
                //window.open("data:text/html," + encodeURIComponent(data.relatorio),"_blank");
                window.open('relatorioGenerico?relatorio='+ encodeURIComponent(data.relatorio),"_blank");
            }
            else {
                $("#flash-message").html(data.error);
                $("#flash-message").slideDown(500);
            }
        },
        error: function() { // if error occured
            alert("Ocorreu um erro, tente novamente mais tarde!");
        },

    dataType:'json'
    });
}   
    
</script>

<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Relatórios';
$this->breadcrumbs=array(
	'Lançamento/Estabelecimento',
);
?>

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
            'prompt' => '-- Estabelecimento --',
            'options'=> array($estabelecimento=>array('selected'=>true))
        ));

?>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'button', 'label' => 'Gerar Relatório', 'htmlOptions'=>array('onclick'=>'send();'))
);

$this->endWidget();
?>

<div id="flash-message" class="alert in alert-block fade alert-error" style="display:none;"></div>