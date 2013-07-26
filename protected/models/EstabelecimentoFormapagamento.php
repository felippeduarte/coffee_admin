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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_estabelecimento' => 'Id Estabelecimento',
			'id_formaPagamento' => 'Id Forma Pagamento',
			'nu_taxaPercentual' => 'Nu Taxa Percentual',
		);
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
}