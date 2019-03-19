# WordPress Plugin Development using Boilerplate
 This plugin is used as a project in the course on **WordPress Plugin Development using Boilerplate**


## How To Use

All the Branches of this repository corresponds to relative lectures of the course. Each branch is the **final** code of the corresponding lecture.

*For instance*
> Branch `06_10` corresponds to end of Section 6, Lecture 10

> Branch `10_02` is the code at the end of 2nd video of Section 10

Suppose you intend to take 8th video lecture of 9th section (`Section 9, Lecture 8 `), then, the code at the start of lecture will look according to the branch no `09_07` and at the end of video, it will look like `09_08` 

There are two ways to get the required git branch for lecture:
### Method 1 : Using online Github repo 
You simply select the Branch from dropdown and click on "Clone or download" button and then click on "Download ZIP" and you get the .zip file with branch name suffixed to the plugin name. 

### Method 2 : Using local git repo
To use this method, you clone the repo in your local development and setup inside `wp-content/plugins/` using the command:

`git clone https://github.com/booskills/rocket-books.git`

and then you go inside the plugin directory:

`cd rocket-books`

and simply checkout the required branch (09_07 in example) using the command:

`git checkout 09_07`


## Topics covered in the course
- Using [Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) to create plugins 
- Custom Post Types
- Custom Taxonomies
- Metaboxes
	- Registering Metaboxes
	- Custom Fields in Metaboxes
	- Validation and sanitization of Custom Fields
	- Adding different Field Types
- Settings API
	- Plugin admin section
	- Top level admin menu
	- Sub menu item for plugin, 
	- Settings page, 
	- Saving and retrieving fields
- Shortcodes API
	- Registering shortcodes
	- Shortcode attributes
	- Using Attributes to alter Shortcode Output
	- Best Practices for Enqueuing CSS and JS
- Widgets API
	- Registering widgets
	- Using `form()` , `widget()` and `update()` methods
	- Adding different Field Types
- Plugin Security and Best Practices
- Best Practices to Avoid Naming Collisions
- POT File Generation for plugin Translation
- Cleaning up at Uninstall