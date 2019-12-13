import React, { Fragment } from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import immutable from 'object-path-immutable'
import moment from 'moment'
import { Snackbar, Menu, MenuItem, TextField, Button, Tooltip, Typography, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle, Grid, Icon, CircularProgress, IconButton } from '@material-ui/core'
import SaveIcon from '@material-ui/icons/Save'
import DeleteIcon from '@material-ui/icons/Delete'
import PhotoSizeSelectActualIcon from '@material-ui/icons/PhotoSizeSelectActual'
import { withStyles } from '@material-ui/core/styles'
import { getSpeaker, getSpeakerVersions, deleteSpeaker, putSpeaker, postSpeaker, uploadFile } from '../../actions'
import Alert from '../alert/alert'
import IsAuthorized, { ACTION_SPEAKER_EDIT, ACTION_SPEAKER_CREATE, ACTION_SPEAKER_DELETE } from '../../isauthorized/isauthorized'

export class SpeakerForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      speaker: {
        name: '',
        title: { fr: '', en: '', es: '', de: '', it: '' },
        description: { fr: '', en: '', es: '', de: '', it: '' },
        image: 'http://via.placeholder.com/340x200'
      },
      alertOpen: false,
      status: '',
      fileUploading: false,
      versionCount: 0,
      showDeleteAlert: false,
      versions: [],
      anchorVersion: null
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleCloseVersion = this.handleCloseVersion.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleVersionOpen = this.handleVersionOpen.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleChangeFileUpload = this.handleChangeFileUpload.bind(this)
    this.handleCloseSnack = this.handleCloseSnack.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }
  handleVersion (event, key) {
    event.preventDefault()
    this.setState({ versionCount: key, anchorVersion: null })
    this.props.dispatch(getSpeaker(this.state.speaker.id, this.state.versions[key].version)).then((speaker) => {
      this.setState({ speaker: { ...speaker } })
    })
    if (key === null) {
      this.props.versionHandler(null)
    } else {
      this.props.versionHandler(this.props.versions[key].version)
    }
  }

  handleCloseVersion () {
    this.setState({ anchorVersion: null })
  }

  handleVersionOpen (event) {
    this.setState({ anchorVersion: event.currentTarget })
  }

  handleInputChange (event) {
    const state = immutable.set(this.state, event.target.name, event.target.value)
    this.setState(state)
  }

  handleSubmit (event) {
    event.preventDefault()
    if (this.props.edit) {
      this.props.dispatch(putSpeaker(this.state.speaker)).then((res) => {
        if (res.message === 'Speaker Updated') {
          this.setState({ snackbarContent: 'Intervenant enregistré et publié', snackbarOpen: true })
          this.props.dispatch(getSpeakerVersions(this.props.speakerId)).then((versions) => {
            this.setState({ versions: { ...versions } })
          })
        } else {
          this.setState({
            status: res.message,
            alertOpen: true
          })
        }
      })
    } else {
      this.props.dispatch(postSpeaker(this.state.speaker)).then((res) => {
        if (res.id) {
          this.props.history.push(`/speaker-edit/${res.id}`)
        } else {
          this.setState({
            status: res.message,
            alertOpen: true
          })
        }
      })
    }
  }

  handleDelete () {
    this.setState({ showDeleteAlert: true })
  }
  handleDeleteClose () {
    this.setState({ showDeleteAlert: false })
  }

  handleDeleteConfirm () {
    this.setState({ showDeleteAlert: false })
    this.props.dispatch(deleteSpeaker(this.state.speaker.id)).then((res) => {
      this.props.history.push('/speaker-list')
    })
  }

  handleCloseSnack () {
    this.setState({ snackbarOpen: false, snackbarContent: '' })
  }

  handleClose () {
    this.setState({ status: '', alertOpen: false })
  }

  handleChangeFileUpload (event) {
    this.setState({
      fileUploading: true
    })
    this.props.dispatch(uploadFile(event.target.files[0])).then((res) => {
      this.setState((prevState) => {
        return {
          fileUploading: false,
          speaker: {
            ...prevState.speaker,
            image: res.path
          }
        }
      })
    })
  }

  isSubmitEnabled () {
    return (this.state.speaker.name !== '' && this.state.speaker.title !== '' && this.state.speaker.image !== '')
  }
  componentWillMount () {
    if (this.props.edit) {
      this.props.dispatch(getSpeaker(this.props.speakerId)).then((speaker) => {
        this.setState({ speaker: { ...speaker } })
        this.props.dispatch(getSpeakerVersions(this.props.speakerId)).then((versions) => {
          this.setState({ versions: { ...versions } })
        })
      })
    }
  }
  componentWillReceiveProps (nextProps) {
    if (nextProps.uploadStatus) {
      if (nextProps.uploadStatus === 'File too big') {
        this.setState({ status: nextProps.uploadStatus, alertOpen: true })
      }
    }
  }

  render () {
    const { classes } = this.props
    return (
      <div className={classes.container}>
        <Typography variant='display1' className={classes.title}>
          Intervenant
        </Typography>
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
        <form className={classes.form}>
          <Grid container spacing={32}>
            <Grid item xs={6}>
              <Tooltip
                enterDelay={300}
                id='tooltip-controlled'
                leaveDelay={100}
                onClose={this.handleTooltipClose}
                onOpen={this.handleTooltipOpen}
                open={this.state.open}
                placement='bottom'
                title="Renseigner le nom de l'intervenant"
              >
                <TextField
                  required
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  className={classes.textfield}
                  fullWidth
                  name='speaker.name'
                  label='Nom'
                  value={this.state.speaker.name}
                  onChange={this.handleInputChange} />
              </Tooltip>
              <Tooltip
                enterDelay={300}
                id='tooltip-controlled'
                leaveDelay={100}
                onClose={this.handleTooltipClose}
                onOpen={this.handleTooltipOpen}
                open={this.state.open}
                placement='bottom'
                title="Renseigner le titre de l'intervenant"
              >
                <TextField
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  className={classes.textfield}
                  fullWidth
                  name={`speaker.title.${this.props.locale}`}
                  label='Titre'
                  value={this.state.speaker.title[this.props.locale]}
                  onChange={this.handleInputChange} />
              </Tooltip>
              <Tooltip
                enterDelay={300}
                id='tooltip-controlled'
                leaveDelay={100}
                onClose={this.handleTooltipClose}
                onOpen={this.handleTooltipOpen}
                open={this.state.open}
                placement='bottom'
                title="Renseigner la description de l'intervenant"
              >
                <TextField
                  autoComplete='off'
                  InputLabelProps={{ shrink: true }}
                  className={classes.textfield}
                  fullWidth
                  multiline
                  rows='4'
                  name={`speaker.description.${this.props.locale}`}
                  label='Description'
                  value={this.state.speaker.description[this.props.locale]}
                  onChange={this.handleInputChange}
                  onKeyPress={this.handleInputFilter} />
              </Tooltip>
            </Grid>
            <Grid item xs={6}>
              <div className={classes.tile} style={{ backgroundImage: `url('${this.state.speaker.image}')` }}>
                {
                  this.state.fileUploading.isUploading
                    ? <CircularProgress />
                    : (
                      <div>
                        <IconButton
                          color='primary'>
                          <PhotoSizeSelectActualIcon />
                          <Tooltip
                            enterDelay={300}
                            id='tooltip-controlled'
                            leaveDelay={100}
                            onClose={this.handleTooltipClose}
                            onOpen={this.handleTooltipOpen}
                            open={this.state.open}
                            placement='bottom'
                            title='Assigner une image'
                          >
                            <input
                              type='file'
                              className={classes.inputfile}
                              onChange={this.handleChangeFileUpload} />
                          </Tooltip>
                        </IconButton>
                      </div>
                    )
                }
              </div>

            </Grid>
          </Grid>
        </form>
        <div className={classes.buttons}>
          {
            this.props.edit &&
            (
              <Fragment>
                <Tooltip
                  enterDelay={300}
                  id='tooltip-controlled'
                  leaveDelay={300}
                  onClose={this.handleTooltipClose}
                  onOpen={this.handleTooltipOpen}
                  open={this.state.open}
                  placement='bottom'
                  title='Revenir à une version antérieur'
                >
                  <Button
                    className={classes.button}
                    variant='fab'
                    color='primary'
                    aria-label='More'
                    aria-owns={this.state.anchorVersion ? 'long-menu' : null}
                    aria-haspopup='true'
                    onClick={this.handleVersionOpen}
                  >
                    <Icon>history</Icon>
                  </Button>
                </Tooltip>
                <Menu
                  id='long-menu'
                  anchorEl={this.state.anchorVersion}
                  open={Boolean(this.state.anchorVersion)}
                  onClose={this.handleCloseVersion}
                  PaperProps={{
                    style: {
                      maxHeight: 40 * 4.5,
                      width: 200
                    }
                  }}
                >
                  <MenuItem key={null} selected={this.state.versionCount === null} onClick={event => this.handleVersion(event, null)}>Courante</MenuItem>
                  {Object.keys(this.state.versions).map((key) => (
                    <MenuItem key={key} selected={key === this.state.versionCount} onClick={event => this.handleVersion(event, key)}>
                      {moment(this.state.versions[key].logged_at).format('DD/MM/YYYY HH:mm:ss')}
                    </MenuItem>
                  ))}
                    }
                </Menu>
              </Fragment>
            )
          }
          {
            (this.props.edit)
              ? (
                <div>
                  <IsAuthorized action={ACTION_SPEAKER_DELETE}>
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
                  </IsAuthorized>
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
          <IsAuthorized action={[ACTION_SPEAKER_EDIT, ACTION_SPEAKER_CREATE]}>
            <Tooltip
              enterDelay={300}
              id='tooltip-controlled'
              leaveDelay={300}
              onClose={this.handleTooltipClose}
              onOpen={this.handleTooltipOpen}
              open={this.state.open}
              placement='bottom'
              title='Publier'
            >
              <Button
                disabled={!this.isSubmitEnabled()}
                onClick={this.handleSubmit}
                className={classes.button}
                variant='fab'
                color='primary'>
                <SaveIcon />
              </Button>
            </Tooltip>
          </IsAuthorized>
        </div>
        <Alert open={this.state.alertOpen} content={this.state.status} onClose={this.handleClose} />
      </div>
    )
  }
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
    status: state.postSpeakerStatus,
    locale: state.locale,
    uploadStatus: state.status
  }
}

SpeakerForm.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(SpeakerForm))
