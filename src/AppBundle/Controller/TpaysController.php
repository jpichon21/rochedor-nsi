<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\TpaysRepository;

class TpaysController extends Controller
{
    /**
     * @Rest\Get("/xhr/tpays/code/{country}", name="get_country_code")
     * @Rest\View()
    */
    public function xhrGetCountryCode(Request $request, $country, TpaysRepository $repo)
    {
        return ['status' => 'ok' , 'data' => $repo->findCode($country)];
    }
}
