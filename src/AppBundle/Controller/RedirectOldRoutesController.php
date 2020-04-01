<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ce controller a pour but d'effectuer les redirections entre les routes de l'ancien site et les routes du nouveau site
 */
class RedirectOldRoutesController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Route(path="/kiwi", name="old_kiwi")
     */
    public function oldKiwiAction(Request $request)
    {
        // Redirections vers la home
        $urlsKiwi = [
            '/kiwi',
        ];

        if (in_array($request->getRequestUri(), $urlsKiwi)) {
            if (preg_match('/nsi\.rochedor\.fr/', $request->getUri())) {
                return new RedirectResponse(
                    'https://kiwi.nsi.rochedor.fr/kiwi/',
                    301
                );
            }
            if (preg_match('/rochedor\.fr/', $request->getUri())) {
                return new RedirectResponse(
                    'https://kiwi.staging.rochedor.fr/kiwi/',
                    301
                );
            }
        }

        return new RedirectResponse(
            $this->generateUrl('home'),
            301
        );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Route(path="/", name="old_home")
     * @Route(path="/site", name="old_site")
     * @Route(path="/site/", name="old_site2")
     * @Route(path="/site/biblio/doc/general/Formulaire virement ponctuel.pdf", name="old_site3")
     * @Route(path="/site/biblio/doc/don/Lettre de don ponctuel par cheque.pdf", name="old_site4")
     * @Route(path="/site/biblio/doc/don/Lettre de don ponctuel par virement.pdf", name="old_site5")
     * @Route(path="/site/biblio/doc/general/Formulaire virement regulier.pdf", name="old_site6")
     * @Route(path="/site/biblio/doc/don/Lettre de virement regulier.pdf", name="old_site7")
     * @Route(path="/site/biblio/doc/rochedor/2E ROR Renseignements.htm", name="old_site8")
     */
    public function oldHomeAction(Request $request)
    {
        // Redirections vers la home
        $urlsHome = [
            '/site',
            '/site/',
            '/site/?Titre=Actualit%C3%A9s&Cle=233',
        ];

        if (in_array($request->getRequestUri(), $urlsHome)) {
            return new RedirectResponse(
                $this->generateUrl('home'),
                301
            );
        }

        // Redirections vers la page de contact Fontanilles
        $urlsContactFt = [
            '/site/?Cle=17&Page=site2/Acces_font_Street.htm',
            '/site/?Cle=173',
            '/site/?Titre=Contact&Cle=173',
        ];

        if (in_array($request->getRequestUri(), $urlsContactFt)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/contact-ft',
                301
            );
        }

        // Redirections vers la page de contact Roche d'Or
        $urlsContactRo = [
            '/site/?Titre=Les%20collections&Cle=257',
            '/site/?Cle=144',
            '/site/?Cle=16&Page=site2/Acces_roch_Street.htm',
            '/site/?Titre=Contact&Cle=144',
        ];

        if (in_array($request->getRequestUri(), $urlsContactRo)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/contact-ro',
                301
            );
        }

        // Redirections vers la page de dons
        $urlsDonPonctuel = [
            '/site/biblio/doc/general/Formulaire%20virement%20ponctuel.pdf',
            '/site/?Cle=153',
            '/site/biblio/doc/don/Lettre%20de%20don%20ponctuel%20par%20cheque.pdf',
            '/site/?Titre=Dons&Cle=58',
        ];

        if (in_array($request->getRequestUri(), $urlsDonPonctuel)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/don-ponctuel',
                301
            );
        }

        // Redirections vers la page de don temps
        $urlsDonTemps = [
            '/site/?Cle=149',
        ];

        if (in_array($request->getRequestUri(), $urlsDonTemps)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/donner-du-temps',
                301
            );
        }

        // Redirections vers la page autre forme de dons
        $urlsAutresDons = [
            '/site/?Cle=150',
            '/site/?Cle=188',
            '/site/biblio/doc/don/Lettre%20de%20don%20ponctuel%20par%20virement.pdf',
            '/site/biblio/doc/general/Formulaire%20virement%20regulier.pdf',
            '/site/?Cle=151',
            '/site/?Cle=152',
            '/site/?Cle=56',
            '/site/?Cle=960',
            '/site/?Titre=Soutenir%20notre%20action&Cle=56',
            '/site/biblio/doc/don/Lettre%20de%20virement%20regulier.pdf',
        ];

        if (in_array($request->getRequestUri(), $urlsAutresDons)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/dons',
                301
            );
        }

        // Redirections vers les éditions
        $urlsEditions = [
            '/site/?Titre=Der%20Verlag%20Roche%20d%E2%80%99Or&Cle=716',
            '/site/?Cle=29',
        ];

        if (in_array($request->getRequestUri(), $urlsEditions)) {
            return new RedirectResponse(
                'https://editionsrochedor.com' . $request->getRequestUri(),
                301
            );
        }

        // Redirections vers la page infos pratiques RO
        $urlsInfosPratiquesRO = [
            '/site/biblio/doc/rochedor/2E%20ROR%20Renseignements.htm',
            '/site/?Cle=33',
            '/site/?Titre=Informations%20Pratiques&Cle=33',
        ];

        if (in_array($request->getRequestUri(), $urlsInfosPratiquesRO)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/infos-pratiques-de-la-roche-dor',
                301
            );
        }

        // Redirections vers la page infos pratiques FT
        $urlsInfosPratiquesFT = [
            '/site/?Cle=143',
        ];

        if (in_array($request->getRequestUri(), $urlsInfosPratiquesFT)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/infos-pratiques-des-fontanilles',
                301
            );
        }

        // Redirections vers la page intervenants
        $urlsIntervenants = [
            '/site/?Cle=20',
            '/site/?Cle=24',
            '/site/?Titre=Les%20intervenants&Cle=20',
            '/site/?Titre=Les%20intervenants&Cle=24',
        ];

        if (in_array($request->getRequestUri(), $urlsIntervenants)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/intervenants',
                301
            );
        }

        // Redirections vers la page communauté
        $urlsCommunaute = [
            '/site/?Cle=15',
            '/site/?Cle=28',
            '/site/?Titre=La%20Communaut%C3%A9&Cle=15',
            '/site/?Titre=La%20Communaut%C3%A9&Cle=15&Lang=DE',
            '/site/?Titre=La%20Communaut%C3%A9&Cle=15&Lang=IT',
            '/site/?Titre=La%20Communaut%C3%A9&Cle=28',
        ];

        if (in_array($request->getRequestUri(), $urlsCommunaute)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/la-communaute',
                301
            );
        }

        // Redirections vers la page LRDO
        $urlsLRDO = [
            '/site/?Cle=16',
            '/site/?Cle=25',
            '/site/?Titre=La%20maison&Cle=25',
            '/site/?Titre=La%20Roche%20d%27Or&Cle=16',
            '/site/?Titre=La%20Roche%20d%27Or&Cle=16&Lang=DE',
            '/site/?Titre=La%20Roche%20d%27Or&Cle=16&Lang=IT',
        ];

        if (in_array($request->getRequestUri(), $urlsLRDO)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/la-rochedor',
                301
            );
        }

        // Redirections vers la page Fontanilles
        $urlsFontanilles = [
            '/site/?Cle=17',
            '/site/?Titre=Hameau%20des%20Fontanilles&Cle=97',
            '/site/?Titre=Les%20Fontanilles&Cle=17',
            '/site/?Titre=Les%20Fontanilles&Cle=17&Lang=DE',
            '/site/?Titre=Les%20Fontanilles&Cle=17&Lang=IT',
        ];

        if (in_array($request->getRequestUri(), $urlsFontanilles)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/les-fontanilles',
                301
            );
        }

        // Redirections vers la page Liens amis
        $urlsLiensAmis = [
            '/site/?Titre=Liens%20amis&Cle=202',
        ];

        if (in_array($request->getRequestUri(), $urlsLiensAmis)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/liens-amis',
                301
            );
        }

        // Redirections vers la page Choix site
        $urlsRetraites = [
            '/site/?Cle=115',
            '/site/?Cle=122',
            '/site/?Cle=142',
            '/site/?Cle=18',
            '/site/?Cle=21',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraites)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/choix-site',
                301
            );
        }

        // Redirections vers la page Liste retraites LRDO
        $urlsRetraites = [
            '/site/?Cle=21&Lieu=Roch',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraites)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/liste-retraites?site=lrdo',
                301
            );
        }

        // Redirections vers la page Liste retraites Fontanilles
        $urlsRetraites = [
            '/site/?Cle=21&Lieu=Font',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraites)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/liste-retraites?site=font',
                301
            );
        }

        // Redirections vers la page Liste retraites
        $urlsRetraites = [
            '/site/?Titre=Le%20calendrier&Cle=122',
            '/site/?Titre=Le%20calendrier&Cle=21',
            '/site/?Titre=S%27inscrire&Cle=142',
            '/site/?Titre=S%27inscrire&Cle=18',
            '/site/?Titre=Inscription&Cle=125',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraites)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/liste-retraites',
                301
            );
        }

        // Redirections vers la page Mentions légales
        $urlsMentionsLegales = [
            '/site/?Titre=Mentions%20l%C3%A9gales&Cle=38',
        ];

        if (in_array($request->getRequestUri(), $urlsMentionsLegales)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/mentions-legales',
                301
            );
        }

        // Redirections vers la page Retraites LRDO
        $urlsRetraitesLRDO = [
            '/site/?Cle=19',
            '/site/?Cle=231',
            '/site/?Titre=Nos%20propositions&Cle=19',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraitesLRDO)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/retraites-a-la-roche-dor',
                301
            );
        }

        // Redirections vers la page Retraites Fontanilles
        $urlsRetraitesFontanilles = [
            '/site/?Titre=Nos%20propositions&Cle=23',
            '/site/?Cle=135',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraitesFontanilles)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/retraites-aux-fontanilles',
                301
            );
        }

        // Redirections vers la page Retraites
        $urlsRetraites = [
            '/site/?Titre=Qui%20vient%20en%20retraite%20?&Cle=231',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraites)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/venir-en-retraite',
                301
            );
        }

        return new RedirectResponse(
            $this->generateUrl('home'),
            301
        );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @Route(path="/site/biblio/doc/editions/CGV.htm", name="old_cgv")
     * @Route(path="/site/editions.php", name="old_cgv2")
     */
    public function oldCgvAction(Request $request)
    {
        // Redirections vers les CGV
        $urlsCgv = [
            '/site/biblio/doc/editions/CGV.htm',
            '/site/editions.php?Titre=Conditions%20g%C3%A9n%C3%A9rales%20de%20vente&Cle=301',
        ];

        if (in_array($request->getRequestUri(), $urlsCgv)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/cgv',
                301
            );
        }

        // Redirections vers la page de contact Fontanilles
        $urlsContactFt = [
            '/site/editions.php?Cle=173',
        ];

        if (in_array($request->getRequestUri(), $urlsContactFt)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/contact-ft',
                301
            );
        }

        // Redirections vers la page de contact Roche d'Or
        $urlsContactRo = [
            '/site/editions.php?Cle=144',
        ];

        if (in_array($request->getRequestUri(), $urlsContactRo)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/contact-ro',
                301
            );
        }

        // Redirections vers la page de dons
        $urlsDonPonctuel = [
            '/site/editions.php?Cle=153',
        ];

        if (in_array($request->getRequestUri(), $urlsDonPonctuel)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/don-ponctuel',
                301
            );
        }

        // Redirections vers la page autre forme de dons
        $urlsAutresDons = [
            '/site/?Cle=150',
            '/site/editions.php?Cle=152',
            '/site/editions.php?Cle=150',
            '/site/editions.php?Cle=188',
            '/site/editions.php?Titre=Soutenir%20notre%20action&Cle=56',
        ];

        if (in_array($request->getRequestUri(), $urlsAutresDons)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/dons',
                301
            );
        }

        // Redirections vers les éditions
        $urlsEditions = [
            "/site/editions.php",
            "/site/editions.php?Rub=21",
            "/site/editions.php?Rub=21&Lang=IT",
            "/site/editions.php?Rubrique=Les%20in%C3%A9dits&Rub=21",
            "/site/editions.php?ISBN=9782913391058",
            "/site/editions.php?Produit=Band%201%20%E2%80%93%20Das%20Drama%20der%20Welt&CodPrd=40&Lang=DE",
            "/site/editions.php?Produit=Band%201%20%E2%80%93%20Maria,%20mein%20Geheimnis!&CodPrd=39&Lang=DE",
            "/site/editions.php?Produit=C%C3%A9l%C3%A9bration%20Liturgique%20Mariale&CodPrd=2",
            "/site/editions.php?Produit=C%C3%A9l%C3%A9bration%20Liturgique%20Mariale&CodPrd=2&Lang=IT",
            "/site/editions.php?Produit=Combats%20de%20foi&CodPrd=9&Lang=IT",
            "/site/editions.php?Produit=Combattimenti%20di%20fede&CodPrd=33&Lang=IT",
            "/site/editions.php?Produit=Heut%20ist%20fûr%20uns%20ein%20licht%20aufgestrahlt&CodPrd=37&Lang=DE",
            "/site/editions.php?Produit=Hommage%20%C3%A0%20une%20m%C3%A8re&CodPrd=28",
            "/site/editions.php?Produit=Hommage%20%C3%A0%20une%20m%C3%A8re&CodPrd=28&Lang=IT",
            "/site/editions.php?Produit=L%27%C3%A9vangile%20selon%20Saint%20Jean%20lu%20par%20le%20p%C3%A8re%20Florin%20Callerand&CodPrd=49&Lang=IT",
            "/site/editions.php?Produit=L%27%C3%A9vangile%20selon%20Saint%20Jean%20lu%20par%20le%20p%C3%A8re%20Florin%20Callerand&CodPrd=50",
            "/site/editions.php?Produit=L%27%C3%A9vangile%20selon%20Saint%20Jean%20lu%20par%20le%20p%C3%A8re%20Florin%20Callerand&CodPrd=50&Lang=IT",
            "/site/editions.php?Produit=L%E2%80%99imprevedibile%20incontro&CodPrd=32&Lang=IT",
            "/site/editions.php?Produit=Libre%20parole%20pour%20des%20temps%20extrêmes&CodPrd=44",
            "/site/editions.php?Produit=Libre%20parole%20pour%20des%20temps%20extrêmes&CodPrd=44&Lang=IT",
            "/site/editions.php?Produit=Ommagio%20a%20una%20Madre&CodPrd=43&Lang=IT",
            "/site/editions.php?Produit=Tome%201%20-%20L%27impr%C3%A9visible%20rencontre&CodPrd=11",
            "/site/editions.php?Produit=Tome%201%20-%20L%27impr%C3%A9visible%20rencontre&CodPrd=11&Lang=IT",
            "/site/editions.php?Produit=Tome%201%20-%20Le%20drame%20du%20Monde&CodPrd=15",
            "/site/editions.php?Produit=Tome%201%20-%20Le%20drame%20du%20Monde&CodPrd=15&Lang=IT",
            "/site/editions.php?Produit=Tome%201%20-%20Ma%20Th%C3%A9r%C3%A8se&CodPrd=7",
            "/site/editions.php?Produit=Tome%201%20-%20Ma%20Th%C3%A9r%C3%A8se&CodPrd=7&Lang=IT",
            "/site/editions.php?Produit=Tome%201%20-%20Marie,%20mon%20secret&CodPrd=8",
            "/site/editions.php?Produit=Tome%201%20-%20Marie,%20mon%20secret&CodPrd=8&Lang=IT",
            "/site/editions.php?Produit=Tome%201%20-%20Partons,%20c%27est%20l%27heure%20!&CodPrd=3",
            "/site/editions.php?Produit=Tome%201%20-%20Partons,%20c%27est%20l%27heure%20!&CodPrd=3&Lang=IT",
            "/site/editions.php?Produit=Tome%202%20-%20Divine%20Mati%C3%A8re&CodPrd=16",
            "/site/editions.php?Produit=Tome%202%20-%20Divine%20Mati%C3%A8re&CodPrd=16&Lang=IT",
            "/site/editions.php?Produit=Tome%202%20-%20Où%20donc%20voulez-vous%20que%20j%27aille%20?&CodPrd=4",
            "/site/editions.php?Produit=Tome%202%20-%20Où%20donc%20voulez-vous%20que%20j%27aille%20?&CodPrd=4&Lang=IT",
            "/site/editions.php?Produit=Tome%202%20-%20Un%20pauvre%20appelle,%20Dieu%20r%C3%A9pond&CodPrd=12",
            "/site/editions.php?Produit=Tome%202%20-%20Un%20pauvre%20appelle,%20Dieu%20r%C3%A9pond&CodPrd=12&Lang=IT",
            "/site/editions.php?Produit=Tome%203%20-%20Tu%20verras%20les%20fils%20de%20tes%20fils%20!&CodPrd=5",
            "/site/editions.php?Produit=Tome%203%20-%20Tu%20verras%20les%20fils%20de%20tes%20fils%20!&CodPrd=5&Lang=IT",
            "/site/editions.php?Produit=Tome%204%20-%20Toi,%20je%20ne%20veux%20pas%20que%20tu%20meures%20!&CodPrd=6",
            "/site/editions.php?Produit=Tome%204%20-%20Toi,%20je%20ne%20veux%20pas%20que%20tu%20meures%20!&CodPrd=6&Lang=IT",
            "/site/editions.php?Produit=Ultimes%20Paroles%20de%20Florin&CodPrd=1",
            "/site/editions.php?Produit=Ultimes%20Paroles%20de%20Florin&CodPrd=1&Lang=IT",
            "/site/editions.php?Produit=Un%20regard%20croise%20les%20%C3%A9crits%20de%20Florin%20Callerand,%20proph%C3%A8te%20pour%20ces%20temps&CodPrd=51",
            "/site/editions.php?Produit=Un%20regard%20croise%20les%20%C3%A9crits%20de%20Florin%20Callerand,%20proph%C3%A8te%20pour%20ces%20temps&CodPrd=51&Lang=IT",
            "/site/editions.php?Produit=Victoire%20sur%20une%20enfance%20assi%C3%A9g%C3%A9e&CodPrd=10&Lang=IT",
            "/site/editions.php?Produit=Vittoria%20su%20un%E2%80%99infanzia%20assediata&CodPrd=34&Lang=IT",
            "/site/editions.php?Produit=vol.2%20-%20Ouvrez%20vos%20voiles%20-%20Livret&CodPrd=25",
            "/site/editions.php?Produit=vol.2%20-%20Ouvrez%20vos%20voiles%20-%20Livret&CodPrd=25&Lang=IT",
            "/site/editions.php?Produit=vol.2%20-%20Ouvrez%20vos%20voiles&CodPrd=18",
            "/site/editions.php?Produit=vol.2%20-%20Ouvrez%20vos%20voiles&CodPrd=18&Lang=IT",
            "/site/editions.php?Produit=vol.3%20-%20Souffle%20d%E2%80%99un%20peuple%20-%20Livret&CodPrd=23",
            "/site/editions.php?Produit=vol.3%20-%20Souffle%20d%E2%80%99un%20peuple%20-%20Livret&CodPrd=23&Lang=IT",
            "/site/editions.php?Produit=vol.3%20-%20Souffle%20d%E2%80%99un%20peuple&CodPrd=19",
            "/site/editions.php?Produit=vol.3%20-%20Souffle%20d%E2%80%99un%20peuple&CodPrd=19&Lang=IT",
            "/site/editions.php?Produit=vol.4%20-%20Au%20fil%20des%20jours%20-%20Livret&CodPrd=24",
            "/site/editions.php?Produit=vol.4%20-%20Au%20fil%20des%20jours%20-%20Livret&CodPrd=24&Lang=IT",
            "/site/editions.php?Produit=vol.4%20-%20Au%20fil%20des%20jours&CodPrd=20",
            "/site/editions.php?Produit=vol.4%20-%20Au%20fil%20des%20jours&CodPrd=20&Lang=IT",
            "/site/editions.php?Produit=vol.5%20-%20Racines%20vives%20-%20Livret&CodPrd=27",
            "/site/editions.php?Produit=vol.5%20-%20Racines%20vives%20-%20Livret&CodPrd=27&Lang=IT",
            "/site/editions.php?Produit=vol.5%20-%20Racines%20vives&CodPrd=26",
            "/site/editions.php?Produit=vol.5%20-%20Racines%20vives&CodPrd=26&Lang=IT",
            "/site/editions.php?Produit=vol.6%20-%20Viens%20boire%20%C3%A0%20la%20Source%20-%20Livret&CodPrd=47",
            "/site/editions.php?Produit=vol.6%20-%20Viens%20boire%20%C3%A0%20la%20Source%20-%20Livret&CodPrd=47&Lang=IT",
            "/site/editions.php?Produit=vol.6%20-%20Viens%20boire%20%C3%A0%20la%20Source&CodPrd=48",
            "/site/editions.php?Produit=vol.6%20-%20Viens%20boire%20%C3%A0%20la%20Source&CodPrd=48&Lang=IT",
            "/site/editions.php?Produit=Volume%201%20%E2%80%93%20Il%20dramma%20del%20mondo&CodPrd=31&Lang=IT",
            "/site/editions.php?Produit=Volume%201%20%E2%80%93%20Maria,%20il%20mio%20segreto&CodPrd=30&Lang=IT",
            "/site/editions.php?Produit=Volume%201%20%E2%80%93%20Partiamo,%20%C3%A8%20l%E2%80%99ora%20!&CodPrd=29&Lang=IT",
            "/site/editions.php?Rub=1",
            "/site/editions.php?Rub=12&Lang=DE",
            "/site/editions.php?Rub=13&Lang=IT",
            "/site/editions.php?Rub=15&Lang=IT",
            "/site/editions.php?Rub=17&Lang=IT",
            "/site/editions.php?Rub=18&Lang=DE",
            "/site/editions.php?Rub=2",
            "/site/editions.php?Rub=2&Lang=IT",
            "/site/editions.php?Rub=20&Lang=IT",
            "/site/editions.php?Rub=3",
            "/site/editions.php?Rub=3&Lang=IT",
            "/site/editions.php?Rub=4",
            "/site/editions.php?Rub=4&Lang=IT",
            "/site/editions.php?Rub=5",
            "/site/editions.php?Rub=5&Lang=IT",
            "/site/editions.php?Rub=8&Lang=IT",
            "/site/editions.php?Rubrique=CDs%20und%20liturgische%20Liederhefte&Rub=18&Lang=DE",
            "/site/editions.php?Rubrique=Die%20Hefte%20von%20Florin%20Callerand&Rub=12&Lang=DE",
            "/site/editions.php?Rubrique=I%20Quaderni%20di%20Florin%20Callerand&Rub=13&Lang=IT",
            "/site/editions.php?Rubrique=I%20Testi%20Scelti%20di%20Florin%20Callerand&Rub=17&Lang=IT",
            "/site/editions.php?Rubrique=Le%20analisi%20di%20Florin%20Callerand&Rub=15&Lang=IT",
            "/site/editions.php?Rubrique=Les%20Analyses%20de%20Florin%20Callerand&Rub=3",
            "/site/editions.php?Rubrique=Les%20Cahiers%20de%20Florin%20Callerand&Rub=2",
            "/site/editions.php?Rubrique=Les%20CD%20et%20livrets%20de%20chants%20liturgiques&Rub=5",
            "/site/editions.php?Rubrique=Les%20Sources&Rub=1",
            "/site/editions.php?Rubrique=Les%20Textes%20Choisis%20de%20Florin%20Callerand&Rub=4",
            "/site/editions.php?Rubrique=Lettere%20e%20Scritti&Rub=20&Lang=IT",
            "/site/editions.php?Rubrique=Lettres%20et%20Ecrits&Rub=8",
            "/site/editions.php?Produit=Hommage%20%C3%A0%20une%20m%C3%A8re&CodPrd=28",
            "/site/editions.php?Produit=L%E2%80%99imprevedibile%20incontro&CodPrd=32&Lang=IT",
            "/site/editions.php?Produit=vol.1%20-%20De%20terre%20et%20de%20Ciel&CodPrd=17",
            "/site/editions.php?Produit=vol.1%20-%20De%20terre%20et%20de%20Ciel&CodPrd=21",
            "/site/editions.php?Rub=20&Lang=IT",
            "/site/editions.php?Rub=8",
        ];

        if (in_array($request->getRequestUri(), $urlsEditions)) {
            return new RedirectResponse(
                'https://editionsrochedor.com' . $request->getRequestUri(),
                301
            );
        }

        // Redirections vers la page infos pratiques RO
        $urlsInfosPratiquesRO = [
            '/site/editions.php?Cle=33',
        ];

        if (in_array($request->getRequestUri(), $urlsInfosPratiquesRO)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/infos-pratiques-de-la-roche-dor',
                301
            );
        }

        // Redirections vers la page intervenants
        $urlsIntervenants = [
            '/site/editions.php?Titre=Les%20intervenants&Cle=24',
            '/site/editions.php?Titre=Actualit%C3%A9s&Cle=232',
        ];

        if (in_array($request->getRequestUri(), $urlsIntervenants)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/intervenants',
                301
            );
        }

        // Redirections vers la page communauté
        $urlsCommunaute = [
            '/site/editions.php?Cle=28',
        ];

        if (in_array($request->getRequestUri(), $urlsCommunaute)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/la-communaute',
                301
            );
        }

        // Redirections vers la page LRDO
        $urlsLRDO = [
            '/site/?Cle=16',
        ];

        if (in_array($request->getRequestUri(), $urlsLRDO)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/la-roche-dor',
                301
            );
        }

        // Redirections vers la page Fontanilles
        $urlsFontanilles = [
            '/site/editions.php?Titre=Hameau%20des%20Fontanilles&Cle=97',
        ];

        if (in_array($request->getRequestUri(), $urlsFontanilles)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/les-fontanilles',
                301
            );
        }

        // Redirections vers la page Liens amis
        $urlsLiensAmis = [
            '/site/editions.php?Titre=Liens%20amis&Cle=202',
            '/site/editions.php?Titre=Liens%20amis&Cle=202&Lang=DE',
            '/site/editions.php?Titre=Liens%20amis&Cle=202&Lang=IT',
        ];

        if (in_array($request->getRequestUri(), $urlsLiensAmis)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/liens-amis',
                301
            );
        }

        // Redirections vers la page Liens amis
        $urlsLiensAmis = [
            '/site/editions.php?Titre=Liens%20amis&Cle=202',
        ];

        if (in_array($request->getRequestUri(), $urlsLiensAmis)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/liens-amis',
                301
            );
        }

        // Redirections vers la page Choix site
        $urlsRetraites = [
            '/site/editions.php?Titre=Le%20calendrier&Cle=21',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraites)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/choix-site',
                301
            );
        }

        // Redirections vers la page Mentions légales
        $urlsMentionsLegales = [
            '/site/editions.php?Titre=Mentions%20l%C3%A9gales&Cle=38',
            '/site/editions.php?Titre=Mentions%20l%C3%A9gales&Cle=38&Lang=DE',
            '/site/editions.php?Titre=Mentions%20l%C3%A9gales&Cle=38&Lang=IT',
        ];

        if (in_array($request->getRequestUri(), $urlsMentionsLegales)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/mentions-legales',
                301
            );
        }

        // Redirections vers la page Retraites LRDO
        $urlsRetraitesLRDO = [
            '/site/editions.php?Titre=Nos%20propositions&Cle=19',
            '/site/editions.php?Titre=Nos%20propositions&Cle=23',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraitesLRDO)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/retraites-a-la-roche-dor',
                301
            );
        }

        // Redirections vers la page Retraites
        $urlsRetraites = [
            '/site/editions.php?Titre=Qui%20vient%20en%20retraite%20?&Cle=231',
        ];

        if (in_array($request->getRequestUri(), $urlsRetraites)) {
            return new RedirectResponse(
                '/' . $request->getLocale() . '/venir-en-retraite',
                301
            );
        }

        return new RedirectResponse(
            $this->generateUrl('home'),
            301
        );
    }
}
