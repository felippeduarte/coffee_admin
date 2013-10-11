<?php

class CadastroController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

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
    
	/**
	 * Página de Cadastros
	 */
	public function actionIndex()
	{
            $itemOptions = array(
                'Fornecedor' => array(),
                'Colaborador' => array(),
                'Usuario' => array(),
                'Estabelecimento' => array(),
                'CategoriaLancamento' => array(),
            );
            $optionsAtivo = array('class'=>'active');

            $titleBox = null;
            $viewForm = null;
            
            $action = Yii::app()->getRequest()->getQuery('action');
            
            switch($action)
            {
                case 'fornecedor':
                    $titleBox = 'Fornecedor';
                    $itemOptions['Fornecedor'] = $optionsAtivo;
                    $viewForm = $this->actionFornecedor();
                    break;
                case 'colaborador':
                    $titleBox = 'Colaborador';
                    $itemOptions['Colaborador'] = $optionsAtivo;
                    $viewForm = $this->actionColaborador();
                    break;
                case 'usuario':
                    $titleBox = 'Usuário';
                    $itemOptions['Usuario'] = $optionsAtivo;
                    $viewForm = $this->actionUsuario();
                    break;
                case 'estabelecimento':
                    $titleBox = 'Estabelecimento';
                    $itemOptions['Estabelecimento'] = $optionsAtivo;
                    $viewForm = $this->actionEstabelecimento();
                    break;
                case 'categorialancamento':
                    $titleBox = 'Categoria Lancamento';
                    $itemOptions['CategoriaLancamento'] = $optionsAtivo;
                    $viewForm = $this->actionCategoriaLancamento();
                    break;
                default:
                    break;
            }
            
            $this->render('index',
                array('viewForm' => $viewForm, 'viewAtiva' => $action, 'titleBox' => $titleBox, 'itemOptions'=>$itemOptions)
            );
	}
        
    /**
	 * Página de Cadastro de Pessoa Jurídica
	 */
	protected function actionPessoaJuridica($form,$model)
	{
		return $this->renderPartial('pessoaJuridica',
                            array('modelPessoaJuridica' => $model,
                                  'form'=>$form),
                            true
                        );
	}
        
    /**
	 * Página de Cadastro de Pessoa Física
	 */
	protected function actionPessoaFisica($form, $model)
	{
		return $this->renderPartial('pessoaFisica',
                            array('modelPessoaFisica' => $model,
                                  'form'=>$form),
                            true
                        );
	}
        
    /**
	 * Página de Cadastro de Fornecedor
	 */
	protected function actionFornecedor()
	{
        $id = false;
        
        if (!empty($_POST['Pessoa']['id_pessoa']))
        {
            $id = $_POST['Pessoa']['id_pessoa'];
            $modelPessoa = Pessoa::model()->findByPk($id);
            
            if($_POST['Pessoa']['tp_pessoa']==Pessoa::TP_PESSOA_FISICA)
            {
                $modelPessoaFisica = Pessoafisica::model()->findByPk($id);
                $modelPessoaJuridica = new Pessoajuridica();
            } else if($_POST['Pessoa']['tp_pessoa']==Pessoa::TP_PESSOA_JURIDICA)
            {
                $modelPessoaFisica = new Pessoafisica('cadastro');
                $modelPessoaJuridica = Pessoajuridica::model()->findByPk($id);
            }
            
            $modelFornecedor = Fornecedor::model()->findByPk($id);
        } else {
            $modelPessoa = new Pessoa();
            $modelPessoaFisica = new Pessoafisica('cadastro');
            $modelPessoaJuridica = new Pessoajuridica();
            $modelFornecedor = new Fornecedor();
        }
        
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroFornecedor')
		{   
            if($_POST['Pessoa']['tp_pessoa']==Pessoa::TP_PESSOA_FISICA)
            {
                echo CActiveForm::validate(array($modelPessoa,$modelPessoaFisica));
            }
            else if($_POST['Pessoa']['tp_pessoa']==Pessoa::TP_PESSOA_JURIDICA)
            {
                echo CActiveForm::validate(array($modelPessoa,$modelPessoaJuridica));
            }
            else {
                echo CActiveForm::validate($modelPessoa);
            }
            
			Yii::app()->end();
		}
        
        if(isset($_POST['Pessoa']))
		{
            if(!$id)
            {
                $modelPessoa->attributes = $_POST['Pessoa'];
            }
            
            if($modelPessoa->validate())
            {
                $transacao = Yii::app()->db->beginTransaction();
                
                try
                {
                    $modelPessoa->save();
                    
                    if($modelPessoa->tp_pessoa == Pessoa::TP_PESSOA_FISICA)
                    {
                        $modelPessoaEspecialista = $modelPessoaFisica;
                        $modelPessoaEspecialista->attributes = $_POST['Pessoafisica'];
                        $modelPessoaEspecialista->tp_pessoaFisica = Pessoafisica::TP_PESSOAFISICA_FORNECEDOR;
                    }
                    else if($modelPessoa->tp_pessoa == Pessoa::TP_PESSOA_JURIDICA)
                    {
                        $modelPessoaEspecialista = $modelPessoaJuridica;
                        $modelPessoaEspecialista->attributes = $_POST['Pessoajuridica'];
                    }

                    $modelPessoaEspecialista->id_pessoa = $modelPessoa->id_pessoa;
                    
                    //se não for update                    
                    if(empty($modelFornecedor->id_pessoa))
                    {
                        $modelFornecedor->id_pessoa = $modelPessoa->id_pessoa;
                    }

                    $modelPessoaEspecialista->save();

                    $modelFornecedor->save();

                    $transacao->commit();
                    
                    $id ?
                        Yii::app()->user->setFlash('success', "Fornecedor $modelPessoa->nm_pessoa alterado com sucesso!"):
                        Yii::app()->user->setFlash('success', "Fornecedor $modelPessoa->nm_pessoa cadastrado com sucesso!");                    
                    
                    $modelPessoa = new Pessoa();
                    $modelPessoaFisica = new Pessoafisica('cadastro');
                    $modelPessoaJuridica = new Pessoajuridica();
                    $modelFornecedor = new Fornecedor();
                
                }
                catch (Exception $e)
                {
                    $transacao->rollback();
                }
            }
		}
        
        //filtro do grid
        if(isset($_GET['Fornecedor']))
        {
            $modelFornecedor->unsetAttributes();
            $modelFornecedor->id_pessoa = $_GET['Fornecedor']['id_pessoa'];
            $modelFornecedor->identificador = $_GET['Fornecedor']['identificador'];
            $modelFornecedor->nm_pessoa = $_GET['Fornecedor']['nm_pessoa'];
        }
        
		return $this->renderPartial('fornecedor',
                            array('modelPessoa' => $modelPessoa,
                                  'modelPessoaFisica' => $modelPessoaFisica,
                                  'modelPessoaJuridica' => $modelPessoaJuridica,
                                  'modelFornecedor' => $modelFornecedor),
                            true
                        );
	}
        
    /**
	 * Página de Cadastro de Colaborador
	 */
	protected function actionColaborador()
	{
        $id = false;
        
        if (!empty($_POST['Pessoa']['id_pessoa']))
        {
            $id = $_POST['Pessoa']['id_pessoa'];
            $modelPessoa = Pessoa::model()->findByPk($id);
            $modelPessoaFisica = Pessoafisica::model()->findByPk($id);
            $modelColaborador = Colaborador::model()->findByPk($id);
        } else {
            $modelPessoa = new Pessoa();
            $modelPessoaFisica = new Pessoafisica('cadastro');
            $modelColaborador = new Colaborador();
        }
        
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroColaborador')
		{   
            echo CActiveForm::validate(array($modelPessoa,$modelPessoaFisica, $modelColaborador));
            
			Yii::app()->end();
		}
        
        if(isset($_POST['Pessoa']))
		{
            if(!$id)
            {
                $modelPessoa->attributes = $_POST['Pessoa'];
            }
            
            $modelPessoa->tp_pessoa = Pessoa::TP_PESSOA_FISICA;
            
            if($modelPessoa->validate())
            {
                $transacao = Yii::app()->db->beginTransaction();
                
                try
                {
                    $modelPessoa->save();
                       
                    $modelPessoaFisica->attributes = $_POST['Pessoafisica'];
                    $modelPessoaFisica->tp_pessoaFisica = Pessoafisica::TP_PESSOAFISICA_COLABORADOR;
                    $modelPessoaFisica->id_pessoa = $modelPessoa->id_pessoa;
                    
                    $modelColaborador->attributes = $_POST['Colaborador'];
                    
                    //se não for update                    
                    if(empty($modelColaborador->id_pessoa))
                    {
                        $modelColaborador->id_pessoa = $modelPessoa->id_pessoa;
                    }

                    $modelPessoaFisica->save();

                    $modelColaborador->save();

                    $transacao->commit();
                    
                    $id ?
                        Yii::app()->user->setFlash('success', "Colaborador $modelPessoa->nm_pessoa alterado com sucesso!"):
                        Yii::app()->user->setFlash('success', "Colaborador $modelPessoa->nm_pessoa cadastrado com sucesso!");
                    
                    $modelPessoa = new Pessoa();
                    $modelPessoaFisica = new Pessoafisica('cadastro');
                    $modelColaborador = new Colaborador();
                }
                catch (Exception $e)
                {
                    $transacao->rollback();
                }
            }
		}

        //filtro do grid
        if(isset($_GET['Colaborador']))
        {
            $modelColaborador->unsetAttributes();
            $modelColaborador->id_pessoa = $_GET['Colaborador']['id_pessoa'];
            $modelColaborador->nu_cpf = $_GET['Colaborador']['nu_cpf'];
            $modelColaborador->nm_pessoa = $_GET['Colaborador']['nm_pessoa'];
            $modelColaborador->nu_colaborador = $_GET['Colaborador']['nu_colaborador'];
            $modelColaborador->nm_cargoColaborador = $_GET['Colaborador']['nm_cargoColaborador'];
        }
        
		return $this->renderPartial('colaborador',
                            array('modelPessoa' => $modelPessoa,
                                  'modelPessoaFisica' => $modelPessoaFisica,
                                  'modelColaborador' => $modelColaborador,
                                  'modelCargoColaborador' => new Cargocolaborador()),
                            true
                        );
	}
        
    /**
	 * Página de Cadastro de Usuários
	 */
	protected function actionUsuario()
	{
        $modelUsuario = new Usuario();
        $modelColaborador = new Colaborador('usuario');
        
        if(isset($_POST['Usuario']) && isset($_POST['Colaborador']))
		{
            $modelUsuario->attributes = $_POST['Usuario'];
            if(isset($_POST['Usuario']['de_senha_confirmacao']))
            {
                $modelUsuario->de_senha_confirmacao = $_POST['Usuario']['de_senha_confirmacao'];
            }
            
            if(!empty($_POST['Usuario']['id_pessoa']))
            {
                $update = true;
                $modelUsuario->setScenario('update');
            } else {
                $update = false;
            }
            
            $modelColaborador->id_pessoa = $_POST['Colaborador']['id_pessoa'];
            $usuario = Usuario::model()->findByPk($modelColaborador->id_pessoa);
            if(!empty($usuario))
            {
                //se for update, ignora o id atual
                if($update)
                {
                    if($usuario->id_pessoa != $_POST['Usuario']['id_pessoa'])
                    {
                        $modelColaborador->addCustomError('id_pessoa','Este colaborador já está associado à um usuário');
                    }
                } else {
                    $modelColaborador->addCustomError('id_pessoa','Este colaborador já está associado à um usuário');
                }
            }
        }
        
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroUsuario')
		{
            echo CActiveForm::validate(array($modelUsuario,$modelColaborador));
			Yii::app()->end();
		}
        
        if(isset($_POST['Usuario']) && isset($_POST['Colaborador']))
		{
            
            $pk = $modelUsuario->id_pessoa;
            $modelUsuario->id_pessoa = $_POST['Colaborador']['id_pessoa'];
            
            if($modelUsuario->validate() && $modelColaborador->validate())
            {
                if(!$update || ($update && !empty($_POST['Usuario']['de_senha'])))
                {
                    $modelUsuario->de_senha = Yii::app()->bulebar->criptografaSenha($modelUsuario->de_senha);
                    $modelUsuario->de_senha_confirmacao = Yii::app()->bulebar->criptografaSenha($modelUsuario->de_senha_confirmacao);
                }
                
                if($update)
                {
                    Usuario::model()->updateByPk($pk,$modelUsuario->attributes);
                    Yii::app()->user->setFlash('success', "Usuário $modelUsuario->nm_login alterado com sucesso!");
                } else {
                    $modelUsuario->save();
                    Yii::app()->user->setFlash('success', "Usuário $modelUsuario->nm_login cadastrado com sucesso!");
                }   
            }
            
            $modelUsuario = new Usuario();
        }
        
        if(isset($_GET['Usuario']))
        {
            $modelUsuario->unsetAttributes();
            $modelUsuario->id_pessoa = $_GET['Usuario']['id_pessoa'];
            $modelUsuario->nm_login = $_GET['Usuario']['nm_login'];
        }
        
		return $this->renderPartial('usuario',
                            array('modelColaborador' => new Colaborador(),
                                  'modelUsuario' => $modelUsuario),
                            true
                        );
	}
    
    /**
	 * Página de Cadastro de Estabelecimento
	 */
	protected function actionEstabelecimento()
	{
        $modelEstabelecimento = new Estabelecimento();
                
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroEstabelecimento')
		{
            echo CActiveForm::validate(array($modelEstabelecimento));
			Yii::app()->end();
		}
        
        if(isset($_POST['Estabelecimento']))
		{
            $modelEstabelecimento->id_estabelecimento = $_POST['Estabelecimento']['id_estabelecimento'];
            $estabelecimento = Estabelecimento::model()->findByPk($modelEstabelecimento->id_estabelecimento);
            
            $modelEstabelecimento->attributes = $_POST['Estabelecimento'];
            
            $pk = $modelEstabelecimento->id_estabelecimento;
            $modelEstabelecimento->id_estabelecimento = $_POST['Estabelecimento']['id_estabelecimento'];
            
            if($modelEstabelecimento->validate())
            {
                if(!empty($_POST['Estabelecimento']['id_estabelecimento']))
                {
                    Estabelecimento::model()->updateByPk($pk,$modelEstabelecimento->attributes);
                    Yii::app()->user->setFlash('success', "Estabelecimento $modelEstabelecimento->nm_estabelecimento alterado com sucesso!");
                } else {
                    $modelEstabelecimento->save();
                    Yii::app()->user->setFlash('success', "Estabelecimento $modelEstabelecimento->nm_estabelecimento cadastrado com sucesso!");
                }   
            }
            
            $modelEstabelecimento = new Estabelecimento();
        }
        
        //filtro do grid
        if(isset($_GET['Estabelecimento']))
        {
            $modelEstabelecimento->unsetAttributes();
            $modelEstabelecimento->id_estabelecimento = $_GET['Estabelecimento']['id_estabelecimento'];
            $modelEstabelecimento->nm_estabelecimento = $_GET['Estabelecimento']['nm_estabelecimento'];
        }
        
		return $this->renderPartial('estabelecimento',
                            array('modelEstabelecimento' => $modelEstabelecimento),
                            true
                        );
	}
    
    /**
	 * Página de Cadastro de Categorias de lançamento
	 */
	protected function actionCategoriaLancamento()
	{
        $modelCategoriaLancamento = new Categorialancamento();
        
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroCategoriaLancamento')
		{
            echo CActiveForm::validate(array($modelCategoriaLancamento));
			Yii::app()->end();
		}
        
        if(isset($_POST['Categorialancamento']))
		{
            $modelCategoriaLancamento->attributes = $_POST['Categorialancamento'];
            
            $pk = $modelCategoriaLancamento->id_categoriaLancamento;
            $modelCategoriaLancamento->id_categoriaLancamento = $_POST['Categorialancamento']['id_categoriaLancamento'];
                        
            if($modelCategoriaLancamento->validate())
            {
                if(!empty($_POST['Categorialancamento']['id_categoriaLancamento']))
                {
                    Categorialancamento::model()->updateByPk($modelCategoriaLancamento->id_categoriaLancamento,$modelCategoriaLancamento->attributes);
                    Yii::app()->user->setFlash('success', "Categoria $modelCategoriaLancamento->nm_categoriaLancamento alterado com sucesso!");
                } else {
                    $modelCategoriaLancamento->save();
                    Yii::app()->user->setFlash('success', "Categoria $modelCategoriaLancamento->nm_categoriaLancamento cadastrado com sucesso!");
                }   
            }
            
            $modelCategoriaLancamento = new Categorialancamento();
        }
        
        //filtro do grid
        if(isset($_GET['Categorialancamento']))
        {
            $modelCategoriaLancamento->unsetAttributes();
            $modelCategoriaLancamento->id_categoriaLancamento = $_GET['Categorialancamento']['id_categoriaLancamento'];
            $modelCategoriaLancamento->nm_categoriaLancamento = $_GET['Categorialancamento']['nm_categoriaLancamento'];
            $modelCategoriaLancamento->tp_categoriaLancamento = $_GET['Categorialancamento']['tp_categoriaLancamento'];
            $modelCategoriaLancamento->nm_categoriaLancamentoPai = $_GET['Categorialancamento']['nm_categoriaLancamentoPai'];
        }
        
		return $this->renderPartial('categorialancamento',
                            array('modelCategoriaLancamento' => $modelCategoriaLancamento),
                            true
                        );
	}
    
    public function actionGetFornecedor()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $fornecedor = Fornecedor::model()->getFornecedorCompleto($_POST['idPessoa']);
        
        $json = new stdClass();
        
        foreach ($fornecedor as $key=>$value) {
            $json->$key = $value;
        }
        
        foreach ($fornecedor->idPessoa as $key=>$value) {
            $json->$key = $value;
        }
        
        if(!empty($fornecedor->pessoaFisica))
        {
            foreach ($fornecedor->pessoaFisica[0] as $key=>$value)
            {
                $json->$key = $value;
            }
        }
        
        if(!empty($fornecedor->pessoaJuridica))
        {
            foreach ($fornecedor->pessoaJuridica[0] as $key=>$value)
            {
                $json->$key = $value;
            }
        }
                
        echo CJSON::encode(array('fornecedor'=>$json));

        Yii::app()->end();
    }
    
    public function actionGetColaborador()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $colaborador = Colaborador::model()->getColaboradorCompleto($_POST['idPessoa']);
        
        $json = new stdClass();
        
        foreach ($colaborador as $key=>$value) {
            $json->$key = $value;
        }
        
        foreach ($colaborador->idPessoa as $key=>$value) {
            $json->$key = $value;
        }
        
        foreach ($colaborador->pessoaFisica as $key=>$value)
        {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('colaborador'=>$json));

        Yii::app()->end();
    }
    
    public function actionGetUsuario()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $usuario = Usuario::model()->findByPk($_POST['idPessoa']);
        
        $json = new stdClass();
        
        foreach ($usuario as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('usuario'=>$json));

        Yii::app()->end();
    }
    
    public function actionGetEstabelecimento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $estabelecimento = Estabelecimento::model()->findByPk($_POST['idEstabelecimento']);
        
        $json = new stdClass();
        
        foreach ($estabelecimento as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('Estabelecimento'=>$json));

        Yii::app()->end();
    }
    
    public function actionGetCategoriaLancamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $categoria = Categorialancamento::model()->findByPk($_POST['idCategoriaLancamento']);
        
        $json = new stdClass();
        
        foreach ($categoria as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('CategoriaLancamento'=>$json));

        Yii::app()->end();
    }
    
    public function actionDelPessoa()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idPessoa']))
        {
            $id = $_POST['idPessoa'];
            $modelPessoa = Pessoa::model()->findByPk($id);
            $modelPessoa->fl_inativo = true;
            $modelPessoa->save();
            
            Yii::app()->user->setFlash('success', "$modelPessoa->nm_pessoa removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover $modelPessoa->nm_pessoa!");
        }

        Yii::app()->end();
    }
    
    public function actionDelUsuario()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idPessoa']))
        {
            $id = $_POST['idPessoa'];
            $modelUsuario = Usuario::model()->findByPk($id);
            $modelUsuario->delete();
            
            Yii::app()->user->setFlash('success', "$modelUsuario->nm_login removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover $modelUsuario->nm_login!");
        }
        Yii::app()->end();
    }
    
    public function actionDelEstabelecimento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idEstabelecimento']))
        {
            $id = $_POST['idEstabelecimento'];
            $modelEstabelecimento = Estabelecimento::model()->findByPk($id);
            $modelEstabelecimento->delete();
            
            Yii::app()->user->setFlash('success', "$modelEstabelecimento->nm_estabelecimento removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover $modelEstabelecimento->nm_estabelecimento!");
        }
        Yii::app()->end();
    }
    
    public function actionDelCategoriaLancamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idCategoriaLancamento']))
        {
            $id = $_POST['idCategoriaLancamento'];
            $modelCategoriaLancamento = Categorialancamento::model()->findByPk($id);
            $modelCategoriaLancamento->delete();
            
            Yii::app()->user->setFlash('success', "$modelCategoriaLancamento->nm_categoriaLancamento removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover $modelCategoriaLancamento->nm_categoriaLancamento!");
        }
        Yii::app()->end();
    }
}