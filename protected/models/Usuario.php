<?php

/**
 * This is the model class for table "usuario".
 *
 * The followings are the available columns in table 'usuario':
 * @property integer $id_pessoa
 * @property string $nm_login
 * @property string $de_senha
 *
 * The followings are the available model relations:
 * @property Lancamento[] $lancamentos
 * @property Colaborador $idPessoa
 */
class Usuario extends CActiveRecord
{
    public $de_senha_confirmacao;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Usuario the static model class
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
		return 'usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa, nm_login, de_senha, de_senha_confirmacao', 'required','on'=>'insert'),
			array('id_pessoa', 'numerical', 'integerOnly'=>true),
			array('nm_login', 'length', 'max'=>40),
			array('de_senha', 'length', 'max'=>45),
            array('id_pessoa, nm_login, de_senha,de_senha_confirmacao', 'safe'),
            array('de_senha', 'senhaConfirmada'),
            array('id_pessoa, nm_login', 'required','on' => 'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_pessoa, nm_login, de_senha', 'safe', 'on'=>'search'),
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
			'lancamentos' => array(self::HAS_MANY, 'Lancamento', 'id_pessoaUsuario'),
            'idPessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
			'idColaborador' => array(self::BELONGS_TO, 'Colaborador', 'id_pessoa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pessoa' => 'Colaborador',
			'nm_login' => 'Login',
			'de_senha' => 'Senha',
            'de_senha_confirmacao' => 'Confirme a senha',
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
		$criteria->compare('nm_login',$this->nm_login,true);
		$criteria->compare('de_senha',$this->de_senha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function login()
	{
		$criteria=new CDbCriteria;

		$criteria->addCondition("nm_login = '$this->nm_login'");
		$criteria->addCondition("de_senha = '$this->de_senha'");

		return self::model()->find($criteria);
	}
    
    public function senhaConfirmada($attribute)
    {
        if($this->de_senha != $this->de_senha_confirmacao)
        {
            $this->addError($attribute, 'Senhas não conferem');
        }
    }
    
    public function getUsuarioGrid()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idPessoa' => array('select'=>'nm_pessoa'),
        );
        
        $criteria->select = array(
            'id_pessoa','nm_login'
        );
        
        $criteria->condition = 'idPessoa.fl_inativo = :fl_inativo';
        $criteria->params = array(
            ':fl_inativo' => false,
        );
        
        $criteria->compare('t.id_pessoa', $this->id_pessoa);
        $criteria->compare('t.nm_login', $this->nm_login, true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getUsuarioCompleto($id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
        
        $criteria->with = array(
            'idPessoa' => array('select'=>array('nm_pessoa', 'dt_nascimento')),
            'pessoaFisica' => array('select'=>'nu_cpf,nm_apelido,nu_rg'),
        );
        
        $criteria->select = array(
            'id_pessoa','id_cargoColaborador'
        );
        //$criteria->condition= "id_pessoa = $id";
        $criteria->condition = 'idPessoa.id_pessoa = :id_pessoa';
        $criteria->params = array(
            ':id_pessoa' => $id,
        );
        
        return $this->find($criteria);
	}
}