import React from 'react'
import { connect } from 'react-redux'
import AppMenu from '../../components/app-menu/app-menu'
import { Card, CardContent, Typography, CardMedia, CardActions, Button } from '@material-ui/core'
import IsAuthorized, {
  ACTION_HOME_EDIT,
  ACTION_NEWS_CREATE,
  ACTION_CONTENT_ASSOCIATION_VIEW,
  ACTION_CONTENT_EDITION_VIEW,
  ACTION_SPEAKER_EDIT,
  ACTION_NEWS_VIEW
} from '../../isauthorized/isauthorized'
import { NavLink } from 'react-router-dom'
class HomeAdmin extends React.Component {
  render () {
    return (
      <div>
        <AppMenu title={`Panneau d'administration`} />
        <Card>
          <CardMedia
            image={`/assets/img/bg-${Math.floor(Math.random() * Math.floor(7) + 1)}.jpg`}
            style={{height: 0, paddingTop: '20%'}}
          />
          <CardContent>
            <Typography gutterBottom variant='headline' component='h2'>
            Bienvenue.
            </Typography>
            <Typography component='p'>
              {this.props.fullname}, bienvenue dans le panneau d'administration du site web de la Roche d'Or.
              <br/>Rappel : les Produits des Editions Roche d'Or et les Activités (retraites) se gèrent dans Kiwi.
              <br/>Que souhaitez vous faire?
            </Typography>
          </CardContent>
          <CardActions>
            <IsAuthorized action={ACTION_HOME_EDIT}>
              <NavLink to='/home-edit' style={{textDecoration: 'none'}}>
                <Button style={{margin: '0 15px'}} variant='outlined' color='primary'>Page d'accueil</Button>
              </NavLink>
            </IsAuthorized>
            <IsAuthorized action={[ACTION_CONTENT_ASSOCIATION_VIEW, ACTION_CONTENT_EDITION_VIEW]}>
              <NavLink to='/content-list' style={{textDecoration: 'none'}}>
                <Button style={{margin: '0 15px'}} variant='outlined' color='primary'>Contenus</Button>
              </NavLink>
            </IsAuthorized>
            <IsAuthorized action={ACTION_NEWS_VIEW}>
              <NavLink to='/news-list' style={{textDecoration: 'none'}}>
                <Button style={{margin: '0 15px'}} variant='outlined' color='primary'>Nouveautés</Button>
              </NavLink>
            </IsAuthorized>
            <IsAuthorized action={ACTION_SPEAKER_EDIT}>
              <NavLink to='/speaker-list' style={{textDecoration: 'none'}}>
                <Button style={{margin: '0 15px'}} variant='outlined' color='primary'>Intervenants</Button>
              </NavLink>
            </IsAuthorized>
          </CardActions>
        </Card>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {fullname: state.fullname}
}

export default connect(mapStateToProps)(HomeAdmin)
