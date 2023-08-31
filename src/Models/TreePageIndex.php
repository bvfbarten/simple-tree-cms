<?php

namespace Bvfbarten\SimpleCms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreePageIndex extends Model
{
    use HasFactory;
    protected $connection = "simple_cms";
    protected $fillable = [
      'id',
      'template',
      'key',
      'value',
      'tree_page_id',
      'created_at',
      'updated_at'
    ];
}
