<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RelatorioController
 *
 * @author frbduarte
 */
class RelatorioController extends Controller
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
        $itemOptions = array(
                'LancamentoEstabelecimento' => array(),
                'LancamentoFornecedor' => array(),
                'FolhaPagamentoMensal' => array(),
            );
            $optionsAtivo = array('class'=>'active');

            $titleBox = null;
            $viewForm = null;
            
            $action = Yii::app()->getRequest()->getQuery('action');
            
            switch($action)
            {
                case 'lancamentoestabelecimento':
                    $titleBox = 'Lançamento/Estabelecimento';
                    $itemOptions['LancamentoEstabelecimento'] = $optionsAtivo;
                    $viewForm = $this->actionLancamentoEstabelecimento();
                    break;
                case 'lancamentofornecedor':
                    $titleBox = 'Lançamento/Fornecedor';
                    $itemOptions['LancamentoFornecedor'] = $optionsAtivo;
                    $viewForm = $this->actionLancamentoFornecedor();
                    break;
                case 'folhapagamentomensal':
                    $titleBox = 'Folha de Pagamento Mensal';
                    $itemOptions['FolhaPagamentoMensal'] = $optionsAtivo;
                    $viewForm = $this->actionFolhaPagamentoMensal();
                    break;
                default:
                    break;
            }
            
            $this->render('index',
                array('viewForm' => $viewForm, 'viewAtiva' => $action, 'titleBox' => $titleBox, 'itemOptions'=>$itemOptions)
            );
    }
    
    /**
	 * Página de relatório de Lançamento por Estabelecimento
	 */
	protected function actionLancamentoEstabelecimento()
	{
        $dataInicio = isset($_POST['dataInicio']) ? $_POST['dataInicio'] : date("01/m/Y");
        $dataFim = isset($_POST['dataFim']) ? $_POST['dataFim'] : date("t/m/Y");
        $estabelecimento = isset($_POST['estabelecimento']) ? $_POST['estabelecimento'] : null;
        
        $relatorio = "";
        
        if(isset($_POST['dataInicio']))
		{
            if(!empty($estabelecimento))
            {
                $relatorio = new Relatorio();
            
                $relatorio = $relatorio->relatorioLancamentoEstabelecimento($dataInicio, $dataFim, $estabelecimento);
            
                echo CJSON::encode(array('success'=>1, 'relatorio'=>$relatorio));
                Yii::app()->end();
            } else {
                echo CJSON::encode(array('success'=>0, 'error'=>'Selecione um estabelecimento!'));
                Yii::app()->end();
            }
        }
        return $this->renderPartial('lancamentoEstabelecimento',
                array(
                    'dataInicio' => $dataInicio,
                    'dataFim' => $dataFim,
                    'estabelecimento' => $estabelecimento
                ), true);
	}
    
    public function actionRelatorioGenerico()
    {
        return $this->renderPartial('relatorioGenerico',
                array(
                    'relatorio' => $_GET['relatorio']
                ));
    }
}
?>
