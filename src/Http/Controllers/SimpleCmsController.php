<?php

namespace Bvfbarten\SimpleCms\Http\Controllers;


use Bvfbarten\SimpleCms\Models\Site;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SimpleCmsController extends Controller
{
    //
  public function show(Request $request) {
    $page = Site::findByPath($request->server('SERVER_NAME'), $request->path());
    if (!$page) {
      throw new NotFoundHttpException();
    }
    $templateName = 'templates.' . Str::slug(($page->template)::title());
    return view($templateName, compact('page'));
  }
}
