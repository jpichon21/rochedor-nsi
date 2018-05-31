import React from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { withStyles } from '@material-ui/core/styles'
import AppBar from '@material-ui/core/AppBar'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import IconButton from '@material-ui/core/IconButton'
import MenuIcon from '@material-ui/icons/Menu'
import { Menu, MenuItem } from '@material-ui/core'
import { NavLink } from 'react-router-dom'

class AppMenu extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      anchorEl: null,
      open: false,
      auth: true
    }
    this.handleChange = this.handleChange.bind(this)
    this.handleMenu = this.handleMenu.bind(this)
    this.handleMenu = this.handleMenu.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }
  handleChange (event, checked) {
    this.setState({ auth: checked })
  }

  handleMenu (event) {
    this.setState({ anchorEl: event.currentTarget })
  }

  handleClose () {
    this.setState({ anchorEl: null })
  }

  render () {
    const { classes } = this.props
    const { anchorEl } = this.state
    const open = Boolean(anchorEl)
    return (
      <AppBar position='static'>
        <Toolbar>
          <IconButton
            className={classes.menuButton}
            aria-owns={open ? 'menu-appbar' : null}
            aria-haspopup='true'
            onClick={this.handleMenu}
            color='inherit'>
            <MenuIcon />
          </IconButton>
          <Typography variant='title' color='inherit' className={classes.flex}>
            { this.props.title }
          </Typography>
          <Menu
            id='menu-appbar'
            anchorEl={anchorEl}
            anchorOrigin={{
              vertical: 'top',
              horizontal: 'right'
            }}
            transformOrigin={{
              vertical: 'top',
              horizontal: 'right'
            }}
            open={open}
            onClose={this.handleClose}>
            <NavLink to='/page-list' className={classes.link}>
              <MenuItem onClick={this.handleClose}>Pages</MenuItem>
            </NavLink>
            <NavLink to='' className={classes.link}>
              <MenuItem onClick={this.handleClose}>
                Page d'accueil
              </MenuItem>
            </NavLink>
            <NavLink to='' className={classes.link}>
              <MenuItem onClick={this.handleClose}>
                Nouveautés
              </MenuItem>
            </NavLink>
            <NavLink to='' className={classes.link}>
              <MenuItem onClick={this.handleClose}>
                Intervenants
              </MenuItem>
            </NavLink>
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
  menuButton: {
    marginLeft: -12,
    marginRight: 20
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
    loading: state.loading,
    title: state.title,
  }
}

export default connect(mapStateToProps)(withStyles(styles)(AppMenu))
