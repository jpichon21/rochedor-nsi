import React from 'react'
import { connect } from 'react-redux'

export const ACTION_SPEAKER_VIEW = 'SPEAKER_VIEW'
export const ACTION_SPEAKER_CREATE = 'SPEAKER_CREATE'
export const ACTION_SPEAKER_EDIT = 'SPEAKER_EDIT'
export const ACTION_SPEAKER_DELETE = 'SPEAKER_DELETE'
export const ACTION_HOME_VIEW = 'HOME_VIEW'
export const ACTION_HOME_CREATE = 'HOME_CREATE'
export const ACTION_HOME_EDIT = 'HOME_EDIT'
export const ACTION_HOME_DELETE = 'HOME_DELETE'
export const ACTION_CONTENT_VIEW = 'CONTENT_VIEW'
export const ACTION_CONTENT_CREATE = 'CONTENT_CREATE'
export const ACTION_CONTENT_EDIT = 'CONTENT_EDIT'
export const ACTION_CONTENT_DELETE = 'CONTENT_DELETE'
export const ACTION_NEWS_VIEW = 'NEWS_VIEW'
export const ACTION_NEWS_CREATE = 'NEWS_CREATE'
export const ACTION_NEWS_EDIT = 'NEWS_EDIT'
export const ACTION_NEWS_DELETE = 'NEWS_DELETE'
export const ACTION_PAGE_VIEW = 'PAGE_VIEW'
export const ACTION_PAGE_CREATE = 'PAGE_CREATE'
export const ACTION_PAGE_EDIT = 'PAGE_EDIT'
export const ACTION_PAGE_DELETE = 'PAGE_DELETE'
export const ACTION_USER_VIEW = 'USER_VIEW'
export const ACTION_USER_CREATE = 'USER_CREATE'
export const ACTION_USER_EDIT = 'USER_EDIT'
export const ACTION_USER_DELETE = 'USER_DELETE'

export class IsAuthorized extends React.Component {
  isAuthorized (action) {
    if (this.hasRole('ROLE_SUPER_ADMIN') || this.hasRole('ROLE_GOD')) {
      return true
    }
    let authorized = false
    if (Array.isArray(action)) {
      action.forEach(a => {
        if (this.isActionAuthorized(a)) {
          authorized = true
        }
      })
      return authorized
    }
    return this.isActionAuthorized(action)
  }
  hasRole (role) {
    if (!this.props.roles || !this.props.roles.length) {
      return false
    }
    return this.props.roles.includes(role)
  }
  isActionAuthorized (action) {
    switch (action) {
      case ACTION_SPEAKER_VIEW:
        return this.hasRole('ROLE_ADMIN_SPEAKER_VIEW')
      case ACTION_SPEAKER_CREATE:
        return this.hasRole('ROLE_ADMIN_SPEAKER_CREATE')
      case ACTION_SPEAKER_EDIT:
        return this.hasRole('ROLE_ADMIN_SPEAKER_EDIT')
      case ACTION_SPEAKER_DELETE:
        return this.hasRole('ROLE_ADMIN_SPEAKER_DELETE')
      case ACTION_HOME_VIEW:
        return this.hasRole('ROLE_ADMIN_HOME_VIEW')
      case ACTION_HOME_CREATE:
        return this.hasRole('ROLE_ADMIN_HOME_CREATE')
      case ACTION_HOME_EDIT:
        return this.hasRole('ROLE_ADMIN_HOME_EDIT')
      case ACTION_HOME_DELETE:
        return this.hasRole('ROLE_ADMIN_HOME_DELETE')
      case ACTION_CONTENT_VIEW:
        return this.hasRole('ROLE_ADMIN_CONTENT_VIEW')
      case ACTION_CONTENT_CREATE:
        return this.hasRole('ROLE_ADMIN_CONTENT_CREATE')
      case ACTION_CONTENT_EDIT:
        return this.hasRole('ROLE_ADMIN_CONTENT_EDIT')
      case ACTION_CONTENT_DELETE:
        return this.hasRole('ROLE_ADMIN_CONTENT_DELETE')
      case ACTION_NEWS_VIEW:
        return this.hasRole('ROLE_ADMIN_NEWS_VIEW')
      case ACTION_NEWS_CREATE:
        return this.hasRole('ROLE_ADMIN_NEWS_CREATE')
      case ACTION_NEWS_EDIT:
        return this.hasRole('ROLE_ADMIN_NEWS_EDIT')
      case ACTION_NEWS_DELETE:
        return this.hasRole('ROLE_ADMIN_NEWS_DELETE')
      case ACTION_PAGE_VIEW:
        return this.hasRole('ROLE_ADMIN_PAGE_VIEW')
      case ACTION_PAGE_CREATE:
        return this.hasRole('ROLE_ADMIN_PAGE_CREATE')
      case ACTION_PAGE_EDIT:
        return this.hasRole('ROLE_ADMIN_PAGE_EDIT')
      case ACTION_PAGE_DELETE:
        return this.hasRole('ROLE_ADMIN_PAGE_DELETE')
      case ACTION_USER_VIEW:
        return this.hasRole('ROLE_ADMIN_USER_VIEW')
      case ACTION_USER_CREATE:
        return this.hasRole('ROLE_ADMIN_USER_CREATE')
      case ACTION_USER_EDIT:
        return this.hasRole('ROLE_ADMIN_USER_EDIT')
      case ACTION_USER_DELETE:
        return this.hasRole('ROLE_ADMIN_USER_DELETE')
      default:
        return false
    }
  }

  render () {
    const isAuthorized = this.isAuthorized(this.props.action)
    return (isAuthorized) ? this.props.children : this.props.alternative
  }
}

const mapStateToProps = state => { return { roles: state.roles } }

IsAuthorized.defaultProps = { roles: [], action: null, alternative: '' }

export default connect(mapStateToProps)(IsAuthorized)
