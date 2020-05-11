=== Today Utilities ===
Contributors: ucfwebcom
Requires at least: 4.9.7
Tested up to: 5.3
Stable tag: 1.0.11
Requires PHP: 5.4
License: GPLv3 or later
License URI: http://www.gnu.org/copyleft/gpl-3.0.html

Feature and utility plugin for the UCF Today website.


== Description ==

Features and utilities for the UCF Today WordPress site that are otherwise not suited for inclusion within a theme.


== Documentation ==

Head over to the [Today Utilities wiki](https://github.com/UCF/Today-Utilities/wiki) for detailed information about this plugin, installation instructions, and more.


== Changelog ==

= 1.0.11 =
Enhancements:
* Extended the request timeout for oEmbed content fetches
* Updated packages

= 1.0.10 =
Enhancements:
* Added primary category and tag to post REST API results

= 1.0.9 =
Documentation:
* Updated contributing doc to reflect the switch from slack to teams.

= 1.0.8 =
Bug Fixes:
* Fixed undefined index 'paged' notice on main site stories feed.

= 1.0.7 =
Bug Fixes:
* Corrected PHP notice dealing with accessing an array index that was not set.

= 1.0.6 =
Enhancements:
* Added oEmbed support for Juxtapose embeds.

= 1.0.5 =
Bug Fixes:
* Added default posts API parameters to the main-site-stories endpoint.

= 1.0.4 =
Bug Fixes:
* Updated the image size used for spotlight images.

= 1.0.3 =
Bug Fixes:
* Added missing support for `category_slugs` and `tag_slugs params` in REST API results for posts.  Necessary for compatiblity with the UCF News plugin.

= 1.0.2 =
Bug Fixes:
* Updated `tu_add_author_byline_to_post_feed` function to use proper parameter.
* Added GMUCF Announcements feed logic and customizer option.

= 1.0.1 =
Bug Fixes:
* Updated feed to output image size which is valid.

= 1.0.0 =
* Initial release


== Upgrade Notice ==

n/a


== Development ==

Note that compiled, minified css and js files are included within the repo.  Changes to these files should be tracked via git (so that users installing the plugin using traditional installation methods will have a working plugin out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

= Requirements =
* node
* gulp-cli

= Instructions =
1. Clone the Today-Utilities repo into your local development environment, within your WordPress installation's `plugins/` directory: `git clone https://github.com/UCF/Today-Utilities.git`
2. `cd` into the new Today-Utilities directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Optional: If you'd like to enable [BrowserSync](https://browsersync.io) for local development, or make other changes to this project's default gulp configuration, copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.

    To enable BrowserSync, set `sync` to `true` and assign `syncTarget` the base URL of a site on your local WordPress instance that will use this plugin, such as `http://localhost/wordpress/my-site/`.  Your `syncTarget` value will vary depending on your local host setup.

    The full list of modifiable config values can be viewed in `gulpfile.js` (see `config` variable).
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment to test this plugin against, and [install and activate all plugin dependencies](https://github.com/UCF/Today-Utilities/wiki/Installation#installation-requirements).
5. Activate this plugin on your development WordPress site.
6. Run `gulp watch` to continuously watch changes to scss and js files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when plugin files change.

= Other Notes =
* This plugin's README.md file is automatically generated. Please only make modifications to the README.txt file, and make sure the `gulp readme` command has been run before committing README changes.  See the [contributing guidelines](https://github.com/UCF/Today-Utilities/blob/master/CONTRIBUTING.md) for more information.


== Contributing ==

Want to submit a bug report or feature request?  Check out our [contributing guidelines](https://github.com/UCF/Today-Utilities/blob/master/CONTRIBUTING.md) for more information.  We'd love to hear from you!
