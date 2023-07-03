<?php

namespace Bvfbarten\SimpleCms\Helpers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileHelper
{
  static function getAllFiles($basePath, $fullPath = true)
  {
    $directoryIterator = new RecursiveDirectoryIterator($basePath, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);
    
    $files = [];
    foreach ($iterator as $item) {
      if ($item->isFile()) {
        $files[] = $item->getPathname();
      }
    }
    if ($fullPath) {
      return $files;
    }
    $rtn = [];
    foreach($files as $file) {
      $rtn[] = str_replace($basePath, '', $file);
    }
    return $rtn;
  }
}
