<?php

use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('terms', function ($table) {
			$table->increments('id');
			$table->integer('vocabulary_id')->unsigned();
			$table->foreign('vocabulary_id')->references('id')->on('vocabularies')->onDelete('cascade');
			$table->string('name');
			$table->string('key');
			$table->integer('parent')->unsigned()->nullable();
			$table->integer('weight');
			$table->timestamps();

			$table->unique(['vocabulary_id', 'key']);

			// indexes
			$table->index('parent');

			// relations
			$table->foreign('parent')->references('id')->on('terms')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('terms');
	}

}