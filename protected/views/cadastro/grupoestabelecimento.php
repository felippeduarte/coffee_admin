<script  type='text/javascript'>
$('#gridGrupoEstabelecimento a.update').live('click',function() {
    var idGrupoEstabelecimento = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getGrupoEstabelecimento",
        type: "post",
        data: { "idGrupoEstabelecimento" : idGrupoEstabelecimento },
        dataType:'json',
        success: function (data) { 
            //popula
            var e = data.GrupoEstabelecimento;
            
            $("[name='Grupoestabelecimento[id_grupoEstabelecimento]']")[1].value = e.id_grupoEstabelecimento;
            $("[name='Grupoestabelecimento[nm_grupoEstabelecimento]']")[1].value = e.nm_grupoEstabelecimento;
            
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridGrupoEstabelecimento a.delete').live('click',function() {

    if(confirm('Deseja remover o Grupo Estabelecimento '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
    {
       var idGrupoEstabelecimento = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delGrupoEstabelecimento",
            type: "post",
            data: { "idGrupoEstabelecimento" : idGrupoEstabelecimento },
            dataType:'json',
            success: function (data) {
                alert('Removido com sucesso');
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
    $('[name="Grupoestabelecimento[id_grupoEstabelecimento]')[1].value = null;
    $('#cadastroGrupoEstabelecimento')[0].reset();
}
</script>

<?php 

$dataProvider = $modelGrupoEstabelecimento->getGrupoEstabelecimentoGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                't.nm_grupoEstabelecimento'=>array(
                    'asc'=>'t.nm_grupoEstabelecimento',
                    'desc'=>'t.nm_grupoEstabelecimento DESC',
                ),
                '*',
            ));

$gridColumns = array(
    array(
        'header' => '#',
        'name'  => 'id_grupoEstabelecimento',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Nome Grupo Estabelecimento',
        'name'  => 'nm_grupoEstabelecimento',
        'value' => '$data->nm_grupoEstabelecimento',
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
    'id'=>'gridGrupoEstabelecimento',
	'filter'=>$modelGrupoEstabelecimento,
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
        'id'=>'cadastroGrupoEstabelecimento',
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
        <h3>GrupoEstabelecimento</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelGrupoEstabelecimento),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelGrupoEstabelecimento, 'id_grupoEstabelecimento'); ?>
            <?php echo $form->textFieldRow($modelGrupoEstabelecimento, 'nm_grupoEstabelecimento', array('class'=>'input-xxlarge')); ?>
            
        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
