<script  type='text/javascript'>
$('#gridCategoriaLancamento a.update').live('click',function() {
    var idCategoriaLancamento = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "getCategoriaLancamento",
        type: "post",
        data: { "idCategoriaLancamento" : idCategoriaLancamento },
        dataType:'json',
        success: function (data) { 
            //popula
            var c = data.CategoriaLancamento;
            
            $("[name='Categorialancamento[id_categoriaLancamento]']")[1].value = c.id_categoriaLancamento;
            $("[name='Categorialancamento[nm_categoriaLancamento]']")[1].value = c.nm_categoriaLancamento;
            $("#Categorialancamento_id_categoriaLancamentoPai option[value="+c.id_categoriaLancamentoPai+"]").prop('selected', true);
            
            if(c.tp_categoriaLancamento == 'D') {
                $(":radio[value=D]").prop("checked", true);
            }
            else if(c.tp_categoriaLancamento == 'R') {
                $(":radio[value=R]").prop("checked", true);
            }
            
            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});

$('#gridCategoriaLancamento a.delete').live('click',function() {

    if(confirm('Deseja remover a Categoria '+$(this).closest('tr').find('td:eq(2)').text()+'?'))
    {
       var idCategoriaLancamento = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "delCategoriaLancamento",
            type: "post",
            data: { "idCategoriaLancamento" : idCategoriaLancamento },
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
</script>

<?php 

$dataProvider = $modelCategoriaLancamento->getCategoriaLancamentoGrid();
$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                't.nm_categoriaLancamento'=>array(
                    'asc'=>'t.nm_categoriaLancamento',
                    'desc'=>'t.nm_categoriaLancamento DESC',
                ),
                'nm_categoriaLancamentoPai'=>array(
                    'asc'=>'idCategoriaLancamentoPai.nm_categoriaLancamento',
                    'desc'=>'idCategoriaLancamentoPai.nm_categoriaLancamento DESC',
                ),
                '*',
            ));

$gridColumns = array(
    array(
        'header' => '#',
        'name'  => 'id_categoriaLancamento',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Nome Categoria Lançamento',
        'name'  => 'nm_categoriaLancamento',
        'value' => '$data->nm_categoriaLancamento',
        ),
    array(
        'header' => 'Tipo Categoria Lançamento',
        'name'  => 'tp_categoriaLancamento',
        'value' => 'Yii::app()->bulebar->getTipoCategoria($data->tp_categoriaLancamento)',
        ),
    array(
        'header' => 'Categoria Lançamento Pai',
        'name'  => 'nm_categoriaLancamentoPai',
        'value' => '(!empty($data->idCategoriaLancamentoPai->nm_categoriaLancamento))?$data->idCategoriaLancamentoPai->nm_categoriaLancamento:null',
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
    'id'=>'gridCategoriaLancamento',
	'filter'=>$modelCategoriaLancamento,
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
        'id'=>'cadastroCategoriaLancamento',
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
        <h3>CategoriaLancamento</h3>
    </div>
    <div class="modal-body">
        <fieldset>
            <?php echo $form->errorSummary(array($modelCategoriaLancamento),'Sumário de Erros'); ?>

            <?php echo $form->hiddenField($modelCategoriaLancamento, 'id_categoriaLancamento'); ?>
            <?php echo $form->textFieldRow($modelCategoriaLancamento, 'nm_categoriaLancamento', array('class'=>'input-xxlarge')); ?>
            <?php echo $form->radioButtonListRow($modelCategoriaLancamento, 'tp_categoriaLancamento',
                            array(
                                'D' => 'Despesa',
                                'R' => 'Receita',
                                )
                    ); ?>
            <?php echo $form->dropDownListRow($modelCategoriaLancamento,
                    'id_categoriaLancamentoPai',
                    CHtml::listData(CategoriaLancamento::model()->getComboCategoriaLancamento(),'id_categoriaLancamento','nm_categoriaLancamento'),
                    array('prompt' => '--Escolha a categoria pai--')); ?>

        </fieldset>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Confirmar')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Limpar')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>