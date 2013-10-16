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
	{$this->render('index');
        if(isset($_POST))
        {
            $dataInicio = isset($_POST['dataInicio']) ? $_POST['dataInicio'] : null;
            $dataFim = isset($_POST['dataFim']) ? $_POST['dataFim'] : null;
            $estabelecimento = isset($_POST['estabelecimento']) ? $_POST['estabelecimento'] : null;
            
            $this->render('grid',array(
            'modelLancamento' => new Lancamento()));
        }
        
        
	}
    
    public function actionEdit()
    {
        var_dump($_POST);
        var_dump("<hr>");
        var_dump($_GET);
        //die();
    }
}