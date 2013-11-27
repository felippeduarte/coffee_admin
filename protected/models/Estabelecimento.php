<?php

/**
 * This is the model class for table "estabelecimento".
 *
 * The followings are the available columns in table 'estabelecimento':
 * @property integer $id_estabelecimento
 * @property string $nm_estabelecimento
 * @property integer $id_grupoEstabelecimento
 *
 * The followings are the available model relations:
 * @property Grupoestabelecimento $idGrupoEstabelecimento
 * @property Formapagamento[] $formapagamentos
 * @property Lancamento[] $lancamentos
 */
class Estabelecimento extends CActiveRecord
{
    public $nm_grupoEstabelecimento;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Estabelecimento the static model class
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
		return 'estabelecimento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nm_estabelecimento', 'required'),
			array('id_grupoEstabelecimento', 'numerical', 'integerOnly'=>true),
			array('nm_estabelecimento', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_estabelecimento, nm_estabelecimento, id_grupoEstabelecimento', 'safe', 'on'=>'search'),
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
			'idGrupoEstabelecimento' => array(self::BELONGS_TO, 'Grupoestabelecimento', 'id_grupoEstabelecimento'),
			'formapagamentos' => array(self::MANY_MANY, 'Formapagamento', 'estabelecimento_formapagamento(id_estabelecimento, id_formaPagamento)'),
			'lancamentos' => array(self::HAS_MANY, 'Lancamento', 'id_estabelecimento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_estabelecimento' => 'Id Estabelecimento',
			'nm_estabelecimento' => 'Nome Estabelecimento',
			'id_grupoEstabelecimento' => 'Grupo Estabelecimento',
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

		$criteria->compare('id_estabelecimento',$this->id_estabelecimento);
		$criteria->compare('nm_estabelecimento',$this->nm_estabelecimento,true);
		$criteria->compare('id_grupoEstabelecimento',$this->id_grupoEstabelecimento);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getComboEstabelecimento()
    {
        $criteria=new CDbCriteria;
        
        $criteria->select = array(
            'id_estabelecimento','nm_estabelecimento'
        );
        
        return $this->findAll($criteria);
    }
    
    public function getEstabelecimentoGrid()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

        $criteria->together = true;
        
        $criteria->with = array(
            'idGrupoEstabelecimento' => array('select'=>'nm_grupoEstabelecimento'),
        );
        
        $criteria->select = array(
            'id_estabelecimento', 'nm_estabelecimento'
        );
        
        $criteria->compare('t.id_estabelecimento', $this->id_estabelecimento);
        $criteria->compare('t.nm_estabelecimento', $this->nm_estabelecimento, true);
        $criteria->compare('idGrupoEstabelecimento.nm_grupoEstabelecimento', $this->nm_grupoEstabelecimento, true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function getHtmlDropDownMainMenu()
    {
        $estabelecimentos = $this->getComboEstabelecimento();
        
        $retorno = array();
        
        foreach($estabelecimentos as $estabelecimento)
        {
            $retorno[]= array(
                'label'=>$estabelecimento->nm_estabelecimento,
                'url'=>array('/site/setEstabelecimento/id/'.$estabelecimento->id_estabelecimento)
            );
        }
        
        return $retorno;
    }
}