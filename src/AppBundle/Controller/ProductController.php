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
use AppBundle\Service\Mailer;
use AppBundle\Entity\Produit;
use AppBundle\ServiceShowPage;

class ProductController extends Controller
{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(
        ProductRepository $productRepository,
        Mailer $mailer,
        Translator $translator
    ) {
        $this->productRepository = $productRepository;
        $this->mailer = $mailer;
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
     * @Route("/edition/nouveautes")
     */
    public function showNewProductsAction(Request $request)
    {
        $products = $this->productRepository->findNewProducts();
        dump($products);
        return $this->render('test.html.twig');
    }
}
