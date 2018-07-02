import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { doLogin, initStatus } from '../../actions'
import { TextField, Paper, Button, Typography } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { Redirect } from 'react-router-dom'
import Alert from '../alert/alert'

export class Login extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      username: '',
      password: '',
      error: '',
      alertOpen: false
    }

    this.handleClose = this.handleClose.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleInputChange = this.handleInputChange.bind(this)
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully') || nextProps.error) {
      this.setState({alertOpen: true})
    }
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({alertOpen: false})
    this.setState({error: ''})
  }

  handleSubmit (event) {
    this.props.dispatch(doLogin(this.state.username, this.state.password)).then((res) => {
      if (res.error) {
        this.setState({error: 'Invalid credentials'})
        this.setState({alertOpen: true})
      }
    })
    event.preventDefault()
  }

  handleInputChange (event) {
    this.setState({[event.target.name]: event.target.value})
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    return (
      <div>
        <div className={classes.container}>
          {this.props.isLoggedIn && <Redirect to={'/content-list'} />}
          <Alert open={this.state.alertOpen} content={this.state.error} onClose={this.handleClose} />
          <div className={classes.container}>
            <Typography variant='display1' className={classes.title}>
              Connexion
            </Typography>
            <form onSubmit={this.handleSubmit} className={classes.form}>
              <TextField
                required
                autoComplete='off'
                InputLabelProps={{ shrink: true }}
                className={classes.textfield}
                fullWidth
                name='username'
                label='Entrer votre Identifiant'
                placeholder='Identifiant'
                value={this.state.username}
                onChange={this.handleInputChange} />
              <TextField
                type={'password'}
                autoComplete='off'
                InputLabelProps={{ shrink: true }}
                className={classes.textfield}
                fullWidth
                name='password'
                label='Entrer votre mot de passe'
                placeholder='Mot de passe'
                value={this.state.password}
                onChange={this.handleInputChange} />
              <Button type={'submit'} color='primary'>Connexion</Button>
            </form>
          </div>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    loading: state.loading,
    status: state.status,
    error: state.error,
    isLoggedIn: !!state.username
  }
}

Login.propTypes = {
  classes: PropTypes.object.isRequired
}

export default compose(withStyles(styles), connect(mapStateToProps))(Login)
