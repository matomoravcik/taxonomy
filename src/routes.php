<?php

$prefix = config('taxonomy.config.route_prefix');

Route::group(array('prefix' => $prefix), function() use ($prefix) {

  Route::resource('taxonomy', 'MatoMoravcik\Taxonomy\Controllers\TaxonomyController');

  Route::post('taxonomy/{id}/order', array(
    'as' => $prefix .'.taxonomy.order.terms',
    'uses' => 'MatoMoravcik\Taxonomy\Controllers\TaxonomyController@orderTerms',
  ));

  Route::resource('terms', 'MatoMoravcik\Taxonomy\Controllers\TermsController');

});
