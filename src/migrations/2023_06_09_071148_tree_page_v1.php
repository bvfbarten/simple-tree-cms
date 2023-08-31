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
      if (!is_file($file = config('simple-tree-cms-config.simple-tree-cms-db.database'))) {
        file_put_contents($file, '');
      }


      Schema::connection('simple_cms')->create('domains', function (Blueprint $table) {
        $table->id();
        $table->string('value')->unique();
        $table->boolean('is_primary')->default(0);
        $table->integer('site_id');
        $table->timestamps();
        $table->index(['site_id', 'is_primary']);
      });
      Schema::connection('simple_cms')->create('sites', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->boolean('has_home_page')->default(0);
        $table->timestamps();
      });

      Schema::connection('simple_cms')->create('tree_page_indices', function (Blueprint $table) {
        $table->id();
        $table->string('template');
        $table->string('key');
        $table->string('value')->index()->nullable(true);
        $table->integer('tree_page_id')->index();
        $table->timestamps();
        $table->index(['template', 'key', 'value']);
        $table->index(['template', 'key', 'tree_page_id']);
        $table->index(['key', 'value']);
      });

      Schema::connection('simple_cms')->create('tree_pages', function (Blueprint $table) {
        $table->id();
        $table->treeColumns();
        $table->boolean('is_home')->default(0);
        $table->boolean('is_live')->default(0);
        $table->integer('site_id');
        $table->dateTime('activation_date')->nullable();
        $table->dateTime('deactivation_date')->nullable();
        $table->string('slug');
        $table->string('template')->nullable();
        $table->json('content')->nullable();
        $table->timestamps();
        $table->index(['parent_id', 'slug']);
        $table->unique(['site_id', 'parent_id', 'slug', 'is_home']);
      });       //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
      Schema::connection('simple_cms')->dropIfExists('tree_pages');
      Schema::connection('simple_cms')->dropIfExists('tree_page_indexes');
      Schema::connection('simple_cms')->dropIfExists('tree_page_indices');
      Schema::connection('simple_cms')->dropIfExists('domains');
      Schema::connection('simple_cms')->dropIfExists('sites');
    }
};
