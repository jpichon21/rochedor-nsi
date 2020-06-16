import React from 'react'
import { compose } from 'redux'
import { connect } from 'react-redux'
import { Card, CardContent, CardMedia, Typography } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import AppMenu from '../app-menu/app-menu'

export class Help extends React.Component {
  render () {
    Moment.locale(this.props.locale)
    return (
      <div>
        <AppMenu title={'Aide'} />
        <Card>
          <CardMedia
            image={`/assets/img/bg-${Math.floor(Math.random() * Math.floor(7) + 1)}.jpg`}
            style={{height: 0, paddingTop: '20%'}}
          />
          <CardContent>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              1. Sécurité d'accès au CMS
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Compte tenu des possibilités offertes par le CMS, il convient de s'assurer d'un accès strict à celui-ci.
              <br />
              Cet accès, pour un utilisateur donné, est donc protégé par un identifiant et un mot de passe non renouvelable automatiquement.
              <br />
              <br />
              En cas de perte de votre mot de passe, il vous est demandé de contacter l'un des administrateurs afin qu'il vous en attribue un nouveau.
              <br />
              <br />
              Tout mot de passe du CMS est strictement personnel. Il convient de ne pas l'écrire sur un quelconque support ou dans un quelconque lieu (tiroir, sous le clavier...) placé à proximité de votre ordinateur.
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              2. Droits d'accès
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Trois types de droits sont attribuables.
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Administrateur</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Association</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Éditions</Typography>
              </Typography>
              <br />
              Les droits <strong>Association</strong> permettent d'accéder à toutes les pages de contenu relevant de l'association Roche d'Or ainsi que de gérer les nouveautés apparaissant en page d'accueil.
              <br />
              Si des nouveautés relevant des Éditions sont à y insérer, il est prévu qu'une demande soit faite en ce sens à un des administrateurs.
              <br />
              <br />
              Les doits <strong>Éditions</strong> permettent d'accéder à toutes les pages de contenu relevant de la SARL Foyer de la Roche d'Or. Cette entité juridique doit pouvoir administrer ses pages sans pouvoir accéder à celles de l'association.
              <br />
              Il convient de noter que l'administration des produits : fiche descriptive et photos, n'est pas incluse dans le CMS mais dans Kiwi et soumise également à des droits d'accès.
              <br />
              <br />
              Les droits d'<strong>Administrateur (Admin)</strong> permettent d'accéder à toutes les rubriques du site.
              <br />
              <br />
              Il est possible de désactiver ou d'activer un utilisateur, par exemple en cas d'absence longue durée, en cas de départ définitif, en cas de travail périodique, etc...
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              3. Pages de contenu
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Les rubriques à <u>ne toucher sous aucun prétexte</u> sont les suivantes :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Affectation</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Catégorie</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Titre ligne 1</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Titre ligne 2</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>URL</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Méta-description</Typography>
              </Typography>
              <br />
              La <strong>méta description</strong> n'a pas de "valeur" pour Google du point de vue du positionnement du site dans la liste qui apparaît suite à une recherche dans un des moteurs de recherche.
              <br />
              Le but est de faciliter la "lecture" des résultats de recherche de l'internaute. L'essentiel est que le descriptif explique le contenu ("la promesse" / "l'intention") de la page.
              <br />
              <br />
              Du point de vue de Google et de ses règles de "ranking", il est pénalisant de ne pas remplir cette balise, car contraire à la net-éthique.
              <br />
              Quitte à la remplir, il est préférable que son contenu ait du sens (soit pertinent), donc les expressions-clé utilisées dans la page à laquelle elle se rapporte doivent s'y retrouver.
              <br />
              <br />
              Le classement chez Google qui est à ce jour le principal moteur de recherche provient de la fréquentation du site mais également d'autres paramètres comme la fréquence des mises à jour ou encore, la cohérence intérieure.
              <br />
              Google mesure pour chaque page la cohérence des occurences des mots/expressions renseignés dans les éléments suivants : Titre, URL, contenu textuel de la page, images.
              <br />
              <br />
              A noter que dans notre CMS, les légendes des photos permettent de référencer les images du site et de les retrouver lors des recherches sur images.
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              4. Nouveautés
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Pour ne pas avoir de variation de positionnement vertical du bloc Nouveautés sur la page d'accueil, il est recommandé d'avoir la même longueur de textes pour chacune des nouveautés.
              <br />
              En effet, suivant que l'on utilise 1 ou 3 lignes, le positionnement varie. Avoir des longueurs homogènes permet d'éviter cette variation.
              <br />
              <br />
              Pour obtenir la possibilité d'enregistrer (apparition en bas à droite d'un symbole de disquette dans un cercle bleu) la fiche nouveauté créée doit être totalement renseignée, hormis le lien qui est factultatif.
              <br />
              Les dates doivent avoir été indiquées en commençant par la date/heure de départ de l'affichage puis de fin d'affichage. Il convient de mettre à jour ces deux dates, en particulier celle de début d'affichage, même si la date proposée convient.
              <br />
              <br />
              Pour obtenir un ordre d'affichage, utiliser la date de départ de l'affichage et l'heure si le jour est le même. La fiche ayant la date et l'heure de début d'affichage les plus récents se place en premier et ainsi de suite.
              <br />
              <br />
              A l'affichage des nouveautés dans le site, un clic sur le bouton "Découvrir" provoque l'ouverture d'un nouvel onglet si le lien est externe au site.
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              5. Légende des images
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              La longueur du texte de légende des images doit :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Respecter les contraintes techniques suivantes : texte de 7 à 10 mots (soit entre 35 et 55 caractères ponctuation comprise). La limite haute est de 16 mots (soit 85 caractères avec ponctuation)</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Intégrer des expressions clé en lien avec le sens du visuel concerné : prenons l'exemple d'une image dont le nom de fichier est le suivant : "151018-reco-fr-32jpg1555323123". Le nom suivant sera déjà beaucoup plus satisfaisant "retraites-à-la-roche-d'or-retraites-fondamentales-hubert".<br />Mais une phrase plus agréable à lire est encore mieux, à la condition toujours de respecter les règles de longueur de texte et en utilisant les expressions-clé comme : "A la Roched'Or, Hubert anime une retraite fondamentale".</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>A noter que pour Google, il n'y a pas de différence d'efficité tangible.</Typography>
              </Typography>
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              6. Les contraintes de longueur des textes de contenus
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              <u>MODÈLE N°1 / PAGE « homepage » (Page d’accueil)</u>
              <br />
              Caractères, signes ou espaces :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Titre Rubrique : mini néant, maxi néant</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Citation : mini 90, maxi 200</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Titre nouveauté : mini 90, maxi 180</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Texte nouveauté : mini 180, maxi 360</Typography>
              </Typography>
              <br />
              <u>MODÈLE N°2 / PAGE « texte+citation »</u>
              <br />
              Caractères, signes ou espaces :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Introduction : mini 150, maxi 240</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Paragraphe (si O citation) : mini 750, maxi 1750</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Paragraphe (si 1 citation) : mini 500, maxi 1500</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Citation : mini 120, maxi 270</Typography>
              </Typography>
              <br />
              <u>MODÈLE N°3 / PAGE « à volets »</u>
              <br />
              <u>Attention</u> : à l’ouverture des volets, le volet s’ouvre mais pas en commençant par son titre. <u>Si l’on a besoin de mettre un texte long, placer celui-ci dans le dernier volet ou créer une page avec un seul volet.</u>
              <br />
              Caractères, signes ou espaces :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Introduction : mini 150, maxi 240</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Volet : mini 250, maxi 750</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Lien PDF : mini 20, maxi 60</Typography>
              </Typography>
              <br />
              <u>MODÈLE N°4 / PAGE « intervenant »</u>
              <br />
              Caractères, signes ou espaces :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Introduction : 150, maxi 240</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Paragraphe : mini 425, maxi 850</Typography>
              </Typography>
              <br />
              <u>MODÈLE N°5 / PAGE « texte information réglementaire »</u>
              <br />
              Caractères, signes ou espaces :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Paragraphe : pas de mini, pas de maxi</Typography>
              </Typography>
              <br />
              <u>MODÈLE N°6 / PAGE « texte additionnel »</u>
              <br />
              Caractères, signes ou espaces :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Sans objet</Typography>
              </Typography>
              <br />
              <u>MODÈLE N°7 / PAGE « texte destiné à publication PDF »</u>
              <br />
              Caractères, signes ou espaces :
              <br />
              <br />
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Introduction : mini 150, maxi pas de limite</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Paragraphe : maxi pas de limite</Typography>
              </Typography>
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              7. Le placement des citations
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Ne pas placer une citation en début de texte de contenu.
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              8. Photos et vidéos
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Pour la gestion des photos et des vidéos, se reporter au document "LRDO - règles de nommage des fichiers" téléchargeable grâce au lien suivant : <a style={{'color': 'red'}} target="_blank" href={location.protocol + '//' + location.host + '/assets/img/LRDO - regles de nommage des fichiers.pdf'}>téléchargez le document "LRDO - règles de nommage des fichiers"</a>
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              9. Documentation technique CMS
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Pour consulter la documentation technique CMS, téléchargeable grâce au lien suivant : <a style={{'color': 'red'}} target="_blank" href={location.protocol + '//' + location.host + '/assets/img/documentation technique CMS.pdf'}>téléchargez le document "Documentation technique CMS"</a>
            </Typography>
          </CardContent>
        </Card>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    speakers: state.speakers,
    loading: state.loading,
    status: state.status,
    error: state.error
  }
}

export default compose(withStyles(styles), connect(mapStateToProps))(Help)
