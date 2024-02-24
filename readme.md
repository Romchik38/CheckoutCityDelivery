# CheckoutCityDelivery module

A Shipping method. 

## Dependencies

- Magento_Checkout

## Functionality

- it's a shipping method
- activates when *city* field is filled
- free shipping availible

## Вehavior modification

- checkout_index_index.xml
  - *city* field fillment trigger a request
- checkout_cart_index.xml
  - hide two blocks: summury and shipping

## TODO

1. hide checkout_cart_index boxes  
  [+] create admin functionality ( system.xml )  
  [-] edit checkout_cart_index

2. add city field to checkout_cart_index


