<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatorLoginKiksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('operator_login_kik', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('key_string');

			$table->string('rnumber');
			$table->string('operator');

			$table->string('activity_id');

			$table->dateTime('login_date');
			
			$table->dateTime('login_actual');
			$table->dateTime('shift_start');

			$table->dateTime('logout_actual')->nullable();
			$table->dateTime('shift_end')->nullable();

			$table->string('shift_name');

			$table->string('logout_motivation')->nullable();
			
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
		Schema::drop('operator_login_kik');
	}

}
