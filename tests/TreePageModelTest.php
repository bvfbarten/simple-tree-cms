<?php
// tests/Unit/UserModelTest.php
namespace Bvfbarten\SimpleCms\Tests;

use Bvfbarten\SimpleCms\Filament\PageTemplates\Contact;
use Bvfbarten\SimpleCms\Models\TreePage;
use Tests\TestCase;
use Bvfbarten\SimpleCms\Models\Site;

class TreePageModelTest extends TestCase
{
  public function test_get_home()
  {
    $page = TreePage::findByPath('/', '127.0.0.1');
    $this->assertTrue($page->title == 'home', 'getting home');
    $this->assertTrue(is_array($page->get('sections')), 'sections not showing up');
    $page->set(['sections' => ['test' => 'test row']]);
    $page->set('sections.test', 'test row');
    $this->assertTrue($page->get('sections.test') == 'test row', 'test row is: ' . json_encode($page->get('sections'), JSON_PRETTY_PRINT));
    $page->save();
    $page->remove('sections.test'); //deletes sections.test
    $page->save();
    dump($page->content);
    $this->assertTrue($page->get('sections.test') == null, 'test row was not deleted property');
  }
  public function test_create_contact()
  {
    $site = Site::findByDomain('127.0.0.1');
    $contact = $site->newTreePage(Contact::class);
    TreePage::where('template', Contact::class)->delete();
    $contact
      ->set('name', 'jonny hancock')
      ->set('email', 'jonny@gmail.com')
      ->set([
        'phone' => '8019110909',
        'description' => 'simple description'
      ]);
    $contact->save();
    $this->assertTrue($contact->title == 'jonny hancock');
    $this->assertTrue($contact->slug == '/jonny-hancock', 'slug is: ' . $contact->slug);
    $contact2 = $site->newTreePage(Contact::class);
    $contact2->set($contact->content);
    $contact2->save();
    $contact3 = $site->newTreePage(Contact::class);
    $contact3->set($contact->content);
    $contact3->save();
    dump([
      $contact->slug,
      $contact2->slug,
      $contact3->slug,
    ]);
    $this->assertTrue($contact2->title == 'jonny hancock');
    $this->assertTrue($contact2->slug == '/jonny-hancock-2', 'slug is: ' . $contact2->slug);
    $this->assertTrue($contact3->slug == '/jonny-hancock-3', 'slug is: ' . $contact3->slug);

    //$contact->delete();
    //$contact2->delete();
  }
}

