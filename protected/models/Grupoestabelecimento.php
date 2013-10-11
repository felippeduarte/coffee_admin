<?php

/**
 * This is the model class for table "grupoestabelecimento".
 *
 * The followings are the available columns in table 'grupoestabelecimento':
 * @property integer $id_grupoEstabelecimento
 * @property string $nm_grupoEstabelecimento
 *
 * The followings are the available model relations:
 * @property Estabelecimento[] $estabelecimentos
 */
class Grupoestabelecimento extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Grupoestabelecimento the static model class
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
		return 'grupoestabelecimento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nm_grupoEstabelecimento', 'required'),
			array('nm_grupoEstabelecimento', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_grupoEstabelecimento, nm_grupoEstabelecimento', 'safe', 'on'=>'search'),
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
			'estabelecimentos' => array(self::HAS_MANY, 'Estabelecimento', 'id_grupoEstabelecimento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_grupoEstabelecimento' => 'Id Grupo Estabelecimento',
			'nm_grupoEstabelecimento' => 'Nm Grupo Estabelecimento',
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

		$criteria->compare('id_grupoEstabelecimento',$this->id_grupoEstabelecimento);
		$criteria->compare('nm_grupoEstabelecimento',$this->nm_grupoEstabelecimento,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getComboGrupoEstabelecimento()
    {
        $criteria=new CDbCriteria;
        
        $criteria->select = array(
            'id_grupoEstabelecimento','nm_grupoEstabelecimento'
        );
        
        return $this->findAll($criteria);
    }
}