<?php

use Illuminate\Support\Facades\Validator;

function controller($controller, $page) {
  if (request()->method() == 'POST') {
    if ($validatedData = Validator::validate(request()->post(), [
      'name' => 'required',
      'email' => 'required|email',
      'description' => ''
    ])) {
      $newPage = $page->newTreePage(Contact::class);
      $page->set($validatedData);
      $page->save();
      return redirect('/')->with('status', 'Contact Submitted!');
    }
  }
}
