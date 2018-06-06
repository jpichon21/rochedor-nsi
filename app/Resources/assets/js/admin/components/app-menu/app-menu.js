import React from 'react'
import { connect } from 'react-redux'
import { compose } from 'redux'
import { NavLink, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types'
import { withStyles } from '@material-ui/core/styles'
import { Menu, MenuItem, AppBar, Toolbar, Typography, IconButton, Button } from '@material-ui/core'
import MenuIcon from '@material-ui/icons/Menu'
import ArrowBackIcon from '@material-ui/icons/ArrowBack'

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
    return (
      <AppBar position='static'>
        <Toolbar>
          {
            this.props.goBack
              ? (
                <IconButton
                  className={classes.leftButton}
                  onClick={this.handleGoBack}
                  color='inherit'>
                  <ArrowBackIcon />
                </IconButton>
              )
              : (
                <IconButton
                  className={classes.leftButton}
                  aria-owns={menuOpen ? 'menu-appbar' : null}
                  aria-haspopup='true'
                  onClick={this.handleMenu}
                  color='inherit'>
                  <MenuIcon />
                </IconButton>
              )
          }
          <Typography variant='title' color='inherit' className={classes.flex}>
            { this.props.title }
          </Typography>
          <Button
            className={classes.rightButton}
            aria-owns={langOpen ? 'lang-appbar' : null}
            aria-haspopup='true'
            onClick={this.handleLang}
            color='inherit'>
            {this.props.locales[this.props.locale]}
          </Button>
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
            <NavLink to='/page-list' className={classes.link}>
              <MenuItem onClick={this.handleCloseMenu}>Pages</MenuItem>
            </NavLink>
            <NavLink to='/home-edit' className={classes.link}>
              <MenuItem onClick={this.handleCloseMenu}>
                Page d'accueil
              </MenuItem>
            </NavLink>
            <NavLink to='/news-list' className={classes.link}>
              <MenuItem onClick={this.handleCloseMenu}>
                Nouveautés
              </MenuItem>
            </NavLink>
            <NavLink to='/speaker-list' className={classes.link}>
              <MenuItem onClick={this.handleCloseMenu}>
                Intervenants
              </MenuItem>
            </NavLink>
            {
              !!this.props.username
              ? (

                <NavLink to='/logout' className={classes.link}>
                  <MenuItem onClick={this.handleCloseMenu}>
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
                selected={key === this.state.selectedIndex}
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
