<?php

use Illuminate\Database\Migrations\Migration;

class CreateVocabulariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vocabularies', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->string('key');
			$table->timestamps();

			$table->unique('name');
			$table->unique('key');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('vocabularies');
	}

}