<?php namespace MatoMoravcik\Taxonomy\Models;

class Term extends \Eloquent {

  protected $fillable = [
    'name',
    'vocabulary_id',
    'parent',
    'weight',
    'children' // not in db only auxiliary attribute to hold children in terms tree view
  ];

	public static $rules = [
		'name' => 'required'
  ];

  public function termRelation() {
    return $this->morphMany('MatoMoravcik\Taxonomy\Models\TermRelation', 'relationable');
  }

	public function vocabulary() {
		return $this->belongsTo('MatoMoravcik\Taxonomy\Models\Vocabulary');
	}

  public function childrens() {
    return $this->hasMany('MatoMoravcik\Taxonomy\Models\Term', 'parent', 'id')
      ->orderBy('weight', 'ASC');
  }

  public function parentTerm() {
    return $this->hasOne('MatoMoravcik\Taxonomy\Models\Term', 'id', 'parent');
  }
}
