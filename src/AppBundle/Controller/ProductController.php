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
     * @Route("/edition/nouveautes", name="product-news-fr")
     * @Route("/book/news", name="product-news-en")
     * @Route("/buch/neu", name="product-news-de")
     * @Route("/libros/nuevo", name="product-news-es")
     * @Route("/libri/nuovo", name="product-news-it")
     */
    public function showNewProductsAction(Request $request)
    {
        $products = $this->productRepository->findNewProducts();
        return $this->render('product/news.html.twig', ['products' => $products]);
    }
    
    /**
     * @Route("/edition/collections")
     */
    public function showCollections(Request $request)
    {
        $locale = $request->getLocale();
        $collections = $this->productRepository->findCollections($locale);
        dump($collections);
        $themes = $this->productRepository->findThemes();
        dump($themes);
        $products = $this->productRepository->findByTheme('theme2');
        dump($products);
        return $this->render('test.html.twig');
    }
}
