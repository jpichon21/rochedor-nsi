<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Tax;
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
    const FILTER_TYPE_SUPPORT = 'support';
    const FILTER_TYPE_AUTHOR = 'author';
    const FILTER_TYPE_GENDER = 'gender';
    const FILTER_TYPE_THEME = 'theme';

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
        $tax = null;
        if (array_key_exists(0, $product) && array_key_exists('typprd', $product[0])) {
            $tax = $this->taxRepository->findTax($product[0]['typprd'], 'FR');
        }

        // force Tax::rate to be a float, in order to correctly handle the display for 1.2 (instead of 1.20) or 1.23
        if ($tax instanceof Tax) {
            $tax->setRate((float)$tax->getRate());
        }

        return $this->render(
            'product/details.html.twig',
            [
                'product' => $product,
                'taxes' => $tax,
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
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function showCollections(Request $request)
    {
        $locale = $request->getLocale();
        $supportFilter = $request->get('support');
        $authorFilter = $request->get('author');
        $genderFilter = $request->get('gender');
        $themeFilter = $request->get('theme');

        $collections = $this->productRepository->findCollections($locale);
        $supports = $this->formatDataForFilters(
            $this->productRepository->findSupports(),
            self::FILTER_TYPE_SUPPORT,
            $supportFilter
        );
        $authors = $this->formatDataForFilters(
            $this->productRepository->findAuthors(),
            self::FILTER_TYPE_AUTHOR,
            $authorFilter
        );
        $genders = $this->formatDataForFilters(
            $this->productRepository->findGenders(),
            self::FILTER_TYPE_GENDER,
            $genderFilter
        );
        $themes = $this->formatDataForFilters(
            $this->productRepository->findThemes(),
            self::FILTER_TYPE_THEME,
            $themeFilter
        );

        $products = null;
        if (!is_null($supportFilter) ||
            !is_null($authorFilter) ||
            !is_null($genderFilter) ||
            !is_null($themeFilter)
        ) {
            $products = $this->productRepository->findByThemesFilter(
                $supportFilter,
                $authorFilter,
                $genderFilter,
                $themeFilter
            );
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
                'productsCategorized' => $productsCategorized,
                'products' => $products,
                'cartCount' => $this->cartService->getCartCount($request->cookies->get('cart')),
                'supports' => $supports,
                'authors' => $authors,
                'genders' => $genders,
                'themes' => $themes
            ]
        );
    }

    /**
     * @param array $data
     * @param string $type
     * @param string $selectedData
     * @return array|null
     */
    private function formatDataForFilters(array $data, $type, $selectedData = '')
    {
        $formattedData = [];

        if (empty($data)) {
            return null;
        }

        foreach ($data as $datum) {
            $name = $datum;
            $value = $datum;
            switch ($type) {
                case self::FILTER_TYPE_SUPPORT:
                    if (array_key_exists($datum, Produit::TYP_PRD)) {
                        $name = Produit::TYP_PRD[$datum];
                    }
                    break;
                case self::FILTER_TYPE_GENDER:
                    if (array_key_exists($datum, Produit::GENDER)) {
                        $name = Produit::GENDER[$datum];
                    }
                    break;
            }

            $formattedData[] = [
                'name' => $name,
                'value' => $value,
                'selected' => $datum == $selectedData ? true : false
            ];
        }

        return $formattedData;
    }
}
