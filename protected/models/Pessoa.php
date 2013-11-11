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
    const TP_PESSOA_FISICA = 'PF';
    const TP_PESSOA_JURIDICA = 'PJ';
    
    public $nm_comboFavorecido;
    public $identificador;
    
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
            array('tp_pessoa','in','range'=>array('PF','PJ'),'allowEmpty'=>false),
			array('dt_nascimento', 'safe'),
            array('dt_nascimento', 'date', 'format'=>'yyyy-MM-dd'),
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
            'fornecedor' => array(self::HAS_ONE, 'Fornecedor', 'id_pessoa'),
            'colaborador' => array(self::HAS_ONE, 'Colaborador', 'id_pessoa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_pessoa' => 'Id Pessoa',
			'nm_pessoa' => 'Nome',
			'tp_pessoa' => 'Tipo',
			'dt_nascimento' => 'Data Nascimento',
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
    
    protected function afterFind()
    {
        // convert to view format
        $this->dt_nascimento = date("d/m/Y", strtotime($this->dt_nascimento));

        return parent::afterFind();
    }
    
    protected function beforeValidate()
    {
        $this->view2model();
        return parent::beforeValidate();
    }
    
    protected function afterValidate()
    {
        $this->model2view();        
        return parent::afterValidate();
    }
    
    protected function beforeSave()
    {
        $this->view2model();
        return parent::beforeSave();
    }
    
    protected function afterSave()
    {
        $this->model2view();
        return parent::afterSave();
    }

    private function model2view()
    {
        if(!empty($this->dt_nascimento))
        {
            // convert to view format
            $this->dt_nascimento = Yii::app()->bulebar->trocaDataModelParaView($this->dt_nascimento);
        }
    }
    
    private function view2model()
    {
        if(!empty($this->dt_nascimento))
        {
            // convert to view format
            $this->dt_nascimento = Yii::app()->bulebar->trocaDataViewParaModel($this->dt_nascimento);
        }
    }
    
    public function getComboFavorecido()
    {
        $criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'pessoafisica',
            'pessoajuridica'
        );
        
        $criteria->select = array(
            'id_pessoa',
            'CONCAT(COALESCE(pessoafisica.nu_cpf,pessoajuridica.nu_cnpj), " - ", t.nm_pessoa) as nm_comboFavorecido'
        );
        
        //elimina inativos e administrador
        $criteria->condition = 't.fl_inativo = :fl_inativo AND t.id_pessoa != :administrador';
        $criteria->params = array(
            ':fl_inativo' => false,
            ':administrador' => 1
        );
        
        return $this->findAll($criteria);
    }
    
    /**
     * Monta lista de "<option>" para usar em combobox com select2
     * @param string $tipo tp_categoriaLancamnetoPessoa ('C', 'F' ou array('C','F'))
     * @return string html option
     */
    public function getHtmlDropdownOptionsCategoriasPorTipo($tipoFavorecido)
    {
        $criteria = new CDbCriteria;
        
        $criteria->with = array(
            'pessoafisica',
            'pessoajuridica'
        );
        
        if($tipoFavorecido)
        {
            if($tipoFavorecido == 'C')
            {
                $criteria->with = $criteria->with +
                        array(
                            'colaborador'=>array(
                            'select'=>false,
                            'joinType'=>'INNER JOIN',
                        ));
            }
            else if($tipoFavorecido == 'F')
            {
                $criteria->with = $criteria->with +
                        array(
                            'fornecedor'=>array(
                            'select'=>false,
                            'joinType'=>'INNER JOIN',
                        ));
            }
            else if($tipoFavorecido == array('C','F'))
            {
                $criteria->with = $criteria->with +
                        array(
                            'colaborador'=>array(
                            'select'=>false,
                            'joinType'=>'LEFT JOIN',
                        )) +
                        array(
                            'fornecedor'=>array(
                            'select'=>false,
                            'joinType'=>'LEFT JOIN',
                        ));
            }
        }
        
        $criteria->select = array(
            'id_pessoa',
            'CONCAT(COALESCE(pessoafisica.nu_cpf,pessoajuridica.nu_cnpj)) as identificador',
            't.nm_pessoa'
        );
    
        //elimina inativos e administrador
        $criteria->condition = 't.fl_inativo = :fl_inativo AND t.id_pessoa != :administrador';
        $criteria->params = array(
            ':fl_inativo' => false,
            ':administrador' => 1
        );
        
        $criteria->order = 't.nm_pessoa';
        
        $data = $this->findAll($criteria);

        $opt = "<option value></option>";
        
        foreach($data as $pessoa)
        {
            $opt .= CHtml::tag('option',
                    array('value'=>$pessoa->id_pessoa),
                          CHtml::encode($pessoa->nm_pessoa." (".Yii::app()->bulebar->adicionaMascaraIdentificador($pessoa->identificador).")"),true);
        }
        return $opt;
    }
    
    /**
     * Monta lista de "<option>" para usar em combobox com select2
     * @param string $idEstabelecimento id_estabelcimento
     * @return string html option
     */
    public function getHtmlDropdownOptionsPorEstabelecimento($idEstabelecimento)
    {
        $criteria = new CDbCriteria;
        
        $criteria->with = array(
            'pessoafisica',
            'pessoajuridica',
            'colaborador',
        );
        
        $criteria->select = array(
            'id_pessoa',
            'CONCAT(COALESCE(pessoafisica.nu_cpf,pessoajuridica.nu_cnpj)) as identificador',
            't.nm_pessoa'
        );
    
        //elimina inativos e administrador
        $criteria->condition = 't.fl_inativo = :fl_inativo AND 
                                t.id_pessoa != :administrador AND
                                colaborador.id_estabelecimento = :id_estabelecimento';
        $criteria->params = array(
            ':fl_inativo' => false,
            ':administrador' => 1,
            ':id_estabelecimento' => $idEstabelecimento
        );
        
        $criteria->order = 't.nm_pessoa';
        
        $data = $this->findAll($criteria);

        $opt = "<option value></option>";
        
        foreach($data as $pessoa)
        {
            $opt .= CHtml::tag('option',
                    array('value'=>$pessoa->id_pessoa),
                          CHtml::encode($pessoa->nm_pessoa." (".Yii::app()->bulebar->adicionaMascaraIdentificador($pessoa->identificador).")"),true);
        }
        return $opt;
    }
}