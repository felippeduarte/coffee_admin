<?php

/**
 * This is the model class for table "categorialancamento".
 *
 * The followings are the available columns in table 'categorialancamento':
 * @property integer $id_categoriaLancamento
 * @property string $nm_categoriaLancamento
 * @property string $tp_categoriaLancamento
 * @property integer $id_categoriaLancamentoPai
 * @property bool $fl_ehFolhaPagamento
 *
 * The followings are the available model relations:
 * @property Categorialancamento $idCategoriaLancamentoPai
 * @property Categorialancamento[] $categorialancamentos
 * @property Lancamento[] $lancamentos
 */
class Categorialancamento extends CActiveRecord
{
    public $nm_categoriaLancamentoPai;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Categorialancamento the static model class
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
		return 'categorialancamento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nm_categoriaLancamento, tp_categoriaLancamento, tp_categoriaLancamentoPessoa', 'required'),
			array('id_categoriaLancamentoPai', 'numerical', 'integerOnly'=>true),
			array('nm_categoriaLancamento', 'length', 'max'=>45),
			array('tp_categoriaLancamento', 'length', 'max'=>1),
            array('fl_ehFolhaPagamento', 'in', 'range'=>array(0,1)),
            array('tp_categoriaLancamentoPessoa','in','range'=>$this->enumTipoCategoriaLancamentoPessoa(true),'allowEmpty'=>false),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_categoriaLancamento, nm_categoriaLancamento, tp_categoriaLancamento, id_categoriaLancamentoPai, tp_categoriaLancamentoPessoa, fl_ehFolhaPagamento', 'safe', 'on'=>'search'),
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
			'idCategoriaLancamentoPai' => array(self::BELONGS_TO, 'Categorialancamento', 'id_categoriaLancamentoPai'),
			'categorialancamentos' => array(self::HAS_MANY, 'Categorialancamento', 'id_categoriaLancamentoPai'),
			'lancamentos' => array(self::HAS_MANY, 'Lancamento', 'id_categoriaLancamento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_categoriaLancamento' => 'Id Categoria Lancamento',
			'nm_categoriaLancamento' => 'Nome Categoria Lancamento',
			'tp_categoriaLancamento' => 'Tipo Categoria Lancamento',
			'id_categoriaLancamentoPai' => 'Categoria Lancamento Pai',
            'tp_categoriaLancamentoPessoa' => 'Tipo Favorecido',
            'fl_ehFolhaPagamento' => 'É folha de pagamento?'
		);
	}

    private function enumTipoCategoriaLancamentoPessoa($completo=false)
    {
        return  $completo ? array('C','F','X') : array('C','F');
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

        $criteria->with = 'idCategoriaLancamentoPai';
		$criteria->compare('id_categoriaLancamento',$this->id_categoriaLancamento);
		$criteria->compare('nm_categoriaLancamento',$this->nm_categoriaLancamento,true);
        $criteria->compare('idCategoriaLancamentoPai.nm_categoriaLancamento',$this->nm_categoriaLancamentoPai,true);
		$criteria->compare('tp_categoriaLancamento',$this->tp_categoriaLancamento,true);
		$criteria->compare('id_categoriaLancamentoPai',$this->id_categoriaLancamentoPai);
        $criteria->compare('tp_categoriaLancamentoPessoa',$this->tp_categoriaLancamentoPessoa,true);
        $criteria->compare('fl_ehFolhaPagamento',$this->fl_ehFolhaPagamento,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    protected function beforeValidate()
    {
        //se for array transforma
        if($this->tp_categoriaLancamentoPessoa == $this->enumTipoCategoriaLancamentoPessoa())
        {
            $this->tp_categoriaLancamentoPessoa = 'X';
        } else {
            $this->tp_categoriaLancamentoPessoa = $this->tp_categoriaLancamentoPessoa[0];
        }
        return parent::beforeValidate();
    }
    
    protected function afterFind()
    {
        //se for array transforma
        if($this->tp_categoriaLancamentoPessoa == 'X')
        {
            $this->tp_categoriaLancamentoPessoa = $this->enumTipoCategoriaLancamentoPessoa();
        }
        
        return parent::afterFind();
    }
    
    public function getCategoriaLancamentoGrid()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idCategoriaLancamentoPai' => array('select'=>'nm_categoriaLancamento'),
        );
        
        $criteria->select = array(
            'id_categoriaLancamento','nm_categoriaLancamento','tp_categoriaLancamento','tp_categoriaLancamentoPessoa'
        );
        
        $criteria->compare('t.id_categoriaLancamento', $this->id_categoriaLancamento);
        $criteria->compare('t.nm_categoriaLancamento', $this->nm_categoriaLancamento, true);
        $criteria->compare('t.tp_categoriaLancamento', $this->tp_categoriaLancamento, true);
        $criteria->compare('idCategoriaLancamentoPai.nm_categoriaLancamento', $this->nm_categoriaLancamentoPai, true);
        $criteria->compare('tp_categoriaLancamentoPessoa', $this->tp_categoriaLancamentoPessoa,true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getComboCategoriaLancamento($tipoLancamento = null)
    {
        $criteria=new CDbCriteria;

        $criteria->select = array('id_categoriaLancamento','nm_categoriaLancamento');
        
        if(!empty($tipoLancamento))
        {
            $criteria->condition = 'tp_lancamento = '.$tipoLancamento[0];
        }
        
        return $this->findAll($criteria);
    }
    
    public function getNextId()
    {
        $id = Yii::app()->db->createCommand('SHOW TABLE STATUS LIKE "categorialancamento"')->queryAll();
        return $id[0]['Auto_increment'];
    }
    
    /**
     * Monta lista de "<option>" para usar em combobox com select2
     * @param string $tipo tp_lancamento (D ou R)
     * @return string html option
     */
    public function getHtmlDropdownOptionsCategoriasPorTipo($tipo)
    {
        $data = Categorialancamento::model()->findAll('tp_categoriaLancamento = :tp_categoriaLancamento', 
        array(':tp_categoriaLancamento'=>$tipo));

        $data = CHtml::listData($data,'id_categoriaLancamento','nm_categoriaLancamento');

        return $this->generateHtmlDropDown($data);
    }
    
    public function getComboCategoriaLancamentoFolha()
    {
        $criteria=new CDbCriteria;

        $criteria->select = array('id_categoriaLancamento','nm_categoriaLancamento');
        
        $criteria->condition = 'fl_ehFolhaPagamento = 1';
        
        return $this->findAll($criteria);
    }
    
    private function generateHtmlDropDown($data)
    {
        $opt = "<option value></option>";
        
        foreach($data as $id_categoriaLancamento=>$nm_categoriaLancamento)
        {
            $opt .= CHtml::tag('option', array('value'=>$id_categoriaLancamento),CHtml::encode($nm_categoriaLancamento),true);
        }
        
        return $opt;
    }
    
    public function getTipoCategoriaLancamento($abreviacao)
    {
        if ($abreviacao == 'D') return "Despesa";
        else if ($abreviacao == 'R') return "Receita";
    }
    
    public function getTipoCategoriaLancamentoPessoa($abreviacao)
    {
        if ($abreviacao == 'C') 
        {
            return "Colaborador";
        }
        else if ($abreviacao == 'F') 
        {
            return "Fornecedor";
        }
        else if (($abreviacao == 'X')||($abreviacao == $this->enumTipoCategoriaLancamentoPessoa()))
        {
            return "Colaborador/Fornecedor";
        }
    }
    
    /**
     * Retorna lista de categorias
     * @param string $tp_categoriaLancamento R ou D
     * @param bool $fl_ehFolhaPagamento
     */
    public function getCategoriasLancamento($tp_categoriaLancamento = null, $fl_ehFolhaPagamento = null)
    {
        $criteria = new CDbCriteria;
        
        $criteria->select = array(
            'id_categoriaLancamento',
            'nm_categoriaLancamento',
            'tp_categoriaLancamento',
            'id_categoriaLancamentoPai',
            'fl_ehFolhaPagamento'
        );
        
        $condicao = null;
        
        if(!empty($tp_categoriaLancamento))
        {
            $condicao[] = 'tp_categoriaLancamento = '.$tp_categoriaLancamento;
        }
        
        if(!empty($fl_ehFolhaPagamento))
        {
            $condicao[] = 'fl_ehFolhaPagamento = '.$fl_ehFolhaPagamento;
        }
        
        $criteria->condition = join(' AND ', $condicao);
        
        return $this->findAll($criteria);
    }
}