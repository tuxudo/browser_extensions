<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class BrowserExtensions extends Migration
{
    private $tableName = 'browser_extensions';

    public function up()
    {
        $capsule = new Capsule();
        
        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name')->nullable();
            $table->string('extension_id')->nullable();
            $table->string('version')->nullable();
            $table->text('description')->nullable();
            $table->string('browser')->nullable();
            $table->bigInteger('date_installed')->nullable();
            $table->string('developer')->nullable();
            $table->boolean('enabled')->nullable();
            $table->string('user')->nullable();

            $table->index('serial_number');
            $table->index('name');
            $table->index('extension_id');
            $table->index('version');
            $table->index('browser');
            $table->index('developer');
            $table->index('enabled');
            $table->index('user');
        });
    }

    public function down()
    {
        $capsule::schema()->dropIfExists($this->tableName);
    }
}
