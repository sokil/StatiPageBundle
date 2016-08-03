StaticPageBundle
================

## Configuration

### Page view

Place file to `app/Resources/StaticPageBundle/views/Page/index.html.twig` with your own markup of static page.
Page instance `Sokil\StaticPageBundle\Entity\Page` accessable as `page` variable, also `locale` must be passed to template. To get localized data, call `page.getLocalizations()[locale]` which gives you instance of `Sokil\StaticPageBundle\Entity\PageLocalization`. 

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
