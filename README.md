# Eshop package for FuelPHP application

Eshop package with cart driver
Cart instance calculates item prices and taxes 
 
# Cart usage

Get cart instance:

    $cart = \Eshop\Cart::instance();
    
Get cart items:

    $cart = \Eshop\Cart::get_items();
or
    
    $cart->get_items();
    
Add cart item:

    $values = array(
        'code' => 'code_1',
        'title' => 'Product 1',
        'price' => 10
    );
    \Eshop\Cart::add($values);
    
or
    
    $cart->add($values);

Delete cart item:
    
    \Eshop\Cart::delete($item_id);
or
   
    $cart->delete($item_id);
    
Delete cart:

    \Eshop\Cart::clear();

or
   
    $cart->clear();
    
    
    
