import React from 'react'
import { compose } from 'redux'
import { connect } from 'react-redux'
import { Button, Card, CardActions, CardContent, CardMedia, Typography } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import AppMenu from '../app-menu/app-menu'
import IsAuthorized, {
  ACTION_CONTENT_ASSOCIATION_VIEW,
  ACTION_CONTENT_EDITION_VIEW,
  ACTION_HOME_EDIT, ACTION_NEWS_VIEW, ACTION_SPEAKER_EDIT
} from '../../isauthorized/isauthorized'
import { NavLink } from 'react-router-dom'

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
              Sécurité d'accès au CMS
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Compte tenu des possibilités offertes par le CMS, il convient de s'assurer d'un accès strict à celui-ci.
              <br />
              Cet accès, pour un utilisateur donné, est donc protégé par un identifiant et un mot de passe non renouvelable automatiquement.
              <br />
              <br />
              En cas de perte de son mot de passe, il lui est demandé de contacter l'un des administrateurs afin qu'il lui attribue un nouveau mot de passe.
              <br />
              <br />
              Tout mot de passe du CMS est strictement personnel. Il convient de ne pas l'écrire sur un quelconque support ou dans un quelconque lieu (tiroir, sous le clavier...) placé à proximité des ordinateurs.
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              Droits d'accès
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
              Les droits <strong>Association</strong> permettent d'accéder à toutes les pages de contenu relevant de l'association Roche d'Or ainsi que de gérer les nouveautés liées à la page d'accueil.
              <br />
              Si des nouveautés relevant des Éditions sont à placer en page d'accueil, il est prévu qu'une demande soit faite en ce sens à un des administrateurs.
              <br />
              <br />
              Les doits <strong>Éditions</strong> permettent d'accéder à toutes les pages de contenu relevant de la SARL Foyer de la Roche d'Or. Cette entité juridique doit pouvoir administrer ses pages sans pouvoir accéder à celles de l'association.
              <br />
              L'administration des produits : fiche descriptive et photos, n'est pas incluse dans le CMS mais dans Kiwi et soumise également à des droits d'accès.
              <br />
              <br />
              Les droits d'<strong>Administrateur (Admin)</strong> permettent d'accéder à toutes les rubriques du site.
              <br />
              <br />
              Il est possible de désactiver ou d'activer un utilisateur, par exemple en cas d'absence longue durée, en cas de départ définitif, en cas de travail périodique...
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              Pages de contenu
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
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
              Nouveautés
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Pour ne pas avoir de variation de positionnement vertical du bloc sur la page d'accueil, il est recommandé d'avoir la même longueur de textes pour chacune des nouveautés.
              <br />
              En effet, suivant que l'on utilise 1 ou 3 lignes, le positionnement varie. Avoir des longueurs homogènes permet d'éviter cette variation.
              <br />
              <br />
              Pour obtenir la possibilité d'enregistrer (apparition en bas à droite d'un symbole de disquette dans un cercle bleu) la fiche nouveauté créée doit être totalement renseignée, hormis le lien qui est factultatif.
              <br />
              Les dates doivent avoir été indiquées en commençant par la date/heure de départ de l'affichage puis de fin d'affichage. Il convient de mettre à jour ces deux dates, en particulier celle de début d'affichage, même si ce qui est affiché convient.
              <br />
              <br />
              Pour obtenir un ordre d'affichage, utiliser la date de départ de l'affichage et l'heure si le jour est le même. La fiche ayant la date et l'heure de début d'affichage les plus récents se place en premier et ainsi de suite.
              <br />
              <br />
              Lors de l'affichage des nouveautés dans le site et d'un clic sur le bouton "Découvrir", les liens externes au site provoquent l'ouverture d'un nouvel onglet.
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              Légende des images
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              A définir
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              Les contraintes de longueur des textes de contenus
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              A définir
              <br />
              <br />
              Si l'on a besoin de mettre un texte long, placer celui-ci dans le dernier volet ou créer une page avec un seul volet.
            </Typography>
            <Typography variant='headline' component='h3' style={{'margin-bottom': '20px'}}>
              Le placement des citations
            </Typography>
            <Typography component='p'>
              <Typography component='ul'>
                <Typography component='li' style={{'display': 'list-item'}}>Ne pas placer une citation en début de texte de contenu.</Typography>
                <Typography component='li' style={{'display': 'list-item'}}>Ne pas insérer plus d'une citation dans un paragraphe de texte.</Typography>
              </Typography>
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
