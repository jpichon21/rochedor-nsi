import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { doForgottenPassword, initStatus } from '../../actions'
import { TextField, Button, Typography, Snackbar } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Redirect } from 'react-router-dom'
import Alert from '../alert/alert'
import I18n from '../../../i18n'

export class ForgottenPassword extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      email: '',
      alertOpen: false,
      submitted: false,
    }

    this.handleClose = this.handleClose.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleInputChange = this.handleInputChange.bind(this)
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
    this.props.dispatch(doForgottenPassword(this.state.email)).then((res) => {
      if (res.error) {
        this.setState({ error: this.i18n.trans(res.error) })
        this.setState({ alertOpen: true })
        return
      }
      // console.log(this.props);
      this.setState({ submitted: true })
      setTimeout(() => this.props.history.push('/'), 2000)
    })
    event.preventDefault()
  }

  handleInputChange (event) {
    this.setState({ [event.target.name]: event.target.value })
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    return (
      <div>
        <div className={classes.container}>
          {this.state.submitted && <Snackbar
            open
            autoHideDuration={4000}
            ContentProps={{
              'aria-describedby': 'snackbar-fab-message-id'
            }}
            message={<span id='snackbar-fab-message-id'>Email envoyé</span>}
          />}
          {this.props.isLoggedIn && <Redirect to={'/'} />}
          <Alert open={this.state.alertOpen} content={this.state.error} onClose={this.handleClose} />
          <div className={classes.container}>
            <Typography variant='display1' className={classes.title}>
              Mot de passe oublié
            </Typography>
            <Typography component='p' style={{'margin-bottom': '30px'}}>
              Pour réinitialiser votre mot de passe, contacter l'un ou l'autre des administrateurs suivants : Claire MESLOT, Danièle VALÈS, Hugues CHAPELLE.
            </Typography>
            <NavLink to='/login' style={{textDecoration: 'none'}}>
              <Button type={'submit'} color='secondary' style={{float: 'left'}}>Retour</Button>
            </NavLink>
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

ForgottenPassword.propTypes = {
  classes: PropTypes.object.isRequired
}

export default compose(withStyles(styles), connect(mapStateToProps))(ForgottenPassword)
