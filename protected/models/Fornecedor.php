<?php

/**
 * This is the model class for table "fornecedor".
 *
 * The followings are the available columns in table 'fornecedor':
 * @property integer $id_pessoa
 *
 * The followings are the available model relations:
 * @property Pessoafisica $idPessoa
 */
class Fornecedor extends CActiveRecord
{
    public $identificador;
    public $nm_pessoa = null;
    public $tp_pessoa;
    public $dt_nascimento;
    public $nu_cpf;
    public $nm_apelido;
    public $nu_cnpj;
    public $nm_nomeFantasia;
    public $nu_inscricaoEstadual;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Fornecedor the static model class
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
		return 'fornecedor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_pessoa', 'required'),
			array('id_pessoa', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_pessoa, nm_pessoa', 'safe', 'on'=>'search'),
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
            'pessoaFisica' => array(self::HAS_MANY, 'Pessoafisica', 'id_pessoa'),
            'pessoaJuridica' => array(self::HAS_MANY, 'Pessoajuridica', 'id_pessoa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pessoa' => 'Id Pessoa',
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

        $criteria->with = 'idPessoa';
		$criteria->compare('id_pessoa', $this->id_pessoa);
        $criteria->compare('idPessoa.id_pessoa', $this->id_pessoa, true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function getFornecedorGrid()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idPessoa' => array('select'=>'nm_pessoa'),
            'pessoaFisica',
            'pessoaJuridica'
        );
        
        $criteria->select = array(
            'id_pessoa',
            'CONCAT(COALESCE(pessoaFisica.nu_cpf,""),COALESCE(pessoaJuridica.nu_cnpj,"")) as identificador'
        );
        
        $criteria->condition = 'idPessoa.fl_inativo = :fl_inativo';
        $criteria->params = array(
            ':fl_inativo' => false,
        );
        
        $criteria->compare('t.id_pessoa', $this->id_pessoa);
        $criteria->compare('pessoaFisica.nu_cpf', $this->identificador, true, 'AND');
        $criteria->compare('pessoaJuridica.nu_cnpj', $this->identificador, true, 'OR');
        $criteria->compare('idPessoa.nm_pessoa', $this->nm_pessoa, true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getFornecedorCompleto($id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

        //$criteria->together = true;
        
        $criteria->with = array(
            'idPessoa' => array('select'=>array('nm_pessoa', 'tp_pessoa', 'dt_nascimento')),
            'pessoaFisica' => array('select'=>'nu_cpf,nm_apelido'),
            'pessoaJuridica' => array('select'=>'nu_cnpj, nm_nomeFantasia, nu_inscricaoEstadual'),
        );
    
        /*select 
        p.id_pessoa, p.nm_pessoa, p.tp_pessoa, p.dt_nascimento,
        pf.nu_cpf, pf.nm_apelido, pj.nu_cnpj, pj.nm_nomeFantasia, pj.nu_inscricaoEstadual

        from pessoa p
        left join pessoaFisica pf on p.id_pessoa = pf.id_pessoa
        left join pessoaJuridica pj on p.id_pessoa = pj.id_pessoa
        left join Fornecedor f on p.id_pessoa = f.id_pessoa
        where p.id_pessoa = 14
        */
        
        $criteria->select = array(
            'id_pessoa',//'nm_pessoa'
        );
        //$criteria->condition= "id_pessoa = $id";
        $criteria->condition = 'idPessoa.id_pessoa = :id_pessoa';
        $criteria->params = array(
            ':id_pessoa' => $id,
        );
        
        return $this->find($criteria);
	}
}