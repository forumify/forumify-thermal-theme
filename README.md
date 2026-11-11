# Thermal

Thermal targeting optics: ironbow heat gradients, a scope reticle and panels that heat up under your cursor.

Thermal targeting optics. The ironbow palette of a thermal camera runs through the
whole theme: a heat scale with tick marks hangs under the header, every panel carries a
heat edge and warms up under the cursor, rows bloom with heat as you point at them, avatars
sit in a ring of the scale and every poster leaves a heat signature behind their avatar.
A scope reticle sits behind the page and sensor scanlines run across it.

Dark mode is the classic ironbow view on cold violet. Light mode is the camera's black-hot
greyscale.

Set in [Saira](https://fonts.google.com/specimen/Saira), with headlines stretched to its
widest setting. The heat-up transitions are switched off for visitors who prefer reduced
motion.

## Screenshots

| Light | Dark |
| --- | --- |
| ![Forum index](screenshots/forum-index.png) | ![Forum index, dark](screenshots/forum-index-dark.png) |
| ![Topic](screenshots/topic.png) | ![Topic, dark](screenshots/topic-dark.png) |
| ![Topic list](screenshots/topic-list.png) | ![Profile, dark](screenshots/profile-dark.png) |
| ![Comment editor](screenshots/reply-editor.png) | ![Moderation menu](screenshots/topic-menu.png) |
| ![Login](screenshots/login.png) | |

| Phone | Phone menu |
| --- | --- |
| ![Phone](screenshots/mobile.png) | ![Phone menu](screenshots/mobile-menu.png) |

## How it is built

The theme only styles forumify's own CSS framework: `box`, `card`, the `btn-*` buttons,
menus, form fields, tables, tabs, tags and alerts, plus the forum's own building blocks.
It overrides no templates. Pages added by forumify updates or by plugins are built from
the same classes, so they pick up the look without any extra work.

Every colour comes from a theme variable. forumify writes the light or dark set of
variables depending on the visitor's preference, and the stylesheet never needs to know
which one is active. forumify's own variables keep their meaning (the admin panel uses
them too), only their defaults change.

## Theme variables

On top of forumify's own colours, borders and font size, the theme adds these. All of
them can be changed under **Admin → Settings → Themes**, separately for dark mode.

| Variable | What it does |
| --- | --- |
| `thermal-cold … thermal-white` | The six stops of the heat scale, from cold to white hot |
| `thermal-glow` | Glow around panels and buttons that are heating up |
| `thermal-reticle` | Colour of the scope reticle, `transparent` hides it |
| `thermal-scanlines` | Opacity of the sensor scanlines, 0 to 1 |
| `thermal-font` | Font family |
| `color-scheme` | `light` or `dark`, so native controls and scrollbars match |

## Requirements

- PHP 8.4 or newer
- Composer

## What's in here

```
src/
  ThermalTheme.php   Theme class: name, author, stylesheets, variables
public/
  style.css               Loaded on every page while the theme is active
templates/                Overrides for forumify's and plugins' templates
```

Two things in `composer.json` make it a theme:

```json
{
    "type": "forumify-theme",
    "extra": {
        "forumify-plugin-class": "Forumify\\ThermalTheme\\ThermalTheme"
    }
}
```

forumify discovers installed themes by that package type, and reads the class named in
`extra` for the theme's name, stylesheets and variables.

## Variables

Your theme class extends forumify's base theme, so it starts out with every variable
forumify's own styles rely on: colours, elevations, borders and font size. Admins can
change all of them under **Admin → Settings → Themes**, with separate values for dark
mode.

Add your own by overriding `getThemeConfig()`, the docblock on your theme class shows
how. Each variable becomes a CSS custom property:

```css
.header {
    background-color: var(--c-header);
}
```

## Stylesheets and other static files

Everything in `public/` is linked into the forumify instance at
`public/themes/forumify/thermal-theme/` when the theme is installed or refreshed.
Stylesheets listed in `getStylesheets()` are loaded on every page, anything else
(images, fonts) can be referenced relative to them.

Using an asset builder for sass or similar? Keep the sources in `assets/` and build into
`public/`: only `public/` reaches the forumify instance.

## Overriding templates

Templates in `templates/` replace forumify's own. The first directory is `Forumify` for
core templates, or the plugin class name for a plugin's templates:

```
templates/Forumify/frontend/forum/list.html.twig
templates/AcmeCalendarPlugin/frontend/calendar.html.twig
```

Extend the original with the `@!` prefix and override only the blocks you need, so your
theme keeps working when forumify updates the template:

```twig
{% extends '@!Forumify/frontend/forum/list.html.twig' %}

{% block body %}
    <div class="flex gap-4">
        {{ parent() }}
    </div>
{% endblock %}
```

Missing a block you need? Add it to the original template and open a pull request on the
[platform repository](https://github.com/forumify/forumify-platform).

## Developing against a forumify instance

Point a local forumify instance at your working copy with a path repository, so your
changes are live without publishing anything:

```bash
cd /path/to/your/forumify-instance
composer config repositories.thermal path /path/to/thermal
composer require forumify/thermal-theme:@dev
```

The theme then shows up under **Admin → Settings → Themes**, where you can activate it.
If it doesn't, refresh the plugin list:

```bash
php bin/console forumify:plugins:refresh
```

## Code quality

```bash
make quality        # phpcs + phpstan
make quality-fix    # fix what phpcs can fix automatically
```

## Publishing

1. Push the repository to GitHub.
2. Submit it to [Packagist](https://packagist.org/packages/submit).
3. Tag a release. Users install it with
   `composer require forumify/thermal-theme`.

## Documentation

- [Theme guide](https://docs.forumify.net/guides/theme)
- [Customization guides](https://docs.forumify.net/guides/customization/introduction)
