<?php

namespace Bvfbarten\SimpleCms\Console\Commands;

use Bvfbarten\SimpleCms\Models\Backup;
use Bvfbarten\SimpleCms\Models\Domain;
use Bvfbarten\SimpleCms\Models\Site;
use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Console\Command;

class ModelFromDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:from-dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads Cms Models From Yaml';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
      $models = [
        Site::class,
        TreePage::class,
        Domain::class,
      ];
      foreach($models as $model) {
        Backup::loadModel($model);
      }
    }
}
