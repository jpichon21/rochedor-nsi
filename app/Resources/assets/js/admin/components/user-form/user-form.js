import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import immutable from 'object-path-immutable'
import {
  Snackbar,
  TextField,
  Button,
  Tooltip,
  Typography,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  Switch,
  FormControlLabel,
  AppBar,
  Tabs,
  Tab,
  Table,
  TableHead,
  TableCell,
  TableBody,
  TableRow,
  Checkbox,
  MenuItem, Select, InputLabel, FormControl
} from '@material-ui/core'
import SaveIcon from '@material-ui/icons/Save'
import DeleteIcon from '@material-ui/icons/Delete'
import { withStyles } from '@material-ui/core/styles'
import { getUser, deleteUser, putUser, postUser } from '../../actions'
import Alert from '../alert/alert'
import I18n from '../../../i18n'
import { ROLE_ADMIN_ASSOCIATION, ROLE_ADMIN_EDITION, ROLE_SUPER_ADMIN } from '../../isauthorized/isauthorized'

const TooltipWrapper = ({children, title}) => (
  <Tooltip
    enterDelay={300}
    id='tooltip-controlled'
    leaveDelay={100}
    placement='bottom'
    title={title}
  >
    {children}
  </Tooltip>
)

export class UserForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      user: {
        roles: ['ROLE_ADMIN'],
        name: '',
        username: '',
        email: '',
        password: '',
        active: false
      },
      password: '',
      repeat: '',
      alertOpen: false,
      status: '',
      showDeleteAlert: false,
      currentTab: 0,
      inputPasswordVisible: false,
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleCloseSnack = this.handleCloseSnack.bind(this)
    this.handleClose = this.handleClose.bind(this)
    this.handleChangeRole = this.handleChangeRole.bind(this)
    this.hasPasswordFieldError = this.hasPasswordFieldError.bind(this)
    this.togglePasswordVisibility = this.togglePasswordVisibility.bind(this)

    this.i18n = new I18n('fr')
  }

  handleInputChange (event) {
    let value
    if (event.target.value.includes('check')) {
      value = event.target.checked
    } else {
      value = event.target.value
    }
    const state = immutable.set(this.state, event.target.name, value)
    this.setState(state)
  }

  handleSubmit (event) {
    event.preventDefault()
    const state = immutable.set(this.state, 'user.password', this.state.password)
    this.setState(state, () => {
      if (this.props.edit) {
        this.props.dispatch(putUser(this.state.user)).then((res) => {
          if (res.message === 'User Updated') {
            this.setState({snackbarContent: 'Utilisateur enregistré', snackbarOpen: true})
          } else {
            this.setState({
              status: this.i18n.trans(res.message),
              alertOpen: true
            })
          }
        })
      } else {
        this.props.dispatch(postUser(this.state.user)).then((res) => {
          if (res.id) {
            this.props.history.push(`/user-edit/${res.id}`)
          } else {
            this.setState({
              status: this.i18n.trans(res.message),
              alertOpen: true
            })
          }
        })
      }
    })
  }

  handleDelete () {
    this.setState({ showDeleteAlert: true })
  }
  handleDeleteClose () {
    this.setState({ showDeleteAlert: false })
  }

  handleDeleteConfirm () {
    this.setState({ showDeleteAlert: false })
    this.props.dispatch(deleteUser(this.state.user.id)).then((res) => {
      this.props.history.push('/user-list')
    })
  }

  handleCloseSnack () {
    this.setState({snackbarOpen: false, snackbarContent: ''})
  }

  handleClose () {
    this.setState({status: '', alertOpen: false})
  }

  handleChangeRole (value) {
    const state = immutable.set(this.state, 'user.roles', [value])
    this.setState(state)
  }

  hasPasswordFieldError () {
    if (this.state.password !== this.state.repeat) {
      return true
    }

    return this.state.password !== '' && this.state.password.length < 8
  }

  isSubmitEnabled () {
    if (this.state.password !== '' && this.state.password.length < 8) {
      return false
    }
    if (!this.props.edit && this.state.password === '') {
      return false
    }
    return (
      this.state.user.name !== '' &&
      this.state.user.username !== '' &&
      this.state.user.email !== '' &&
      this.state.password === this.state.repeat
    )
  }
  componentDidMount () {
    if (this.props.edit) {
      this.props.dispatch(getUser(this.props.userId)).then((user) => {
        this.setState({ user: { ...user } })
      })
    }

    // trying to remove password autofill
    setTimeout(() => {
      document.getElementsByClassName('disable_password_autofill')[0].getElementsByTagName('input')[0].value = ''
    }, 800)
  }

  togglePasswordVisibility (event) {
    event.preventDefault()
    this.setState(({inputPasswordVisible}) => ({ inputPasswordVisible: !inputPasswordVisible }))
  }

  render () {
    const { classes } = this.props
    return (
      <div className={classes.container}>
        <Snackbar
          open={this.state.snackbarOpen}
          autoHideDuration={4000}
          onClose={this.handleCloseSnack}
          ContentProps={{
            'aria-describedby': 'snackbar-fab-message-id'
          }}
          message={<span id='snackbar-fab-message-id'>{this.state.snackbarContent}</span>}
          action={
            <Button color='inherit' size='small' onClick={this.handleCloseSnack}>
                Ok
            </Button>
          }
        />
        <Typography variant='display1' className={classes.title}>
          Utilisateur
        </Typography>
        <div>
          <form className={classes.form} onSubmit={this.handleSubmit}>
            <TooltipWrapper
              title={`Activer l'utilisateur`}
            >
              <FormControlLabel
                control={
                  <Switch
                    name='user.active'
                    label='Actif'
                    value='user.active.check'
                    checked={this.state.user.active}
                    onChange={this.handleInputChange}
                  />
                }
                label='Actif'
              />
            </TooltipWrapper>
            <TooltipWrapper
              title='Renseigner le nom'
            >
              <TextField
                required
                autoComplete='off'
                InputLabelProps={{ shrink: true }}
                className={classes.textfield}
                fullWidth
                name='user.name'
                label='Nom'
                value={this.state.user.name}
                onChange={this.handleInputChange} />
            </TooltipWrapper>
            <TooltipWrapper
              title={`Renseigner l'identifiant`}
            >
              <TextField
                autoComplete='off'
                InputLabelProps={{ shrink: true }}
                className={classes.textfield}
                fullWidth
                name='user.username'
                label='Identifiant'
                value={this.state.user.username}
                onChange={this.handleInputChange} />
            </TooltipWrapper>
            <TooltipWrapper
              title={`Renseigner l'adresse email`}
            >
              <TextField
                autoComplete='off'
                InputLabelProps={{ shrink: true }}
                className={classes.textfield}
                fullWidth
                name='user.email'
                label='Email'
                value={this.state.user.email}
                onChange={this.handleInputChange} />
            </TooltipWrapper>
            <TooltipWrapper
              title={`Renseigner le mot de passe (8 caratères minimum)`}
            >
              <div style={{position: 'relative'}}>
                <TextField
                  error={this.hasPasswordFieldError()}
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  className={`${classes.textfield} disable_password_autofill`}
                  fullWidth
                  type={this.state.inputPasswordVisible ? 'text' : 'password'}
                  name='password'
                  label='Mot de passe'
                  value={this.state.password}
                  onChange={this.handleInputChange} />
                <a className='toggle-password' style={togglePwdStyle} onClick={this.togglePasswordVisibility} />
              </div>
            </TooltipWrapper>
            <TooltipWrapper
              title={`Répéter le mot de passe`}
            >
              <div style={{position: 'relative'}}>
                <TextField
                  error={this.hasPasswordFieldError()}
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  className={classes.textfield}
                  fullWidth
                  type={this.state.inputPasswordVisible ? 'text' : 'password'}
                  name='repeat'
                  label='Confirmation'
                  value={this.state.repeat}
                  onChange={this.handleInputChange} />
                <a className='toggle-password' style={togglePwdStyle} onClick={this.togglePasswordVisibility} />
              </div>
            </TooltipWrapper>

            <TooltipWrapper
              title={`Role de l'utilisateur`}
            >
              <FormControl style={{ width: '100%', marginBottom: '30px' }}>
                <InputLabel htmlFor={'type'} shrink>Droits</InputLabel>
                <Select
                  id={'type'}
                  fullWidth
                  className={classes.select}
                  value={getMainRole(this.state.user.roles)}
                  onChange={e => this.handleChangeRole(e.target.value)}
                >
                  {[
                    {roleName: ROLE_SUPER_ADMIN, label: 'Admin'},
                    {roleName: ROLE_ADMIN_ASSOCIATION, label: 'Association'},
                    {roleName: ROLE_ADMIN_EDITION, label: 'Editions'}
                  ].map(({roleName, label}) => (
                    <MenuItem key={roleName} value={roleName}>{label}</MenuItem>
                  ))}
                </Select>
              </FormControl>
            </TooltipWrapper>
            <br />
            <br />
          </form>
        </div>
        <div className={classes.buttons}>
          {
            (this.props.edit)
              ? (
                <div>
                  <Tooltip
                    enterDelay={300}
                    id='tooltip-controlled'
                    leaveDelay={300}
                    onClose={this.handleTooltipClose}
                    onOpen={this.handleTooltipOpen}
                    open={this.state.open}
                    placement='bottom'
                    title='Supprimer'
                  >
                    <Button
                      onClick={this.handleDelete}
                      className={classes.button}
                      variant='fab'
                      color='secondary'>
                      <DeleteIcon />
                    </Button>
                  </Tooltip>
                  <Dialog
                    open={this.state.showDeleteAlert}
                    onClose={this.handleDeleteClose}
                    aria-labelledby='alert-dialog-title'
                    aria-describedby='alert-dialog-description'>
                    <DialogTitle id='alert-dialog-title'>
                      {'Êtes-vous sûr ?'}
                    </DialogTitle>
                    <DialogContent>
                      <DialogContentText id='alert-dialog-description'>
                    Cette action est irréversible, souhaitez-vous continuer?
                      </DialogContentText>
                    </DialogContent>
                    <DialogActions>
                      <Button onClick={this.handleDeleteConfirm} color='secondary' autoFocus>Oui</Button>
                      <Button onClick={this.handleDeleteClose} color='primary' autoFocus>Annuler</Button>
                    </DialogActions>
                  </Dialog>
                </div>
              )
              : (
                ''
              )
          }
          <TooltipWrapper
            title='Enregistrer'
          >
            <div>
              <Button
                disabled={!this.isSubmitEnabled()}
                onClick={this.handleSubmit}
                className={classes.button}
                variant='fab'
                color='primary'>
                <SaveIcon />
              </Button>
            </div>
          </TooltipWrapper>
        </div>
        <Alert open={this.state.alertOpen} content={this.state.status} onClose={this.handleClose} />
      </div>
    )
  }
}

const getMainRole = (roles) => {
  if (roles.includes(ROLE_SUPER_ADMIN)) {
    return ROLE_SUPER_ADMIN
  }
  if (roles.includes(ROLE_ADMIN_ASSOCIATION)) {
    return ROLE_ADMIN_ASSOCIATION
  }
  if (roles.includes(ROLE_ADMIN_EDITION)) {
    return ROLE_ADMIN_EDITION
  }
  return ''
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
  ...theme,
  paper: {
    padding: theme.myMarge
  },
  options: {
    marginTop: theme.myMarge,
    textAlign: 'center'
  },
  option: {
    marginRight: theme.myMarge / 3,
    marginLeft: theme.myMarge / 3
  },
  expansion: {
    marginBottom: theme.myMarge
  },
  image: {
    maxWidth: '100%',
    maxHeight: '100%'
  },
  tile: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    width: '100%',
    height: '100%',
    backgroundSize: 'cover',
    backgroundPosition: 'center',
    backgroundColor: '#eeeeee'
  },
  inputfile: {
    position: 'absolute',
    width: '100%',
    height: '100%',
    cursor: 'pointer',
    opacity: 0
  }

})

const mapStateToProps = state => {
  return {
    status: state.postUserStatus,
    locale: state.locale,
    uploadStatus: state.status
  }
}

UserForm.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(UserForm))
