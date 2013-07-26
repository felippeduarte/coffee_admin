<?php

/**
 * This is the model class for table "lancamento".
 *
 * The followings are the available columns in table 'lancamento':
 * @property integer $id_lancamento
 * @property string $dt_lancamento
 * @property string $vl_lancamento
 * @property integer $id_estabelecimento
 * @property integer $id_categoriaLancamento
 * @property integer $id_pessoaLancamento
 * @property integer $id_formaPagamento
 * @property string $nm_turno
 * @property string $de_observacao
 * @property integer $id_pessoaUsuario
 * @property string $dt_ultimaAlteracao
 *
 * The followings are the available model relations:
 * @property Estabelecimento $idEstabelecimento
 * @property Categorialancamento $idCategoriaLancamento
 * @property Pessoa $idPessoaLancamento
 * @property Formapagamento $idFormaPagamento
 * @property Usuario $idPessoaUsuario
 */
class Lancamento extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lancamento the static model class
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
		return 'lancamento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dt_lancamento, vl_lancamento, id_categoriaLancamento, id_formaPagamento, id_pessoaUsuario, dt_ultimaAlteracao', 'required'),
			array('id_estabelecimento, id_categoriaLancamento, id_pessoaLancamento, id_formaPagamento, id_pessoaUsuario', 'numerical', 'integerOnly'=>true),
			array('vl_lancamento', 'length', 'max'=>12),
			array('nm_turno', 'length', 'max'=>1),
			array('de_observacao', 'length', 'max'=>4000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_lancamento, dt_lancamento, vl_lancamento, id_estabelecimento, id_categoriaLancamento, id_pessoaLancamento, id_formaPagamento, nm_turno, de_observacao, id_pessoaUsuario, dt_ultimaAlteracao', 'safe', 'on'=>'search'),
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
			'idEstabelecimento' => array(self::BELONGS_TO, 'Estabelecimento', 'id_estabelecimento'),
			'idCategoriaLancamento' => array(self::BELONGS_TO, 'Categorialancamento', 'id_categoriaLancamento'),
			'idPessoaLancamento' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoaLancamento'),
			'idFormaPagamento' => array(self::BELONGS_TO, 'Formapagamento', 'id_formaPagamento'),
			'idPessoaUsuario' => array(self::BELONGS_TO, 'Usuario', 'id_pessoaUsuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_lancamento' => 'Id Lancamento',
			'dt_lancamento' => 'Dt Lancamento',
			'vl_lancamento' => 'Vl Lancamento',
			'id_estabelecimento' => 'Id Estabelecimento',
			'id_categoriaLancamento' => 'Id Categoria Lancamento',
			'id_pessoaLancamento' => 'Id Pessoa Lancamento',
			'id_formaPagamento' => 'Id Forma Pagamento',
			'nm_turno' => 'Nm Turno',
			'de_observacao' => 'De Observacao',
			'id_pessoaUsuario' => 'Id Pessoa Usuario',
			'dt_ultimaAlteracao' => 'Dt Ultima Alteracao',
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

		$criteria->compare('id_lancamento',$this->id_lancamento);
		$criteria->compare('dt_lancamento',$this->dt_lancamento,true);
		$criteria->compare('vl_lancamento',$this->vl_lancamento,true);
		$criteria->compare('id_estabelecimento',$this->id_estabelecimento);
		$criteria->compare('id_categoriaLancamento',$this->id_categoriaLancamento);
		$criteria->compare('id_pessoaLancamento',$this->id_pessoaLancamento);
		$criteria->compare('id_formaPagamento',$this->id_formaPagamento);
		$criteria->compare('nm_turno',$this->nm_turno,true);
		$criteria->compare('de_observacao',$this->de_observacao,true);
		$criteria->compare('id_pessoaUsuario',$this->id_pessoaUsuario);
		$criteria->compare('dt_ultimaAlteracao',$this->dt_ultimaAlteracao,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}