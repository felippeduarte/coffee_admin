<?php

/**
 * This is the model class for table "pessoa".
 *
 * The followings are the available columns in table 'pessoa':
 * @property integer $id_pessoa
 * @property string $nm_pessoa
 * @property string $tp_pessoa
 * @property string $dt_nascimento
 *
 * The followings are the available model relations:
 * @property Lancamento[] $lancamentos
 * @property Pessoafisica $pessoafisica
 * @property Pessoajuridica $pessoajuridica
 */
class Pessoa extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pessoa the static model class
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
		return 'pessoa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nm_pessoa, tp_pessoa', 'required'),
			array('nm_pessoa', 'length', 'max'=>200),
			array('tp_pessoa', 'length', 'max'=>2),
			array('dt_nascimento', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_pessoa, nm_pessoa, tp_pessoa, dt_nascimento', 'safe', 'on'=>'search'),
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
			'lancamentos' => array(self::HAS_MANY, 'Lancamento', 'id_pessoaLancamento'),
			'pessoafisica' => array(self::HAS_ONE, 'Pessoafisica', 'id_pessoa'),
			'pessoajuridica' => array(self::HAS_ONE, 'Pessoajuridica', 'id_pessoa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pessoa' => 'Id Pessoa',
			'nm_pessoa' => 'Nm Pessoa',
			'tp_pessoa' => 'Tp Pessoa',
			'dt_nascimento' => 'Dt Nascimento',
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

		$criteria->compare('id_pessoa',$this->id_pessoa);
		$criteria->compare('nm_pessoa',$this->nm_pessoa,true);
		$criteria->compare('tp_pessoa',$this->tp_pessoa,true);
		$criteria->compare('dt_nascimento',$this->dt_nascimento,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}