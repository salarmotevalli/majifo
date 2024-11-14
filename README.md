## Project Information:
- symfony 7
- doctrin orm 
- postgresql
- tailwind
- twig

### DB changes
- use ulid instead of uuid
- add `author` field to post table;
- add `status` field to post table to avoid `JOIN` post table with approval table for many time.

### Caching:

- Use ESI to avoid connecting to the database for entities that change infrequently (categories, post types) in /post page (filter form data)

- group admin routes by prefix (https://symfony.com/doc/6.4/the-fast-track/en/21-cache.html#grouping-similar-routes-with-a-prefix)

- database cache for last 4 published and approved posts.


- use binary level cache to avoid recompile php code using opcache and attach config/preload.php file to opcache to precompile some services...


in docker:

- use franken to cache an instance of application into RAM  


### Admin
open `0.0.0.0:8000/admin`

- only admin users can see this page
- there are four admin role: super-admin, normal-admin, read-only-admin, post-manager-admin
- super-admin have all read and write access for all entity
- read-only-admin have only read access for all entity
- post-manager-admin have access to read and write post entity
- normal-admin have access to read and write all entity except user






### Post
