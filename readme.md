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

- [+] create system.xml
- [+] create config.xml
- [+] create Model
    - [+] free delivery
    - [+] error
    - [+] city names case insensentive, list separated by colon
- [+] shipping rates
    - [+] validation view
    - [+] validator model
    - [+] validation rules model
- [+] hide summury and shipping from checkout/cart


