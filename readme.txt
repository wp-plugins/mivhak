=== Mivhak Syntax Highlighter ===
Contributors: Askupa Software
Tags: syntax highlighter, code prettifier, highlighting, syntax, google-code-prettify, code snippet, formatting, programming, software development.
Requires at least: 3.0
Tested up to: 4.1.1
Stable tag: 1.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight, editor safe syntax highlighter with real time syntax highlighting and error checking.

== Description ==

Have you been looking for a syntax highlighter that is safe to use on both the visual and the HTML WordPress editor? What
about a tool that highlights your syntax *While* writing your code, and also checks for syntax errors?

Well, look no further. *Mivhak* is a lightweight syntax highlighter for WordPress, based on a slightly modified version of the great *Ace Code Editor*.
Mivhak comes with a simple settings panel that allows the user to setup basic plugin behavior and appearance.

Additionally, code can be easily inserted to both the HTML and the visual editor using a TinyMCE popup the features live syntax highlighting and error checking for 100+ languages.

**Features**

* Lightweight - minified CSS and JS, language scripts and themes are loaded on request
* Supports 100+ different programming languages
* 36 different skins
* Visual + HTML editor code insertion buttons
* Live syntax highlighting and error checking while writing code
* Easy-to-use control panel
* Visual editor placeholders with floating control bar (see screenshots)
* Auto code highlighting by tag name
* Inline and block code widgets

**Useful Links**

* [Official Page](http://products.askupasoftware.com/mivhak/)
* [Examples](http://products.askupasoftware.com/mivhak/examples/)
* [Documentation](http://products.askupasoftware.com/mivhak/documentation/)

== Installation ==

1. Download and activate the plugin.
1. Use the control panel to choose a skin.
1. Specify which tags you would like Mivhak to prettify (CODE, PRE, XHR).

== Frequently Asked Questions ==


== Screenshots ==

1. Code snippet before Mivhak
2. Code snippet after Mivhak
3. General settings section under Mivhak options page
4. Skin selector under Mivhak options page
5. Code can be easily edited or removed in the visual editor
6. The code insertion/edition popup window

== Changelog ==

= 1.2.3 =
* (FIX) Fixed some notices that were showing when WP_DEBUG was set to true
* (FIX) Uninstalling the plugin now removes any traces from the database
* (NEW) Add an option the write custom CSS (under Mivhak->appearance)

= 1.2.2 =
* (FIX) PHP Strict Standards issue

= 1.2.1 =
* (NEW) Selected text is used as input for the popup code editor
* (NEW) Option to select a default language when no language has been detected
* (NEW) Programming languages pretty names (As opposed to all uppercase names as it has been until now)
* (FIX) Extra lines/spaces will be trimmed
* (FIX) Visual Editor issue that was preventing code blocks without the class attribute from being edited
* (FIX) Non-breaking space issue that was treated as an invalid character
* (FIX) A bug that was causing line breaks to be added when switching between the visual and the HTML editors
* (FIX) CSS issues

= 1.2.0 =
* (UPDATE) Amarkal Framework v0.3.4
* (UPDATE) Completed migration to Ace Editor
* (UPDATE) Slightly modified visual appearance

= 1.1.1 =
* (UPDATE) Amarkal Framework v0.3.3
* (FIX) Fixed an issue that was causing line breaks to be removed when switching between visual and HTML editor.

= 1.1.0 =
* (NEW) Added support for bbPress
* (NEW) Visual + HTML editor buttons with code writing tools
* (NEW) Static/dynamic highlighting is now implemented using the great Ace Code Editor
* (NEW) Visual editor placeholders with floating control bar
* (UPDATE) Improved CSS

= 1.0.7 =
* (FIX) Minor CSS fixes (tested on multiple themes)
* (UPDATE) Amarkal Framework

= 1.0.6 =
* (FIX) Visibility issue in FireFox (thanks zeaks!)
* (FIX) Issue with code snippets in comments
* (UPDATE) Code blocks are now print friendly
* (UPDATE) Brand new admin page - see screenshots

= 1.0.5 =
* (UPDATE) Amarkal framework update

= 1.0.4 =
* (FIX) Prevent line numbers from being copied

= 1.0.3 =
* (FIX) inline code segments issue
* (FIX) no line-numbers issue
* (UPDATE) Improved CSS styling
* (NEW) Choose whether to show/hide meta header by line count

= 1.0.2 =
* (FIX) script tag encoding issue
* (UPDATE) Amarkal framework
* (NEW) Meta header shows language name

= 1.0.1 =
* (FIX) HTML encoding issue

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.2.3 =
* New features and minor bug fixes

= 1.2.2 =
* Improved code formatting

= 1.2.1 =
* New features and minor bug fixes

= 1.2.0 =
* Completed migration to Ace Editor

= 1.1.1 =
* Updated framework, minor bug fixes

= 1.1.0 =
* Migrated to Ace Editor, multiple new features

= 1.0.7 =
* Minor CSS fixes

= 1.0.6 =
* Visibility improvements

= 1.0.5 =
* Amarkal framework update

= 1.0.4 =
* Prevent line numbers from being copied

= 1.0.3 =
* Fixed line numbers issue

= 1.0.2 =
* Fixed script tag encoding issue

= 1.0.1 =
* Fixed HTML encoding issue