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
        array $preferredCountries = ['FR', 'CS', 'GP', 'MQ', 'GF', 'RE', 'YT', 'PM', 'WF', 'PF', 'NC', 'TF']
    ) {
        $countriesJSON = [];
        $preferredChoices = [];
        $keyFR = null;
        $keyCS = null;
        foreach ($countries as $country) {
            if (in_array($country->getCodpays(), $preferredCountries)) {
                if ($country->getCodpays() === 'FR') {
                    $keyFR = count($preferredChoices);
                }
                if ($country->getCodpays() === 'CS') {
                    $keyCS = count($preferredChoices);
                }
                $preferredChoices[] = ['codpays' => $country->getCodpays(), 'nompays' => $country->getNompays()];
            } else {
                $countriesJSON[] = ['codpays' => $country->getCodpays(), 'nompays' => $country->getNompays()];
            }
        }

        if (!is_null($keyFR) && !is_null($keyCS)) {
            $this->moveElement($preferredChoices, $keyCS, $keyFR);
        }

        return [$countriesJSON, $preferredChoices];
    }

    public function orderCountryListByPreferenceEditions(
        array $countries,
        array $preferredCountries = ['FR', 'CS', 'GP', 'MQ', 'GF', 'RE', 'YT', 'PM', 'WF', 'PF', 'NC', 'TF']
    ) {
        $countriesJSON = [];
        $preferredChoices = [];
        $keyFR = null;
        $keyCS = null;
        foreach ($countries as $country) {
            if (in_array($country->getCodpays(), $preferredCountries)) {
                if ($country->getCodpays() === 'FR') {
                    $keyFR = count($preferredChoices);
                }
                if ($country->getCodpays() === 'CS') {
                    $keyCS = count($preferredChoices);
                }
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

        if (!is_null($keyFR) && !is_null($keyCS)) {
            $this->moveElement($preferredChoices, $keyCS, $keyFR);
        }

        return [$countriesJSON, $preferredChoices];
    }

    private function moveElement(&$array, $oldPosition, $newPosition)
    {
        $out = array_splice($array, $oldPosition, 1);
        array_splice($array, $newPosition, 0, $out);
    }
}
