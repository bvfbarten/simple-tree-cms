<?php

namespace Bvfbarten\SimpleCms\Console\Commands;

use Bvfbarten\SimpleCms\Models\Backup;
use Bvfbarten\SimpleCms\Models\Site;
use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Console\Command;

class ModelToDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:to-dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dumps Cms Objects to Yaml';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $models = [
        Site::class,
        TreePage::class,
      ];
      foreach($models as $model) {
        Backup::dumpModel($model);
      }
    }
}
