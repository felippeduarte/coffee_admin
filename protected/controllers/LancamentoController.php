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
		$this->render('index',array(
            'modelLancamento' => new Lancamento()));
	}
    
    public function actionEdit()
    {
        var_dump($_POST);
        var_dump("<hr>");
        var_dump($_GET);
        //die();
    }
}