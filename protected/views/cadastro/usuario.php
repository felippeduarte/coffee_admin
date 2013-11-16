<script  type='text/javascript'>
$('#gridUsuario a.update').live('click',function() {
    var idPessoa = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getUsuario",
        type: "post",
        data: { "idPessoa" : idPessoa },
        dataType:'json',
        success: function (data) { 
            //popula
            var u = data.usuario;
            
            $("[name='Usuario[id_pessoa]']")[1].value = u.id_pessoa;
            $("[name='Usuario[nm_login]']")[1].value = u.nm_login;
            
            $('#Colaborador_id_pessoa').select2().select2('val',u.id_pessoa).select2({width:'resolve'});
            $("#Colaborador_id_pessoa option[value="+u.id_pessoa+"]").attr('selected', 'selected');
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridUsuario a.delete').live('click',function() {

    if(confirm('Deseja remover o usuario '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
    {
       var idPessoa = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delUsuario",
            type: "post",
            data: { "idPessoa" : idPessoa },
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
    $("#cadastroUsuario input").val("");
    $('#cadastroUsuario')[0].reset();
    $('#Colaborador_id_pessoa').select2('val','').select2({placeholder:'-- Escolha o colaborador --',width:'resolve'});
}
</script>

<?php 

$dataProvider = $modelUsuario->getUsuarioGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                'nm_login'=>array(
                    'asc'=>'nm_login',
                    'desc'=>'nm_login DESC',
                ),
                'nm_pessoa'=>array(
                    'asc'=>'idPessoa.nm_pessoa',
                    'desc'=>'idPessoa.nm_pessoa DESC',
                ),
                '*',
            ));

$gridColumns = array(
    array(
        'header' => '#',
        'name'  => 'id_pessoa',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Login',
        'name'  => 'nm_login',
        'value' => '$data->nm_login',
        ),
    array(
        'header' => 'Colaborador',
        'name'  => 'nm_pessoa',
        'value' => '$data->idPessoa->nm_pessoa',
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
    'id'=>'gridUsuario',
	'filter'=>$modelUsuario,
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
        'id'=>'cadastroUsuario',
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
        <h3>Usuario</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelUsuario),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelUsuario, 'id_pessoa'); ?>
            <?php echo $form->textFieldRow($modelUsuario, 'nm_login', array('class'=>'input-xxlarge', 'autocomplete'=>'off')); ?>
            <?php echo $form->passwordFieldRow($modelUsuario, 'de_senha', array('class'=>'input-xxlarge', 'autocomplete'=>'off')); ?>
            <?php //echo CHtml::passwordField('de_senha2'); ?>
            <?php echo $form->passwordFieldRow($modelUsuario, 'de_senha_confirmacao', array('class'=>'input-xxlarge', 'autocomplete'=>'off')); ?>
            
            <?php echo $form->select2Row($modelColaborador,'id_pessoa',array(
                'data' => CHtml::listData(Colaborador::model()->getComboColaborador(), 'id_pessoa', 'idPessoa.nm_pessoa'),
                'asDropDownList' => true,
                'options' => array('allowClear' => true,
                            'placeholder' => '-- Escolha o colaborador --',
                            'width'=>'40%'))); ?>

        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
