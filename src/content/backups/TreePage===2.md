---
slug: TreePage===2
id: '2'
class: Bvfbarten\SimpleCms\Models\TreePage
created_at: 2023-07-04T23:35:25+00:00
updated_at: 2023-07-06T08:10:01+00:00
---
id: 2
title: home
parent_id: -1
order: 1
is_home: 1
site_id: 1
activation_date: null
deactivation_date: null
slug: /
template: App\Filament\PageTemplates\Content
content:
  is_home: 1
  site_id: 1
  sections:
    -
      title: 'About Simple Cms'
      image: null
      content: |
        <p>SimpleCms is meant for creating a very simple cms for the laravel platform.</p><p>It is meant to stay out of your way allowing you to build your app, but allow you to focus on adding your content in the easiest way possible. It does this by focusing on 4 things:</p>
        <ul>
            <li>filamentphp&nbsp;</li>
            <li>&nbsp;TreePage: A flexible model that focuses on allowing multiple children and allowing any filament inputs you need.</li><li>Routing built around the parent/child structure of treepages to locate the page data and combining it with a view preset based on the template assigned to the TreePage loaded.</li><li>Yaml: Allows editing the cms content from your favorite text or backing up your table data in a way that is easily zipable.</li>
        </ul>
      image_name: null
    -
      title: Install
      image: null
      content: "<p>This is the first section</p>\n<p>test second line!</p>\n"
      image_name: null
    -
      title: 'Site Model'
      image: null
      content: "<p>This is the first section</p>\n<p>test second line!</p>\n"
      image_name: null
    -
      title: 'Page Model'
      image: null
      content: "<p>This is the first section</p>\n<p>test second line!</p>\n"
      image_name: null
    -
      title: Templates
      image: null
      content: "<p>Templates will be called directly at the directory vendor.simplecms.{template}.blade.php</p>\n<p>As an example, To override this template, create or update the file at resources/views/vendor/simplecms/home.blade.php\n"
      image_name: null
    -
      title: 'Yaml Dump'
      image: null
      content: "<p>This is the first section</p>\n<p>test second line!</p>\n"
      image_name: null
    -
      title: Routing
      image: null
      content: "<p>This is the first section</p>\n<p>test second line!</p>\n"
      image_name: null
    -
      title: Contact Us
      image: null
      content: |
        <p>This is an eample on how to use a custom TreePage Model as a model for quick access</p>
        <p>test is still the second line!</p>
      image_name: null
  content: null
created_at: '2023-07-03T07:45:13.000000Z'
updated_at: '2023-07-06T08:09:47.000000Z'
