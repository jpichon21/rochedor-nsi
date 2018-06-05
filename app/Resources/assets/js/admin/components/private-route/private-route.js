import React from 'react'
import { Route, Redirect } from 'react-router'
import { connect } from 'react-redux'

export class PrivateRoute extends React.Component {
  render () {
    const isLoggedIn = this.props.isLoggedIn
    if (isLoggedIn) {
      return <Route {...this.props} />
    } else {
      return <Redirect to='/login' />
    }
  }
}

const mapStateToProps = state => {
  return {
    isLoggedIn: !!state.username
  }
}

export default connect(mapStateToProps)(PrivateRoute)
