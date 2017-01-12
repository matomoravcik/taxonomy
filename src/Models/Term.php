<?php namespace MatoMoravcik\Taxonomy\Models;

/**
 * Class Term
 * @package  MatoMoravcik\Taxonomy\Models
 * @property string  $key
 * @property string  $name
 * @property integer $vocabulary_id
 * @property integer $parent
 * @property integer $weight
 * @property [] $children
 */
class Term extends \Eloquent
{

	protected $fillable = [
		'key',
		'name',
		'vocabulary_id',
		'parent',
		'weight',
		'children'
		// not in db only auxiliary attribute to hold children in terms tree view
	];

	public static $rules = [
		'key' => 'required|unique:terms,key',
		'name' => 'required'
	];

	public function termRelation()
	{
		return $this->morphMany('MatoMoravcik\Taxonomy\Models\TermRelation', 'relationable');
	}

	public function vocabulary()
	{
		return $this->belongsTo('MatoMoravcik\Taxonomy\Models\Vocabulary');
	}

	public function childrens()
	{
		return $this->hasMany('MatoMoravcik\Taxonomy\Models\Term', 'parent', 'id')->orderBy('weight', 'ASC');
	}

	public function parentTerm()
	{
		return $this->hasOne('MatoMoravcik\Taxonomy\Models\Term', 'id', 'parent');
	}

	public static function getTermByKey($key)
	{
		return Term::where('key', '=', $key)->first();
	}
}
