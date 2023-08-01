<?php

namespace Bvfbarten\SimpleCms\Models;

use Bvfbarten\SimpleCms\Interfaces\Dumpable;
use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Domain extends Model implements Dumpable
{
  use HasFactory;
  protected $connection = "simple_cms";
  protected $table = "domains";
  protected $fillable = [ "site_id", "value", "is_primary"];
  public function Site() : BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
  public function toDump() : array
  {
    return $this->toArray();
  }
}
