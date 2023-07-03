<?php

namespace Bvfbarten\SimpleCms\Models;

use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChildrenNode extends TreePage
{
    use HasFactory;
    public function treePages() {
      return $this->hasMany(TreePage::class, "parent_id");
    }
}
