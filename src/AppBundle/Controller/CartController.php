<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\CalendarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Cart;
use AppBundle\Repository\CartRepository;
use AppBundle\Repository\ProductRepository;
use AppBundle\Entity\Cartline;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;


class CartController extends Controller
{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Translator
     */
    private $translator;


    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        EntityManagerInterface $em
    ) {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->em = $em;
    }

    /**
     * @Route("/{_locale}/cart/add/{productId}", name="cart-add", methods={"GET"}, requirements={"productId"="\d+"})
     */
    public function addAction($productId, Request $request)
    {
        $session = new Session();
        $product = $this->productRepository->find($productId);
        if ($product === null) {
            return $this->redirectToRoute('product-series-' . $request->getLocale());
        }
        $cartId = $session->get('cart');
        $cart = $this->cartRepository->find($cartId);
        if ($cart === null) {
            $cart = new Cart();
            $this->em->persist($cart);
            $this->em->flush();
        }
        $cartLine = $this->addProduct($cart, $product);
        $this->em->persist($cartLine);
        $this->em->flush();
        $session->set('cart', $cart->getId());
        $session->getFlashBag()->add('info', 'cart.product.added');
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Rest\Patch("/xhr/cart/patch", name="patch_product")
     * @Rest\View()
    */
    public function xhrPatchProductAction(Request $request)
    {
        $data = $request->get('data');
        $productId = $data['productId'];
        $typeAction = $data['typeAction'];

        if ($data === null || $productId === null || $typeAction === null) {
            return new JsonResponse([
                'status' => 'ko',
                'message' => 'missing parameters'
            ]);
        }

        $session = new Session();
        $product = $this->productRepository->find($productId);
        $cartId = $session->get('cart');

        $cart = $this->cartRepository->find($cartId);
        if ($cart === null) {
            $cart = new Cart();
            $this->em->persist($cart);
            $this->em->flush();
        }

        if ($typeAction === "add") {
            $cartLine = $this->addProduct($cart, $product);
        } else {
            $cartLine = $this->removeProduct($cart, $product);
        }

        $this->em->persist($cartLine);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'ok'
        ]);
    }

    /**
     * @Rest\Delete("/xhr/cart/remove/{cartId}/{codprd}", name="delete_cartline")
     * @Rest\View()
     */
    public function xhrRemoveAction($codprd, $cartId, Request $request)
    {
        $product = $this->productRepository->find($codprd);
        $cart = $this->cartRepository->find($cartId);

        $cartLine = $this->cartRepository->findCartline($cart , $product);
        $this->removeCartline($cartLine);
        return new JsonResponse([
            'status' => 'ok'
        ]);
        // return $this->redirectToRoute('order-'.$request->getLocale());
    }

    /**
     * Add a product to the Cart
     *
     * @param Cart $cart
     * @param Produit $product
     * @return CartLine
     */
    private function addProduct(Cart $cart, Produit $product)
    {
        $cartLine = $this->cartRepository->findCartline($cart, $product);
        if ($cartLine === null) {
            $cartLine = new CartLine();
            $cartLine->setProduct($product)
            ->setCart($cart)
            ->setQuantity(1);
        } else {
            $cartLine->setQuantity($cartLine->getQuantity() + 1);
        }
        return $cartLine;
    }

    /**
     * Remove a product to the Cart
     *
     * @param Cart $cart
     * @param Produit $product
     * @return CartLine
     */
    private function removeProduct(Cart $cart, Produit $product)
    {
        $cartLine = $this->cartRepository->findCartline($cart, $product);
        if ($cartLine != null) {
            if (($cartLine->getQuantity() - 1) === 0) {
                $this->removeCartline($cartLine);
            }
            $cartLine->setQuantity($cartLine->getQuantity() - 1);
        }
        return $cartLine;
    }

    private function removeCartline(Cartline $cartLine){
        $cartLine->setCart(null);
        $this->em->persist($cartLine);
        $this->em->flush();
        $session = new Session();
        $session->getFlashBag()->add('info', 'cart.product.removed');
    }
    
}
