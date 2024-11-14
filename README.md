### Caching:

- Use ESI to avoid connecting to the database for entities that change infrequently (categories, post types) in /post page (filter form data)

- group admin routes by prefix (https://symfony.com/doc/6.4/the-fast-track/en/21-cache.html#grouping-similar-routes-with-a-prefix)

- database cache for last 4 published and approved posts.


- use binary level cache to avoid recompile php code using opcache and attach config/preload.php file to opcache to precompile some services...


in docker:

- use franken to cache an instance of application into RAM  


