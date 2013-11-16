<script  type='text/javascript'>

$(document).ready(function()
{
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
    
    $("#Lancamento_id_estabelecimento").change(function() {
        $.ajax({
            url: "folha/carregaFavorecidos",
            type: "post",
            data: { "id_estabelecimento" : $(this).val() },
            dataType:'text',
            success: function (html) {
                $('#Lancamento_id_pessoaLancamento').html(html);
                $('#Lancamento_id_pessoaLancamento').select2().select2({placeholder:'-- Escolha um favorecido --',width:'resolve'});
            }
        });
    });
    
    
    $("form").click(function() {
        var proventos = 0;
        var descontos = 0;
        var valor = 0;
        $.each($('[name="FolhaDePagamento[proventos][vl_lancamento][]"]'),function()
        {
            valor = $(this).val();
            valor = valor.replace(".","").replace(",",".");
            proventos = proventos + (valor *100);
        });
        
        $.each($('[name="FolhaDePagamento[descontos][vl_lancamento][]"]'),function()
        {
            valor = $(this).val();
            valor = valor.replace(".","").replace(",",".");
            descontos = descontos + (valor *100);
        });
        valor = parseInt(proventos-descontos)/100;
        $('#Lancamento_vl_total').val(valor.toString().replace(".",","));
    });
});

function updateModal(categoria,tipo,reset,novo)
{
    $('#Lancamento_id_categoriaLancamento').html(categoria);
    _setModalHeader(tipo, novo);
    
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
    
    $('#Lancamento_id_estabelecimento').select2().select2({val:'',placeholder:'-- Escolha o estabelecimento --',width:'resolve'});
    $('#Lancamento_id_categoriaLancamento').select2({placeholder:'-- Escolha a categoria --',width:'resolve'});
    $('#Lancamento_id_pessoaLancamento').select2().select2({placeholder:'-- Escolha uma categoria --',width:'resolve'});
    $('#Lancamento_id_formaPagamento').select2().select2({placeholder:'-- Escolha a forma de pagamento --',width:'resolve'});
    
    $('#lancamento')[0].reset();
}

function _setModalHeader(tipo, novo)
{
    novo ?
        $('.modal-header h3').text('Lançar '):
        $('.modal-header h3').text('Editar ');
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
            
            _setModalHeader(t,false);
            
            $("#Lancamento_id_lancamento").val(l.id_lancamento);
            $("#Lancamento_dt_lancamento").val(l.dt_lancamento);
            $("#Lancamento_vl_lancamento").val(l.vl_lancamento);
            
            $('#Lancamento_id_estabelecimento').select2().select2('val',l.id_estabelecimento).select2({width:'resolve'});
            $("#Lancamento_id_estabelecimento option[value="+l.id_estabelecimento+"]").attr('selected', 'selected');
            
            $('#Lancamento_id_categoriaLancamento').html(c);
            $('#Lancamento_id_categoriaLancamento').select2().select2('val',l.id_categoriaLancamento).select2({width:'resolve'});
            $("#Lancamento_id_categoriaLancamento option[value="+l.id_categoriaLancamento+"]").prop('selected', true);
            
            $("#Lancamento_nm_turno").val(l.nm_turno);
            $('div [name="Lancamento_turno"] a').removeClass("active");
            $('div [name="Lancamento_turno"] [value="'+l.nm_turno+'"]').addClass("active");

            $('#Lancamento_id_pessoaLancamento').select2().select2('val',l.id_pessoaLancamento).select2({width:'resolve'});
            $("#Lancamento_id_pessoaLancamento option[value="+l.id_pessoaLancamento+"]").prop('selected', true);
            
            $('#Lancamento_id_formaPagamento').select2().select2('val',l.id_formaPagamento).select2({width:'resolve'});
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

$('#addProvento').live('click',function() {
    var provento = $('#proventos [name="proventos"]').first().clone(true);
    $('#proventos [name="proventos"]').last().after(provento);
    provento.find('input').val('').first().mask('000.000.000.000,00', {reverse: true});
});
$('#addDesconto').live('click',function() {
    var desconto = $('#descontos [name="descontos"]').first().clone(true);
    $('#descontos [name="descontos"]').last().after(desconto);
    desconto.find('input').val('').first().mask('000.000.000.000,00', {reverse: true});
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
        'header' => '!',
        'name'  => 'tp_categoriaLancamento',
        'value'  => '$data->idCategoriaLancamento->tp_categoriaLancamento',
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
        'value' => '$data->vl_lancamento',
        'cssClassExpression' => '$data->vl_lancamento >=0 ? "text-success" : "text-error"',
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