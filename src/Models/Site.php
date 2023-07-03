<?php

namespace Bvfbarten\SimpleCms\Models;

use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Site extends Model
{
  use HasFactory;
  protected $connection = "simple_cms";
  protected $table = "sites";
  protected $fillable = ["id", "title", "domain", "alternative_domains"];
  protected $casts = [
    'alternative_domains'=> 'array'
  ];

  public function TreePages() {
    return $this->hasMany(TreePage::class);
  }

  public static function findByPath($domain, $path) {
    $site = Site::where('domain', $domain)->first();
    if (!$site) {
      $site = Site::where('alternative_domains', 'like', '%"'.$domain.'"%') -> first();
    }
    if (!$site) {
      throw new NotFoundHttpException();
    }
    if ($path == '/') {
      return $site->TreePages()->where('is_home', true)->first();
    }
    $pathArray = explode('/', $path);
    $pathArray = array_reverse($pathArray);
    $rtn = $site
      ->TreePages()
      ->where('slug', '/' . array_shift($pathArray));
    foreach($pathArray as $line) {
      $rtn->whereHas('ParentNode', function($query) use ($line) {
        $query->where('slug', "/{$line}");
      });
    }
    $rtn->whereHas('ParentNode', function($query) {
      $query->where('is_home', true);
    });
    return $rtn->first();
  }
  public function toDump() {
    return $this->toArray();
  }
  public static function booted() {
    static::deleting(function(Site $model){
      $treePages = $model->TreePages;
      foreach($treePages as $treePage) {
        $treePage->delete();
      }
    });

    static::created(function (Site $model) {
      $treePage = new treePage();          
      $treePage->is_home = true;
      $treePage->site_id = $model->id;
      $treePage->slug = '/';
      $treePage->template = 'App\Filament\PageTemplates\Home';
      $treePage->title = 'home';
      $treePage->save();
    });
  }
}
