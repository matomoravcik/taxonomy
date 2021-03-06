<?php namespace MatoMoravcik\Taxonomy\Models;

class Vocabulary extends \Eloquent
{

	protected $fillable = [
		'name',
		'key',
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

	public static function getIdIdByKey($key)
	{
		return self::where('key', '=', $key)->pluck('id')->first();
	}

	/**
	 * Function converts vocabulary terms to tree
	 *
	 * @param     $elements
	 * @param int $parentId
	 *
	 * @return array
	 */
	public function buildTree(&$elements = null, $parentId = null)
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
