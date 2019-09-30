# SEO Polish

This module contains:

- configurable field to set the noindex status to specific pages 
- additional (and optional) small tweaks that are often needed:
  - add prev and next rel links to paged content
  - removing the hreflang
  - removing the canonical on 404 pages
  - removing the canonical on pages with noindex status
  - removing the canonical provided through the http-header

# Dependencies

- This is a standalone module.

# Install

To get the newest version copy the following string into the `composer.json` in the section `repositories`.

```json
{
    "type": "git",
    "url": "https://github.com/silent-murmur/seo_polish.git"
}
```

Require the module via `composer require "silent-murmur/seo_polish:~1.0"`.

# Update

Use the following command: `composer update "silent-murmur/seo_polish"`

# Configuration

The settings page can be found here: /admin/config/seo_polish/settings