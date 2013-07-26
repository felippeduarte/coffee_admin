<?php

/**
 * This is the model class for table "pessoafisica".
 *
 * The followings are the available columns in table 'pessoafisica':
 * @property integer $id_pessoa
 * @property string $nu_cpf
 * @property string $nm_apelido
 * @property string $nu_rg
 * @property string $tp_pessoaFisica
 *
 * The followings are the available model relations:
 * @property Colaborador $colaborador
 * @property Pessoa $idPessoa
 */
class Pessoafisica extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pessoafisica the static model class
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
		return 'pessoafisica';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa, nu_cpf, tp_pessoaFisica', 'required'),
			array('id_pessoa', 'numerical', 'integerOnly'=>true),
			array('nu_cpf', 'length', 'max'=>11),
			array('nm_apelido', 'length', 'max'=>200),
			array('nu_rg', 'length', 'max'=>20),
			array('tp_pessoaFisica', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_pessoa, nu_cpf, nm_apelido, nu_rg, tp_pessoaFisica', 'safe', 'on'=>'search'),
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
			'colaborador' => array(self::HAS_ONE, 'Colaborador', 'id_pessoa'),
			'idPessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pessoa' => 'Id Pessoa',
			'nu_cpf' => 'Nu Cpf',
			'nm_apelido' => 'Nm Apelido',
			'nu_rg' => 'Nu Rg',
			'tp_pessoaFisica' => 'Tp Pessoa Fisica',
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
		$criteria->compare('nu_cpf',$this->nu_cpf,true);
		$criteria->compare('nm_apelido',$this->nm_apelido,true);
		$criteria->compare('nu_rg',$this->nu_rg,true);
		$criteria->compare('tp_pessoaFisica',$this->tp_pessoaFisica,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}