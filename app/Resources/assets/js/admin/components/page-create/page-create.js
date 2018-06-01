import React from 'react'
import { connect } from 'react-redux'
import { Redirect } from 'react-router-dom'
import { Button, DialogActions, Dialog, DialogContent, DialogContentText, DialogTitle, Icon } from '@material-ui/core'
import { postPage, initStatus, setMessage, setLocale } from '../../actions'
import PageForm from '../page-form/page-form'
import AppMenu from '../app-menu/app-menu'
import { t } from '../../translations'
import { locales } from '../../locales'

export class PageCreate extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      page: {
        title: '',
        sub_title: '',
        url: '',
        description: '',
        locale: 'fr'
      },
      alertOpen: false
    }
    this.handleClose = this.handleClose.bind(this)
    this.onSubmit = this.onSubmit.bind(this)
    this.onLocaleChange = this.onLocaleChange.bind(this)
  }
  handleClose () {
    this.setState({alertOpen: false})
  }
  componentWillReceiveProps (nextProps) {
    this.setState({ alertOpen: (nextProps.status !== 'ok' && nextProps.status !== null) })
  }
  onSubmit (page) {
    this.props.dispatch(postPage(page))
  }
  componentWillMount () {
    this.props.dispatch(initStatus())
  }
  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
  }
  render () {
    if (this.props.status === 'ok') {
      this.props.dispatch(setMessage('Page créee'))
      this.props.dispatch(initStatus())
      return <Redirect to='/page-list' />
    }
    return (
      <div>
        <Dialog
          open={this.state.alertOpen || false}
          onClose={this.handleClose}
          aria-labelledby='alert-dialog-title'
          aria-describedby='alert-dialog-description'>
          <DialogTitle id='alert-dialog-title'>
            <Icon color='error'>error</Icon>
            {'Une erreur est survenue'}
          </DialogTitle>
          <DialogContent>
            <DialogContentText id='alert-dialog-description'>
              {t.t(this.props.status)}
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button onClick={this.handleClose} color='primary' autoFocus>
              Ok
            </Button>
          </DialogActions>
        </Dialog>
        <AppMenu title={'Création de page'} localeHandler={this.onLocaleChange} locales={locales} />
        <PageForm submitHandler={this.onSubmit} />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    status: state.status
  }
}

export default connect(mapStateToProps)(PageCreate)
