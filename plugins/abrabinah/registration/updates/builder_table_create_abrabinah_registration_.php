<?php namespace Abrabinah\Registration\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAbrabinahRegistration extends Migration
{
    public function up()
    {
        Schema::create('abrabinah_registration_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('test')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('abrabinah_registration_');
    }
}
