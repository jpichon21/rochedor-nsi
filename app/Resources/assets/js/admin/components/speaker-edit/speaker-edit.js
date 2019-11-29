import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { setLocale, initStatus } from '../../actions'
import SpeakerForm from '../speaker-form/speaker-form'
import AppMenu from '../app-menu/app-menu'
import IsAuthorized, { ACTION_SPEAKER_EDIT } from '../../isauthorized/isauthorized'

import { locales } from '../../locales'
import Redirect from 'react-router-dom/Redirect'

export class SpeakerEdit extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false,
      locales: locales
    }

    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }
  componentWillMount () {
    const { match: { params } } = this.props
    this.setState({ speakerId: params.speakerId })
  }
  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'speaker-edit-ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully') || nextProps.error) {
      // this.setState({alertOpen: true})
    }
  }
  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
  }
  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({ alertOpen: false })
    this.props.history.push('/speaker-list')
  }
  render () {
    return (
      <div>
        <IsAuthorized action={ACTION_SPEAKER_EDIT} alternative={<Redirect to='/speaker-list' />}>
          <AppMenu goBack='/speaker-list' title={`Modification d'un intervenant`} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
          <SpeakerForm speaker={this.props.speaker} locale={this.props.locale} edit speakerId={this.state.speakerId} />
        </IsAuthorized>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    loading: state.loading,
    speaker: state.speaker,
    status: state.status,
    error: state.error,
    locale: state.locale
  }
}

SpeakerEdit.defaultProps = {
  speaker: {
    id: null,
    title: '',
    sub_title: '',
    url: '',
    description: '',
    locale: 'fr'
  },
  status: ''
}

export default withRouter(connect(mapStateToProps)(SpeakerEdit))
