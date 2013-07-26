<?php

/**
 * This is the model class for table "formapagamento".
 *
 * The followings are the available columns in table 'formapagamento':
 * @property integer $id_formaPagamento
 * @property string $nm_formaPagamento
 *
 * The followings are the available model relations:
 * @property Estabelecimento[] $estabelecimentos
 * @property Lancamento[] $lancamentos
 */
class Formapagamento extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Formapagamento the static model class
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
		return 'formapagamento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nm_formaPagamento', 'required'),
			array('nm_formaPagamento', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_formaPagamento, nm_formaPagamento', 'safe', 'on'=>'search'),
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
			'estabelecimentos' => array(self::MANY_MANY, 'Estabelecimento', 'estabelecimento_formapagamento(id_formaPagamento, id_estabelecimento)'),
			'lancamentos' => array(self::HAS_MANY, 'Lancamento', 'id_formaPagamento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_formaPagamento' => 'Id Forma Pagamento',
			'nm_formaPagamento' => 'Nm Forma Pagamento',
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

		$criteria->compare('id_formaPagamento',$this->id_formaPagamento);
		$criteria->compare('nm_formaPagamento',$this->nm_formaPagamento,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}