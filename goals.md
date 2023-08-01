# Goals
## publish
    * publish config
        * publishes config
            * config will have option to turn contactController on or off
    * publish documentation
        * publishes Content
        * publishes Home and Contact Template Classes 
        * ContactController will be accessible through config
        * home blade is overridable through laravel
## Tests
    * Tests should be ran against Content
## SiteModel
    * Container for TreePages based on domain
    * Should be able to find by alternative domains
### TreePage
    * Contains custom content inside content
    * custom content is accessible through get($key, $default)
    * custom content is editable through set($key, $value) or setMany($arr)
    * custom content events can be accessed through laravel model event name + 'Event', eg: savingEvent
## SiteController
    * locates required TreePage via SiteModel then relevant TreePage
    * blade template found via vendor/simplecms/{template_name}.blade.php
