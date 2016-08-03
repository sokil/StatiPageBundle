StaticPageBundle
================

## Configuration

### Routing

To enable default routing configuration just add `routing.yml` to you routes config `app/config/routing.yml`:
```yaml
static_page:
    resource: "@StaticPageBundle/Resources/config/routing.yml"
    prefix:   /
```
Or add your own routes for required actions.

### Roles

Register role at `app/config/security.yml`:

```yaml
# http://symfony.com/doc/current/book/security.html
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    # http://symfony.com/doc/current/book/security.html#hierarchical-roles
    role_hierarchy:
        ROLE_PAGE_MANAGER:          [ROLE_USER]
```
