<?php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Repository\CartRepository;

class CartService
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCartCount()
    {
        $session = new Session();
        $cartId = $session->get('cart');
        $cart = $this->cartRepository->find($cartId);
        $count = 0;
        if ($cart === null) {
            return null;
        }
        foreach ($cart->getCartlines() as $line) {
            $count += $line->getQuantity();
        }
        return $count;
    }
}
