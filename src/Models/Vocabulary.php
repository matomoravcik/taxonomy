<?php namespace MatoMoravcik\Taxonomy\Models;

class Vocabulary extends \Eloquent
{

	protected $fillable = [
		'name',
	];

	protected $table = 'vocabularies';

	public $rules = [
		'name' => 'required'
	];

	public function terms()
	{
		return $this->HasMany('MatoMoravcik\Taxonomy\Models\Term')->orderBy('weight');
	}

	public function relations()
	{
		return $this->HasMany('MatoMoravcik\Taxonomy\Models\TermRelation');
	}

	/**
	 * Function converts vocabulary terms to tree
	 *
	 * @param     $elements
	 * @param int $parentId
	 *
	 * @return array
	 */
	public function buildTree(&$elements = null, $parentId = 0)
	{
		if ( $elements === null ) {
			$elements = $this->terms;
		}

		$branch = [];

		foreach ( $elements as &$element ) {

			if ( $element->parent == $parentId ) {
				$children = $this->buildTree($elements, $element->id);
				if ( $children ) {
					$element->children = $children;
				}
				$branch[$element->id] = $element;
				unset($element);
			}
		}
		return $branch;
	}

}
