<?php

/**
 * This is the model class for table "lancamento".
 *
 * The followings are the available columns in table 'lancamento':
 * @property integer $id_lancamento
 * @property string $dt_lancamento
 * @property string $vl_lancamento
 * @property integer $id_estabelecimento
 * @property integer $id_categoriaLancamento
 * @property integer $id_pessoaLancamento
 * @property integer $id_formaPagamento
 * @property string $nm_turno
 * @property string $de_observacao
 * @property integer $id_pessoaUsuario
 * @property string $dt_ultimaAlteracao
 * @property string $fl_inativo
 * @property string $id_lancamentoVinculado
 *
 * The followings are the available model relations:
 * @property Estabelecimento $idEstabelecimento
 * @property Categorialancamento $idCategoriaLancamento
 * @property Pessoa $idPessoaLancamento
 * @property Formapagamento $idFormaPagamento
 * @property Usuario $idPessoaUsuario
 */
class Lancamento extends CActiveRecord
{
    public $nm_pessoa;
    public $nm_estabelecimento;
    public $nm_categoriaLancamento;
    public $tp_categoriaLancamento;
    public $soma;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lancamento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lancamento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dt_lancamento, vl_lancamento, id_categoriaLancamento, id_formaPagamento', 'required', 'on'=>'ajax_R,insert_R,insert'),
            array('dt_lancamento, vl_lancamento, id_categoriaLancamento, id_pessoaLancamento, id_formaPagamento', 'required', 'on'=>'ajax_D,insert_D'),
            array('dt_lancamento, vl_lancamento, id_categoriaLancamento, id_pessoaLancamento, id_formaPagamento', 'required', 'on'=>'folhaDePagamento,ajaxFolhaDePagamento'),
            array('id_estabelecimento, id_categoriaLancamento, id_pessoaLancamento, id_formaPagamento, id_pessoaUsuario', 'numerical', 'integerOnly'=>true),
			array('vl_lancamento', 'length', 'max'=>12),
			array('nm_turno', 'length', 'max'=>1),
			array('de_observacao', 'length', 'max'=>4000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_lancamento, dt_lancamento, vl_lancamento, id_estabelecimento, id_categoriaLancamento, id_pessoaLancamento, id_formaPagamento, nm_turno, de_observacao, id_pessoaUsuario, dt_ultimaAlteracao', 'safe', 'on'=>'search, folhaDePagamento'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idEstabelecimento' => array(self::BELONGS_TO, 'Estabelecimento', 'id_estabelecimento'),
			'idCategoriaLancamento' => array(self::BELONGS_TO, 'Categorialancamento', 'id_categoriaLancamento'),
			'idPessoaLancamento' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoaLancamento'),
			'idFormaPagamento' => array(self::BELONGS_TO, 'Formapagamento', 'id_formaPagamento'),
			'idPessoaUsuario' => array(self::BELONGS_TO, 'Usuario', 'id_pessoaUsuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
        $labels = array(
                    'id_lancamento' => 'Id Lancamento',
                    'dt_lancamento' => 'Data Lancamento',
                    'vl_lancamento' => 'Valor',
                    'id_estabelecimento' => 'Estabelecimento',
                    'id_categoriaLancamento' => 'Categoria Lancamento',
                    'id_pessoaLancamento' => 'Favorecido',
                    'id_formaPagamento' => 'Forma Pagamento',
                    'nm_turno' => 'Turno',
                    'de_observacao' => 'Observação',
                    'id_pessoaUsuario' => 'Usuário',
                    'dt_ultimaAlteracao' => 'Data Última Alteração',
                );
        
        switch ($this->scenario)
        {
            case 'folhaDePagamento':
                $labels['id_formaPagamento'] = 'Origem Pagamento';
                break;
            default:
                break;
        }
        
        return $labels;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_lancamento',$this->id_lancamento);
		$criteria->compare('dt_lancamento',$this->dt_lancamento,true);
		$criteria->compare('vl_lancamento',$this->vl_lancamento,true);
		$criteria->compare('id_estabelecimento',$this->id_estabelecimento);
		$criteria->compare('id_categoriaLancamento',$this->id_categoriaLancamento);
		$criteria->compare('id_pessoaLancamento',$this->id_pessoaLancamento);
		$criteria->compare('id_formaPagamento',$this->id_formaPagamento);
		$criteria->compare('nm_turno',$this->nm_turno,true);
		$criteria->compare('de_observacao',$this->de_observacao,true);
		$criteria->compare('id_pessoaUsuario',$this->id_pessoaUsuario);
		$criteria->compare('dt_ultimaAlteracao',$this->dt_ultimaAlteracao,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    protected function afterFind()
    {
        $this->model2view();
        return parent::afterFind();
    }
    
    protected function beforeValidate()
    {
        if($this->scenario == 'ajaxFolhaDePagamento')
        {
            $this->vl_lancamento = $this->vl_lancamento[0];
            $this->id_categoriaLancamento = $this->id_categoriaLancamento[0];
        }
        $this->view2model();
        return parent::beforeValidate();
    }
    
    protected function afterValidate()
    {
        $this->model2view();        
        return parent::afterValidate();
    }
    
    protected function beforeSave()
    {
        if(empty($this->nm_turno)) $this->nm_turno = null;
        $this->id_pessoaUsuario = Yii::app()->user->getId();
        $this->dt_ultimaAlteracao = date('d/m/Y H:i:s');
        $this->view2model();
        $this->negativaValores();
        return parent::beforeSave();
    }
    
    protected function afterSave()
    {
        $this->model2view();
        if($this->scenario != 'folhaDePagamento')
        {
            $this->lancamentoVinculado();
        }
        if($this->isNewRecord)
        {
            $this->id_lancamento = Yii::app()->db->getLastInsertID();
        }
        
        return parent::afterSave();
    }
    
    private function negativaValores()
    {
        if($this->tp_categoriaLancamento == 'D')
        {
            $this->vl_lancamento = -1 * $this->vl_lancamento;
        }
    }   
    
    private function lancamentoVinculado()
    {
        if(empty($this->id_lancamentoVinculado))
        {
            //apenas para receitas
            if($this->tp_categoriaLancamento == 'R')
            {
                $estabelecimentoFormaPagamento = EstabelecimentoFormapagamento::model()->findByPk(array(
                'id_estabelecimento' => $this->id_estabelecimento, 
                'id_formaPagamento' => $this->id_formaPagamento));

                if(!empty($estabelecimentoFormaPagamento))
                {
                    $lancamento = new Lancamento();
                    $lancamento->id_lancamentoVinculado = $this->id_lancamento;
                    $lancamento->attributes = $this->attributes;
                    $lancamento->vl_lancamento = -1 * round((Yii::app()->bulebar->trocaDecimalViewParaModel($this->vl_lancamento) * $estabelecimentoFormaPagamento->nu_taxaPercentual/100),2);
                    
                    $lancamento->save();
                    
                    //atualiza id vinculado do lançamento principal
                    $this->id_lancamentoVinculado = $lancamento->id_lancamento;
                    $this->isNewRecord = false;
                    $this->save();
                }
            }
        }
    }
    
    private function model2view()
    {
        if(!empty($this->dt_lancamento))
        {
            $this->dt_lancamento = Yii::app()->bulebar->trocaDataModelParaView($this->dt_lancamento);
        }
        if(!empty($this->dt_ultimaAlteracao))
        {
            $this->dt_ultimaAlteracao = Yii::app()->bulebar->trocaTimestampModelParaView($this->dt_ultimaAlteracao);
        }
        
        if(!empty($this->vl_lancamento))
        {
            $this->vl_lancamento = Yii::app()->bulebar->trocaDecimalModelParaView($this->vl_lancamento);
        }
    }
    
    private function view2model()
    {
        if(!empty($this->dt_lancamento))
        {
            $this->dt_lancamento = Yii::app()->bulebar->trocaDataViewParaModel($this->dt_lancamento);
        }
        if(!empty($this->dt_ultimaAlteracao))
        {
            $this->dt_ultimaAlteracao = Yii::app()->bulebar->trocaTimestampViewParaModel($this->dt_ultimaAlteracao);
        }
        
        if(!empty($this->vl_lancamento))
        {
            $this->vl_lancamento = Yii::app()->bulebar->trocaDecimalViewParaModel($this->vl_lancamento);
        }
    }
    
    public function getLancamentoGrid($dataInicio, $dataFim, $estabelecimento, $categoria)
	{
		$criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idPessoaLancamento' => array('select'=>'nm_pessoa'),
            'idEstabelecimento' => array('select'=>'nm_estabelecimento'),
            'idCategoriaLancamento' => array('select'=>'nm_categoriaLancamento,tp_categoriaLancamento'),
            'idPessoaLancamento' => array('select'=>'nm_pessoa'),
        );
        
        $criteria->select = array(
            'id_lancamento','dt_lancamento','vl_lancamento','id_lancamentoVinculado','id_pessoaLancamento'
        );
        
        $condicao = array('t.fl_inativo = 0 AND idCategoriaLancamento.fl_ehFolhaPagamento = 0');
        
        if(!empty($dataInicio))
        {
            $condicao[] = 'dt_lancamento >= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataInicio).'"';
        }
        if(!empty($dataFim))
        {
            $condicao[] = 'dt_lancamento <= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataFim).'"';
        }
        if(!empty($estabelecimento))
        {
            $condicao[] = 't.id_estabelecimento = '.(int)$estabelecimento;
        }
        if(!empty($categoria))
        {
            $condicao[] = 't.id_categoriaLancamento = '.(int)$categoria;
        }
        
        $criteria->condition = join(' AND ', $condicao);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getFolhaDePagamentoGrid($dataInicio, $dataFim, $estabelecimento)
	{
		$criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idPessoaLancamento' => array('select'=>'nm_pessoa'),
            'idEstabelecimento' => array('select'=>'nm_estabelecimento'),
            'idCategoriaLancamento' => array('select'=>'nm_categoriaLancamento'),
            'idPessoaLancamento' => array('select'=>'nm_pessoa'),
        );
        
        $criteria->select = array(
            'id_lancamento','dt_lancamento','vl_lancamento','id_lancamentoVinculado'
        );
        
        $condicao = array(
            't.fl_inativo = 0',
            'idCategoriaLancamento.fl_ehFolhaPagamento = 1'
        );
        
        if(!empty($dataInicio))
        {
            $condicao[] = 'dt_lancamento >= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataInicio).'"';
        }
        if(!empty($dataFim))
        {
            $condicao[] = 'dt_lancamento <= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataFim).'"';
        }
        if(!empty($estabelecimento))
        {
            $condicao[] = 't.id_estabelecimento = '.(int)$estabelecimento;
        }
        
        $criteria->condition = join(' AND ', $condicao);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getLancamentos($dataInicio=null, $dataFim=null, $idEstabelecimento=null, $idCategoriaLancamento=null, $tpCategoriaLancamento=null)
    {
        $criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idPessoaLancamento' => array('select'=>'nm_pessoa'),
            'idEstabelecimento' => array('select'=>'nm_estabelecimento'),
            'idCategoriaLancamento' => array('select'=>'nm_categoriaLancamento'),
            'idPessoaLancamento' => array('select'=>'nm_pessoa'),
        );
        
        $criteria->select = array(
            'id_lancamento','dt_lancamento','vl_lancamento','id_lancamentoVinculado'
        );
        
        $condicao = array(
            't.fl_inativo = 0'
        );
        
        if(!empty($dataInicio))
        {
            $condicao[] = 'dt_lancamento >= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataInicio).'"';
        }
        if(!empty($dataFim))
        {
            $condicao[] = 'dt_lancamento <= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataFim).'"';
        }
        if(!empty($idEstabelecimento))
        {
            $condicao[] = 't.id_estabelecimento = '.(int)$idEstabelecimento;
        }
        if(!empty($idCategoriaLancamento))
        {
            $condicao[] = 't.id_categoriaLancamento = '.(int)$idCategoriaLancamento;
        }
        if(!empty($tpCategoriaLancamento))
        {
            $condicao[] = 'idCategoriaLancamento.tp_categoriaLancamento = "'.$tpCategoriaLancamento.'"';
        }
        
        $criteria->condition = join(' AND ', $condicao);
        
		return $this->findAll($criteria);
    }
    
    public function getLancamentosSumarizado($dataInicio=null, $dataFim=null, $idEstabelecimento=null, $idCategoriaLancamento=null, $tpCategoriaLancamento=null)
    {
        $criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idEstabelecimento' => array('select'=>'nm_estabelecimento'),
            'idCategoriaLancamento' => array('select'=>'nm_categoriaLancamento'),
        );
        
        $criteria->select = array('SUM(vl_lancamento) as soma');
        
        $condicao = array(
            't.fl_inativo = 0'
        );
        
        if(!empty($dataInicio))
        {
            $condicao[] = 'dt_lancamento >= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataInicio).'"';
        }
        if(!empty($dataFim))
        {
            $condicao[] = 'dt_lancamento <= "'.Yii::app()->bulebar->trocaDataViewParaModel($dataFim).'"';
        }
        if(!empty($idEstabelecimento))
        {
            $condicao[] = 't.id_estabelecimento = '.(int)$idEstabelecimento;
        }
        if(!empty($idCategoriaLancamento))
        {
            $condicao[] = 't.id_categoriaLancamento = '.(int)$idCategoriaLancamento;
        }
        if(!empty($tpCategoriaLancamento))
        {
            $condicao[] = 'idCategoriaLancamento.tp_categoriaLancamento = "'.$tpCategoriaLancamento.'"';
        }
        
        $criteria->condition = join(' AND ', $condicao);
        
		return $this->findAll($criteria);
    }
    
    public function getRadioButtonsTurno()
    {
        return array(
            array('label' => 'Matutino', 'htmlOptions'=> array('value'=>'M')),
            array('label' => 'Vespertino', 'htmlOptions'=> array('value'=>'V')),
            array('label' => 'Noturno', 'htmlOptions'=> array('value'=>'N')),
            array('label' => 'Madrugada', 'htmlOptions'=> array('value'=>'Ma'))
        );
    }
}