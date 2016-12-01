INTRODUCTION
------------

 **Bibliography & Citation module is under active development. It is not ready for use on production sites and and breaking changes are possible until Beta**
 At the current moment implemented basic features are render, export and import. Here is a list of modules included in the project and their features, that are already implemented:
 
 **Bibliography & Citation**
 It is a core module that provides API for a render of bibliography citation. The library we used is from official CSL style repository with over 8000 styles. Those styles are available without charge under a Creative Commons Attribution-ShareAlike (BY-SA) license.
 
 **Bibliography & Citation - Entity**
 Implements storage for bibliographic data as Drupal entities: Reference, Contributor and Keyword. Reference entity can be rendered as citations, exported and imported.
 
 **Bibliography & Citation - Export** 
 Provides the possibility to export bibliographic content. Adds export links to citations (configurable)
 
 **Bibliography & Citation - Import** 
 Provides import feature and UI for import from files.

 * For a full description of the module, visit the project page:
   https://drupal.org/project/bibcite

 * To submit bug reports and feature suggestions, or to track changes:
   https://drupal.org/project/issues/bibcite
   
REQUIREMENTS
------------

This module requires the following modules:

 * Serialization (Core module)
 
This module requires the following libraries:

 * "academicpuma/citeproc-php": "~1.0",
 * "davidgorges/human-name-parser": "~0.2",
 * "technosophos/LibRIS": "~2.0",
 * "audiolabs/bibtexparser": "dev-master"
   
INSTALLATION
------------

 * Install requirement libraries via [Composer](https://www.drupal.org/docs/8/extending-drupal/installing-modules-composer-dependencies)

 * Install as you would normally install a contributed Drupal module. See:
   https://www.drupal.org/docs/8/extending-drupal/installing-contributed-modules
   for further information.

MAINTAINERS
-----------

Current maintainers:
 * Sergey Sergin (kruhak) - https://www.drupal.org/u/kruhak

This project has been sponsored by [ADCI Solutions](http://www.adcisolutions.com/)