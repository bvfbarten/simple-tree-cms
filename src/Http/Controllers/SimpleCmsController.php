<?php

namespace Bvfbarten\SimpleCms\Http\Controllers;

use Bvfbarten\SimpleCms\Models\TreePage;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SimpleCmsController extends Controller
{
    //
  public function show(Request $request) {
    $page = TreePage::findByPath($request->path());
    if ($page) {
      $templateName = 'simplecms::' . Str::slug(($page->template)::title());
      if(View::exists($templateName) || config('simple-cms-config.show_templates')) {
        return view($templateName, compact('page'));
      } 
    }
    throw new NotFoundHttpException();
  }
}
