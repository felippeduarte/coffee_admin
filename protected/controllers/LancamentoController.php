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
            if(!empty($_POST['Lancamento']['id_lancamento']))
            {
                $modelLancamento = Lancamento::model()->findByPk($_POST['Lancamento']['id_lancamento']);
            }
            $modelLancamento->attributes = $_POST['Lancamento'];
            $modelLancamento->id_pessoaUsuario = Yii::app()->user->getId();
            $modelLancamento->dt_ultimaAlteracao = date('d/m/Y h:i:s');
            $modelLancamento->tp_categoriaLancamento = $_POST['Lancamento']['tp_categoriaLancamento'];
            
            $modelLancamento->setScenario('insert');
         
            if($modelLancamento->validate())
            {
                $modelLancamento->save();
                
                !empty($_POST['Lancamento']['id_lancamento']) ?
                    Yii::app()->user->setFlash('success', "Lançamento alterado com sucesso!") :
                    Yii::app()->user->setFlash('success', "Lançamento cadastrado com sucesso!");
            }
        }
        
        $dataProvider = $modelLancamento->getLancamentoGrid($dataInicio, $dataFim, $estabelecimento, $categoria);
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'estabelecimento' => $estabelecimento,
            'categoria' => $categoria,
            'modelLancamento' => new Lancamento(),
        ));
        
	}
    
    public function actionEdit()
    {
        var_dump($_POST);
        var_dump("<hr>");
        var_dump($_GET);
        //die();
    }
    
    public function actionGetLancamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $lancamento = Lancamento::model()->findByPk($_POST['idLancamento']);
        $tipoCategoria = Categorialancamento::model()->findByPk($lancamento->id_categoriaLancamento);        
        $comboCategoria = Categorialancamento::model()->getHtmlDropdownOptionsCategoriasPorTipo($tipoCategoria->tp_categoriaLancamento);
        
        $json = new stdClass();
        
        foreach ($lancamento as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array(
            'categoriaLancamento'=>$comboCategoria,
            'lancamento'=>$json,
            'tipoCategoriaLancamento'=>$tipoCategoria->tp_categoriaLancamento));

        Yii::app()->end();
    }
    
    public function actionDelLancamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idLancamento']))
        {
            $id = $_POST['idLancamento'];
            $modelLancamento = Lancamento::model()->findByPk($id);
            $modelLancamento->id_pessoaUsuario = Yii::app()->user->getId();
            $modelLancamento->dt_ultimaAlteracao = date('d/m/Y h:i:s');
            $modelLancamento->fl_inativo = true;
            $modelLancamento->save();
            
            Yii::app()->user->setFlash('success', "Lançamento removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover o lançamento!");
        }
        Yii::app()->end();
    }
    
    public function actionCarregaCategorias()
    {
        echo Categorialancamento::model()->getHtmlDropdownOptionsCategoriasPorTipo($_POST['tp_categoriaLancamento'][0]);
    }
    
    public function actionCarregaFavorecidos()
    {
        $categoria = Categorialancamento::model()->findByPk($_POST['id_categoriaLancamento']);
        
        if(in_array($categoria->tp_categoriaLancamentoPessoa, array('C','F',array('C','F'))))
        {
            echo Pessoa::model()->getHtmlDropdownOptionsCategoriasPorTipo($categoria->tp_categoriaLancamentoPessoa);
        }
    }
}