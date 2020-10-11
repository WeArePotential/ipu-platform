# Onboarding

## Install


Install Drupal:

### Drush

#### Windows

(https://github.com/drupal-composer/drupal-project/issues/421)

    cp C:\xampp\htdocs\drupalDrush\vendor\bin\drush.bat C:\xampp\htdocs\drupalDrush\vendor\bin\drush.php.bat```




## Development




### Theme

The IPU site uses a sub-theme of Radix in themes/ipu.

Run npm install from the theme folder to set things up.

SASS is compiled using webpack.

From web/themes/ipu run

```npm run watch```


### Patches

* Add to composer.json
* Run composer install. If failing use compser install -vvv

In windows, run composer install from Git command.

### Notes:

