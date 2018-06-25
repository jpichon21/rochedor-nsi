<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\CalendarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\CartRepository;
use AppBundle\Entity\Produit;
use AppBundle\Service\PageService;

/**
 * @Route("/{_locale}")
 */
class ProductController extends Controller
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
     * @var Translator
     */
    private $translator;

    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        Translator $translator,
        PageService $pageService
    ) {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->translator = $translator;
        $this->pageService = $pageService;
    }

    /**
     * @Route("/edition/{id}", requirements={"id"="\d+"}, name="collection-fr")
     * @Route("/publication/{id}", requirements={"id"="\d+"}, name="collection-en")
     * @Route("/veroffentlichung/{id}", requirements={"id"="\d+"}, name="collection-de")
     * @Route("/publicacion/{id}", requirements={"id"="\d+"}, name="collection-es")
     * @Route("/pubblicazione/{id}", requirements={"id"="\d+"}, name="collection-it")
     */
    public function showProductAction($id, Request $request)
    {
        $contentDocument = $this->pageService->getContentFromRequest($request);
        $avaiableLocales = $this->pageService->getAvailableLocales($contentDocument);
        $product = $this->productRepository->findProduct($id);
        return $this->render(
            'product/details.html.twig',
            [
                'product' => $product,
                'avaiableLocales' => $avaiableLocales,
                'page' => $contentDocument,
                'cartCount' => $this->getCartCount()
            ]
        );
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
            [
                'products' => $products,
                'avaiableLocales' => $avaiableLocales,
                'page' => $contentDocument,
                'cartCount' => $this->getCartCount()
            ]
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
                'cartCount' => $this->getCartCount()
            ]
        );
    }

    private function getCartCount()
    {
        $session = new Session();
        $cartId = $session->get('cart');
        $cart = $this->cartRepository->find($cartId);
        $count = 0;
        foreach ($cart->getCartlines() as $line) {
            $count += $line->getQuantity();
        }
        return $count;
    }
}
