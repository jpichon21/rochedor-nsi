import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { getPage, putPage, setTitle, setLocale, initStatus } from '../../actions'
import PageForm from '../page-form/page-form'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'

export class PageEdit extends React.Component {
  static defaultProps = {
    page: {
      title: '',
      sub_title: '',
      url: '',
      description: '',
      locale: 'fr'
    },
    status: ''
  }
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }

    this.onSubmit = this.onSubmit.bind(this)
    this.onVersionChange = this.onVersionChange.bind(this)
    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }
  componentDidMount () {
    const { match: { params } } = this.props
    this.props.dispatch(getPage(params.pageId))
  }
  componentWillReceiveProps (nextProps) {
    this.props.dispatch(setTitle(`Modification de la page ${(nextProps.page) ? nextProps.page.title : ''}`))
    if ((nextProps.status !== 'ok' && nextProps.status !== '') || nextProps.error) {
      this.setState({alertOpen: true})
    }
  }
  onSubmit (page) {
    console.log(page)
    this.props.dispatch(putPage(page))
  }
  onVersionChange (page, version) {
    this.props.dispatch(getPage(page.id, version))
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
        <AppMenu title={`Modification de la page ${(this.props.page) ? this.props.page.title: ''}`} localeHandler={this.onLocaleChange} locales={locales} />
        <PageForm page={this.props.page} submitHandler={this.onSubmit} versionHandler={this.onVersionChange} edit />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    loading: state.loading,
    page: state.page,
    status: state.status,
    error: state.error
  }
}

export default withRouter(connect(mapStateToProps)(PageEdit))
