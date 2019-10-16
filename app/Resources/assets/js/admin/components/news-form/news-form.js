import React, { Fragment } from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import update from 'immutability-helper'
import { Menu, MenuItem, TextField, Button, Tooltip, Typography, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle, Icon } from '@material-ui/core'
import MomentUtils from 'material-ui-pickers/utils/moment-utils'
import moment from 'moment'
import 'moment/locale/fr'
import { MuiPickersUtilsProvider } from 'material-ui-pickers'
import DateTimePicker from 'material-ui-pickers/DateTimePicker'
import SaveIcon from '@material-ui/icons/Save'
import DeleteIcon from '@material-ui/icons/Delete'
import { withStyles } from '@material-ui/core/styles'
import IsAuthorized, { ACTION_NEWS_DELETE } from '../../isauthorized/isauthorized'

moment.locale('fr')

export class NewsForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: this.props.lang,
      news: this.props.news,
      versionCount: 0,
      showDeleteAlert: false,
      anchorVersion: null
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleStartChange = this.handleStartChange.bind(this)
    this.handleStopChange = this.handleStopChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleCloseVersion = this.handleCloseVersion.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleVersionOpen = this.handleVersionOpen.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
  }

  handleVersion (event, key) {
    event.preventDefault()
    if (key === null) {
      this.props.versionHandler(this.state.news, null)
    } else {
      this.props.versionHandler(this.state.news, this.props.versions[key].version)
    }
    this.setState({ versionCount: key, anchorVersion: null })
  }

  handleCloseVersion () {
    this.setState({ anchorVersion: null })
  }

  handleVersionOpen (event) {
    this.setState({ anchorVersion: event.currentTarget })
  }

  handleInputChange (event) {
    const value = event.target.value
    const name = event.target.name
    this.setState(prevState => {
      return {
        news: {
          ...prevState.news,
          [name]: value
        }
      }
    })
  }

  handleStartChange (date) {
    this.setState(prevState => {
      return {
        news: {
          ...prevState.news,
          start: date
        }
      }
    })
  }
  handleStopChange (date) {
    this.setState(prevState => {
      return {
        news: {
          ...prevState.news,
          stop: date
        }
      }
    })
  }

  handleSubmit (event) {
    event.preventDefault()
    let n = this.state.news
    this.props.submitHandler(update(n, {
      start: { $set: moment(n.start).format() },
      stop: { $set: moment(n.stop).format() }
    }))
  }

  handleDelete () {
    this.setState({ showDeleteAlert: true })
  }
  handleDeleteClose () {
    this.setState({ showDeleteAlert: false })
  }

  handleDeleteConfirm () {
    this.props.deleteHandler(this.state.news)
    this.setState({ showDeleteAlert: false })
  }

  isSubmitEnabled () {
    return (this.state.news.intro !== '')
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.news) {
      this.setState({ news: nextProps.news })
    }
    if (nextProps.locale) {
      this.setState((prevState) => {
        return {
          news: {
            ...prevState.news,
            locale: nextProps.locale,
            parent_id: null
          }
        }
      })
    }
  }
  render () {
    const { classes } = this.props
    const versions = this.props.versions
    return (
      <div className={classes.container}>
        <Typography variant='display1' className={classes.title}>
          Nouveauté
        </Typography>
        <form className={classes.form}>
          <Tooltip
            enterDelay={300}
            id='tooltip-controlled'
            leaveDelay={100}
            onClose={this.handleTooltipClose}
            onOpen={this.handleTooltipOpen}
            open={this.state.open}
            placement='bottom'
            title="Renseigner le texte d'introduction de la nouveauté"
          >
            <TextField
              required
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              name='intro'
              label='Introduction'
              value={this.state.news.intro}
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
            title='Renseigner la description de la nouveauté'
          >
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              name='description'
              label='Description'
              value={this.state.news.description}
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
            title='Renseigner le lien de la nouveauté'
          >
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              name='url'
              label='Lien'
              value={this.state.news.url}
              onChange={this.handleInputChange}
              onKeyPress={this.handleInputFilter} />
          </Tooltip>
          <MuiPickersUtilsProvider utils={MomentUtils} moment={moment} locale='fr' label='Début'>
            <Tooltip
              enterDelay={300}
              id='tooltip-controlled'
              leaveDelay={100}
              onClose={this.handleTooltipClose}
              onOpen={this.handleTooltipOpen}
              open={this.state.open}
              placement='bottom'
              title="Date de début d'affichage"
            >
              <DateTimePicker
                name='start'
                value={this.state.news.start}
                onChange={this.handleStartChange}
                cancelLabel='Annuler'
                format={'DD/MM/YYYY HH:mm'}
                ampm={false}
              />
            </Tooltip>
          </MuiPickersUtilsProvider>
          <MuiPickersUtilsProvider utils={MomentUtils} moment={moment} locale='fr' label='Fin'>
            <Tooltip
              enterDelay={300}
              id='tooltip-controlled'
              leaveDelay={100}
              onClose={this.handleTooltipClose}
              onOpen={this.handleTooltipOpen}
              open={this.state.open}
              placement='bottom'
              title="Date de fin d'affichage"
            >
              <DateTimePicker
                name='stop'
                value={this.state.news.stop}
                onChange={this.handleStopChange}
                format={'DD/MM/YYYY HH:mm'}
                cancelLabel='Annuler'
                ampm={false}
              />
            </Tooltip>
          </MuiPickersUtilsProvider>
        </form>
        <div className={classes.buttons}>
          {
            (this.props.edit) &&
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
                  {Object.keys(versions).map((key) => (
                    <MenuItem key={key} selected={key === this.state.versionCount} onClick={event => this.handleVersion(event, key)}>
                      {moment(versions[key].logged_at).format('DD/MM/YYYY HH:mm:ss')}
                    </MenuItem>
                  ))}
                }
                </Menu>
                <IsAuthorized action={ACTION_NEWS_DELETE}>
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
              </Fragment>
            )
          }
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
        </div>
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
  }
})

const mapStateToProps = state => {
  return {
    status: state.postNewsStatus,
    versions: state.newsVersions,
    version: state.newsVersion,
    locale: state.locale
  }
}

NewsForm.propTypes = {
  classes: PropTypes.object.isRequired
}

NewsForm.defaultProps = {
  news: {
    locale: 'fr',
    intro: '',
    description: '',
    url: '',
    start: new Date(),
    stop: new Date()
  },
  versions: {}
}

export default compose(withStyles(styles), connect(mapStateToProps))(NewsForm)
