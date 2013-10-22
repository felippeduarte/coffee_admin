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
        
        $dataInicio = isset($_POST['dataInicio']) ? $_POST['dataInicio'] : date("01/m/Y");
        $dataFim = isset($_POST['dataFim']) ? $_POST['dataFim'] : date("t/m/Y");
        $estabelecimento = isset($_POST['estabelecimento']) ? $_POST['estabelecimento'] : null;
        $categoria = isset($_POST['categoriaLancamento']) ? $_POST['categoriaLancamento'] : null;

        if(isset($_POST['ajax']) && $_POST['ajax']==='lancamento')
		{
            $modelLancamento->setScenario('ajax');
            echo CActiveForm::validate(array($modelLancamento));
			Yii::app()->end();
		}
        
        if(isset($_POST['Lancamento']))
        {
            if(isset($_POST['Lancamento']['id_lancamento']))
            {
                $modelLancamento->id_lancamento = $_POST['Lancamento']['id_lancamento'];
            }
            $modelLancamento->attributes = $_POST['Lancamento'];
            $modelLancamento->id_pessoaUsuario = Yii::app()->user->getId();
            $modelLancamento->dt_ultimaAlteracao = date('d/m/Y h:i:s');
            
            $modelLancamento->setScenario('insert');
            
            if($modelLancamento->validate())
            {
                //atualização
                if(!empty($modelLancamento->id_lancamento))
                {
                    LancamentoController::model()->updateByPk($modelLancamento->id_lancamento,$modelLancamento->attributes);
                    Yii::app()->user->setFlash('success', "Lançamento alterado com sucesso!");
                } else {
                    $modelLancamento->save();
                    Yii::app()->user->setFlash('success', "Lançamento cadastrado com sucesso!");
                }
            }
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
            echo CHtml::tag('option', array('value'=>$id_categoriaLancamento),CHtml::encode($nm_categoriaLancamento),true);
        }
    }
}