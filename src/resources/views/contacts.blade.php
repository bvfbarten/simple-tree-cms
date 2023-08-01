<?php

use Illuminate\Support\Facades\Validator;
use App\Filament\PageTemplates\Contact;

function controller($controller, $page) {
  if (request()->method() == 'POST') {
    if ($validatedData = Validator::validate(request()->post(), [
      'name' => 'required',
      'email' => 'required|email',
      'description' => ''
    ])) {
      $newPage = $page->newTreePage(Contact::class);
      $newPage->set($validatedData);
      $newPage->save();
      return redirect('/')->with('status', 'Contact Submitted!');
    }
  }
}
