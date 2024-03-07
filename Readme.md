
# Magento 2 Core web vitals, optimization for Google Insights

This modules allows you modify the HTML, Javascript, CSS, update the position, optimize CWV (Core Web Vitals) scores.

By: Hidro Le.  Website: [https://www.solutiontutorials.com](https://www.solutiontutorials.com)

#More packages:
- Graylog: https://github.com/hieuhidro/magento2-graylog/
- Checkout my critical service: [https://store.solutiontutorials.com/magento-2-core-web-vital-critical-css.html](https://store.solutiontutorials.com/magento-2-core-web-vital-critical-css.html)

## - Main Functionalities
- Minify HTML code.
- Adding https/2 push.
- Preload fonts
- Lazy loading Iframe, Images.
- Move javascript to footer.
- Adding Rocket-Loader.
- Defer javascript codes.
- Defer Google Recaptcha script
- Defer/preload CSS files by using javascript/browser preload.
- Minify inline CSS, Javascript.
- Active the first item of Owl Carousel first.

## - Specifications

- You have to disable merge **css** if you want to use CSS modifier functions.

## Installation

### Type 1: Zip file

- Unzip the zip file in `app/code/Hidro`
- Enable the module by running `php bin/magento module:enable Hidro_CoreWebVitals`
- Apply database updates by running `php bin/magento setup:upgrade`\*
- Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

- Make the module available in a composer repository for example:
    - public repository `packagist.org`
    - public github repository as vcs
- Install the module composer by running `composer require hidro/core-web-vitals`
- enable the module by running `php bin/magento module:enable Hidro_CoreWebVitals`
- apply database updates by running `php bin/magento setup:upgrade`
- Flush the cache by running `php bin/magento cache:flush`

### After install the module:
- `bin/magento config:set dev/js/minify_files 1 -l;`
- `bin/magento config:set dev/js/merge_files 1 -l;`
- `bin/magento config:set dev/css/minify_files 1 -l;`
- `bin/magento config:set dev/css/merge_css_files 0 -l;`
- `bin/magento config:set dev/template/minify_html 1 -l;`
- `bin/magento deploy:mode:set production;`

### DEVELOP
- app/code/Hidro/CoreWebVitals/Model/Asset/CriticalCss.php:109 
  - Adding a custom critical css for special body class
- Override default.css for updating entire default critical css
- Override the core_vital.css for adding special custom css
- Override fonts.css for updating webfont.
