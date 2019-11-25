import React from 'react'
import { connect } from 'react-redux'
import { compose } from 'redux'
import { NavLink, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types'
import { withStyles } from '@material-ui/core/styles'
import { Menu, MenuItem, AppBar, Toolbar, Typography, IconButton, Tooltip, Button, Divider } from '@material-ui/core'
import MenuIcon from '@material-ui/icons/Menu'
import ArrowBackIcon from '@material-ui/icons/ArrowBack'
import IsAuthorized, { ACTION_SPEAKER_VIEW, ACTION_HOME_VIEW, ACTION_CONTENT_VIEW, ACTION_NEWS_VIEW, ACTION_PAGE_VIEW, ACTION_USER_VIEW} from '../../isauthorized/isauthorized'
import PeopleIcon from '@material-ui/icons/People'
import RecentActorsIcon from '@material-ui/icons/RecentActors'
import TocIcon from '@material-ui/icons/Toc'
import EditIcon from '@material-ui/icons/Edit'
import AnnouncementIcon from '@material-ui/icons/Announcement'
import LaunchIcon from '@material-ui/icons/Launch'
import ExitToAppIcon from '@material-ui/icons/ExitToApp'

class AppMenu extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      goBack: false,
      anchorMenu: null,
      anchorLang: null,
      menuOpen: false,
      langOpen: false,
      locale: 'fr'
    }
    this.handleMenu = this.handleMenu.bind(this)
    this.handleLang = this.handleLang.bind(this)
    this.handleGoBack = this.handleGoBack.bind(this)
    this.handleCloseMenu = this.handleCloseMenu.bind(this)
    this.handleCloseLang = this.handleCloseLang.bind(this)
    this.handleChangeLang = this.handleChangeLang.bind(this)
  }

  handleGoBack () {
    this.setState({ goBack: true })
  }

  handleMenu (event) {
    this.setState({ anchorMenu: event.currentTarget })
  }

  handleLang (event) {
    this.setState({ anchorLang: event.currentTarget })
  }

  handleCloseMenu () {
    this.setState({ anchorMenu: null })
  }

  handleCloseLang (event) {
    this.setState({ anchorLang: null })
  }

  handleChangeLang (event, locale) {
    this.setState({ anchorLang: null, locale: locale })
    this.props.localeHandler(locale)
  }

  render () {
    if (this.state.goBack) {
      return <Redirect to={this.props.goBack} />
    }
    const { classes } = this.props
    const { anchorMenu, anchorLang } = this.state
    const menuOpen = Boolean(anchorMenu)
    const langOpen = Boolean(anchorLang)
    const isConnected = Boolean(this.props.username)
    return (
      <AppBar position='static'>
        <Toolbar>
          {
            this.props.goBack
              ? (
                <Tooltip
                  enterDelay={300}
                  id='tooltip-controlled'
                  leaveDelay={300}
                  onClose={this.handleTooltipClose}
                  onOpen={this.handleTooltipOpen}
                  open={this.state.open}
                  placement='bottom'
                  title='Retourner à la page précédente'
                >
                  <IconButton
                    className={classes.leftButton}
                    onClick={this.handleGoBack}
                    color='inherit'>
                    <ArrowBackIcon />{' '}
                  </IconButton>
                </Tooltip>
              )
              : (
                <Tooltip
                  enterDelay={300}
                  id='tooltip-controlled'
                  leaveDelay={100}
                  onClose={this.handleTooltipClose}
                  onOpen={this.handleTooltipOpen}
                  open={this.state.open}
                  placement='bottom'
                  title='Menu'
                >
                  <IconButton
                    className={classes.leftButton}
                    aria-owns={menuOpen ? 'menu-appbar' : null}
                    aria-haspopup='true'
                    onClick={this.handleMenu}
                    color='inherit'>
                    <MenuIcon />{' '}
                  </IconButton>
                </Tooltip>
              )
          }
          <Typography variant='title' color='inherit' className={classes.flex}>
            { this.props.title }
          </Typography>
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={100}
            onClose={this.handleTooltipClose}
            onOpen={this.handleTooltipOpen}
            open={this.state.open}
            placement='bottom'
            title='Choisir une langue'
          >
            <Button
              className={classes.rightButton}
              aria-owns={langOpen ? 'lang-appbar' : null}
              aria-haspopup='true'
              onClick={this.handleLang}
              color='inherit'>
              {(this.props.locales[this.props.locale]) ? this.props.locales[this.props.locale] : ' '}
            </Button>
          </Tooltip>
          <Menu
            id='menu-appbar'
            anchorEl={anchorMenu}
            anchorOrigin={{
              vertical: 'top',
              horizontal: 'left'
            }}
            transformOrigin={{
              vertical: 'top',
              horizontal: 'left'
            }}
            open={menuOpen}
            onClose={this.handleCloseMenu}>

            <IsAuthorized action={ACTION_USER_VIEW} alternative={null}>
              <NavLink to='/user-list' className={classes.link}>
                <MenuItem onClick={this.handleCloseMenu}>
                  <PeopleIcon />{' '}
                  Utilisateurs
                </MenuItem>
              </NavLink>
            </IsAuthorized>

            <IsAuthorized action={ACTION_SPEAKER_VIEW}>
              <NavLink to='/speaker-list' className={classes.link}>
                <MenuItem onClick={this.handleCloseMenu}>
                  <RecentActorsIcon />{' '}
                  Intervenants
                </MenuItem>
              </NavLink>
            </IsAuthorized>

            <Divider className={'divider'} />
            <IsAuthorized action={ACTION_CONTENT_VIEW} alternative={null}>
              <NavLink to='/content-list' className={classes.link}>
                <MenuItem onClick={this.handleCloseMenu}>
                  <TocIcon />{' '}
                  Contenus
                </MenuItem>
              </NavLink>
            </IsAuthorized>

            <IsAuthorized action={ACTION_HOME_VIEW} alternative={null}>
              <NavLink to='/home-edit' className={classes.link}>
                <MenuItem onClick={this.handleCloseMenu}>
                  <EditIcon />{' '}
                  Page d'accueil
                </MenuItem>
              </NavLink>
            </IsAuthorized>

            <IsAuthorized action={ACTION_HOME_VIEW || ACTION_CONTENT_VIEW}>
              <IsAuthorized action={[ACTION_SPEAKER_VIEW || ACTION_PAGE_VIEW || ACTION_NEWS_VIEW]}>
                <Divider className={'divider'} />
              </IsAuthorized>
            </IsAuthorized>
            <IsAuthorized action={ACTION_NEWS_VIEW} alternative={null}>
              <NavLink to='/news-list' className={classes.link}>
                <MenuItem onClick={this.handleCloseMenu}>
                  <AnnouncementIcon />{' '}
                  Nouveautés
                </MenuItem>
              </NavLink>
            </IsAuthorized>
            {/* <IsAuthorized action={ACTION_PAGE_VIEW} alternative={null}>
              <NavLink to='/page-list' className={classes.link}>
                <MenuItem onClick={this.handleCloseMenu}>
                Liste des pages
                </MenuItem>
              </NavLink>
            </IsAuthorized> */}

            <Divider className={'divider'} />
            <a href='/' target='_blank' className={classes.link}>
              <MenuItem className={classes.visit} onClick={this.handleCloseMenu}>
                <LaunchIcon />{' '}
                Voir le site
              </MenuItem>
            </a>
            <Divider className={'divider'} />
            {
              isConnected
                ? (
                  <NavLink to='/logout' className={classes.link}>
                    <MenuItem className={classes.logout} onClick={this.handleCloseMenu}>
                      <ExitToAppIcon />{' '}
                      Déconnexion
                    </MenuItem>
                  </NavLink>
                )
                : (
                  <NavLink to='/login' className={classes.link}>
                    <MenuItem onClick={this.handleCloseMenu}>
                      Connexion
                    </MenuItem>
                  </NavLink>
                )
            }
          </Menu>
          <Menu
            id='lang-appbar'
            anchorEl={anchorLang}
            anchorOrigin={{
              vertical: 'top',
              horizontal: 'right'
            }}
            transformOrigin={{
              vertical: 'top',
              horizontal: 'right'
            }}
            open={langOpen}
            onClose={this.handleCloseLang}>
            {Object.keys(this.props.locales).map((key) => (
              <MenuItem
                key={key}
                disabled={key === 0}
                selected={key === this.props.locale}
                onClick={event => this.handleChangeLang(event, key)}
              >
                {this.props.locales[key]}
              </MenuItem>
            ))}
          </Menu>
        </Toolbar>
      </AppBar>
    )
  }
}

const styles = theme => ({
  ...theme,
  root: {
    flexGrow: 1
  },
  flex: {
    flex: 1
  },
  leftButton: {
    marginLeft: -10,
    marginRight: 20
  },
  rightButton: {
    marginRight: -10,
    marginLeft: 20
  },
  link: {
    textDecoration: 'none',
    outline: 'none'
  },
  divider: {
    marginTop: 10,
    marginBottom: 10
  },
  visit: {
    color: '#3f51b5'
  },
  logout: {
    color: '#e91e63'
  }
})

AppMenu.propTypes = {
  classes: PropTypes.object.isRequired
}

const mapStateToProps = state => {
  return {
    username: state.username
  }
}

AppMenu.defaultProps = {
  locales: {},
  locale: 'fr'
}

export default compose(withStyles(styles), connect(mapStateToProps))(AppMenu)
