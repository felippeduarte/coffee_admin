<script  type='text/javascript'>
$('#gridFornecedor a.update').live('click',function() {
    var idPessoa = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getFornecedor",
        type: "post",
        data: { "idPessoa" : idPessoa },
        dataType:'json',
        success: function (data) { 
            //popula
            var f = data.fornecedor;
            $("#Pessoa_id_pessoa").val(f.id_pessoa);
            $("#Pessoa_nm_pessoa").val(f.nm_pessoa);
            $("#Pessoa_dt_nascimento").val(f.dt_nascimento);

            if(f.tp_pessoa == 'PF')
            {
                $(":radio[value=PF]").prop("checked", true);
                $("#pessoaJuridica").hide();
                $("#pessoaFisica").show();
                $("#Pessoafisica_nm_apelido").val(f.nm_apelido);
                $("#Pessoafisica_nu_cpf").val(f.nu_cpf);
                $("#Pessoafisica_nu_rg").val(f.nu_rg);
            } else if(f.tp_pessoa == 'PJ') {
                $(":radio[value=PJ]").prop("checked", true);
                $("#pessoaFisica").hide();
                $("#pessoaJuridica").show();
                $("#Pessoajuridica_nm_nomeFantasia").val(f.nm_nomeFantasia);
                $("#Pessoajuridica_nu_cnpj").val(f.nu_cnpj);
                $("#Pessoajuridica_nu_inscricaoEstadual").val(f.nu_inscricaoEstadual);    
            }

            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridFornecedor a.delete').live('click',function() {

    if(confirm('Deseja remover o fornecedor '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
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
</script>

<?php 

$dataProvider = $modelFornecedor->getFornecedorGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                'nm_pessoa'=>array(
                    'asc'=>'idPessoa.nm_pessoa',
                    'desc'=>'idPessoa.nm_pessoa DESC',
                ),
                'identificador'=>array(
                    'asc'=>'identificador',
                    'desc'=>'identificador DESC',
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
        'header' => 'Identificador',
        'name'  => 'identificador',
        'value' => 'Yii::app()->bulebar->adicionaMascaraIdentificador($data->identificador)',
        'htmlOptions'=>array('style'=>'width: 140px')
        ),
    array(
        'header' => 'Nome',
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
    'id'=>'gridFornecedor',
	'filter'=>$modelFornecedor,
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
        'id'=>'cadastroFornecedor',
        'type'=>'horizontal',
        //'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
)); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
                                            'id' => 'modal-cadastro'
                                             )
          ); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>Fornecedor</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelPessoa,$modelPessoaFisica,$modelPessoaJuridica),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelPessoa, 'id_pessoa'); ?>
            <?php echo $form->textFieldRow($modelPessoa, 'nm_pessoa', array('class'=>'input-xxlarge')); ?>
            <?php echo $form->maskedTextFieldRow($modelPessoa,'dt_nascimento','99/99/9999',
                        array('prepend'=>'<i class="icon-calendar"></i>',
                              'class' => 'input-small'
                        )
                    ); ?>
            <?php echo $form->radioButtonListRow($modelPessoa, 'tp_pessoa',
                            array(
                                'PF' => 'Pessoa Física',
                                'PJ' => 'Pessoa Jurídica',
                                ),
                            array('onChange'=> 'if(this.value=="PF"){$("#pessoaFisica").show("slow");$("#pessoaJuridica").hide();}else{$("#pessoaFisica").hide();$("#pessoaJuridica").show("slow");}')
                    ); ?>


            <div id="pessoaFisica" class="hide">
                <?php echo Yii::app()->getController()->actionPessoaFisica($form,$modelPessoaFisica); ?>
            </div>

            <div id="pessoaJuridica" class="hide">
                <?php echo Yii::app()->getController()->actionPessoaJuridica($form,$modelPessoaJuridica); ?>
            </div>
        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>