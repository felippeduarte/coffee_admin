<?php

/**
 * This is the model class for table "cargocolaborador".
 *
 * The followings are the available columns in table 'cargocolaborador':
 * @property integer $id_cargoColaborador
 * @property string $nm_cargoColaborador
 *
 * The followings are the available model relations:
 * @property Colaborador[] $colaboradors
 */
class Cargocolaborador extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Cargocolaborador the static model class
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
		return 'cargocolaborador';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('nm_cargoColaborador', 'required'),
			array('nm_cargoColaborador', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_cargoColaborador, nm_cargoColaborador', 'safe', 'on'=>'search'),
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
			'colaboradors' => array(self::HAS_MANY, 'Colaborador', 'id_cargoColaborador'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_cargoColaborador' => 'Id Cargo Colaborador',
			'nm_cargoColaborador' => 'Nome do Cargo do Colaborador',
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

		$criteria->compare('id_cargoColaborador',$this->id_cargoColaborador);
		$criteria->compare('nm_cargoColaborador',$this->nm_cargoColaborador,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getComboCargoColaborador()
    {
        $criteria=new CDbCriteria;
        
        $criteria->select = array(
            'id_cargoColaborador','nm_cargoColaborador'
        );
        
        return $this->findAll($criteria);
    }
    
    public function getCargoColaboradorGrid()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
        
        $criteria->select = array(
            'id_cargoColaborador', 'nm_cargoColaborador'
        );
        
        $criteria->compare('id_cargoColaborador', $this->id_cargoColaborador);
        $criteria->compare('nm_cargoColaborador', $this->nm_cargoColaborador, true);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}