<?php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Repository\CartRepository;

class CountryService
{

    /**
     * Transvase de $countries les éléments présents dans $preferredCountries dans un nouveau tableau
     * @param array $countries
     * @param array [optional] $preferredCountries
     *
     * @return array
     */
    public function orderCountryListByPreference(
        array $countries,
        array $preferredCountries = ['FR', 'GP', 'MQ', 'GF', 'RE', 'YT', 'PM', 'WF', 'PF', 'NC', 'TF']
    ) {
        $countriesJSON = [];
        $preferredChoices = [];
        foreach ($countries as $country) {
            if (in_array($country->getCodpays(), $preferredCountries)) {
                $preferredChoices[] = ['codpays' => $country->getCodpays(), 'nompays' => $country->getNompays()];
            } else {
                $countriesJSON[] = ['codpays' => $country->getCodpays(), 'nompays' => $country->getNompays()];
            }
        }

        return [$countriesJSON, $preferredChoices];
    }

    public function orderCountryListByPreferenceEditions(
        array $countries,
        array $preferredCountries = ['FR', 'GP', 'MQ', 'GF', 'RE', 'YT', 'PM', 'WF', 'PF', 'NC', 'TF']
    ) {
        $countriesJSON = [];
        $preferredChoices = [];
        foreach ($countries as $country) {
            if (in_array($country->getCodpays(), $preferredCountries)) {
                $preferredChoices[] = [
                    'codpays' => $country->getCodpays(),
                    'nompays' => $country->getNompays(),
                    'minliv' => $country->getMinliv(),
                    'maxliv' => $country->getMaxliv(),
                    'displiv' => $country->getDispliv()
                ];
            } else {
                $countriesJSON[] = [
                    'codpays' => $country->getCodpays(),
                    'nompays' => $country->getNompays(),
                    'minliv' => $country->getMinliv(),
                    'maxliv' => $country->getMaxliv(),
                    'displiv' => $country->getDispliv()
                ];
            }
        }

        return [$countriesJSON, $preferredChoices];
    }
}
