import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Link, withRouter } from 'react-router-dom'
import immutable from 'object-path-immutable'
import moment from 'moment'
import { MenuItem, TextField, Button, Typography, Select, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle, Grid, InputLabel, FormControl, Icon, CircularProgress, IconButton } from '@material-ui/core'
import SaveIcon from '@material-ui/icons/Save'
import DeleteIcon from '@material-ui/icons/Delete'
import PhotoSizeSelectActualIcon from '@material-ui/icons/PhotoSizeSelectActual'
import { withStyles } from '@material-ui/core/styles'
import { getSpeaker, getSpeakerVersions, deleteSpeaker, putSpeaker, postSpeaker, uploadFile } from '../../actions'
import Alert from '../alert/alert'

export class SpeakerForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      speaker: {
        name: '',
        title: {fr: '', en: '', es: '', de: '', it: ''},
        description: { fr: '', en: '', es: '', de: '', it: '' },
        image: 'http://via.placeholder.com/340x200',
        alertOpen: false,
        status: ''
      },
      fileUploading: false,
      versionCount: 0,
      showDeleteAlert: false,
      versions: []
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleChangeFileUpload = this.handleChangeFileUpload.bind(this)
  }

  handleVersion (event) {
    this.setState({ versionCount: event.target.value })
    this.props.dispatch(getSpeaker(this.state.speaker.id, this.state.versions[event.target.value].version)).then((speaker) => {
      this.setState({ speaker: {...speaker} })
    })
    event.preventDefault()
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
          this.props.history.push('/speaker-list')
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
          this.props.history.push('/speaker-list')
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

  handleChangeFileUpload (event) {
    this.setState({
      fileUploading: true
    })
    this.props.dispatch(uploadFile(event.target.files[0])).then((res) => {
      this.setState((prevState) => {
        return {
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

  render () {
    const { classes } = this.props
    const versions = Object.keys(this.state.versions).map((k) => {
      return (
        <MenuItem value={k} key={this.state.versions[k].id}>{moment(this.state.versions[k].logged_at).format('DD/MM/YYYY HH:mm')}</MenuItem>
      )
    })
    return (
      <div className={classes.container}>
        <Typography variant='display1' className={classes.title}>
          Intervenant
        </Typography>
        <form className={classes.form}>
          <Grid container spacing={32}>
            <Grid item xs={6}>
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
              <TextField
                autoComplete='off'
                InputLabelProps={{ shrink: true }}
                className={classes.textfield}
                fullWidth
                name={`speaker.title.${this.props.locale}`}
                label='Titre'
                value={this.state.speaker.title[this.props.locale]}
                onChange={this.handleInputChange} />
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
            </Grid>
            <Grid item xs={6}>
              <div className={classes.tile} style={{backgroundImage: `url('${this.state.speaker.image}')`}}>
                {
                  this.state.fileUploading.isUploading
                    ? <CircularProgress />
                    : (
                      <div>
                        <IconButton
                          color='primary'>
                          <PhotoSizeSelectActualIcon />
                          <input
                            type='file'
                            className={classes.inputfile}
                            onChange={this.handleChangeFileUpload} />
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
              <FormControl>
                <InputLabel htmlFor='history-select'>Historique</InputLabel>
                <Select
                  id={'history-select'}
                  label={'historique'}
                  className={classes.option}
                  value={this.state.versionCount}
                  onChange={this.handleVersion}
                  inputProps={{
                    name: 'historique',
                    id: 'version'
                  }}>
                  {versions}
                </Select>
              </FormControl>
            )
          }
          <Button component={Link} to={'/speaker-list'}
            className={classes.button}
            variant='fab'
            color='primary'>
            <Icon>arrow_left</Icon>
          </Button>
          {
            (this.props.edit)
              ? (
                <div>
                  <Button
                    onClick={this.handleDelete}
                    className={classes.button}
                    variant='fab'
                    color='secondary'>
                    <DeleteIcon />
                  </Button>
                  <Dialog
                    open={this.state.showDeleteAlert}
                    onClose={this.handleDeleteClose}
                    aria-labelledby='alert-dialog-title'
                    aria-describedby='alert-dialog-description'>
                    <DialogTitle id='alert-dialog-title'>
                      {'Êtes-vous sure?'}
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

          <Button
            disabled={!this.isSubmitEnabled()}
            onClick={this.handleSubmit}
            className={classes.button}
            variant='fab'
            color='secondary'>
            <SaveIcon />
          </Button>
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
  },

})

const mapStateToProps = state => {
  return {
    status: state.postSpeakerStatus,
    locale: state.locale
  }
}

SpeakerForm.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(SpeakerForm))
