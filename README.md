StaticPageBundle
================

## Installation

Install bundle through composer:
```
composer.phar require sokil/static-page-bundle
```

Bundle uses assetic so you need to register it in assetic config:
```yaml
assetic:
    bundles:
        - StaticPageBundle
```

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

To route any unexisted url to static page handler, you need to add some configuration to `app/config/config.yml`:
```yaml
cmf_routing:
    chain:
        routers_by_id:
            router.default: 200
            static_page.page_router: 100
    dynamic:
        persistence:
            orm:
                enabled: true
```

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
