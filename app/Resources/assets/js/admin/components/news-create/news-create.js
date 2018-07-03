import React from 'react'
import { connect } from 'react-redux'
import { postNews, initStatus, setLocale } from '../../actions'
import NewsForm from '../news-form/news-form'
import AppMenu from '../app-menu/app-menu'
import { locales } from '../../locales'
import Alert from '../alert/alert'

export class NewsCreate extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }

    this.onSubmit = this.onSubmit.bind(this)
    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }

  onSubmit (news) {
    this.props.dispatch(postNews(news)).then((res) => {
      this.props.history.push(`/news-edit/${res.id}`)
    })
  }

  componentWillMount () {
    this.props.dispatch(initStatus())
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
        <AppMenu goBack='/news-list' title={'Ajout d\'une nouveauté'} localeHandler={this.onLocaleChange} locales={locales} />
        <NewsForm submitHandler={this.onSubmit} parents={this.props.parents} />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    status: state.status,
    error: state.error
  }
}

export default connect(mapStateToProps)(NewsCreate)
