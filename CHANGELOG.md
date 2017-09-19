# Changelog


## [8.x-1.0-alpha4] - 2017-04-25

### Changed
- [#2865621]: Set default reference entity from hook, not from form class

### Fixed
- [#2870635]: Check entity keys, not format
- [#2870635]: Temporary normalizer fix for 8.3


## [8.x-1.0-alpha3] - 2017-03-30

### Added
- [#2860034] by Bwolf: Ability to Merge Duplicate Contributor Entries After Import
- [#2864557]: Views integration
- [#2864560]: Citation as extra field for Reference entity type
- [#2849258] by Bwolf: Include Bibliography & Citation entity display to be managed by Display Suite
- add schema for actions configuration
- [#2849617] by kruhak, pukku, Bwolf: Create actions (bulk operations) for bibliographic entity types
- [#2859088]: Create reference entity from one format entry

### Fixed
- Fix cancel routing name for ReferenceTypeDeleteForm
- [#2849617]: Fix update function, install action configs.
- [#2859088]: Fix temporary store identifiers


## [8.x-1.0-alpha2] - 2017-03-06

### Added
- [#2810581]: How to reuse keywords on import, not create new duplicate keyword records
- Add workarounds for BibtexParser library, explode unparsed keywords list
- Add workarounds for LibRIS library, optimize normalizer
- Add basic test for import of RIS format
- [#2832969]: Entity "Reference" - Add uid field and improve permissions
- [#2832987]: "Export all" form - Create a custom routing for downloading of generated files
- [#2832990]: Entity "Reference" - Create a form display for better UX with "Inline entity form"
- [#2832979]: Entity "Reference type" - Add ability to override labels and visibility of Reference fields
- [#2833305] by kruhak: Add bundles support for "Reference" entity type
- [#2832981] by kruhak: Add weight attribute to "Contributor role" and "Contributor category" entities

### Changed
- Optimize normalizers, use one denormalize method from base class
- Update tests, enable "user" module
- [#2836337]: Reference entity - Auto create keywords entries

### Fixed
- Fix "format" value in RIS format mapping


## [8.x-1.0-alpha1] - 2016-12-05

### Added
- Add example of composer commands to the README file
- [#2794157]: Implement format: RIS
- [#2788509]: Make mappings configurable
- [#2802791]: Management system for processor CSL styles
- [#2793977]: Contributor: Create full name string from name parts based on configurable policy
- [#2793983]: Contributor widget: create entity using full name string
- [#2793969]: Create/update Contributor entity using full name string
- [#2791563]: Use Drupal language in the citation processor
- [#2794049]: Export bibliographic data using Views and Action plugin
- [#2794003]: Create a simple interface for exporting all entities to available formats
- [#2794005]: Create batch processing for multiple bibliographic entities
- Added basic test for rendering entity to citation
- [#2791539]: Publication types as a configuration entity
- Added basic test for import module. Test decoding and denormalization.
- Added base test modules and simple test for main export functions
- [#2792531] by Bwolf: Added README.md file
- Add dependencies from export and import modules to the bibcite_entity module
- [#2788405]: Add entity_revers handlers to Contributor and Keyword entities
- [#2788405]: Add views data for Author and Keywords fields
- [#2788361] by discipolo: Add onKernelRequest event subscribers for export formats
- add configurable export links to the table view of bibliography entity
- add links element to the table view of bibliography entity
- get list of import plugins from plugin manager, implement DI
- create keywords entities from bibtex normalizer
- proceed import using batch
- add basic import form
- basic denormalization for bibtex format
- allow to create entities by reference field
- check if entity property is empty
- add HumanNameParser service
- bibcite_ris: allow to decode ris file via a library
- bibcite_ris: allow to export an entity to ris file
- bibcite_ris: normalize RIS format
- add import support to bibtex
- add bibcite_import module
- create configuration for lookup links
- add simple route for multiple export
- bibcite_ris allows to work with RIS format
- make action configurable
- add export action
- add views data for bibliography entity
- return string from encoder
- add links container
- add export links to bibliography full view
- add module settings
- add bibcite_export module with basic export route
- basic bibtex encoder
- add todo comment
- add "citation" view mode for bibliography entity
- add base theme hooks for citation elements
- static fields describe for bibliography entity
- mapping from entity fields to csl fields based on property
- add bibcite_bibtex module
- load default processor in getter
- add cite() method for rendering bibliography entity to citation
- use default processor if not set
- add Styler service and move default_style setting to main configuration
- get info about CSL fields and types from YAML files
- table view for bibliography entity
- add label formatter for contributor entity
- basic entity view with theme hooks
- CSL normalization for entity
- add basic serialization
- add configuration form for citeproc-php processor
- base csl fields
- module entities
- contributor fields
- base entity types and contributor field

### Changed
- Update CSL style updated time only from the form submit
- Use "bibliography_table" theme on the "default" view mode
- move keywords field to other tab
- Bibtex format: Change isset check to empty.
- [#2793991]: Contributor field/widget: Move contributor roles and categories to configuration level
- [#2791539]: Update type mappings for existing formats
- [#2794151]: Restructure of export and import services
- BibTex format decoder: Concat pages array to string.
- [#2788415]: Restructure of the module permissions and make entities accessible by users
- [#2788415]: Group properties to the vertical tabs using process function
- [#2788403]: Restructuring of Bibliography entity properties
- modules restructuring
- move human_name_parser service to the bibcite module
- Merge remote-tracking branch 'origin/entity' into entity
- Merge branch 'entity' of gitlab.com:adci/sc_pub into entity
- reorganize theme hooks
- Merge remote-tracking branch 'origin/entity' into entity
- Escaping special characters for yaml files
- change page title for settings page
- integrate bibtex format with export module
- bibtex normalization
- optimize normalizers for bibliography entity
- optimize entities paths
- use anonymous functions to set bibliography fields
- call services in cite method only
- rework citeproc plugin, move all logic to plugin class
- rename modules folders
- rename project to bibcite
- do not use count
- rename config properties and implement dependency injection
- move citeproc processor to independent service with basic settings
- settings form, handle processor description as render array
- rename entity module, add main module

### Fixed
- Fix configuration schema
- [#2811279] by JacobSanford, marqpdx, bibdoc: NameSpace Confusion
- [#2813871]: Unable to install Bibliography & Citation Entity, bibcite_entity_mapping.csl have dependencies not found
- [#2811281] by camilocodes: RIS import resulted in "bibliography" entities with no title
- Fixed CSL style form validation. Allow to update style
- Fixed CSL style form validation
- [#2804761] by antongp: Export links are not properly added to citations.
- Fix entity storage method declaration
- Fix Bibliography form. Use "#group" property for form restructuring.
- [#2791911] by Bwolf: Unable to configure settings: Parse Error When Trying to Edit Settings
- composer.json fixes
- fix unused statement
- fix serialization dependency
- fix dependencies
- fix array issue
- fix undefined title
- fix type key
- fix class paths
- fix styler service name
- fix default style validation
- fix cache id

### Removed
- Deleted label key from Contributor entity
- [#2788415]: Delete identifiers from the ListBuilders and add type field to the BibliographyListBuilder
- [#2788415]: Delete deprecated methods from ListBuilder classes
- remove some mess from comments
- delete unused import, add lost commentary
- Delete custom view builder and use default theme hook with different content
- delete unused variables
- delete CslDataProvider class and service, change plugin manager service name
- delete CslKeyConverter class
- delete unused files
- delete unused imports


[//]: # "Releases links"
[Unreleased]: https://www.drupal.org/project/bibcite/releases/8.x-1.x-dev
[8.x-1.0-alpha1]: https://www.drupal.org/project/bibcite/releases/8.x-1.0-alpha1
[8.x-1.0-alpha2]: https://www.drupal.org/project/bibcite/releases/8.x-1.0-alpha2
[8.x-1.0-alpha3]: https://www.drupal.org/project/bibcite/releases/8.x-1.0-alpha3
[8.x-1.0-alpha4]: https://www.drupal.org/project/bibcite/releases/8.x-1.0-alpha4


[//]: # "Issues links alpha1"
[#2794157]: https://www.drupal.org/node/2794157
[#2788509]: https://www.drupal.org/node/2788509
[#2802791]: https://www.drupal.org/node/2802791
[#2793977]: https://www.drupal.org/node/2793977
[#2793983]: https://www.drupal.org/node/2793983
[#2793969]: https://www.drupal.org/node/2793969
[#2791563]: https://www.drupal.org/node/2791563
[#2794049]: https://www.drupal.org/node/2794049
[#2794003]: https://www.drupal.org/node/2794003
[#2794005]: https://www.drupal.org/node/2794005
[#2791539]: https://www.drupal.org/node/2791539
[#2792531]: https://www.drupal.org/node/2792531
[#2788405]: https://www.drupal.org/node/2788405
[#2788361]: https://www.drupal.org/node/2788361
[#2793991]: https://www.drupal.org/node/2793991
[#2794151]: https://www.drupal.org/node/2794151
[#2788415]: https://www.drupal.org/node/2788415
[#2788403]: https://www.drupal.org/node/2788403
[#2811279]: https://www.drupal.org/node/2811279
[#2813871]: https://www.drupal.org/node/2813871
[#2811281]: https://www.drupal.org/node/2811281
[#2804761]: https://www.drupal.org/node/2804761
[#2791911]: https://www.drupal.org/node/2791911


[//]: # "Issues links alpha2"
[#2810581]: https://www.drupal.org/node/2810581
[#2832969]: https://www.drupal.org/node/2832969
[#2836337]: https://www.drupal.org/node/2836337
[#2832987]: https://www.drupal.org/node/2832987
[#2832990]: https://www.drupal.org/node/2832990
[#2832979]: https://www.drupal.org/node/2832979
[#2833305]: https://www.drupal.org/node/2833305
[#2832981]: https://www.drupal.org/node/2832981


[//]: # "Issues links alpha3"
[#2860034]: https://www.drupal.org/node/2860034
[#2864557]: https://www.drupal.org/node/2864557
[#2864560]: https://www.drupal.org/node/2864560
[#2849258]: https://www.drupal.org/node/2849258
[#2849617]: https://www.drupal.org/node/2849617
[#2859088]: https://www.drupal.org/node/2859088


[//]: # "Issues links alpha4"
[#2870635]: https://www.drupal.org/node/2870635
[#2865621]: https://www.drupal.org/node/2865621


[//]: # "Issues links dev"
[#2794159]: https://www.drupal.org/node/2794159
[#2794161]: https://www.drupal.org/node/2794161
[#2794165]: https://www.drupal.org/node/2794165
[#2794163]: https://www.drupal.org/node/2794163
[#2903950]: https://www.drupal.org/node/2903950
[#2865665]: https://www.drupal.org/node/2865665
[#2865644]: https://www.drupal.org/node/2865644
[#2865648]: https://www.drupal.org/node/2865648
[#2879865]: https://www.drupal.org/node/2879865
[#2890060]: https://www.drupal.org/node/2890060
[#2904701]: https://www.drupal.org/node/2904701
[#2865622]: https://www.drupal.org/node/2865622
[#2870650]: https://www.drupal.org/node/2870650
[#2882855]: https://www.drupal.org/node/2882855
[#2875764]: https://www.drupal.org/node/2875764
[#2878836]: https://www.drupal.org/node/2878836
[#2875387]: https://www.drupal.org/node/2875387
[#2877810]: https://www.drupal.org/node/2877810
[#2865631]: https://www.drupal.org/node/2865631
[#2870641]: https://www.drupal.org/node/2870641
[#2865633]: https://www.drupal.org/node/2865633
[#2865625]: https://www.drupal.org/node/2865625
