StaticPageBundle
================

## Configuration

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
