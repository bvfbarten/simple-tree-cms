<?php

namespace Bvfbarten\SimpleCms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use SolutionForest\FilamentTree\Concern\ModelTree;
use Illuminate\Support\Facades\Event;

class TreePage extends Model
{
  use ModelTree;
  protected $table = "tree_pages";
  protected $connection = "simple_cms";
  protected $fillable = ["id", "is_live", "parent_id", "is_home", "title", "slug", "order", 'template', "content", "activation_date", "deactivation_date", "created_at", "updated_at", "last_edited_by_id"];
  protected $casts = [
    'content' => 'array'
  ];
  use HasFactory;
  public function set($input, $value = null) {
    if (!is_array($input)) {
      $input = [$input => $value];
    }
    $input = Arr::dot($input);
    $content = $this->content;
    foreach($input as $k => $v) {
      Arr::set($content, $k, $v);
    }
    $this->content = $content;
    return $this;
  }
  public function remove($input) {
     if (!is_array($input)) {
      $input = [$input];
    }
     $content = Arr::dot($this->content);
     foreach($input as $line) {
      Arr::pull($content, $line);
     }
    $this->content = Arr::undot($content);
    return $this;
  }
  public function newTreePage($template) {
    $tp = new TreePage;
    $tp->site_id = $this->site_id;
    $tp->parent_id = $this->id;
    if (is_string($template)) {
      $tp->template = $template;
      return $tp;
    }
    foreach($template as $key => $value) {
      $tp->$key = $value;
    }
    return $tp;
  }
  public function get($key, $default = null) {
    return Arr::get($this->content, $key, $default);
  }
  public function ParentNode() {
    return $this->belongsTo(self::class, "parent_id");
  }
  public function ChildrenNodes() {
    return $this->hasMany(self::class, "parent_id");
  }
  public function toDump() {
    return $this->toArray();
  }
  public function getFullPathAttribute()
  {
    $node = $this;

    $url = [ ];
    while (($node))
    {
      if ($node->slug != '/' || (!count($url) && $node->is_home)) {
        $url[] = $node->slug;
      }
      $node = cache()->remember(get_class($node) . "parent_id={$node->parent_id}", 1, function() use ($node) {
        return $node->ParentNode;
      }); 
    }
    return implode('', array_reverse($url));
  }
  public function TreePageIndexes(): HasMany
  {
    return $this->hasMany(TreePageIndex::class);
  }
  public static function checkTemplateEvent($model, $eventName) {
    $event = explode(' ', $eventName);
    $event = explode(':', $event[0]);
    $event = explode('.', $event[0])[1];
    if (!in_array($event, ['booted']) ) {
      $eventMethod = $event . "Event";
      $class = new ReflectionClass($model->template);
      $staticmethods = array_column($class->getMethods(ReflectionMethod::IS_STATIC), 'name');
      if (in_array($eventMethod, $staticmethods)) {
        return $eventMethod;
      } 
    }

  }
  public static function booting(){
    Event::listen('eloquent.*Bvfbarten\SimpleCms\Models\TreePage', function (string $eventName, array $data) {
      $event = self::checkTemplateEvent($data[0], $eventName); 
      if ($event) {
        return $data[0]['template']::$event($data[0]);
      } else {
        //dump($eventName, $data[0]->toArray());
      }
    });
  }
  public static function whereIndexed($index, $value = null) {
    if (!is_array($index)) {
      $index = [$index => $value];
    }
    $rtn = new self;
    foreach($index as $key => $value) {
      $rtn->where($key, $value);
    }
    return $rtn;
  }
  public static function booted() {
    static::saving(function (TreePage $model) {
      $event = self::checkTemplateEvent($model, 'simplecms.saving');
      if ($event) {
        ($model->template)::$event($model);
      } 
      if ($model->is_home == true) {
        $model->parent_id = -1;
      }
      if ($model->is_home == false  && $model->parent_id == -1) {
        $home = self::where('is_home', 1)
          ->where('site_id', $model->site_id)
          ->first();
        $model->parent_id = $home->id;
      }
      if (!$model->site_id) {
        $model->site_id = $model->ParentNode->site_id;
      }
      if ($model->is_home) {
        $model->slug = "/";
      } else {
        $model->slug = "/" . Str::slug($model->title);
        $count = self::where('slug', $model->slug)
          ->where('id', '!=', $model->id)
          ->where('site_id', $model->site_id)
          ->where('parent_id', $model->parent_id)
          ->first();
        $counter = 2;
        while($count) {
          $model->slug = "/" . Str::slug($model->title) . '-' . $counter;
          $counter += 1;
          $count = self::where('slug', $model->slug)
            ->where('id', '!=', $model->id)
            ->where('site_id', '=', $model->site_id)
            ->first();
        }
      }
      $content = $model->content;
      foreach($model->attributes as $key => $value) {
        if (!in_array($key, $model->fillable)) {
          $content[$key] = $value;
        }
      }
      $model->content = $content;

      return $model;
    });
    Static::saved(function(TreePage $model){
      $class = new ReflectionClass($model->template);
      if(($class->hasMethod('getIndexes'))) {
        $template = $model->template;
        foreach($template::getIndexes() as $index) {
          $value = $model->get($index) ?? null;
          $index = TreePageIndex::firstOrNew([
            'template' =>$model->template,
            'tree_page_id' => $model->id,
            'key' => $index,
            'value' => $value
          ]);
          $index->save();
        }
      }
    });
  }
  /*
  select * from "tree_pages" where "tree_pages"."site_id" = 1 and "tree_pages"."site_id" is not null and "slug" = '/new-child' and exists (select * from "tree_pages" as "laravel_reserved_0" where "laravel_reserved_0"."id" = "tree_pages"."parent_id" and "slug" = '/test') and exists (select * from "tree_pages" as "laravel_reserved_1" where "laravel_reserved_1"."id" = "tree_pages"."parent_id" and "is_home" = 1) limit 1 
*/

  static public function findByPath($path, $domain = null) {
    $request = app('request');
    $site = Site::findByDomain($domain);
    if ($path == '/') {
      return $site->TreePages()->where('is_home', true)->first();
    }
    $pathArray = explode('/', $path);
    $pathArray = array_reverse($pathArray);
    $rtn = $site
      ->TreePages()
      ->where('is_live', true)
      ->where('slug', '/' . array_shift($pathArray));
    $hasLine = [];
    foreach($pathArray as $line) {
      $hasLine[] = "ParentNode";
      $rtn->whereHas(implode('.', $hasLine), function($query) use ($line) {
        $query->where('slug', "/{$line}");
      });
    }
    $hasLine[] = "ParentNode";
    $rtn->whereHas(implode('.', $hasLine), function($query) {
      $query->where('is_home', true);
    });
    return $rtn->first();
  }
}
