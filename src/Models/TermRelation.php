<?php namespace MatoMoravcik\Taxonomy\Models;

class TermRelation extends \Eloquent {

  protected $fillable = [
    'term_id',
    'vocabulary_id',
  ];

	protected $table = 'term_relations';

  public function relationable() {
    return $this->morphTo();
  }

	public function term() {
		return $this->belongsTo('MatoMoravcik\Taxonomy\Models\Term');
	}

}
