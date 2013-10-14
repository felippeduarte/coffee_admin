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
                'CargoColaborador' => array(),
                'Usuario' => array(),
                'Estabelecimento' => array(),
                'GrupoEstabelecimento' => array(),
                'CategoriaLancamento' => array(),
                'FormaPagamento' => array(),
                'EstabelecimentoFormaPagamento' => array(),
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
                case 'cargocolaborador':
                    $titleBox = 'Cargo Colaborador';
                    $itemOptions['CargoColaborador'] = $optionsAtivo;
                    $viewForm = $this->actionCargoColaborador();
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
                case 'grupoestabelecimento':
                    $titleBox = 'Gupo Estabelecimento';
                    $itemOptions['GrupoEstabelecimento'] = $optionsAtivo;
                    $viewForm = $this->actionGrupoEstabelecimento();
                    break;
                case 'categorialancamento':
                    $titleBox = 'Categoria Lancamento';
                    $itemOptions['CategoriaLancamento'] = $optionsAtivo;
                    $viewForm = $this->actionCategoriaLancamento();
                    break;
                case 'formapagamento':
                    $titleBox = 'Forma Pagamento';
                    $itemOptions['FormaPagamento'] = $optionsAtivo;
                    $viewForm = $this->actionFormaPagamento();
                    break;
                case 'estabelecimentoformapagamento':
                    $titleBox = 'Tarifa';
                    $itemOptions['EstabelecimentoFormaPagamento'] = $optionsAtivo;
                    $viewForm = $this->actionEstabelecimentoFormaPagamento();
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
            
            $modelEstabelecimento->attributes = $_POST['Estabelecimento'];
            
            $modelEstabelecimento->id_estabelecimento = $_POST['Estabelecimento']['id_estabelecimento'];
            
            if($modelEstabelecimento->validate())
            {
                if(!empty($_POST['Estabelecimento']['id_estabelecimento']))
                {
                    Estabelecimento::model()->updateByPk($modelEstabelecimento->id_estabelecimento,$modelEstabelecimento->attributes);
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
	 * Página de Cadastro de Cargo do Colaborador
	 */
	protected function actionCargoColaborador()
	{
        $modelCargoColaborador = new CargoColaborador();
                
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroCargoColaborador')
		{
            echo CActiveForm::validate(array($modelCargoColaborador));
			Yii::app()->end();
		}
        
        if(isset($_POST['Cargocolaborador']))
		{
            $modelCargoColaborador->id_cargoColaborador = $_POST['Cargocolaborador']['id_cargoColaborador'];
            
            $modelCargoColaborador->attributes = $_POST['Cargocolaborador'];
            
            $modelCargoColaborador->id_cargoColaborador = $_POST['Cargocolaborador']['id_cargoColaborador'];
            
            if($modelCargoColaborador->validate())
            {
                if(!empty($_POST['Cargocolaborador']['id_cargoColaborador']))
                {
                    CargoColaborador::model()->updateByPk($modelCargoColaborador->id_cargoColaborador,$modelCargoColaborador->attributes);
                    Yii::app()->user->setFlash('success', "Cargo do Colaborador $modelCargoColaborador->nm_cargoColaborador alterado com sucesso!");
                } else {
                    $modelCargoColaborador->save();
                    Yii::app()->user->setFlash('success', "Cargo do Colaborador $modelCargoColaborador->nm_cargoColaborador cadastrado com sucesso!");
                }   
            }
            
            $modelCargoColaborador = new CargoColaborador();
        }
        
        //filtro do grid
        if(isset($_GET['Cargocolaborador']))
        {
            $modelCargoColaborador->unsetAttributes();
            $modelCargoColaborador->id_cargoColaborador = $_GET['Cargocolaborador']['id_cargoColaborador'];
            $modelCargoColaborador->nm_cargoColaborador = $_GET['Cargocolaborador']['nm_cargoColaborador'];
        }
        
		return $this->renderPartial('cargoColaborador',
                            array('modelCargoColaborador' => $modelCargoColaborador),
                            true
                        );
	}
    
    /**
	 * Página de Cadastro de Grupo Estabelecimento
	 */
	protected function actionGrupoEstabelecimento()
	{
        $modelGrupoEstabelecimento = new GrupoEstabelecimento();
                
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroGrupoEstabelecimento')
		{
            echo CActiveForm::validate(array($modelGrupoEstabelecimento));
			Yii::app()->end();
		}
        
        if(isset($_POST['Grupoestabelecimento']))
		{
            $modelGrupoEstabelecimento->id_grupoEstabelecimento = $_POST['Grupoestabelecimento']['id_grupoEstabelecimento'];
            
            $modelGrupoEstabelecimento->attributes = $_POST['Grupoestabelecimento'];
            
            $modelGrupoEstabelecimento->id_grupoEstabelecimento = $_POST['Grupoestabelecimento']['id_grupoEstabelecimento'];
            
            if($modelGrupoEstabelecimento->validate())
            {
                if(!empty($_POST['Grupoestabelecimento']['id_grupoEstabelecimento']))
                {
                    GrupoEstabelecimento::model()->updateByPk($modelGrupoEstabelecimento->id_grupoEstabelecimento,$modelGrupoEstabelecimento->attributes);
                    Yii::app()->user->setFlash('success', "Grupo Estabelecimento $modelGrupoEstabelecimento->nm_grupoEstabelecimento alterado com sucesso!");
                } else {
                    $modelGrupoEstabelecimento->save();
                    Yii::app()->user->setFlash('success', "Grupo Estabelecimento $modelGrupoEstabelecimento->nm_grupoEstabelecimento cadastrado com sucesso!");
                }   
            }
            
            $modelGrupoEstabelecimento = new GrupoEstabelecimento();
        }
        
        //filtro do grid
        if(isset($_GET['Grupoestabelecimento']))
        {
            $modelGrupoEstabelecimento->unsetAttributes();
            $modelGrupoEstabelecimento->id_grupoEstabelecimento = $_GET['Grupoestabelecimento']['id_grupoEstabelecimento'];
            $modelGrupoEstabelecimento->nm_grupoEstabelecimento = $_GET['Grupoestabelecimento']['nm_grupoEstabelecimento'];
        }
        
		return $this->renderPartial('grupoEstabelecimento',
                            array('modelGrupoEstabelecimento' => $modelGrupoEstabelecimento),
                            true
                        );
	}
    
    /**
	 * Página de Cadastro de Forma Pagamento
	 */
	protected function actionFormaPagamento()
	{
        $modelFormaPagamento = new FormaPagamento();
                
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroFormaPagamento')
		{
            echo CActiveForm::validate(array($modelFormaPagamento));
			Yii::app()->end();
		}
        
        if(isset($_POST['Formapagamento']))
		{
            $modelFormaPagamento->id_formaPagamento = $_POST['Formapagamento']['id_formaPagamento'];
            
            $modelFormaPagamento->attributes = $_POST['Formapagamento'];
            
            if($modelFormaPagamento->validate())
            {
                if(!empty($_POST['Formapagamento']['id_formaPagamento']))
                {
                    FormaPagamento::model()->updateByPk($modelFormaPagamento->id_formaPagamento,$modelFormaPagamento->attributes);
                    Yii::app()->user->setFlash('success', "Forma de Pagamento $modelFormaPagamento->nm_formaPagamento alterado com sucesso!");
                } else {
                    $modelFormaPagamento->save();
                    Yii::app()->user->setFlash('success', "Forma de Pagamento $modelFormaPagamento->nm_formaPagamento cadastrado com sucesso!");
                }   
            }
            
            $modelFormaPagamento = new FormaPagamento();
        }
        
        //filtro do grid
        if(isset($_GET['Formapagamento']))
        {
            $modelFormaPagamento->unsetAttributes();
            $modelFormaPagamento->id_formaPagamento = $_GET['Formapagamento']['id_formaPagamento'];
            $modelFormaPagamento->nm_formaPagamento = $_GET['Formapagamento']['nm_formaPagamento'];
        }
        
		return $this->renderPartial('formaPagamento',
                            array('modelFormaPagamento' => $modelFormaPagamento),
                            true
                        );
	}
    
    /**
	 * Página de Cadastro de Estabelecimento Forma Pagamento
	 */
	protected function actionEstabelecimentoFormaPagamento()
	{
        $modelEstabelecimentoFormaPagamento = new EstabelecimentoFormapagamento();
        
        if(isset($_POST['ajax']) && $_POST['ajax']==='cadastroEstabelecimentoFormaPagamento')
		{
            echo CActiveForm::validate(array($modelEstabelecimentoFormaPagamento));
			Yii::app()->end();
		}
        
        if(isset($_POST['EstabelecimentoFormapagamento']))
		{
            $modelEstabelecimentoFormaPagamento->id_formaPagamento = $_POST['EstabelecimentoFormapagamento']['id_formaPagamento'];
            $modelEstabelecimentoFormaPagamento->id_estabelecimento = $_POST['EstabelecimentoFormapagamento']['id_estabelecimento'];
            
            $modelEstabelecimentoFormaPagamento->attributes = $_POST['EstabelecimentoFormapagamento'];
            
            if($modelEstabelecimentoFormaPagamento->validate())
            {
                $pks = array(
                    'id_formaPagamento' => $modelEstabelecimentoFormaPagamento->id_formaPagamento,
                    'id_estabelecimento'=> $modelEstabelecimentoFormaPagamento->id_estabelecimento
                    );
                $estabelecimentoFormaPagamento = EstabelecimentoFormapagamento::model()->findByPk($pks);
                
                $estabelecimento = Estabelecimento::model()->findByPk($modelEstabelecimentoFormaPagamento->id_estabelecimento);
                $formaPagamento = Formapagamento::model()->findByPk($modelEstabelecimentoFormaPagamento->id_formaPagamento);
                
                //update
                if((!empty($_POST['Estabelecimento']['id_estabelecimento']))&&(!empty($_POST['Formapagamento']['id_formaPagamento'])))
                {
                    $estabelecimentoFormaPagamento->attributes = $modelEstabelecimentoFormaPagamento->attributes;
                    $estabelecimentoFormaPagamento->save();
                    Yii::app()->user->setFlash('success', "Forma de pagamento ".$formaPagamento->nm_formaPagamento." para o estabelecimento ".$estabelecimento->nm_estabelecimento." alterado com sucesso!");
                } //insert
                else {
                    $modelEstabelecimentoFormaPagamento->save();
                    Yii::app()->user->setFlash('success', "Forma de pagamento ".$formaPagamento->nm_formaPagamento." para o estabelecimento ".$estabelecimento->nm_estabelecimento." cadastrado com sucesso!");
                }
            }
            
            $modelEstabelecimentoFormaPagamento = new EstabelecimentoFormapagamento();
        }
        
        //filtro do grid
        if(isset($_GET['EstabelecimentoFormapagamento']))
        {
            $modelEstabelecimentoFormaPagamento->unsetAttributes();
            $modelEstabelecimentoFormaPagamento->id_formaPagamento = $_GET['EstabelecimentoFormapagamento']['id_formaPagamento'];
            $modelEstabelecimentoFormaPagamento->nm_formaPagamento = $_GET['EstabelecimentoFormapagamento']['nm_formaPagamento'];
            $modelEstabelecimentoFormaPagamento->id_estabelecimento = $_GET['EstabelecimentoFormapagamento']['id_estabelecimento'];
            $modelEstabelecimentoFormaPagamento->nm_estabelecimento = $_GET['EstabelecimentoFormapagamento']['nm_estabelecimento'];
            $modelEstabelecimentoFormaPagamento->nu_taxaPercentual = $_GET['EstabelecimentoFormapagamento']['nu_taxaPercentual'];
        }
        
		return $this->renderPartial('estabelecimentoformapagamento',
                            array('modelEstabelecimentoFormaPagamento' => $modelEstabelecimentoFormaPagamento,
                                  'modelEstabelecimento' => new Estabelecimento(),
                                  'modelFormaPagamento' => new FormaPagamento()),
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
    
    public function actionGetCargoColaborador()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $cargoColaborador = CargoColaborador::model()->findByPk($_POST['idCargoColaborador']);
        
        $json = new stdClass();
        
        foreach ($cargoColaborador as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('CargoColaborador'=>$json));

        Yii::app()->end();
    }   
    
    public function actionGetGrupoEstabelecimento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $grupoEstabelecimento = GrupoEstabelecimento::model()->findByPk($_POST['idGrupoEstabelecimento']);
        
        $json = new stdClass();
        
        foreach ($grupoEstabelecimento as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('GrupoEstabelecimento'=>$json));

        Yii::app()->end();
    }
    
    public function actionGetFormaPagamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $formaPagamento = FormaPagamento::model()->findByPk($_POST['idFormaPagamento']);
        
        $json = new stdClass();
        
        foreach ($formaPagamento as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('FormaPagamento'=>$json));

        Yii::app()->end();
    }
    
    public function actionGetEstabelecimentoFormaPagamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        
        $pks = array(
            'id_formaPagamento' => $_POST['idFormaPagamento'],
            'id_estabelecimento'=> $_POST['idEstabelecimento']
            );
        
        $estabelecimentoFormaPagamento = EstabelecimentoFormapagamento::model()->findByPk($pks);
        
        $json = new stdClass();
        
        foreach ($estabelecimentoFormaPagamento as $key=>$value) {
            $json->$key = $value;
        }
        
        echo CJSON::encode(array('EstabelecimentoFormaPagamento'=>$json));

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
    
    public function actionDelCargoColaborador()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idCargoColaborador']))
        {
            $id = $_POST['idCargoColaborador'];
            $modelCargoColaborador = CargoColaborador::model()->findByPk($id);
            $modelCargoColaborador->delete();
            
            Yii::app()->user->setFlash('success', "$modelCargoColaborador->nm_cargoColaborador removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover $modelCargoColaborador->nm_cargoColaborador!");
        }
        Yii::app()->end();
    }
    
    public function actionDelGrupoEstabelecimento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idGrupoEstabelecimento']))
        {
            $id = $_POST['idGrupoEstabelecimento'];
            $modelGrupoEstabelecimento = GrupoEstabelecimento::model()->findByPk($id);
            $modelGrupoEstabelecimento->delete();
            
            Yii::app()->user->setFlash('success', "$modelGrupoEstabelecimento->nm_grupoEstabelecimento removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover $modelGrupoEstabelecimento->nm_grupoEstabelecimento!");
        }
        Yii::app()->end();
    }
    
    public function actionDelEstabelecimentoFormaPagamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idFormaPagamento']) && (isset($_POST['idEstabelecimento'])))
        {
            $pks = array(
                'id_formaPagamento' => $_POST['idFormaPagamento'],
                'id_estabelecimento'=> $_POST['idEstabelecimento']
            );
            
            $modelEstabelecimentoFormaPagamento = EstabelecimentoFormapagamento::model()->findByPk($pks);
            $modelEstabelecimentoFormaPagamento->delete();
            
            Yii::app()->user->setFlash('success', "Forma de pagamento $modelEstabelecimentoFormaPagamento->nm_formaPagamento para o estabelecimento $modelEstabelecimentoFormaPagamento->nm_estabelecimento foi removido com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover a forma de pagamento $modelEstabelecimentoFormaPagamento->nm_formaPagamento para o estabelecimento $modelEstabelecimentoFormaPagamento->nm_estabelecimento!");
        }
        Yii::app()->end();
    }
    
    public function actionDelFormaPagamento()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        if (isset($_POST['idFormaPagamento']))
        {
            $id = $_POST['idFormaPagamento'];
            $modelFormaPagamento = FormaPagamento::model()->findByPk($id);
            $modelFormaPagamento->fl_inativo = true;
            $modelFormaPagamento->save();
            
            Yii::app()->user->setFlash('success', "$modelFormaPagamento->nm_formaPagamento removido(a) com sucesso!");
        } else {
            Yii::app()->user->setFlash('error', "Ocorreu um erro ao remover $modelFormaPagamento->nm_formaPagamento!");
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