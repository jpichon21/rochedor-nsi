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

/**
 * @Route("{_locale}/cart")
 */
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
     * @Route("/add/{productId}", name="cart-add", methods={"GET"}, requirements={"productId"="\d+"})
     */
    public function addAction($productId, Request $request)
    {
        $session = new Session();
        $product = $this->productRepository->findProduct($productId);
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
        return $this->redirectToRoute('collection-fr', ['id' => $productId]);
    }

    /**
     * @Route("/editions-nouveautes", name="collections-news-fr")
     * @Route("/publications-news", name="collections-news-en")
     * @Route("/publikationen-neu", name="collections-news-de")
     * @Route("/publicaciones-nuevo", name="collections-news-es")
     * @Route("/pubblicazioni-nuovo", name="collections-news-it")
     */
    public function showNewProductsAction(Request $request)
    {
        $contentDocument = $this->pageService->getContentFromRequest($request);
        $avaiableLocales = $this->pageService->getAvailableLocales($contentDocument);
        $products = $this->productRepository->findNewProducts();
        return $this->render(
            'product/news.html.twig',
            ['products' => $products, 'avaiableLocales' => $avaiableLocales, 'page' => $contentDocument]
        );
    }
    
    /**
     * @Route("/editions", name="collections-fr")
     * @Route("/collections", name="collections-en")
     * @Route("/publikationen", name="collections-de")
     * @Route("/publicaciones", name="collections-es")
     * @Route("/pubblicazioni", name="collections-it")
     */
    public function showCollections(Request $request)
    {
        $locale = $request->getLocale();
        $collections = $this->productRepository->findCollections($locale);
        $themes = $this->productRepository->findThemes();

        $products = null;
        
        $reqThemes = $request->get('themes');
        if ($reqThemes) {
            $products = $this->productRepository->findByThemes($reqThemes);
        }

        $collection = $request->get('collection');
        if ($collection) {
            $products = $this->productRepository->findProducts($collection);
        }

        $productsCategorized = null;
        if (!$products) {
            $productsCategorized = [];
            foreach ($collections as $collection) {
                $productsCategorized[] = [
                    'title' => $collection->getRubrique(),
                    'products' => $this->productRepository->findProducts($collection->getCodrub(), 2)
                ];
            }
        }
        $contentDocument = $this->pageService->getContentFromRequest($request);
        $avaiableLocales = $this->pageService->getAvailableLocales($contentDocument);

        return $this->render(
            'product/list.html.twig',
            [
                'avaiableLocales' => $avaiableLocales,
                'page' => $contentDocument,
                'collections' => $collections,
                'themes' => $themes,
                'productsCategorized' => $productsCategorized,
                'products' => $products,
            ]
        );
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
}
