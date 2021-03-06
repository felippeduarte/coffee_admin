<script  type='text/javascript'>

var select2propEstabelecimento = {placeholder:'-- Escolha o estabelecimento --',width:'resolve',allowClear:'true'};
var select2propCategoriaLancamento = {placeholder:'-- Escolha a categoria --',width:'resolve',allowClear:'true'};
var select2propPessoaLancamento = {placeholder:'-- Escolha um favorecido --',width:'resolve',allowClear:'true'};
var select2propFormaPagamento = {placeholder:'-- Escolha a forma de pagamento --',width:'resolve',allowClear:'true'};

$(document).ready(function()
{
    $("#btn-lancar-despesa").live('click', function(){
        
        $.ajax({
            url: "lancamento/carregaCategorias",
            data: {"tp_categoriaLancamento":"D"},
            dataType:"text",
            type: "post",
            success: function (html) {
                updateModal(html,'D',true,true);
            }
        });
    });
    
    $("#btn-lancar-receita").live('click', function(){
        
        $.ajax({
            url: "lancamento/carregaCategorias",
            data: {"tp_categoriaLancamento":"R"},
            dataType:"text",
            type: "post",
            success: function (html) {
                updateModal(html,'R',true,true);
            }
        });
    });
    
    $(".btn-group[data-single-select] .btn").live('click', function(){
        
        var ativo = $(this).hasClass("active");
        
        $(".btn-group[data-single-select] .btn").each(function ()
        {
            $(this).removeClass('active');
        });
        
        if(ativo)
        {
            $('#Lancamento_nm_turno').val('');
        } else {
            $(this).addClass("active");
            $('#Lancamento_nm_turno').val($(this).attr('value'));
        }
    });
    
    $("#lancamento").bind("reset", function() {
        _resetForm(false);
    });    
    
    $("#Lancamento_id_categoriaLancamento").change(function() {
        carregaFavorecidos($("#Lancamento_id_categoriaLancamento").val());
    });
    
});

function carregaFavorecidos(id,id_selecionado)
{
    $.ajax({
        url: "lancamento/carregaFavorecidos",
        type: "post",
        data: { "id_categoriaLancamento" : id },
        dataType:'text',
        success: function (html) {
            $('#Lancamento_id_pessoaLancamento').html(html);
            $('#Lancamento_id_pessoaLancamento').select2().select2(select2propPessoaLancamento);
            
            if(id_selecionado)
            {
                $('#Lancamento_id_pessoaLancamento').select2().select2('val',id_selecionado).select2(select2propPessoaLancamento);
                $("#Lancamento_id_pessoaLancamento option[value="+id_selecionado+"]").attr('selected', 'selected');
            }
        }
    });
}

function updateModal(categoria,tipo,reset,novo)
{
    $('#Lancamento_id_categoriaLancamento').html(categoria);
    _setModalHeader(tipo, novo);
    _setRequiredFields(tipo);
    
    novo? _resetForm(true) : _resetForm(false);
    
    $('#Lancamento_tp_categoriaLancamento').val(tipo);
    $('#modal-cadastro').modal({'show':true});
}

function _resetForm(complete)
{
    if(complete)
    {
        $('#Lancamento_id_lancamento').val('');
    }
    
    $('#Lancamento_id_estabelecimento').select2().select2(select2propEstabelecimento);
    $('#Lancamento_id_categoriaLancamento').select2(select2propCategoriaLancamento);
    $('#Lancamento_id_pessoaLancamento').select2().select2(select2propCategoriaLancamento).select2('val',''); //categoria pois é load inicial
    $('#Lancamento_id_formaPagamento').select2().select2(select2propFormaPagamento).select2('val','');
    $('div [name="Lancamento_turno"] a').removeClass("active");
    
    $('#lancamento')[0].reset();
    
    $('#Lancamento_id_estabelecimento').select2("val","<?php echo Yii::app()->session['id_estabelecimento'];?>");
}

function _setModalHeader(tipo, novo)
{
    novo ?
        $('.modal-header h3').text('Lançar '):
        $('.modal-header h3').text('Editar ');
    
    (tipo == 'R') ?
        $('.modal-header h3').append('Receita'):
        $('.modal-header h3').append('Despesa');
}

function _setRequiredFields(tipo)
{
    (tipo == 'D') ?
        $("label:contains('Favorecido')").html('Favorecido <span class="required">*</span>'):
        $("label:contains('Favorecido')").html('Favorecido');
}

$('#gridLancamentos a.update').live('click',function() {
    var idLancamento = $(this).closest('tr').find('td:eq(0)').text();

    //ajax para popular modal
    $.ajax({
        url: "lancamento/getLancamento",
        type: "post",
        data: { "idLancamento" : idLancamento },
        dataType:'json',
        success: function (data) { 
            //popula
            var l = data.lancamento;
            var c = data.categoriaLancamento;
            var t = data.tipoCategoriaLancamento;
            
            carregaFavorecidos(l.id_estabelecimento,l.id_pessoaLancamento);
            
            _setModalHeader(t,false);
            _setRequiredFields(t);
            
            $("#Lancamento_id_lancamento").val(l.id_lancamento);
            $("#Lancamento_dt_lancamento").val(l.dt_lancamento);
            $("#Lancamento_vl_lancamento").val(l.vl_lancamento);
            
            $('#Lancamento_id_estabelecimento').select2().select2('val',l.id_estabelecimento).select2(select2propEstabelecimento);
            $("#Lancamento_id_estabelecimento option[value="+l.id_estabelecimento+"]").attr('selected', 'selected');
            
            $('#Lancamento_id_categoriaLancamento').html(c);
            $('#Lancamento_id_categoriaLancamento').select2().select2('val',l.id_categoriaLancamento).select2(select2propCategoriaLancamento);
            $("#Lancamento_id_categoriaLancamento option[value="+l.id_categoriaLancamento+"]").prop('selected', true);
            
            $("#Lancamento_nm_turno").val(l.nm_turno);
            $('div [name="Lancamento_turno"] a').removeClass("active");
            $('div [name="Lancamento_turno"] [value="'+l.nm_turno+'"]').addClass("active");
            
            $('#Lancamento_id_formaPagamento').select2().select2('val',l.id_formaPagamento).select2(select2propFormaPagamento);
            $("#Lancamento_id_formaPagamento option[value="+l.id_formaPagamento+"]").prop('selected', true);

            $("#Lancamento_de_observacao").val(l.de_observacao);

            $('#modal-cadastro').modal('toggle');

        },
        error:function(){
            alert("Ocorreu um erro, tente novamente!");
        }
    });
        
    return false;

});    

$('#gridLancamentos a.delete').live('click',function() {

    if(confirm('Deseja remover o lançamento do dia '+$(this).closest('tr').find('td:eq(2)').text()+
        ', no valor de R$ '+$(this).closest('tr').find('td:eq(7)').text()+
        ' para o favorecido '+$(this).closest('tr').find('td:eq(6)').text()+'?'))
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
                'nm_categoriaLancamento'=>array(
                    'asc'=>'idCategoriaLancamento.nm_categoriaLancamento',
                    'desc'=>'idCategoriaLancamento.nm_categoriaLancamento DESC',
                ),
                'tp_categoriaLancamento'=>array(
                    'asc'=>'idCategoriaLancamento.tp_categoriaLancamento',
                    'desc'=>'idCategoriaLancamento.tp_categoriaLancamento DESC',
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
        'header' => '<i class="icon-retweet"></i>',
        'name'   => 'id_lancamentoVinculado',
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
        'header' => 'Categoria',
        'name'  => 'nm_categoriaLancamento',
        'value'  => '$data->idCategoriaLancamento->nm_categoriaLancamento',
        'htmlOptions'=>array('style'=>'width: 150px')
        ),
    array(
        'header' => '!',
        'name'  => 'tp_categoriaLancamento',
        'value'  => '$data->idCategoriaLancamento->tp_categoriaLancamento',
        'htmlOptions'=>array('style'=>'width: 5px')
        ),
    array(
        'header' => 'Favorecido',
        'name'  => 'nm_pessoa',
        'value'  => 'empty($data->id_pessoaLancamento) ? "" : $data->idPessoaLancamento->nm_pessoa',
        'htmlOptions'=>array('style'=>'width: 200px')
        ),
    array(
        'header' => 'Valor',
        'name'  => 'vl_lancamento',
        'value' => '$data->vl_lancamento',
        'cssClassExpression' => 'Yii::app()->bulebar->trocaDecimalViewParaModel($data->vl_lancamento) >=0 ? "text-success" : "text-error"',
        'htmlOptions'=>array('style'=>'width: 30px')
        ),
    array(
		'htmlOptions' => array('nowrap'=>'nowrap','style'=>'5px'),
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