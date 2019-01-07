import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { initStatus } from '../../actions'
import UserForm from '../user-form/user-form'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { Snackbar, Button } from '@material-ui/core'
import IsAuthorized, { ACTION_USER_EDIT } from '../../isauthorized/isauthorized'
import Redirect from 'react-router/Redirect'

export class UserEdit extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false,
      snackbarOpen: false,
      snackbarContent: ''
    }
    this.handleClose = this.handleClose.bind(this)
    this.handleCloseSnack = this.handleCloseSnack.bind(this)
  }
  componentWillMount () {
    const { match: { params } } = this.props
    this.setState({userId: params.userId})
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
        <IsAuthorized action={ACTION_USER_EDIT} alternative={<Redirect to={'/user-list'} />}>
          <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
          <AppMenu title={`Modification utilisateur`} goBack='/user-list' />
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
          <UserForm edit userId={this.state.userId} />
        </IsAuthorized>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    locale: state.locale,
    loading: state.loading,
    status: state.status,
    error: state.error
  }
}

UserEdit.defaultProps = {
  status: ''
}

export default withRouter(connect(mapStateToProps)(UserEdit))
