import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { doLogin, initStatus } from '../../actions'
import { TextField, Button, Typography } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Redirect } from 'react-router-dom'
import Alert from '../alert/alert'
import I18n from '../../../i18n'

export class Login extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      username: '',
      password: '',
      error: '',
      alertOpen: false,
      inputPasswordVisible: false,
    }

    this.handleClose = this.handleClose.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleInputChange = this.handleInputChange.bind(this)
    this.togglePasswordVisibility = this.togglePasswordVisibility.bind(this)

    this.i18n = new I18n('fr')
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully') || nextProps.error) {
      this.setState({ alertOpen: true })
    }
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({ alertOpen: false })
    this.setState({ error: '' })
  }

  handleSubmit (event) {
    this.props.dispatch(doLogin(this.state.username, this.state.password)).then((res) => {
      if (res.error) {
        this.setState({ error: this.i18n.trans(res.error) })
        this.setState({ alertOpen: true })
      }
    })
    event.preventDefault()
  }

  handleInputChange (event) {
    this.setState({ [event.target.name]: event.target.value })
  }

  togglePasswordVisibility (event) {
    event.preventDefault()
    this.setState(({inputPasswordVisible}) => ({ inputPasswordVisible: !inputPasswordVisible }))
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    return (
      <div>
        <div className={classes.container}>
          {this.props.isLoggedIn && <Redirect to={'/'} />}
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
              <div style={{position: 'relative'}}>
                <TextField
                  type={this.state.inputPasswordVisible ? 'text' : 'password'}
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  className={classes.textfield}
                  fullWidth
                  name='password'
                  label='Entrer votre mot de passe'
                  placeholder='Mot de passe'
                  value={this.state.password}
                  onChange={this.handleInputChange} />
                <a className='toggle-password' style={togglePwdStyle} onClick={this.togglePasswordVisibility} />
              </div>
              <Button type={'submit'} color='primary'>Connexion</Button>
              <NavLink to='/mot-de-passe-oublie' style={{textDecoration: 'none'}}>
                <Button type={'submit'} color='secondary' style={{float: 'right'}}>Mot de passe oubli?? ?</Button>
              </NavLink>

            </form>
          </div>
        </div>
      </div>
    )
  }
}

const togglePwdStyle = {
  position: 'absolute',
  top: '15px',
  right: 0,

  padding: 0,
  margin: 0,
  backgroundColor: 'lightgrey',
  backgroundImage: "url('/assets/img/eye-regular.svg')",
  // backgroundSize: 'cover',
  backgroundSize: '18px',
  backgroundRepeat: 'no-repeat',
  backgroundPosition: '50%',
  height: '31px',
  border: 'none',
  width: '30px',
  cursor: 'pointer'
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
