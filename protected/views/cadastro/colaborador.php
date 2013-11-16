<script  type='text/javascript'>
$('#gridColaborador a.update').live('click',function() {
    var idPessoa = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getColaborador",
        type: "post",
        data: { "idPessoa" : idPessoa },
        dataType:'json',
        success: function (data) { 
            //popula
            var c = data.colaborador;
            $("#Pessoa_id_pessoa").val(c.id_pessoa);
            $("#Pessoa_nm_pessoa").val(c.nm_pessoa);
            $("#Pessoa_dt_nascimento").val(c.dt_nascimento);

            $("#Pessoafisica_nm_apelido").val(c.nm_apelido);
            $("#Pessoafisica_nu_cpf").val(c.nu_cpf);
            $("#Pessoafisica_nu_rg").val(c.nu_rg);
            
            $("[name='Colaborador[nu_colaborador]']")[1].value = c.nu_colaborador;
            
            $('#Colaborador_id_cargoColaborador').select2().select2('val',c.id_cargoColaborador).select2({width:'resolve'});
            $("#Colaborador_id_cargoColaborador option[value="+c.id_cargoColaborador+"]").attr('selected', 'selected');
            
            $('#Colaborador_id_estabelecimento').select2().select2('val',c.id_estabelecimento).select2({width:'resolve'});
            $("#Colaborador_id_estabelecimento option[value="+c.id_estabelecimento+"]").attr('selected', 'selected');
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridColaborador a.delete').live('click',function() {

    if(confirm('Deseja remover o colaborador '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
    {
       var idPessoa = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delPessoa",
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
    $("#cadastroColaborador input").val("");
    $('#cadastroColaborador')[0].reset();
    $('#Colaborador_id_cargoColaborador').select2('val','').select2({placeholder:'-- Escolha o cargo --',width:'resolve'});
    $('#Colaborador_id_estabelecimento').select2('val','').select2({placeholder:'-- Escolha o estabelecimento --',width:'resolve'});
}

</script>

<?php 

$dataProvider = $modelColaborador->getColaboradorGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                'nm_pessoa'=>array(
                    'asc'=>'idPessoa.nm_pessoa',
                    'desc'=>'idPessoa.nm_pessoa DESC',
                ),
                'nu_cpf'=>array(
                    'asc'=>'nu_cpf',
                    'desc'=>'nu_cpf DESC',
                ),
                'nm_cargoColaborador'=>array(
                    'asc'=>'nm_cargoColaborador',
                    'desc'=>'nm_cargoColaborador DESC',
                ),
                'nm_estabelecimento'=>array(
                    'asc'=>'nm_estabelecimento',
                    'desc'=>'nm_estabelecimento DESC',
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
        'header' => 'CPF',
        'name'  => 'nu_cpf',
        'value' => '$data->pessoaFisica->nu_cpf',
        'htmlOptions'=>array('style'=>'width: 140px')
        ),
    array(
        'header' => 'Nome',
        'name'  => 'nm_pessoa',
        'value' => '$data->idPessoa->nm_pessoa',
        ),
    array(
        'header' => 'Matrícula',
        'name'  => 'nu_colaborador',
        'value' => '$data->nu_colaborador',
        ),
    array(
        'header' => 'Cargo',
        'name'  => 'nm_cargoColaborador',
        'value' => '$data->idCargoColaborador->nm_cargoColaborador',
        ),
    array(
        'header' => 'Estabelecimento',
        'name'  => 'nm_estabelecimento',
        'value' => '$data->idEstabelecimento->nm_estabelecimento',
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
    'id'=>'gridColaborador',
	'filter'=>$modelColaborador,
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
        'id'=>'cadastroColaborador',
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
        <h3>Colaborador</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelPessoa,$modelPessoaFisica),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelPessoa, 'id_pessoa'); ?>
            <?php echo $form->textFieldRow($modelPessoa, 'nm_pessoa', array('class'=>'input-xxlarge')); ?>
            <?php echo $form->maskedTextFieldRow($modelPessoa,'dt_nascimento','99/99/9999',
                        array('prepend'=>'<i class="icon-calendar"></i>',
                              'class' => 'input-small'
                        )
                    ); ?>
            
            <?php echo Yii::app()->getController()->actionPessoaFisica($form,$modelPessoaFisica); ?>
            
            <?php echo $form->textFieldRow($modelColaborador, 'nu_colaborador', array('class'=>'input-xxlarge')); ?>
            
            <?php echo $form->select2Row($modelColaborador,'id_cargoColaborador',array(
                    'data' => CHtml::listData(Cargocolaborador::model()->getComboCargoColaborador(), 'id_cargoColaborador', 'nm_cargoColaborador'),
                    'asDropDownList' => true,
                    'options' => array('allowClear' => true,
                                'placeholder' => '-- Escolha o cargo --',
                                'width'=>'40%'))); ?>
            
            
            <?php echo $form->select2Row($modelColaborador,'id_estabelecimento',array(
                    'data' => CHtml::listData(Estabelecimento::model()->getComboEstabelecimento(), 'id_estabelecimento', 'nm_estabelecimento'),
                    'asDropDownList' => true,
                    'options' => array('allowClear' => true,
                                'placeholder' => '-- Escolha o estabelecimento --',
                                'width'=>'40%'))); ?>

        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>