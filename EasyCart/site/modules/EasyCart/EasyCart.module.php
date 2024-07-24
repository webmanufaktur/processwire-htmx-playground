<?php

namespace ProcessWire;

class EasyCart extends WireData implements Module
{

  public static function getModuleInfo()
  {
    return array(
      'title' => 'EasyCart',
      'version' => '1.1.0',
      'summary' => 'Simple shopping cart functionality for ProcessWire with URL hooks',
      'author' => 'Your Name',
      'singular' => true,
      'autoload' => true
    );
  }

  public function init()
  {
    if (!$this->wire('session')->get('cart')) {
      $this->wire('session')->set('cart', []);
    }

    $this->addHook('/cart/list/', $this, 'handleList');
    $this->addHook('/cart/add/{id}/', $this, 'handleAdd');
    $this->addHook('/cart/remove/{id}/', $this, 'handleRemove');
    $this->addHook('/cart/increase/{id}/', $this, 'handleIncrease');
    $this->addHook('/cart/decrease/{id}/', $this, 'handleDecrease');
    $this->addHook('/cart/clear/', $this, 'handleClear');
  }


  // ##########################
  // GET CART
  // ##########################

  public function handleList(HookEvent $event)
  {
    $cart = $this->getCart();
    $html = $this->sendHTML('cartList', ['cart' => $cart]);
    $event->return = $html;
  }

  public function getCart()
  {
    $cart = $this->wire('session')->get('cart');
    return $cart;
  }


  // ##########################
  // ADD TO CART 
  // ##########################
  public function handleAdd(HookEvent $event)
  {
    $id = $event->arguments('id');
    if ($id) {
      $this->addToCart($id, 1);

      $cart = $this->getCart();
      $html = $this->sendHTML('cartList', ['cart' => $cart]);

      $event->return = $html;
      // $event->return = 'Added ✓' . $html;
    } else {
      $event->return = 'Failed to add';
    }
  }

  public function addToCart($id, $amount)
  {
    $cart = $this->wire('session')->get('cart');

    $item = [
      'id' => $id,
      'amount' => $amount
    ];

    $index = array_search($id, array_column($cart, 'id'));

    if ($index !== false) {
      $cart[$index]['amount'] += $amount;
    } else {
      $cart[] = $item;
    }

    $this->wire('session')->set('cart', $cart);
  }


  // ##########################
  // INCREASE ITEM AMOUNT
  // ##########################

  public function handleIncrease(HookEvent $event)
  {
    $id = $event->arguments('id');
    if ($id) {
      $this->increaseCartItemAmount($id);
      $cart = $this->getCart();
      $html = $this->sendHTML('cartList', ['cart' => $cart]);
      $event->return = $html;
    } else {
      $event->return = json_encode(['success' => false, 'message' => 'Invalid parameters']);
    }
  }

  public function increaseCartItemAmount($id)
  {
    $cart = $this->wire('session')->get('cart');
    foreach ($cart as &$item) {
      if ($item['id'] == $id) {
        $item['amount']++;
        break;
      }
    }
    $this->wire('session')->set('cart', $cart);
  }

  // ##########################
  // DECREASE ITEM AMOUNT
  // ##########################

  public function handleDecrease(HookEvent $event)
  {
    $id = $event->arguments('id');
    if ($id) {
      $this->decreaseCartItemAmount($id);
      $cart = $this->getCart();
      $html = $this->sendHTML('cartList', ['cart' => $cart]);
      $event->return = $html;
    } else {
      $event->return = json_encode(['success' => false, 'message' => 'Invalid parameters']);
    }
  }

  public function decreaseCartItemAmount($id)
  {
    $cart = $this->wire('session')->get('cart');
    foreach ($cart as $key => &$item) {
      if ($item['id'] == $id) {
        $item['amount']--;
        if ($item['amount'] <= 0) {
          unset($cart[$key]);
        }
        break;
      }
    }
    $this->wire('session')->set('cart', $cart);
  }


  // ##########################
  // REMOVE FROM CART 
  // ##########################

  public function handleRemove(HookEvent $event)
  {
    $id = $event->arguments('id');
    $this->removeFromCart($id);

    $cart = $this->getCart();
    $html = $this->sendHTML('cartList', ['cart' => $cart]);

    $event->return = $html;
    // $event->return = json_encode(['success' => true]);
  }

  public function removeFromCart($id)
  {
    $cart = $this->wire('session')->get('cart');

    $cart = array_filter($cart, function ($item) use ($id) {
      return $item['id'] != $id;
    });

    $this->wire('session')->set('cart', $cart);
  }


  // ##########################
  // CLEAR CART 
  // ##########################

  public function handleClear(HookEvent $event)
  {
    $cart = $this->clearCart();
    $html = $this->sendHTML('cartList', ['cart' => $cart]);
    $event->return = 'Cleared ✓' . $html;
  }

  public function clearCart()
  {
    $this->wire('session')->set('cart', []);
  }


  // ##########################
  // GET TOTAL 
  // ##########################

  // public function getCartTotal()
  // {
  //   $cart = $this->getCart();
  //   return array_sum(array_column($cart, 'amount'));
  // }


  // ##########################
  // SEND HTML
  // ##########################

  protected function sendHTML(string $partial, array $data)
  {
    $twig = $this->wire('modules')->get('TemplateEngineFactory');
    return $twig->render(
      "partials/$partial",
      $data
    );
  }

  public function ___install()
  {
    // Any installation tasks (if needed)
  }

  public function ___uninstall()
  {
    // Any uninstallation tasks (if needed)
  }
}
