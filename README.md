Download:
<pre>
composer require zent/vars-bundle
</pre>

Enable in AppKernel:
<pre>
$bundles = array(
        // ...
       new Zent\VarsBundle\ZentVarsBundle(),
);
</pre>

Usage with cache (optional):
https://symfony.com/doc/current/bundles/DoctrineCacheBundle/reference.html
<pre>
# app/config/config.yml
doctrine_cache:
    providers:
        vars_query_cache:
            type: file_system
            namespace: query_cache_ns
zent_vars:
  cache_provider: doctrine_cache.providers.vars_query_cache
</pre>

Call:
<pre>
$this->container->get('zent.vars')->getVar('first_var');
or
$this->container->get('zent.vars')->getVar('first_var', 10); //return if var not found 
</pre>
