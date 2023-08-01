<?php
// tests/Unit/UserModelTest.php
namespace Bvfbarten\SimpleCms\Tests;

use Tests\TestCase;
use Bvfbarten\SimpleCms\Models\Site;

class SiteModelTest extends TestCase
{
  public function test_get_site()
  {
    $site = Site::first();
    $this->assertTrue($site->domain == '127.0.0.1', 'Site Shows up');
  }
}
