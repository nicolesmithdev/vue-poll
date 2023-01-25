## Poll Widget

This plugin creates a "Poll" WordPress widget that allows users to vote on their favorite blog post utilizing the WordPress REST API and Vue.js.

## How to Use

1. Drop the `vueplugin` folder in the `wp-content/plugins/` directory of your WordPress installation
2. Activate the plugin through the `Plugins` menu in WordPress
3. Go to `Appearance => Widgets` and add the `Poll` widget to your widget area
4. Go to `Users => Profile` and `Add a New Application Password`. Update lines 12 & 13 in `functions/wp-enqueue-script.php` with the username and application password. This is required for logged out users to be able to `POST` their vote via the WordPress REST API.
5. Confirm or publish a few posts to be able to vote on ;)

## Changelog

### January 25, 2023

-   Upgrade from Vue 2 to Vue 3

### March 30, 2022

One year from my initial commit! I've entirely overhauled the plugin.

-   Now utilizing npm
-   Refactored Vue template literals into single-file components
-   Compiling JavaScript and CSS assets via Webpack Mix
-   Organized PHP functions via includes

### March 28, 2021

-   Initial commit
