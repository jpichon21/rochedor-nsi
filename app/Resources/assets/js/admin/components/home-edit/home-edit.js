import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { getHome, putHome, setTitle, setLocale, initStatus, getHomeVersions } from '../../actions'
import HomeForm from '../home-form/home-form'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'
import update from 'immutability-helper'
import { Snackbar, Button } from '@material-ui/core'

export class HomeEdit extends React.Component {
  static defaultProps = {
    page: {
      id: null,
      title: '',
      sub_title: '',
      url: '',
      description: '',
      locale: 'fr'
    },
    locale: 'fr',
    status: ''
  }
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false,
      locales: locales,
      snackbarOpen: false,
      snackbarContent: ''
    }

    this.onSubmit = this.onSubmit.bind(this)
    this.onVersionChange = this.onVersionChange.bind(this)
    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
    this.handleCloseSnack = this.handleCloseSnack.bind(this)
  }
  componentDidMount () {
    this.props.dispatch(getHome(this.props.locale)).then((res) => {
      this.setState({home: res})
      this.props.dispatch(getHomeVersions(res.id))
    })
  }
  onSubmit (home) {
    this.props.dispatch(putHome(home)).then((res) => {
      this.setState({snackbarContent: 'Page enregistrée', snackbarOpen: true})
    })
  }
  onVersionChange (version) {
    this.props.dispatch(getHome(this.props.locale, version))
  }
  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
    this.props.dispatch(getHome(locale)).then((res) => {
      this.props.dispatch(getHomeVersions(res.id))
    })
  }
  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({alertOpen: false})
  }
  handleCloseSnack () {
    this.setState({snackbarOpen: false, snackbarContent: ''})
  }
  render () {
    return (
      <div>
        <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
        <AppMenu title={`Page d'accueil`} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
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
        <HomeForm home={this.props.home} submitHandler={this.onSubmit} versionHandler={this.onVersionChange} />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    locale: state.locale,
    loading: state.loading,
    home: state.home,
    status: state.status,
    error: state.error
  }
}

HomeEdit.defaultProps = {
  home: {
    locale: 'fr',
    title: '',
    sub_title: '',
    url: '',
    description: '',
    parent_id: null,
    content: {
      intro: '',
      sections: [
        {
          title: '',
          body: '',
          slides: [
            {
              layout: '1-1-2',
              images: [
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' }
              ]
            }
          ]
        }
      ]
    }
  }
}

export default withRouter(connect(mapStateToProps)(HomeEdit))
