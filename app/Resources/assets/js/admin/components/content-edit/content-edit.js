import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { getContent, putContent, setTitle, setLocale, initStatus, getContentTranslations } from '../../actions'
import ContentForm from '../content-form/content-form'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'
import update from 'immutability-helper'
import { Snackbar, Button } from '@material-ui/core'

export class ContentEdit extends React.Component {
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
    const { match: { params } } = this.props
    this.props.dispatch(getContent(params.pageId))
    this.props.dispatch(getContentTranslations(params.pageId))
  }

  componentWillReceiveProps (nextProps) {
    this.props.dispatch(setTitle(`Modification d'un contenu`))
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully' && nextProps.status !== 'Page updated') || nextProps.error) {
      this.setState({ alertOpen: true })
    }
    if (nextProps.page !== null && this.props.page !== null) {
      if (nextProps.page.id !== this.props.page.id) {
        this.props.dispatch(getContentTranslations(nextProps.page.id))
      }
    }
    if (nextProps.translations) {
      const ts = nextProps.translations
      let l = { 'fr': 'Français' }
      for (let k in ts) {
        l = update(l, {
          [ts[k]['locale']]: {
            $set: locales[ts[k]['locale']]
          }
        })
      }
      this.setState({ locales: l })
    }
  }

  onSubmit (page) {
    this.props.dispatch(putContent(page)).then((res) => {
      this.setState({ snackbarContent: 'Contenu enregistré et publié', snackbarOpen: true })
      this.props.dispatch(getContent(page.id))
    })
  }

  onVersionChange (page, version) {
    this.props.dispatch(getContent(page.id, version))
  }

  onLocaleChange (locale) {
    if (locale === 'fr') {
      this.props.dispatch(getContent(this.props.page.parent.id))
      this.props.history.push(`/content-edit/${this.props.page.parent.id}`)
    } else {
      const ts = this.props.translations
      for (let k in ts) {
        if (ts[k].locale === locale) {
          this.props.dispatch(getContent(ts[k].id))
          this.props.history.push(`/content-edit/${ts[k].id}`)
        }
      }
    }
    this.props.dispatch(setLocale(locale))
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({ alertOpen: false })
  }

  handleCloseSnack () {
    this.setState({ snackbarOpen: false, snackbarContent: '' })
  }

  render () {
    return (
      <div>
        <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
        <AppMenu goBack='/content-list' title={`Modification d'un contenu`} localeHandler={this.onLocaleChange} locales={this.state.locales} locale={this.props.page.locale} />
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
        <ContentForm
          page={this.props.page}
          submitHandler={this.onSubmit}
          versionHandler={this.onVersionChange}
          edit
          translations={this.props.translations}
          parents={this.props.parents}
        />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    loading: state.loading,
    page: state.page,
    status: state.status,
    error: state.error,
    translations: state.pageTranslations,
    parents: state.pages
  }
}

ContentEdit.defaultProps = {
  parents: {},
  parentKey: 0,
  page: {
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
                { type: '', url: '', alt: '', video: '', crop: '' },
                { type: '', url: '', alt: '', video: '', crop: '' },
                { type: '', url: '', alt: '', video: '', crop: '' },
                { type: '', url: '', alt: '', video: '', crop: '' }
              ]
            }
          ]
        }
      ]
    }
  }
}

export default withRouter(connect(mapStateToProps)(ContentEdit))
