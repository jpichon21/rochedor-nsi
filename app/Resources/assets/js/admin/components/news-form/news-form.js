import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Link } from 'react-router-dom'
import update from 'immutability-helper'
import { MenuItem, TextField, Button, Typography, Select, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle } from '@material-ui/core'
import MomentUtils from 'material-ui-pickers/utils/moment-utils'
import moment from 'moment'
import 'moment/locale/fr'
import MuiPickersUtilsProvider from 'material-ui-pickers/utils/MuiPickersUtilsProvider'
import DateTimePicker from 'material-ui-pickers/DateTimePicker'
import WrapTextIcon from '@material-ui/icons/WrapText'
import SaveIcon from '@material-ui/icons/Save'
import DeleteIcon from '@material-ui/icons/Delete'
import { withStyles } from '@material-ui/core/styles'

moment.locale('fr')

export class NewsForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: this.props.lang,
      news: this.props.news,
      versionCount: 0,
      showDeleteAlert: false
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleStartChange = this.handleStartChange.bind(this)
    this.handleStopChange = this.handleStopChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleParent = this.handleParent.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
  }

  handleVersion (event) {
    this.setState({ versionCount: event.target.value })
    this.props.versionHandler(this.state.news, this.props.versions[event.target.value].version)
    event.preventDefault()
  }

  handleParent (event) {
    const parentKey = event.target.value
    this.setState((prevState) => {
      return {
        news: {
          ...prevState.news,
          parent_id: this.props.parents[parentKey].id
        },
        parentKey: parentKey
      }
    })
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
      this.setState({news: nextProps.news})
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
    const versions = (this.props.versions.length > 0)
      ? this.props.versions.map((v, k) => {
        return (
          <MenuItem value={k} key={v.id}>{moment(v.logged_at).format('DD/MM/YYYY HH:mm')}</MenuItem>
        )
      })
      : null
    return (
      <div className={classes.container}>
        {
          this.props.edit
            ? (
              <Select
                className={classes.option}
                value={this.state.versionCount}
                onChange={this.handleVersion}
                inputProps={{
                  name: 'historique',
                  id: 'version'
                }}>
                {versions}
              </Select>
            )
            : (
              ''
            )
        }
        <Typography variant='display1' className={classes.title}>
          Nouveauté
        </Typography>
        <form className={classes.form}>
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
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='description'
            label='Description'
            value={this.state.news.description}
            onChange={this.handleInputChange} />
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
          <MuiPickersUtilsProvider utils={MomentUtils} moment={moment} locale='fr' label='Début'>
            <DateTimePicker
              name='start'
              value={this.state.news.start}
              onChange={this.handleStartChange}
              cancelLabel='Annuler'
              format={'DD/MM/YYYY HH:mm'}
              ampm={false}
              disablePast
            />
          </MuiPickersUtilsProvider>
          <MuiPickersUtilsProvider utils={MomentUtils} moment={moment} locale='fr' label='Fin'>
            <DateTimePicker
              name='stop'
              value={this.state.news.stop}
              onChange={this.handleStopChange}
              format={'DD/MM/YYYY HH:mm'}
              cancelLabel='Annuler'
              ampm={false}
              disablePast
            />
          </MuiPickersUtilsProvider>
        </form>
        <div className={classes.buttons}>
          <Button component={Link} to={'/news-list'}
            className={classes.button}
            variant='fab'
            color='primary'>
            <WrapTextIcon />
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