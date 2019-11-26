import React from 'react'
import { connect } from 'react-redux'

export const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN'
export const ROLE_ADMIN_ASSOCIATION = 'ROLE_ADMIN_ASSOCIATION'
export const ROLE_ADMIN_EDITION = 'ROLE_ADMIN_EDITION'

export const ACTION_SPEAKER_VIEW = 'SPEAKER_VIEW'
export const ACTION_SPEAKER_CREATE = 'SPEAKER_CREATE'
export const ACTION_SPEAKER_EDIT = 'SPEAKER_EDIT'
export const ACTION_SPEAKER_DELETE = 'SPEAKER_DELETE'
export const ACTION_HOME_VIEW = 'HOME_VIEW'
export const ACTION_HOME_CREATE = 'HOME_CREATE'
export const ACTION_HOME_EDIT = 'HOME_EDIT'
export const ACTION_HOME_DELETE = 'HOME_DELETE'
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

export const ACTION_CONTENT_SUPER_ADMIN = 'CONTENT_SUPER_ADMIN'

export const ACTION_CONTENT_ASSOCIATION_VIEW = 'CONTENT_ASSOCIATION_VIEW'
export const ACTION_CONTENT_ASSOCIATION_CREATE = 'CONTENT_ASSOCIATION_CREATE'
export const ACTION_CONTENT_ASSOCIATION_EDIT = 'CONTENT_ASSOCIATION_EDIT'
export const ACTION_CONTENT_ASSOCIATION_DELETE = 'CONTENT_ASSOCIATION_DELETE'
export const ACTION_CONTENT_EDITION_VIEW = 'CONTENT_EDITION_VIEW'
export const ACTION_CONTENT_EDITION_CREATE = 'CONTENT_EDITION_CREATE'
export const ACTION_CONTENT_EDITION_EDIT = 'CONTENT_EDITION_EDIT'
export const ACTION_CONTENT_EDITION_DELETE = 'CONTENT_EDITION_DELETE'

export class IsAuthorized extends React.Component {
  isAuthorized (action) {
    if (this.hasRole(ROLE_SUPER_ADMIN)) {
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

      case ACTION_NEWS_VIEW:
        return this.hasRole('ROLE_ADMIN_NEWS_VIEW')
      case ACTION_NEWS_CREATE:
        return this.hasRole('ROLE_ADMIN_NEWS_CREATE')
      case ACTION_NEWS_EDIT:
        return this.hasRole('ROLE_ADMIN_NEWS_EDIT')
      case ACTION_NEWS_DELETE:
        return this.hasRole('ROLE_ADMIN_NEWS_DELETE')
      case ACTION_PAGE_VIEW:
      case ACTION_PAGE_CREATE:
      case ACTION_PAGE_EDIT:
      case ACTION_PAGE_DELETE:
        return true;
      /* case ACTION_PAGE_VIEW:
        return this.hasRole('ROLE_ADMIN_PAGE_VIEW')
      case ACTION_PAGE_CREATE:
        return this.hasRole('ROLE_ADMIN_PAGE_CREATE')
      case ACTION_PAGE_EDIT:
        return this.hasRole('ROLE_ADMIN_PAGE_EDIT')
      case ACTION_PAGE_DELETE:
        return this.hasRole('ROLE_ADMIN_PAGE_DELETE') */
      case ACTION_USER_VIEW:
        return this.hasRole('ROLE_ADMIN_USER_VIEW')
      case ACTION_USER_CREATE:
        return this.hasRole('ROLE_ADMIN_USER_CREATE')
      case ACTION_USER_EDIT:
        return this.hasRole('ROLE_ADMIN_USER_EDIT')
      case ACTION_USER_DELETE:
        return this.hasRole('ROLE_ADMIN_USER_DELETE')

      case ACTION_CONTENT_ASSOCIATION_VIEW:
        return this.hasRole('ROLE_ADMIN_CONTENT_ASSOCIATION_VIEW')
      case ACTION_CONTENT_ASSOCIATION_CREATE:
        return this.hasRole('ROLE_ADMIN_CONTENT_ASSOCIATION_CREATE')
      case ACTION_CONTENT_ASSOCIATION_EDIT:
        return this.hasRole('ROLE_ADMIN_CONTENT_ASSOCIATION_EDIT')
      case ACTION_CONTENT_ASSOCIATION_DELETE:
        return this.hasRole('ROLE_ADMIN_CONTENT_ASSOCIATION_DELETE')

      case ACTION_CONTENT_EDITION_VIEW:
        return this.hasRole('ROLE_ADMIN_CONTENT_EDITION_VIEW')
      case ACTION_CONTENT_EDITION_CREATE:
        return this.hasRole('ROLE_ADMIN_CONTENT_EDITION_CREATE')
      case ACTION_CONTENT_EDITION_EDIT:
        return this.hasRole('ROLE_ADMIN_CONTENT_EDITION_EDIT')
      case ACTION_CONTENT_EDITION_DELETE:
        return this.hasRole('ROLE_ADMIN_CONTENT_EDITION_DELETE')

      case ACTION_CONTENT_SUPER_ADMIN:
        return this.hasRole(ROLE_SUPER_ADMIN)
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
