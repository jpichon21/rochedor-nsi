import React from 'react'
import { connect } from 'react-redux'
import { postPage, initStatus, setLocale, getPages } from '../../actions'
import PageForm from '../page-form/page-form'
import AppMenu from '../app-menu/app-menu'
import { locales } from '../../locales'
import Alert from '../alert/alert'

export class PageCreate extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }
    this.onSubmit = this.onSubmit.bind(this)
    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }

  onSubmit (page) {
    this.props.dispatch(postPage(page)).then((res) => {
      this.props.history.push(`/page-edit/${res.id}`)
    })
  }

  componentWillMount () {
    this.props.dispatch(initStatus())
    this.props.dispatch(getPages('fr'))
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '') || nextProps.error) {
      this.setState({alertOpen: true})
    }
  }

  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({alertOpen: false})
  }

  render () {
    return (
      <div>
        <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
        <AppMenu goBack='/page-list' title={'Création de page'} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
        <PageForm submitHandler={this.onSubmit} parents={this.props.parents} />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    status: state.status,
    error: state.error,
    parents: state.pages,
    locale: state.locale
  }
}

export default connect(mapStateToProps)(PageCreate)
