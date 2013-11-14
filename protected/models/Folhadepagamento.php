<?php

/**
 * This is the model class for table "folhadepagamento".
 *
 * The followings are the available columns in table 'folhadepagamento':
 * @property integer $id_lancamento
 * @property integer $id_categoriaLancamento
 * @property string $vl_lancamento
 */
class Folhadepagamento extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'folhadepagamento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_lancamento, id_categoriaLancamento, vl_lancamento', 'required'),
			array('id_lancamento, id_categoriaLancamento', 'numerical', 'integerOnly'=>true),
			array('vl_lancamento', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_lancamento, id_categoriaLancamento, vl_lancamento', 'safe', 'on'=>'search'),
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
			'id_lancamento' => 'Id Lancamento',
			'id_categoriaLancamento' => 'Id Categoria Lancamento',
			'vl_lancamento' => 'Vl Lancamento',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_lancamento',$this->id_lancamento);
		$criteria->compare('id_categoriaLancamento',$this->id_categoriaLancamento);
		$criteria->compare('vl_lancamento',$this->vl_lancamento,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Folhadepagamento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
