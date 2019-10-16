import React from 'react'
import { connect } from 'react-redux'
import AppMenu from '../../components/app-menu/app-menu'
import { Card, CardContent, Typography, CardMedia, CardActions, Button } from '@material-ui/core'
import IsAuthorized, { ACTION_HOME_EDIT, ACTION_NEWS_CREATE, ACTION_CONTENT_EDIT, ACTION_SPEAKER_EDIT } from '../../isauthorized/isauthorized'
import { NavLink } from 'react-router-dom'
class HomeAdmin extends React.Component {
  render () {
    return (
      <div>
        <AppMenu title={`Panneau d'administration`} />
        <Card>
          <CardMedia
            image={`/assets/img/bg-${Math.floor(Math.random() * Math.floor(7) + 1)}.jpg`}
            style={{ height: 0, paddingTop: '20%' }}
          />
          <CardContent>
            <Typography gutterBottom variant='headline' component='h2'>
            Bienvenue.
            </Typography>
            <Typography component='p'>
            Bienvenue dans le panneau d'administration de la Roche d'Or {this.props.fullname}.<br />
            Que souhaitez vous faire?
            </Typography>
          </CardContent>
          <CardActions>
            <IsAuthorized action={ACTION_HOME_EDIT}>
              <NavLink to='/home-edit' style={{ textDecoration: 'none' }}>
                <Button style={{ margin: '0 15px' }} variant='outlined' color='primary'>Modifier la page d'accueil</Button>
              </NavLink>
            </IsAuthorized>
            <IsAuthorized action={ACTION_CONTENT_EDIT}>
              <NavLink to='/content-list' style={{ textDecoration: 'none' }}>
                <Button style={{ margin: '0 15px' }} variant='outlined' color='primary'>Modifier les contenus</Button>
              </NavLink>
            </IsAuthorized>
            <IsAuthorized action={ACTION_NEWS_CREATE}>
              <NavLink to='/news-create' style={{ textDecoration: 'none' }}>
                <Button style={{ margin: '0 15px' }} variant='outlined' color='primary'>Ajouter une nouveauté</Button>
              </NavLink>
            </IsAuthorized>
            <IsAuthorized action={ACTION_SPEAKER_EDIT}>
              <NavLink to='/speaker-list' style={{ textDecoration: 'none' }}>
                <Button style={{ margin: '0 15px' }} variant='outlined' color='primary'>Modifier les intervenants</Button>
              </NavLink>
            </IsAuthorized>
          </CardActions>
        </Card>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return { fullname: state.fullname }
}

export default connect(mapStateToProps)(HomeAdmin)
