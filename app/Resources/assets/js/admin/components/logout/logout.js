import React from 'react'
import { Redirect } from 'react-router-dom'
import {connect} from 'react-redux'
import {doLogout} from '../../actions'

export class Logout extends React.Component {
  constructor (props) {
    super(props)
    this.onLogout = this.onLogout.bind(this)
    this.onLogout()
  }
  onLogout () {
    this.props.dispatch(doLogout())
  }
  render () {
    return (
      <Redirect to='/' />
    )
  }
}

const mapStateToProps = state => {
  return { username: state.username }
}

export default connect(mapStateToProps)(Logout)
