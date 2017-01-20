<?php namespace MatoMoravcik\Taxonomy;

use MatoMoravcik\Taxonomy\Models\TermRelation;
use MatoMoravcik\Taxonomy\Models\Term;
use MatoMoravcik\Taxonomy\Models\Vocabulary;

trait TaxonomyTrait
{

	/**
	 * Return collection of tags related to the tagged model
	 *
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function related()
	{
		return $this->morphMany('MatoMoravcik\Taxonomy\Models\TermRelation', 'relationable');
	}

	/**
	 * Add an existing term to the inheriting model
	 *
	 * @param $term_id int
	 *                 The ID of the term or an instance of the Term object
	 *
	 * @return object
	 *  The TermRelation object
	 */
	public function addTerm($term_id)
	{
		$term = ($term_id instanceof Term) ? $term_id : Term::findOrFail($term_id);

		$term_relation = [
			'term_id' => $term->id,
			'vocabulary_id' => $term->vocabulary_id,
		];

		return $this->related()->save(new TermRelation($term_relation));
	}

	/**
	 * Function adds multiple terms
	 *
	 * @param $terms
	 */
	public function addTerms($terms)
	{
		foreach ( $terms AS $term ) {
			$this->addTerm($term->id);
		}
	}

	/**
	 * Function sync terms
	 *
	 * @param       $terms
	 * @param array $vocabulary
	 */
	public function syncTerms($terms, array $vocabulary)
	{
		$newTermIds = [];

		foreach ( $terms AS $term ) {

			$newTermIds[] = $term->id;

			if ( !$this->hasTerm($term->id) ) {
				$this->addTerm($term->id);
			}
		}

		// remove terms which are not in new terms array
		$vocabulary = Vocabulary::where($vocabulary)->first();
		$this->related()->whereNotIn('term_id', $newTermIds)->where('vocabulary_id', '=', $vocabulary->id)->delete();

	}

	/**
	 * Check if the Model instance has the passed term as an existing relation
	 *
	 * @param mixed $term_id
	 *  The ID of the term or an instance of the Term object
	 *
	 * @return object
	 *  The TermRelation object
	 */
	public function hasTerm($term_id)
	{
		$term = ($term_id instanceof Term) ? $term_id : Term::findOrFail($term_id);

		$term_relation = [
			'term_id' => $term->id,
			'vocabulary_id' => $term->vocabulary_id,
		];

		return ($this->related()->where('term_id', $term_id)->count()) ? TRUE : FALSE;
	}

	/**
	 * Function returns info if some entity has all terms from inserted vocabulary
	 * 
	 * @param $vocabularyId
	 * @param $termsCount
	 *
	 * @return bool
	 */
	public function hasAllVocabularyTerms($vocabularyId, $termsCount)
	{
		$vocabularyTermsCount = Term::whereVocabularyId($vocabularyId)->count();
		return $termsCount == $vocabularyTermsCount;
	}

	/**
	 * Get all the terms for a given vocabulary that are linked to the current
	 * Model.
	 *
	 * @param $name string
	 *              The name of the vocabulary
	 *
	 * @return object
	 *  A collection of TermRelation objects
	 */
	public function getTermsByVocabularyName($name)
	{
		$vocabulary = \Taxonomy::getVocabularyByName($name);

		return $this->related()->where('vocabulary_id', $vocabulary->id)->get();
	}

	/**
	 * Get all the terms for a given vocabulary that are linked to the current
	 * Model.
	 *
	 * @param $key
	 *
	 * @return object A collection of TermRelation objects
	 * A collection of TermRelation objects
	 * @internal param string $name The name of the vocabulary*              The name of the vocabulary
	 *
	 */
	public function getTermsByVocabularyKey($key)
	{
		$vocabulary = \Taxonomy::getVocabularyByKey($key);

		return $this->related()->where('vocabulary_id', $vocabulary->id)->get();
	}

	/**
	 * Get all the terms for a given vocabulary that are linked to the current
	 * Model.
	 *
	 * @param $id
	 *
	 * @return object A collection of TermRelation objects
	 * A collection of TermRelation objects
	 * @internal param string $name The name of the vocabulary*              The name of the vocabulary
	 *
	 */
	public function getTermsByVocabularyId($id)
	{
		$vocabulary = \Taxonomy::getVocabulary($id);

		return $this->related()->where('vocabulary_id', $vocabulary->id)->get();
	}

	/**
	 * Get all the terms for a given vocabulary that are linked to the current
	 * Model as a key value pair array.
	 *
	 * @param        $name string
	 *                     The name of the vocabulary
	 *
	 * @param string $attribute
	 *
	 * @return array A key value pair array of the type 'id' => 'name'
	 * A key value pair array of the type 'id' => 'name'
	 */
	public function getTermsByVocabularyNameAsArray($name, $attribute = 'name')
	{
		$vocabulary = \Taxonomy::getVocabularyByName($name);

		$term_relations = $this->related()->where('vocabulary_id', $vocabulary->id)->get();

		$data = [];
		foreach ( $term_relations as $term_relation ) {
			$data[$term_relation->term->id] = $term_relation->term->$attribute;
		}

		return $data;
	}

	public function getTermsByVocabularyKeyAsArray($key, $attribute = 'name')
	{
		$vocabulary = \Taxonomy::getVocabularyByKey($key);

		$term_relations = $this->related()->where('vocabulary_id', $vocabulary->id)->get();

		$data = [];
		foreach ( $term_relations as $term_relation ) {
			$data[$term_relation->term->id] = $term_relation->term->$attribute;
		}

		return $data;
	}

	/**
	 * Unlink the given term with the current model object
	 *
	 * @param $term_id int
	 *                 The ID of the term or an instance of the Term object
	 *
	 * @return bool
	 *  TRUE if the term relation has been deleted, otherwise FALSE
	 */
	public function removeTerm($term_id)
	{
		$term_id = ($term_id instanceof Term) ? $term_id->id : $term_id;
		return $this->related()->where('term_id', $term_id)->delete();
	}

	/**
	 * Unlink all the terms from the current model object
	 *
	 * @return bool
	 *  TRUE if the term relation has been deleted, otherwise FALSE
	 */
	public function removeAllTerms()
	{
		return $this->related()->delete();
	}

	/**
	 * Filter the model to return a subset of entries matching the term ID
	 *
	 * @param object $query
	 * @param int    $term_id
	 *
	 * @return void
	 */
	public function scopeGetAllByTermId($query, $term_id)
	{
		return $query->whereHas('related', function ($q) use ($term_id) {
			if ( is_array($term_id) ) {
				$q->whereIn('term_id', $term_id);
			} else {
				$q->where('term_id', '=', $term_id);
			}
		});
	}
}
