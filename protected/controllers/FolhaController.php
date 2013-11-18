<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FolhaController
 *
 * @author frbduarte
 */
class FolhaController extends Controller
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
        $modelLancamento = new Lancamento('folhaDePagamento');
        
        $dataInicio = isset($_POST['dataInicio']) ? $_POST['dataInicio'] : date("01/m/Y");
        $dataFim = isset($_POST['dataFim']) ? $_POST['dataFim'] : date("t/m/Y");
        $estabelecimento = isset($_POST['estabelecimento']) ? $_POST['estabelecimento'] : null;
        
        if(isset($_POST['ajax']) && $_POST['ajax']==='lancamento')
		{
            $modelLancamento->setScenario('ajaxFolhaDePagamento');
            echo CActiveForm::validate(array($modelLancamento));
			Yii::app()->end();
		}
        
        if(isset($_POST['Lancamento']))
        {
            
            $transacao = Yii::app()->db->beginTransaction();
            
            $modelLancamento = new Lancamento('folhaDePagamento');
            $modelLancamento->attributes = $_POST['Lancamento'];
            $modelLancamento->tp_categoriaLancamento = "D";
            
            $erro = false;
            
            if($modelLancamento->validate())
            {
                $modelLancamento->save();
                $id = $modelLancamento->id_lancamento;

                for($i = 0, $c = count($_POST['FolhaDePagamento']['proventos']['vl_lancamento']); $i < $c; $i++)
                {
                    //se categoria ou valor forem em branco, ignora
                    if(empty($_POST['FolhaDePagamento']['proventos']['id_categoriaLancamento'][$i]) ||
                       empty($_POST['FolhaDePagamento']['proventos']['vl_lancamento'][$i]))
                    {
                        continue;
                    }
                    
                    $modelFolha = new Folhadepagamento();
                    $modelFolha->id_lancamento = $id;
                    $modelFolha->id_categoriaLancamento = $_POST['FolhaDePagamento']['proventos']['id_categoriaLancamento'][$i];
                    $modelFolha->vl_lancamento = $_POST['FolhaDePagamento']['proventos']['vl_lancamento'][$i];
                    $modelFolha->tp_categoriaLancamentoFolha = 'P';

                    if($modelFolha->validate())
                    {
                        $modelFolha->save();
                    } else {
                        $erro = true;
                    }
                }
                
                for($i = 0, $c = count($_POST['FolhaDePagamento']['descontos']['vl_lancamento']); $i < $c; $i++)
                {
                    //se categoria ou valor forem em branco, ignora
                    if(empty($_POST['FolhaDePagamento']['descontos']['id_categoriaLancamento'][$i]) ||
                       empty($_POST['FolhaDePagamento']['descontos']['vl_lancamento'][$i]))
                    {
                        continue;
                    }
                    
                    $modelFolha = new Folhadepagamento();
                    $modelFolha->id_lancamento = $id;
                    $modelFolha->id_categoriaLancamento = $_POST['FolhaDePagamento']['descontos']['id_categoriaLancamento'][$i];
                    $modelFolha->vl_lancamento = $_POST['FolhaDePagamento']['descontos']['vl_lancamento'][$i];
                    $modelFolha->tp_categoriaLancamentoFolha = 'D';

                    if($modelFolha->validate())
                    {
                        $modelFolha->save();
                    } else {
                        $erro = true;
                    }
                }
            }
            else
            {
                $erro = true;
            }

            if($erro)
            {   
                foreach($modelLancamento->getErrors() as $error)
                {
                    Yii::app()->user->setFlash('error', $error[0]);
                }
                $transacao->rollback();
            } else {
                $transacao->commit();
                Yii::app()->user->setFlash('success', "Lançamento na folha de pagamento realizado com sucesso!");
            }
        }
        
        $dataProvider = $modelLancamento->getFolhaDePagamentoGrid($dataInicio, $dataFim, $estabelecimento);
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'estabelecimento' => $estabelecimento,
            'modelLancamento' => $modelLancamento,
        ));
    }
    
    public function actionCarregaFavorecidos()
    {
        $estabelecimento = Estabelecimento::model()->findByPk($_POST['id_estabelecimento']);
        
        if(!empty($estabelecimento))
        {
            echo Pessoa::model()->getHtmlDropdownOptionsPorEstabelecimento($estabelecimento->id_estabelecimento);
        }
    }
}

?>
