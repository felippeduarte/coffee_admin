<?php

/**
 * This is the model class for table "colaborador".
 *
 * The followings are the available columns in table 'colaborador':
 * @property integer $id_pessoa
 * @property integer $nu_colaborador
 * @property integer $id_cargoColaborador
 *
 * The followings are the available model relations:
 * @property Cargocolaborador $idCargoColaborador
 * @property Pessoafisica $idPessoa
 * @property Usuario $usuario
 */
class Colaborador extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Colaborador the static model class
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
		return 'colaborador';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa, id_cargoColaborador', 'required'),
			array('id_pessoa, nu_colaborador, id_cargoColaborador', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_pessoa, nu_colaborador, id_cargoColaborador', 'safe', 'on'=>'search'),
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
			'idCargoColaborador' => array(self::BELONGS_TO, 'Cargocolaborador', 'id_cargoColaborador'),
			'idPessoa' => array(self::BELONGS_TO, 'Pessoafisica', 'id_pessoa'),
			'usuario' => array(self::HAS_ONE, 'Usuario', 'id_pessoa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pessoa' => 'Id Pessoa',
			'nu_colaborador' => 'Nu Colaborador',
			'id_cargoColaborador' => 'Id Cargo Colaborador',
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
		$criteria->compare('nu_colaborador',$this->nu_colaborador);
		$criteria->compare('id_cargoColaborador',$this->id_cargoColaborador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}