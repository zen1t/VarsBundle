[![SensioLabsInsight](https://insight.sensiolabs.com/projects/32f41351-fcad-4c5f-a446-30f74d72d301/big.png)](https://insight.sensiolabs.com/projects/32f41351-fcad-4c5f-a446-30f74d72d301)

##### Step 1: Download ZentVarsBundle using composer 
``
composer require zent/vars-bundle dev-master
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
````

If you need additional caching:
````
doctrine_cache:
    providers:
        zent_vars_query_cache:
            type: apc
            namespace: query_cache_ns
            alias:
              - zent_vars.cache
````
Doc: https://symfony.com/doc/current/bundles/DoctrineCacheBundle/reference.html

##### Step 5: Update your database schema

#### Usage

##### Accessing the User Manager service
````
$varsManager = $container->get('zent_vars.manager');
$var = $varsManager->getValue('first');
$var = $varsManager->getValue('first', 10); //return '10' if var not found 
````

##### Twig

````
{{ vars('first') }}
````

#### Command Line Tools
````
php app/console zent:vars:create email_owner test@example.com "Email владельца"
php app/console zent:vars:update email_owner test@test.com
php app/console zent:vars:delete email_owner
php app/console zent:vars:list
````
