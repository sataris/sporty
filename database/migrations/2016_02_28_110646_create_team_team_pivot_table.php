<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamTeamPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_team', function (Blueprint $table) {
            $table->integer('team_id_one')->unsigned()->index();
            $table->foreign('team_id_one')->references('id')->on('teams')->onDelete('cascade');
            $table->integer('team_id_two')->unsigned()->index();
            $table->foreign('team_id_two')->references('id')->on('teams')->onDelete('cascade');
            $table->integer('team_id_one_attack');
            $table->integer('team_id_one_defence');
            $table->integer('team_id_two_attack');
            $table->integer('team_id_two_defence');
            $table->primary(['team_id_one', 'team_id_two']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('team_team');
    }
}
