<?php

namespace Bvfbarten\SimpleCms\Models;

use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SolutionForest\FilamentTree\Concern\ModelTree;

class TreePage extends Model
{
    use ModelTree;
    protected $table = "tree_pages";
    protected $connection = "simple_cms";
    protected $fillable = ["id", "parent_id", "title", "slug", "order", 'template', "content", "activation_date", "deactivation_date", "created_at", "updated_at", "last_edited_by_id"];
    protected $casts = [
        'content' => 'array'
    ];
    use HasFactory;
    public function set($key, $value) {
      $this->content[$key] = $value;
    }
    public function get($key, $value = null) {
      return $this->content[$key] ?? $value;
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
    public static function booted() {
        static::saving(function (TreePage $model) {
          if ($model->is_home == true) {
            $model->parent_id = -1;
          }
          if (!$model->slug) {
            $model->slug = "/" . Str::slug($model->title);
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

    }
}
