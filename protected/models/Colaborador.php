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
    private $customErrors = array();
    
    public $nm_pessoa;
    public $nu_cpf;
    public $id_cargoColaborador;
    public $nm_cargoColaborador;
    
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
			array('id_pessoa, id_cargoColaborador', 'required','on'=>array('insert','update')),
            array('id_pessoa', 'required', 'on'=>'usuario'),
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
			'idPessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
			'usuario' => array(self::HAS_ONE, 'Usuario', 'id_pessoa'),
            'pessoaFisica' => array(self::HAS_ONE, 'Pessoafisica', 'id_pessoa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pessoa' => 'Colaborador',
			'nu_colaborador' => 'Matrícula',
			'id_cargoColaborador' => 'Cargo',
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
    
    public function addCustomError($attribute, $error) {
        $this->customErrors[] = array($attribute, $error);
    }

    protected function beforeValidate() {
        $r = parent::beforeValidate();
        
        foreach ($this->customErrors as $param) {
            $this->addError($param[0], $param[1]);
        }
        return $r;
    }
    
    
    public function getColaboradorGrid()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idPessoa' => array('select'=>'nm_pessoa'),
            'pessoaFisica' => array('select'=>'nu_cpf'),
            'idCargoColaborador' => array('select'=>'nm_cargoColaborador')
        );
        
        $criteria->select = array(
            'id_pessoa', 'nu_colaborador'
        );
        
        $criteria->condition = 'idPessoa.fl_inativo = :fl_inativo';
        $criteria->params = array(
            ':fl_inativo' => false,
        );
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getColaboradorCompleto($id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

        $criteria->with = array(
            'idPessoa' => array('select'=>array('nm_pessoa', 'dt_nascimento')),
            'pessoaFisica' => array('select'=>'nu_cpf,nm_apelido,nu_rg'),
        );
        
        $criteria->select = array(
            'id_pessoa','id_cargoColaborador','nu_colaborador'
        );

        $criteria->condition = 'idPessoa.id_pessoa = :id_pessoa';
        $criteria->params = array(
            ':id_pessoa' => $id,
        );
        
        return $this->find($criteria);
	}
    
    public function getComboColaborador()
    {
        $criteria=new CDbCriteria;

        $criteria->with = array(
            'idPessoa' => array('select'=>'nm_pessoa'),
        );
        
        $criteria->select = array(
            'id_pessoa',
        );

        $criteria->condition = 'idPessoa.fl_inativo = :fl_inativo';
        $criteria->params = array(
            ':fl_inativo' => false,
        );
        
        return $this->findAll($criteria);
    }
}