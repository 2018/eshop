<?php
/**
 * @package     Eshop
 * @version     1.0
 * @category    Modules
 * @author      Oleksiy
 * @license     MIT License
 */

namespace Eshop\Controller;

use Auth\Auth;
use Eshop\Cart;
use \Fuel\Core\Controller;
use Fuel\Core\Debug;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Parser\View_Smarty;

/**
 * The Eshop Controller.
 *
 * A basic controller.
 *
 * @package  Eshop
 * @extends  Controller
 */
class Eshop extends Controller
{

    /**
     * The index action for testing
     *
     * @access  public
     * @return  Response
     */
    public function action_index()
    {
        $cart_id = null;
        $data = new \stdClass();
        # user login
        if (Input::post('login'))
        {
            Auth::instance()->login(Input::post('username'), Input::post('password'));
            Response::redirect('/');
        }

        # user logout
        elseif (Input::post('logout'))
        {
            Auth::logout();
            Response::redirect('/');
        }

        # check login drivers for validated login
        if(Auth::check()){
            $data->user = Auth::get_screen_name();
        }

        # new cart instance
        $data->cart = Cart::instance();

        # delete cart item
        if (Input::post('delete_item'))
        {
            Cart::delete(Input::post('delete_item'));
            Response::redirect('/');
        }

        # add cart item
        if (Input::post('add_item'))
        {
            $id = Input::post('add_item');
            $values = array(
                'code'=>'code_'.$id,
                'title'=>'Product '.$id,
                'price'=>$id * 10,
            );
            Cart::add($values);
            Response::redirect('/');
        }

        # delete cart from storage
        if (Input::post('clear'))
        {
            Cart::clear();
            Response::redirect('/');
        }

        # assign variables to template
        $view = View_Smarty::forge('eshop/scelet.tpl',$data);
        return Response::forge($view);
    }

}