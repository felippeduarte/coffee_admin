<?php

/**
 * This is the model class for table "estabelecimento_formapagamento".
 *
 * The followings are the available columns in table 'estabelecimento_formapagamento':
 * @property integer $id_estabelecimento
 * @property integer $id_formaPagamento
 * @property string $nu_taxaPercentual
 */
class EstabelecimentoFormapagamento extends CActiveRecord
{
    public $nm_estabelecimento;
    public $nm_formaPagamento;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EstabelecimentoFormapagamento the static model class
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
		return 'estabelecimento_formapagamento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_estabelecimento, id_formaPagamento, nu_taxaPercentual', 'required'),
			array('id_estabelecimento, id_formaPagamento', 'numerical', 'integerOnly'=>true),
			array('nu_taxaPercentual', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_estabelecimento, id_formaPagamento, nu_taxaPercentual', 'safe', 'on'=>'search'),
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
            'idFormaPagamento' => array(self::BELONGS_TO, 'Formapagamento', 'id_formaPagamento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_estabelecimento' => 'Nome Estabelecimento',
			'id_formaPagamento' => 'Nome Forma de Pagamento',
			'nu_taxaPercentual' => 'Taxa Percentual',
		);
	}

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            $this->nu_taxaPercentual = str_replace(",", ".", $this->nu_taxaPercentual);
            return true;
        }
        return false;
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

		$criteria->compare('id_estabelecimento',$this->id_estabelecimento);
		$criteria->compare('id_formaPagamento',$this->id_formaPagamento);
		$criteria->compare('nu_taxaPercentual',$this->nu_taxaPercentual,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getEstabelecimentoFormaPagamentoGrid()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
        
        $criteria->together = true;
        
        $criteria->with = array(
            'idFormaPagamento' => array('select'=>'nm_formaPagamento'),
            'idEstabelecimento'=> array('select'=>'nm_estabelecimento'),
        );
        
        $criteria->select = array(
            'id_estabelecimento', 'id_formaPagamento', 'nu_taxaPercentual'
        );
        
        $criteria->condition = 'idFormaPagamento.fl_inativo = :fl_inativo';
        $criteria->params = array(
            ':fl_inativo' => false,
        );
        
        $criteria->compare('t.id_estabelecimento', $this->id_estabelecimento);
        $criteria->compare('t.id_formaPagamento', $this->id_formaPagamento);
        $criteria->compare('idEstabelecimento.nm_estabelecimento', $this->nm_estabelecimento, true);
        $criteria->compare('idFormaPagamento.nm_formaPagamento', $this->nm_formaPagamento, true);
        $criteria->compare('t.nu_taxaPercentual', $this->nu_taxaPercentual, true);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}