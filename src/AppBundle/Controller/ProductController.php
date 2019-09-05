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
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\TaxRepository;
use AppBundle\Repository\CartRepository;
use AppBundle\Entity\Produit;
use AppBundle\Service\PageService;
use AppBundle\Service\CartService;

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
     * @var TaxRepository
     */
    private $taxRepository;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        Translator $translator,
        PageService $pageService,
        CartService $cartService,
        TaxRepository $taxRepository
    ) {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->translator = $translator;
        $this->pageService = $pageService;
        $this->cartService = $cartService;
        $this->taxRepository = $taxRepository;
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
        $product = $this->productRepository->findProduct($id);
        $taxes = $this->taxRepository->findTax($id, "FR");
        return $this->render(
            'product/details.html.twig',
            [
                'product' => $product,
                'taxes' => $taxes,
                'cartCount' => $this->cartService->getCartCount($request->cookies->get('cart'))
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
        if (!$contentDocument) {
            throw $this->createNotFoundException($this->translator->trans('global.page-not-found'));
        }
        $availableLocales = $this->pageService->getAvailableLocales($contentDocument);
        $products = $this->productRepository->findNewProducts();
        return $this->render(
            'product/news.html.twig',
            [
                'products' => $products,
                'availableLocales' => $availableLocales,
                'page' => $contentDocument,
                'cartCount' => $this->cartService->getCartCount($request->cookies->get('cart'))
            ]
        );
    }
    
    /**
     * @Route("/editions-collections", name="collections-fr")
     * @Route("/publications-collections", name="collections-en")
     * @Route("/publikationen-collections", name="collections-de")
     * @Route("/publicaciones-collections", name="collections-es")
     * @Route("/pubblicazioni-collections", name="collections-it")
     */
    public function showCollections(Request $request)
    {
        $locale = $request->getLocale();
        $collections = $this->productRepository->findCollections($locale);
        $themes = $this->productRepository->findThemes();
        $themes = array_filter($themes, function ($theme) {
            return $theme['theme'] != '';
        });

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
                    'id' => $collection->getCodrub(),
                    'title' => $collection->getRubrique(),
                    'products' => $this->productRepository->findProducts($collection->getCodrub(), 2)
                ];
            }
        }
        $contentDocument = $this->pageService->getContentFromRequest($request);
        if (!$contentDocument) {
            throw $this->createNotFoundException($this->translator->trans('global.page-not-found'));
        }
        $availableLocales = $this->pageService->getAvailableLocales($contentDocument);

        return $this->render(
            'product/list.html.twig',
            [
                'availableLocales' => $availableLocales,
                'page' => $contentDocument,
                'collections' => $collections,
                'themes' => $themes,
                'productsCategorized' => $productsCategorized,
                'products' => $products,
                'cartCount' => $this->cartService->getCartCount($request->cookies->get('cart'))
            ]
        );
    }
}
