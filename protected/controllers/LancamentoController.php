<?php

class LancamentoController extends Controller
{
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
    
    public function actionIndex()
	{
        $modelLancamento = new Lancamento();
        $dataInicio = date("01/m/Y");
        $dataFim = date("t/m/Y");
        $estabelecimento = null;
        $categoria = null;
        
        if (!empty($_POST))
        {
            $dataInicio = isset($_POST['dataInicio']) ? $_POST['dataInicio'] : null;
            $dataFim = isset($_POST['dataFim']) ? $_POST['dataFim'] : null;
            $estabelecimento = isset($_POST['estabelecimento']) ? $_POST['estabelecimento'] : null;
            $categoria = isset($_POST['categoriaLancamento']) ? $_POST['categoriaLancamento'] : null;
        }

        $dataProvider = $modelLancamento->getLancamentoGrid($dataInicio, $dataFim, $estabelecimento, $categoria);
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'estabelecimento' => $estabelecimento,
            'categoria' => $categoria,
            'modelLancamento' => $modelLancamento,
        ));
        
	}
    
    public function actionEdit()
    {
        var_dump($_POST);
        var_dump("<hr>");
        var_dump($_GET);
        //die();
    }
    
    public function actionCarregaCategorias()
    {
        $data = Categorialancamento::model()->findAll('tp_categoriaLancamento = :tp_categoriaLancamento', 
        array(':tp_categoriaLancamento'=>$_POST['tp_categoriaLancamento'][0]));

        $data = CHtml::listData($data,'id_categoriaLancamento','nm_categoriaLancamento');

        echo "<option value=''>-- Escolha a Categoria --</option>";
        
        foreach($data as $id_categoriaLancamento=>$nm_categoriaLancamento)
        {
          echo CHtml::tag('option', array('id_categoriaLancamento'=>$id_categoriaLancamento),CHtml::encode($nm_categoriaLancamento),true);
        }
    }
}