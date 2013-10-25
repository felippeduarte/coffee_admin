<script  type='text/javascript'>
$('#gridFormaPagamento a.update').live('click',function() {
    var idFormaPagamento = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getFormaPagamento",
        type: "post",
        data: { "idFormaPagamento" : idFormaPagamento },
        dataType:'json',
        success: function (data) { 
            //popula
            var e = data.FormaPagamento;
            
            $("[name='Formapagamento[id_formaPagamento]']")[1].value = e.id_formaPagamento;
            $("[name='Formapagamento[nm_formaPagamento]']")[1].value = e.nm_formaPagamento;
            
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridFormaPagamento a.delete').live('click',function() {

    if(confirm('Deseja remover a Forma de Pagamento '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
    {
       var idFormaPagamento = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delFormaPagamento",
            type: "post",
            data: { "idFormaPagamento" : idFormaPagamento },
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

$dataProvider = $modelFormaPagamento->getFormaPagamentoGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                't.nm_formaPagamento'=>array(
                    'asc'=>'t.nm_formaPagamento',
                    'desc'=>'t.nm_formaPagamento DESC',
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
        'value' => '$data->nm_formaPagamento',
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
    'id'=>'gridFormaPagamento',
	'filter'=>$modelFormaPagamento,
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
        'id'=>'cadastroFormaPagamento',
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
        <h3>FormaPagamento</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelFormaPagamento),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelFormaPagamento, 'id_formaPagamento'); ?>
            <?php echo $form->textFieldRow($modelFormaPagamento, 'nm_formaPagamento', array('class'=>'input-xxlarge')); ?>
            
        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
