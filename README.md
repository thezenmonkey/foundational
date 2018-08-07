# Foundational

**WARNING** API is under heavy development and will likely change.

This module assigns base Foundation parameters to SilverStripe Elemental. It lets teh use lay out pages using the default XY Grid.

## Requirements
* SilverStripe CMS ^4.2
* Silverstripe Elemental ^3
* Slverstripe Elemental List ^1.0
* Display Logic dev-master
* Theme build on Zurb Foundation 6 use XY Grid


## Installation
```
composer require thezenmonkey/foundational
```

## Features
### BaseElement
* Acts as a foundation cell by default 
* Small Grid Size
* Medium Grid Size
* Large Grid Size
* XLarge Grid Size (optional)
* XXLArge Grid Size (optional)
* StickyProperties to enable the element to be made Sticky
* ContainerProperties to pass various Foundation data attributes to the Element_holder
* ElementProperties to pass various Foundation data attributes to the Element

### ElementList
* Grid Direction for both Horizontal and Vertical grids
* Full size (width) option
* BlockGrid option to allow for the creation of Foundation Block Grids.


## ToDo
* More Configuration Options
