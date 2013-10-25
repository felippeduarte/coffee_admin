<script  type='text/javascript'>
$('#gridCargoColaborador a.update').live('click',function() {
    var idCargoColaborador = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getCargoColaborador",
        type: "post",
        data: { "idCargoColaborador" : idCargoColaborador },
        dataType:'json',
        success: function (data) { 
            //popula
            var e = data.CargoColaborador;
            
            $("[name='Cargocolaborador[id_cargoColaborador]']")[1].value = e.id_cargoColaborador;
            $("[name='Cargocolaborador[nm_cargoColaborador]']")[1].value = e.nm_cargoColaborador;
            
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridCargoColaborador a.delete').live('click',function() {

    if(confirm('Deseja remover o Cargo do Colaborador '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
    {
       var idCargoColaborador = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delCargoColaborador",
            type: "post",
            data: { "idCargoColaborador" : idCargoColaborador },
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
</script>

<?php 

$dataProvider = $modelCargoColaborador->getCargoColaboradorGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                't.nm_cargoColaborador'=>array(
                    'asc'=>'t.nm_cargoColaborador',
                    'desc'=>'t.nm_cargoColaborador DESC',
                ),
                '*',
            ));

$gridColumns = array(
    array(
        'header' => '#',
        'name'  => 'id_cargoColaborador',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Nome do Cargo do Colaborador',
        'name'  => 'nm_cargoColaborador',
        'value' => '$data->nm_cargoColaborador',
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
    'id'=>'gridCargoColaborador',
	'filter'=>$modelCargoColaborador,
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
        'id'=>'cadastroCargoColaborador',
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
        <h3>CargoColaborador</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelCargoColaborador),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelCargoColaborador, 'id_cargoColaborador'); ?>
            <?php echo $form->textFieldRow($modelCargoColaborador, 'nm_cargoColaborador', array('class'=>'input-xxlarge')); ?>
            
        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
