# CheckoutCityDelivery module

CheckoutCityDelivery - is a Magento 2 module, that provides a shipping method. The module add city delivery to checkout page.

## Dependencies

- Magento_Checkout

## Functionality

- it's a shipping method
- activates when *city* field is filled
- free shipping available
- Display Summary and Shipping boxes on checkout_cart_index

## Вehavior modification

- checkout_index_index.xml
  1. *city* field fillment trigger a request
- checkout_cart_index.xml
  1. hide two blocks: summury and shipping

## TODO

1. hide checkout_cart_index boxes  
  [+] create admin functionality ( system.xml )  
  [+] edit checkout_cart_index

2. add city field to checkout_cart_index

## Display Summuary and Shipping boxes on checkout_cart_index

Admin field `Display checkout/cart/ Summary and Shipping boxes`.  
Description: Hide or display blocks *checkout.cart.summary.title* and *checkout.cart.shipping* on checkout/cart/ page.  

The problem: the module modifies *city* field, which is present on *checkout_index_index*, but absent on *checkout_cart_index*. As the result module do not work correctly. 

[img_hide](./Docs/Imgs/hide_summary_n_shipping.png)  
[img_display](./Docs/Imgs/display_summary_n_shipping.png)  
