<?php

namespace Bvfbarten\SimpleCms\Http\Controllers;

use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Str;

class SimpleCmsController extends Controller
{
  public function show(Request $request) {
    $page = TreePage::findByPath($request->path());
    if ($page) {
      $templateName = 'simplecms::' . Str::slug(($page->template)::title());
      if(View::exists($templateName) || config('simple-cms-config.show_templates')) {
        $rtn = view($templateName, compact('page'))->render();
        if (is_callable('controller')) {
          $controllerReturn = controller($this, $page);
          if ($controllerReturn === null) {
            throw new NotFoundHttpException();
          }
          return $controllerReturn;
        }
      }
      return $rtn;
    } 
    throw new NotFoundHttpException();
  }
}
