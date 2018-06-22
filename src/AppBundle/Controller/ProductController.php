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
     * @var Translator
     */
    private $translator;

    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(
        ProductRepository $productRepository,
        Translator $translator,
        PageService $pageService
    ) {
        $this->productRepository = $productRepository;
        $this->translator = $translator;
        $this->pageService = $pageService;
    }

    /**
     * @Route("/edition/{id}", requirements={"id"="\d+"})
     */
    public function showProductAction($id, Request $request)
    {
        $product = $this->productRepository->findProduct($id);
        dump($product);
        return $this->render('test.html.twig');
    }

    /**
     * @Route("/edition/rubrique/{id}", requirements={"id"="\d+"})
     */
    public function showProductsAction($id, Request $request)
    {
        $products = $this->productRepository->findProducts($id);
        dump($products);
        return $this->render('test.html.twig');
    }

    /**
     * @Route("/editions-nouveautes", name="product-news-fr")
     * @Route("/books-news", name="product-news-en")
     * @Route("/buchs-neu", name="product-news-de")
     * @Route("/libros-nuevo", name="product-news-es")
     * @Route("/libri-nuovo", name="product-news-it")
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
     * @Route("/editions-collections", name="product-series-fr")
     * @Route("/books-sammlungen", name="product-series-en")
     * @Route("/buchs-reihe", name="product-series-de")
     * @Route("/libros-colecciones", name="product-series-es")
     * @Route("/libri-collezioni", name="product-series-it")
     */
    public function showCollections(Request $request)
    {
        $locale = $request->getLocale();
        $series = $this->productRepository->findCollections($locale);
        $themes = $this->productRepository->findThemes();

        $products = null;
        $reqThemes = $request->get('themes');
        if ($reqThemes) {
            $products = $this->productRepository->findByThemes($reqThemes);
        }
        
        $productsCategorized = [];
        foreach ($series as $serie) {
            $productsCategorized[] = [
                'title' => $serie->getRubrique(),
                'products' => $this->productRepository->findProducts($serie->getCodrub(), 2)
            ];
        }
        $contentDocument = $this->pageService->getContentFromRequest($request);
        $avaiableLocales = $this->pageService->getAvailableLocales($contentDocument);

        return $this->render(
            'product/list.html.twig',
            [
                'avaiableLocales' => $avaiableLocales,
                'page' => $contentDocument,
                'series' => $series,
                'themes' => $themes,
                'productsCategorized' => $productsCategorized,
                'products' => $products,
            ]
        );
    }
}
