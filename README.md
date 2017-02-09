##### Step 1: Download ZentVarsBundle using composer 
``
composer require zent/vars-bundle "~0.2"
``
##### Step 2: Enable the bundle
Enable the bundle in the kernel:
````
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Zent\VarsBundle\ZentVarsBundle(),
        // ...
    );
}
````

##### Step 3: Create your Vars class
````
// src/AppBundle/Entity/Vars.php
use Zent\VarsBundle\Entity\BaseVars;

class Vars extended BaseVars
{
}
````

##### Step 4: Configure the ZentVarsBundle

````
# app/config/config.yml
zent_vars:
    class: AppBunde\Entity\Vars
    cache_provider: doctrine_cache.providers.vars_query_cache # or other (optional)

````

##### Step 5: Update your database schema
- Cache provider is not required. 
List providers: https://symfony.com/doc/current/bundles/DoctrineCacheBundle/reference.html

#### Usage

##### Accessing the User Manager service
``
$varsManager = $container->get('zent.vars_manager');
``
##### Get value Var
````
$user = $userManager->getVar('first');
$user = $userManager->getVar('first',10); //return '10' if var not found 
````

#### Command Line Tools
````
php app/console zent:vars:create email_owner test@example.com "Email владельца"
php app/console zent:vars:update email_owner test@test.com
php app/console zent:vars:delete email_owner
php app/console zent:vars:list
````