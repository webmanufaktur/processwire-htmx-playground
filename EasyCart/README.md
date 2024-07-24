# EasyCart

EasyCart is a simple shopping cart module for ProcessWire that provides basic cart functionality using URL hooks.

## Introduction

EasyCart is a proof-of-concept module designed to add straightforward shopping cart capabilities to your ProcessWire site. It utilizes URL hooks to manage cart operations, making it easy to integrate with your existing ProcessWire templates and JavaScript code. The demo version of this module uses HTMX for seamless, AJAX-powered interactions.

## Features

- Add items to cart
- Remove items from cart
- Increase/decrease item quantity
- Clear entire cart
- List cart contents
- HTMX integration for dynamic updates

## Installation

1. Download the EasyCart module.
2. Place the `EasyCart.module.php` file in your site's `/site/modules/` directory.
3. In the ProcessWire admin, go to Modules > Refresh.
4. Find "EasyCart" in the list of modules and click "Install".

## Usage

EasyCart provides the following URL hooks for cart operations:

- `/cart/list/`: Get the current cart contents
- `/cart/add/{id}/`: Add an item to the cart
- `/cart/remove/{id}/`: Remove an item from the cart
- `/cart/increase/{id}/`: Increase the quantity of an item
- `/cart/decrease/{id}/`: Decrease the quantity of an item
- `/cart/clear/`: Clear the entire cart

Example usage in a template file:

```php
$cart = $modules->get('EasyCart');
$cartContents = $cart->getCart();
```

Example usage with HTMX:

```html
<!-- Add an item to the cart -->
<button hx-get="/cart/add/123/" hx-target="#cart-container">Add to Cart</button>

<!-- Remove an item from the cart -->
<button hx-get="/cart/remove/123/" hx-target="#cart-container">
  Remove from Cart
</button>

<!-- Cart container to be updated -->
<div id="cart-container">
  <!-- Cart contents will be dynamically updated here -->
</div>

<!-- Add to cart form example -->
<form hx-post="/cart/add/{{ item.id }}/" hx-target="#cartList">
  <button
    type="submit"
    class="w-full rounded bg-red-700 mt-2 text-white text-center font-bold py-2 px-4"
  >
    Add
  </button>
</form>
```

Note: Make sure to include the HTMX library in your project for the above examples to work.

## Customization

The module uses Twig templates for rendering cart contents. You can customize the appearance by modifying the Twig template located at `templates/partials/cartList.twig`.

## HTMX Integration

The demo version of EasyCart uses HTMX to provide a smooth, dynamic user experience. HTMX allows for easy AJAX interactions without writing custom JavaScript. The URL hooks return HTML fragments that can be directly inserted into the page, enabling real-time updates to the cart display.

To use HTMX with EasyCart:

1. Include the HTMX library in your project.
2. Use HTMX attributes in your HTML to trigger cart actions and specify update targets.
3. Ensure your server-side code (ProcessWire templates) is set up to return appropriate HTML fragments for each cart action.

The "Add to cart" form example demonstrates how to use HTMX with a form submission:

```html
<form hx-post="/cart/add/{{ item.id }}/" hx-target="#cartList">
  <button
    type="submit"
    class="w-full rounded bg-red-700 mt-2 text-white text-center font-bold py-2 px-4"
  >
    Add
  </button>
</form>
```

This form will submit a POST request to add an item to the cart and update the `#cartList` element with the response.

## Note

This module is currently a proof-of-concept and may require additional development for production use. Features like price calculations, checkout process, and data validation are not included in this version.

## License

[MIT License](https://opensource.org/licenses/MIT)

## Support

For issues and feature requests, please use the GitHub issue tracker.
