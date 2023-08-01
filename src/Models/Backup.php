<?php

namespace Bvfbarten\SimpleCms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Yaml\Yaml;

class Backup extends Model
{
    use HasFactory;
    use \Orbit\Concerns\Orbital;

    public static $driver = "md";

    public static function schema(Blueprint $table)
    {
        $table->string('slug');
        $table->string('id');
        $table->string('class');
        $table->text('content');
    }
    public function getKeyName()
    {
        return 'slug';
    }
    
    public function getIncrementing()
    {
        return false;
    }
    public function setIdAttribute($id) {
      $this->attributes['id'] = $id;
      $this->setSlug();
    }
    public function setClassAttribute($class) {
      $this->attributes['class'] = $class;
      $this->setSlug();
    }
    public function setSlug() {
        $this->slug = self::getSluggedClass($this, $this->id);
    }
    public static function getSluggedClass($model, $id = null) {
      if (is_object($model)) {
        $model = $model->class;
      }
      if ($id) {
        $id = "==={$id}";
      }
      $rtn = str_replace('\\', '-', $model) . $id;
      $rtn = str_replace('Bvfbarten-SimpleCms-Models-', '', $rtn);
      return $rtn;
    }
    /*
    public function getcontentAttribute() {
      return Yaml::parse($this->attributes['content']);
    }
     */
    public static function dumpModel($modelName, $forced = false) {
      global $saved;
      $saved = [];
      if ($forced) {
        self::where('slug', 'like', self::getSluggedClass($modelName) . '===%')
          ->chunk(100, function($deletable){
            foreach($deletable as $item) {
              $item->delete();
            }
          });
      }
      "{$modelName}"::chunk(100, function($models) {
        global $saved;
        foreach($models as $model) {
          $newModel = new self;
          $newModel->id = $model->id;
          $newModel->class = get_class($model);
          if ($checkModel = self::find($newModel->slug)) {
            $newModel = $checkModel;
          }
          $newModel->content = Yaml::dump($model->toDump(), 10, 2);
          $newModel->save();
          $saved[] = $newModel->slug;  
        }
      });
      self::where('slug', 'like', self::getSluggedClass($modelName) . '===%')
        ->whereNotIn('slug', $saved)
        ->chunk(100, function($deletable){
          foreach($deletable as $item) {
            $item->delete();
          }
        });
      dump(compact('modelName','saved'));
    }
    public static function loadModel($modelName) {
      global $saved;
      $saved = [];
      self::where('class', $modelName)->chunk(100, function($models){
        global $saved;
        foreach($models as $model) {
          $class = $model->class;
          if (!(
            $newModel = $class::find($model->id)
          )) {
            $newModel = new $model->class; 
            $newModel->id = $model->id;
          }
          $content = Yaml::parse($model->content);
          foreach($content as $key => $value) {
            $newModel->{$key} = $value;
          }
          $newModel->save();
          $saved[] = $newModel->id;  
        }
      });
      $modelName::whereNotIn('id', $saved)
        ->chunk(100, function($deletable){
          foreach($deletable as $item) {
            $item->delete();
          }
        });
      dump(compact('modelName','saved'));
    }
}
