<?php

namespace Bvfbarten\SimpleCms\Models;

use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Bvfbarten\SimpleCms\Interfaces\Dumpable;

class Site extends Model implements Dumpable
{
  use HasFactory;
  protected $connection = "simple_cms";
  protected $table = "sites";
  protected $fillable = ["id", "title"];

  public function Domain() : HasOne
  {
    return $this->hasOne(Domain::class)
      ->where('is_primary', true);
  }
  public function AlternativeDomains() : HasMany
  {
    return $this->hasMany(Domain::class)
      ->where('is_primary', false);
  }
  public function TreePages() {
    return $this->hasMany(TreePage::class);
  }

  public static function findByDomain($domain = null) {
    if (!$domain) {
      $request = app('request');
      $domain = $request->server('SERVER_NAME');
    }
    $site = Site::whereHas('Domain', function($query) use ($domain){
      $query->where('value', $domain);
    })->first();
    if (!$site) {
      throw new NotFoundHttpException();
    }
    return $site;
  }
  public function toDump() : array 
  {
    return $this->toArray();
  }
  public function newTreePage($template) {
    $tp = new TreePage;
    $tp->site_id = $this->id;
    if (is_string($template)) {
      $tp->template = $template;
      return $tp;
    }
    foreach($template as $key => $value) {
      $tp->$key = $value;
    }
    return $tp;
  }
  public static function booted() {
    static::deleting(function(Site $model){
      $treePages = $model->TreePages;
      foreach($treePages as $treePage) {
        $treePage->delete();
      }
    });
    static::created(function (Site $model) {
      $treePage = $model->newTreePage([
        'is_home' => true,
        'slug' => '/',
        'template' => 'App\Filament\PageTemplates\Home',
        'title' => 'home'
      ]);
      $treePage->save();
    });
  }
}
