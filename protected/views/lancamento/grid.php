<script  type='text/javascript'>
$('#gridLancamentos a.delete').live('click',function() {

    if(confirm('Deseja remover o lançamento do dia '+$(this).closest('tr').find('td:eq(1)').text()+
        ', no valor de R$ '+$(this).closest('tr').find('td:eq(6)').text()+
        ' para o favorecido '+$(this).closest('tr').find('td:eq(5)').text()+'?'))
    {
       var idLancamento = $(this).closest('tr').find('td:eq(0)').text();
        
        //ajax para popular modal
        $.ajax({
            url: "lancamento/delLancamento",
            type: "post",
            data: { "idLancamento" : idLancamento },
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

$dataProvider->pagination = array('pagesize'=>10);
$dataProvider->sort = array(
            'attributes'=>array(
                'id_lancamento'=>array(
                    'asc'=>'id_lancamento',
                    'desc'=>'id_lancamento DESC',
                ),
                'dt_lancamento'=>array(
                    'asc'=>'dt_lancamento',
                    'desc'=>'dt_lancamento DESC',
                ),
                'nm_estabelecimento'=>array(
                    'asc'=>'idEstabelecimento.nm_estabelecimento',
                    'desc'=>'idEstabelecimento.nm_estabelecimento DESC',
                ),
                'nm_categoriaLancamento'=>array(
                    'asc'=>'idCategoriaLancamento.nm_categoriaLancamento',
                    'desc'=>'idCategoriaLancamento.nm_categoriaLancamento DESC',
                ),
                'nm_pessoa'=>array(
                    'asc'=>'idPessoaLancamento.nm_pessoa',
                    'desc'=>'idPessoaLancamento.nm_pessoa DESC',
                ),
                'vl_lancamento'=>array(
                    'asc'=>'vl_lancamento',
                    'desc'=>'vl_lancamento DESC',
                ),
                '*',
            ));

$gridColumns = array(
      array(
        'header' => '#',
        'name'  => 'id_lancamento',
        'htmlOptions'=>array('style'=>'width: 5px')
        ),
    array(
        'header' => 'Data',
        'name'  => 'dt_lancamento',
        'value' => 'Yii::app()->dateFormatter->format("dd/MM/yyyy",$data->dt_lancamento)',
        'htmlOptions'=>array('style'=>'width: 20px'),
        'class' => 'bootstrap.widgets.TbEditableColumn',
        'editable' => array(
				'url' => $this->createUrl('lancamento/edit'),
				'placement' => 'right',
				'inputclass' => 'span3'
			),
        ),
    array(
        'header' => 'Estabelecimento',
        'name'  => 'nm_estabelecimento',
        'value'  => '$data->idEstabelecimento->nm_estabelecimento',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Categoria',
        'name'  => 'nm_categoriaLancamento',
        'value'  => '$data->idCategoriaLancamento->nm_categoriaLancamento',
        'htmlOptions'=>array('style'=>'width: 100px')
        ),
    array(
        'header' => '',
        'name'  => 'idCategoriaLancamento.tp_categoriaLancamento',
        'htmlOptions'=>array('style'=>'width: 5px')
        ),
    array(
        'header' => 'Favorecido',
        'name'  => 'nm_pessoa',
        'value'  => '$data->idPessoaLancamento->nm_pessoa',
        'htmlOptions'=>array('style'=>'width: 60px')
        ),
    array(
        'header' => 'Valor',
        'name'  => 'vl_lancamento',
        'value' => 'Yii::app()->bulebar->trocaDecimalModelParaView($data->vl_lancamento)',
        'cssClassExpression' => '$data->idCategoriaLancamento->tp_categoriaLancamento == "R" ? "text-success" : "text-error"',
        'htmlOptions'=>array('style'=>'width: 30px')
        ),
    array(
		'htmlOptions' => array('nowrap'=>'nowrap','style'=>'10px'),
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
        ),
    )
);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'id'=>'gridLancamentos',
	'fixedHeader' => true,
	'headerOffset' => 40, // 40px is the height of the main navigation at bootstrap
	'type'=>'striped bordered',
    'enablePagination' => true,
	'dataProvider' => $dataProvider,
	'template' => "{items}\n{pager}",
	'columns' => $gridColumns,
));
?>