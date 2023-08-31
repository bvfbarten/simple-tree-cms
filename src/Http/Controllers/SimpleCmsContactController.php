<?php

namespace Bvfbarten\SimpleCms\Http\Controllers;

use Illuminate\Http\Request;
use Bvfbarten\SimpleCms\Models\TreePage;
use Bvfbarten\SimpleCms\Filament\PageTemplates\Contact;
use Bvfbarten\SimpleCms\Models\Site;

class SimpleCmsContactController extends Controller
{
  public function addContact(Request $request) {
    if ($validatedData = $this->validate($request, [
      'name' => 'required',
      'email' => 'required|email',
      'description' => ''
    ])) {
      $site = Site::findByDomain($request->server('SERVER_NAME'));
      $page = $site->newTreePage(Contact::class);
      $page->set($validatedData);
      $page->save();
    }

    return redirect('/')->with('status', 'Contact Submitted!');

    
    
    
  }    
}
