<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginPreproductionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('login_preproductions', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('key')->unique();

			$table->string('rnumber');
			$table->string('operator');
			
			$table->string('shift');

			$table->dateTime('login_date');
			$table->dateTime('logout_date')->nullable();

			$table->double('total_time')->nullable();
			$table->integer('break')->nullable();
			
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('login_preproductions');
	}

}
