<script  type='text/javascript'>
$('#gridEstabelecimentoFormaPagamento a.update').live('click',function() {
    var idFormaPagamento = $(this).closest('tr').find('td:eq(0)').text();
    var idEstabelecimento = $(this).closest('tr').find('td:eq(2)').text();

    //ajax para popular modal
    $.ajax({
        url: "getEstabelecimentoFormaPagamento",
        type: "post",
        data: { "idEstabelecimento" : idEstabelecimento, "idFormaPagamento" : idFormaPagamento },
        dataType:'json',
        success: function (data) { 
            //popula
            var e = data.EstabelecimentoFormaPagamento;
            
            $("[name='EstabelecimentoFormapagamento[id_formaPagamento]']")[1].value = e.id_formaPagamento;
            $("[name='EstabelecimentoFormapagamento[id_estabelecimento]']")[1].value = e.id_estabelecimento;
            $("[name='Formapagamento[id_formaPagamento]']")[0].value = e.id_formaPagamento;
            $("[name='Estabelecimento[id_estabelecimento]']")[0].value = e.id_estabelecimento;
            $("[name='EstabelecimentoFormapagamento[nu_taxaPercentual]']")[1].value = e.nu_taxaPercentual;
            
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridEstabelecimentoFormaPagamento a.delete').live('click',function() {

    if(confirm('Deseja remover a Forma de Pagamento '+$(this).closest('tr').find('td:eq(1)').text()+
                           ' para o estabelecimento '+$(this).closest('tr').find('td:eq(3)').text()+'?'))
    {
        var idFormaPagamento = $(this).closest('tr').find('td:eq(0)').text();
        var idEstabelecimento = $(this).closest('tr').find('td:eq(2)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delEstabelecimentoFormaPagamento",
            type: "post",
            data: { "idEstabelecimento" : idEstabelecimento, "idFormaPagamento" : idFormaPagamento },
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

$dataProvider = $modelEstabelecimentoFormaPagamento->getEstabelecimentoFormaPagamentoGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                't.id_formaPagamento'=>array(
                    'asc'=>'t.id_formaPagamento',
                    'desc'=>'t.id_formaPagamento DESC',
                ),
                't.id_estabelecimento'=>array(
                    'asc'=>'t.id_estabelecimento',
                    'desc'=>'t.id_estabelecimento DESC',
                ),
                'nm_estabelecimento'=>array(
                    'asc'=>'idEstabelecimento.nm_estabelecimento',
                    'desc'=>'idEstabelecimento.nm_estabelecimento DESC',
                ),
                'nm_formaPagamento'=>array(
                    'asc'=>'idFormaPagamento.nm_formaPagamento',
                    'desc'=>'idFormaPagamento.nm_formaPagamento DESC',
                ),
                't.nu_taxaPercentual'=>array(
                    'asc'=>'t.nu_taxaPercentual',
                    'desc'=>'t.nu_taxaPercentual DESC',
                ),
                '*',
            ));

$gridColumns = array(
    array(
        'header' => '#',
        'name'  => 'id_formaPagamento',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Nome Forma de Pagamento',
        'name'  => 'nm_formaPagamento',
        'value' => '$data->idFormaPagamento->nm_formaPagamento',
        ),
    array(
        'header' => '#',
        'name'  => 'id_estabelecimento',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Nome Estabelecimento',
        'name'  => 'nm_estabelecimento',
        'value' => '$data->idEstabelecimento->nm_estabelecimento',
        ),
    array(
        'header' => 'Taxa Percentual',
        'name'  => 'nu_taxaPercentual',
        'value' => '$data->nu_taxaPercentual',
        
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
    'id'=>'gridEstabelecimentoFormaPagamento',
	'filter'=>$modelEstabelecimentoFormaPagamento,
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
        'id'=>'cadastroEstabelecimentoFormaPagamento',
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
        <h3>Tarifa</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelEstabelecimentoFormaPagamento),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelEstabelecimento, 'id_estabelecimento'); ?>
            <?php echo $form->hiddenField($modelFormaPagamento, 'id_formaPagamento'); ?>
            <?php echo $form->dropDownListRow($modelEstabelecimentoFormaPagamento,
                    'id_estabelecimento',
                    CHtml::listData(Estabelecimento::model()->getComboEstabelecimento(),'id_estabelecimento','nm_estabelecimento'),
                    array('prompt' => '--Escolha o estabelecimento--','class'=>'input-xlarge')); ?>
            <?php echo $form->dropDownListRow($modelEstabelecimentoFormaPagamento,
                    'id_formaPagamento',
                    CHtml::listData(Formapagamento::model()->getComboFormaPagamento(),'id_formaPagamento','nm_formaPagamento'),
                    array('prompt' => '--Escolha a forma de pagamento--','class'=>'input-xlarge')); ?>
            <?php echo $form->maskedTextFieldRow($modelEstabelecimentoFormaPagamento,'nu_taxaPercentual','99,99',
                        array('append'=>'%','class' => 'input-small','id'=>'nu_taxaPercentual')
                    ); ?>
            
        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
