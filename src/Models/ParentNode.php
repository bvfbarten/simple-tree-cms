<?php

namespace Bvfbarten\SimpleCms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bvfbarten\SimpleCms\Models\TreePage;

class ParentNode extends TreePage 
{
    use HasFactory;
    public function treePages() {
      return $this->belongsTo(TreePage::class, "parent_id");
    }
}
