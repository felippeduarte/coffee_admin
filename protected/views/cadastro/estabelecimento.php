<script  type='text/javascript'>
$('#gridEstabelecimento a.update').live('click',function() {
    var idEstabelecimento = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getEstabelecimento",
        type: "post",
        data: { "idEstabelecimento" : idEstabelecimento },
        dataType:'json',
        success: function (data) { 
            //popula
            var e = data.Estabelecimento;
            
            $("[name='Estabelecimento[id_estabelecimento]']")[1].value = e.id_estabelecimento;
            $("[name='Estabelecimento[nm_estabelecimento]']")[1].value = e.nm_estabelecimento;
            
            $('#Estabelecimento_id_grupoEstabelecimento').select2().select2('val',e.id_grupoEstabelecimento).select2({width:'resolve'});
            $("#Estabelecimento_id_grupoEstabelecimento option[value="+e.id_grupoEstabelecimento+"]").attr('selected', 'selected');            
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridEstabelecimento a.delete').live('click',function() {

    if(confirm('Deseja remover a Estabelecimento '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
    {
       var idEstabelecimento = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delEstabelecimento",
            type: "post",
            data: { "idEstabelecimento" : idEstabelecimento },
            dataType:'json',
            success: function (data) {
                alert('Removida com sucesso');
            },
            error:function(){
                alert("Ocorreu um erro, tente novamente!");
            }
        });
    } else {
        return false;
    }
    return false;
});

function _resetForm()
{
    $('[name="Estabelecimento[id_estabelecimento]"]')[1].value = null;
    $('#cadastroEstabelecimento')[0].reset();
    $('#Estabelecimento_id_grupoEstabelecimento').select2('val','').select2({placeholder:'-- Escolha o Grupo --',width:'resolve'});
}
</script>

<?php 

$dataProvider = $modelEstabelecimento->getEstabelecimentoGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                't.nm_estabelecimento'=>array(
                    'asc'=>'t.nm_estabelecimento',
                    'desc'=>'t.nm_estabelecimento DESC',
                ),
                'nm_estabelecimentoPai'=>array(
                    'asc'=>'idEstabelecimentoPai.nm_estabelecimento',
                    'desc'=>'idEstabelecimentoPai.nm_estabelecimento DESC',
                ),
                'nm_grupoEstabelecimento'=>array(
                    'asc'=>'idGrupoEstabelecimento.nm_grupoEstabelecimento',
                    'desc'=>'idGrupoEstabelecimento.nm_grupoEstabelecimento DESC',
                ),
                '*',
            ));

$gridColumns = array(
    array(
        'header' => '#',
        'name'  => 'id_estabelecimento',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Nome Estabelecimento',
        'name'  => 'nm_estabelecimento',
        'value' => '$data->nm_estabelecimento',
        ),
    array(
        'header' => 'Grupo Estabelecimento',
        'name'  => 'nm_grupoEstabelecimento',
        'value' => 'empty($data->idGrupoEstabelecimento->nm_grupoEstabelecimento) ? "" : $data->idGrupoEstabelecimento->nm_grupoEstabelecimento',
        ),
    array(
		'htmlOptions' => array('nowrap'=>'nowrap'),
		'class'=>'bootstrap.widgets.TbButtonColumn',
        'template'=>'{update}{delete}',
        'deleteConfirmation'=>false,
        'buttons'=>array(
            'update' => array(
                'url'=>null,
                'options'=>array(
                    'class'=>'update',
                    'data-toggle' => 'modal',
                ),
            ),
            'delete' => array(
                'url' => null,
                'options'=>array(
                    'class'=>'delete',
                )
            )
        )
	)
); 

$this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>true, // use transitions?
    'closeText'=>'×', // close link text - if set to false, no close link is displayed
    'alerts'=>array( // configurations per alert type
	    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
    ),
));

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'id'=>'gridEstabelecimento',
	'filter'=>$modelEstabelecimento,
	'fixedHeader' => true,
	'headerOffset' => 40, // 40px is the height of the main navigation at bootstrap
	'type'=>'striped bordered',
    'enablePagination' => true,
	'dataProvider' => $dataProvider,
	'template' => "{items}\n{pager}",
	'columns' => $gridColumns,
));

/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'cadastroEstabelecimento',
        'type'=>'horizontal',
        //'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>false,
        ),
)); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
                                            'id' => 'modal-cadastro'
                                             )
          ); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>Estabelecimento</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelEstabelecimento),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelEstabelecimento, 'id_estabelecimento'); ?>
            <?php echo $form->textFieldRow($modelEstabelecimento, 'nm_estabelecimento', array('class'=>'input-xxlarge')); ?>

            <?php echo $form->select2Row($modelEstabelecimento,'id_grupoEstabelecimento',array(
                'data' => CHtml::listData(Grupoestabelecimento::model()->getComboGrupoEstabelecimento(), 'id_grupoEstabelecimento', 'nm_grupoEstabelecimento'),
                'asDropDownList' => true,
                'options' => array('allowClear' => true,
                            'placeholder' => '-- Escolha o Grupo --',
                            'width'=>'40%'))); ?>
        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
