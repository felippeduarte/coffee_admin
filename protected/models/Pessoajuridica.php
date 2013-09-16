<?php

/**
 * This is the model class for table "pessoajuridica".
 *
 * The followings are the available columns in table 'pessoajuridica':
 * @property integer $id_pessoa
 * @property string $nu_cnpj
 * @property string $nm_nomeFantasia
 * @property string $nu_inscricaoEstadual
 *
 * The followings are the available model relations:
 * @property Pessoa $idPessoa
 */
class Pessoajuridica extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pessoajuridica the static model class
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
		return 'pessoajuridica';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa, nu_cnpj', 'required'),
			array('id_pessoa', 'numerical', 'integerOnly'=>true),
			array('nu_cnpj', 'length', 'max'=>14),
            array('nu_cnpj', 'unique', 'className'=>'Pessoajuridica'),
			array('nm_nomeFantasia', 'length', 'max'=>200),
			array('nu_inscricaoEstadual', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_pessoa, nu_cnpj, nm_nomeFantasia, nu_inscricaoEstadual', 'safe', 'on'=>'search'),
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
			'nu_cnpj' => 'CNPJ',
			'nm_nomeFantasia' => 'Nome Fantasia',
			'nu_inscricaoEstadual' => 'Nº Inscricao Estadual',
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
		$criteria->compare('nu_cnpj',$this->nu_cnpj,true);
		$criteria->compare('nm_nomeFantasia',$this->nm_nomeFantasia,true);
		$criteria->compare('nu_inscricaoEstadual',$this->nu_inscricaoEstadual,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    protected function beforeValidate()
    {
        // convert to storage format
        $this->nu_cnpj = preg_replace("/[^0-9]/","",$this->nu_cnpj);

        return parent::beforeValidate();
    }
}