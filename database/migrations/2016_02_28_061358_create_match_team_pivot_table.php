<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchTeamPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_team', function (Blueprint $table) {
            $table->integer('match_id')->unsigned()->index();
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->integer('team_id')->unsigned()->index();
            $table->boolean('is_winner');
            $table->integer('super_goals');
            $table->integer('goals');
            $table->integer('behinds');
            $table->integer('points');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->primary(['match_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('match_team');
    }
}
