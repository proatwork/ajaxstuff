# Proatwork Ajaxstuff

## Changelog
### v0.1.2
- Updated the README with more detailed information
- Added exampe `app.js` and `minicart.js` under `template/proatwork/ajaxstuff/example_js`
### v0.1.1
- Fixed an issue where JS crashed when no blocks were configured in the backend
### v0.1.0
- Initial commit

## Basic Information
Don't forget to update XML Blocknames and CSS Selectors according to your theme.
ex : `div.header-minicart` should be populated with content from the `minicart_head` (XML) block

Also, if using the `rwd` theme or a theme based on `rwd`, then you need to make some changes in your `app.js`, `minicart.js` and also `list.ajaxstuff.phtml`

**minicart.js**
Line 185-186 ( after `cart.showMessage(result)` )
 `container : '#header-cart'` -> `container : 'div.header-minicart'`

**app.js**
 Line ~695-735 (the _Skip Links_ section). You need to put this code into a function (eg: `skipLinksF()`) and move it under the _jQuery Init_ section, but make sure to call the function under the _Skip Links_ section like.
 
**list.ajaxstuff.phtml**
 Find it under `app/design/frontend/base/default/template/proatwork/ajaxstuff/list.ajaxstuff.phtml`, copy it under your theme and on line 73 call the skipLinksF()