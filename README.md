#Cart UI Component
[![Latest Stable Version](https://poser.pugx.org/thesgroup/module-checkout-cart/v/stable)](https://packagist.org/packages/thesgroup/module-checkout-cart)
[![Total Downloads](https://poser.pugx.org/thesgroup/module-checkout-cart/downloads)](https://packagist.org/packages/thesgroup/module-checkout-cart)
[![Latest Unstable Version](https://poser.pugx.org/thesgroup/module-checkout-cart/v/unstable)](https://packagist.org/packages/thesgroup/module-checkout-cart)
[![License](https://poser.pugx.org/thesgroup/module-checkout-cart/license)](https://packagist.org/packages/thesgroup/module-checkout-cart)

The Magento 2 module replaces out of the box cart page with Ui Component. 

![](https://github.com/sashas777/assets/raw/master/cart_ui_component.gif)

## 1. How to install the module

Run the following command at Magento 2 root folder:

```
composer require thesgroup/module-checkout-cart
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## 2. How to remove the module

Run the following command at Magento 2 root folder:

```
composer remove thesgroup/module-checkout-cart
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```