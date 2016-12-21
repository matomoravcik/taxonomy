<?php namespace MatoMoravcik\Taxonomy\Models;

class Vocabulary extends \Eloquent {

  protected $fillable = [
    'name',
  ];

  protected $table = 'vocabularies';

  public $rules = [
    'name' => 'required'
  ];

  public function terms() {
    return $this->HasMany('MatoMoravcik\Taxonomy\Models\Term');
  }

  public function relations() {
    return $this->HasMany('MatoMoravcik\Taxonomy\Models\TermRelation');
  }

}
