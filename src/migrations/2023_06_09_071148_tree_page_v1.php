<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      if (!is_file($file = config('simple-cms-config.simple-cms-db.database'))) {
        file_put_contents($file, '');
      }
      Schema::connection('simple_cms')->create('tree_pages', function (Blueprint $table) {
        $table->id();
        $table->treeColumns();
        $table->boolean('is_home')->default(0);
        $table->integer('site_id');
        $table->dateTime('activation_date')->nullable();
        $table->dateTime('deactivation_date')->nullable();
        $table->string('slug');
        $table->string('template')->nullable();
        $table->json('content')->nullable();
        $table->timestamps();
      });       //
      Schema::connection('simple_cms')->create('sites', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('domain');
        $table->json('alternative_domains')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
      Schema::connection('simple_cms')->dropIfExists('tree_pages');
      Schema::connection('simple_cms')->dropIfExists('sites');
    }
};
