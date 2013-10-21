# NOUVEAU #
**Contributors:** Veraxus  
**Donate link:** http://nouveauframework.org/  
**Author URI:** http://nouveauframework.org/  
**Tags:** framework, php5.3  
**Version:** 0.0.2  
**Requires at least:** 3.6  
**Tested up to:** 3.8  
**Stable tag:** 0.0.2  
**License:** GNU General Public License  
**License URI:** GNU-LICENSE.txt  

NOUVEAU is an open-source, rapid-development theme & plugin framework for WordPress, built on Zurb Foundation 4 and PHP 5.3. Work fast. Be awesome.

## Description ##

**NOUVEAU is NOT a ready-made theme and should only be used by developers.**

NOUVEAU is a rapid-development framework for WordPress. Unlike other "theme frameworks" NOUVEAU doesn't try to cram everything into a single, monolithic, Frankenstein's Monster theme - instead, the theme is dedicated solely to theme-related work, keeping things clean and easy. If you want even more features, we have plugins for that. Download plugins only for the features you really need, and customize them quickly and easily. Everything is standardized, simple, clean, and well commented - so you can work FAST.

###Features###

* **Built for WordPress**
NOUVEAU isn’t a theme or a plugin, it’s a framework. Anything you need to quickly get started on a new theme or plugin is already there, letting you get right to the meat of your WordPress project.

* **Modular**
Everything is available a-la-carte. By keeping the theme and features separate, you can easily use or customize only what you need (as plugins), and none of what you don’t.

* **PHP 5.3+**
**PHP 5.3 provides numerous benefits:** namespaces, closures, anonymous functions... and NOUVEAU takes advantage of that. But if you need to maintain parity with WordPress, a PHP 5.2.4-compatible version (**not this one!**) is also available.  

* **Zurb Foundation 4**
NOUVEAUs theme framework component is built on the latest version of Zurb Foundation, an open-source front-end framework that blows Bootstrap out of the water. Create responsive websites with incredible speed and flexibility.

* **Clean Code. No Assumptions**
No child themes, no file soup, no styles, no assumptions. Just clean, tidy, well-documented code and clean, tidy file structures.

* **Free. Open Source. Always. Forever.**
No purchases, no memberships, no freemiums, no strings. NOUVEAU is free as in speech, and it’s going to stay that way. Forever.

In addition to having very well documented code, you can find a complete **Getting Started** tutorial at [NouveauFramework.com](http://nouveauframework.org/documentation/getting-started/)

## Installation Instructions ##

NOUVEAU is NOT a ready-made theme and should only be used by developers. it is specifically built to facilitate rapid development and easy maintenance by developers. The code is clean, simple, and very well commented.

To install, simply the copy the NOUVEAU theme folder to your `wp-content\themes` directory. Before activating, be sure you rename the folder and perform a global find-replace for NOUVEAU, Nouveau, nouveau (case sensitive), as well as the language scopes (nvLangScope).

You can find any information you may need at [NouveauFramework.com](http://nouveauframework.org/documentation/getting-started/)

Also remember that you can test your own NOUVEAU derivatives by using the WordPress [Theme Unit Test]( http://codex.wordpress.org/Theme_Unit_Test ).

# File Structure #

NOUVEAU has an file structure that encourages better organization of your theme. It includes the following folders...

* _compass - Contains all the original Zurb Foundation files. Uncompiled SASS, JavaScript, etc.
* assets - Contains non-php assets like javascript, css, images, and language files.
* layout - Contains the theme's global header and footer files, leaving the theme root free for page templates.
* nv - Contains all the NOUVEAU core classes.
* overrides - Contains template files that are used solely as admin-selectable page templates.
* parts - Contains any template chunks/fragments/parts that *do not* make up a page-level template. Articles and other such fragments go here.

General page templates (`index.php`, `archive.php`, `page.php`, `single.php`, etc) as well as critical files like `functions.php` still go in the theme's root folder. This allows WordPress's core template system to continue working as-is. As a rule, you should keep your PAGE templates here, and organize any fragment/part templates under the parts directory. This keeps the root clean and helps encourage use of clean, organized, reusable template parts.

You find a complete (yet concise) overview of file and folder structure at [NouveauFramework.com](http://nouveauframework.org/documentation/getting-started/)

# Zurb Foundation Notes #

Foundation has been tweaked slightly to work as part of a WordPress theme, however the tweaks needed to ensure this compatibility are rather minor. One important thing to note is that `config.rb` (i.e. the Compass configuration file) is located in the theme's root directory while the *uncompiled* Zurb Foundation files are located in the `_compass` directory. This allows you to set your compiler (e.g. Compass, CodeKit, etc) to watch the theme folder, and everything is just put in the right places for you automatically (again, `config.rb` is already set up for this).

If you don't want to use SASS, then everything is already compiled for you. Just write your plain CSS in the main style.css file (in the theme root) as you would normally do.

# Companion Plugins #

WordPress themes (and theme frameworks) should never be all-encompassing monstrosities. As a result, all the cool features you want are available as neatly packaged "starter" plugins. They are "starter" because these plugins are meant to be quickly and easily modified by developers like you, to do anything you need. They are literally just a starting point for your own development needs. Use what you want, and none of what you don't.

These plugins were created by developers, for developers. We hope they make your life as a developer much, much easier.

# Internationalization (I18n) #

NOUVEAU comes with all text strings properly scoped for internationalization. To set a custom scope string, you can quickly to a global search and replace for the string `nvLangScope` and you'll be up and running in no time.

## Changelog ##

### 0.0.2 (2013-10-21) ###
* Significant cleanup on code base. Lots of features moved into plugins.
* Basic (very early) support for comments
* Small fixes to SASS/CSS using newest WP unit tests

### 0.0.1 ###
* First commit. Lots and lots of cleanup left before official release.