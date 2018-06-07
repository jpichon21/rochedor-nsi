export const GET_HOME = 'GET_HOME'
export const GET_HOME_SUCCESS = 'GET_HOME_SUCCESS'
export const GET_HOME_FAILURE = 'GET_HOME_FAILURE'
export const GET_HOME_VERSIONS = 'GET_HOME_VERSIONS'
export const GET_HOME_VERSIONS_SUCCESS = 'GET_HOME_VERSIONS_SUCCESS'
export const GET_HOME_VERSIONS_FAILURE = 'GET_HOME_VERSIONS_FAILURE'
export const PUT_HOME = 'PUT_HOME'
export const PUT_HOME_SUCCESS = 'PUT_HOME_SUCCESS'
export const PUT_HOME_FAILURE = 'PUT_HOME_FAILURE'

const API_URL = '/api/'

export function getHome (locale, version = null) {
  return dispatch => {
    dispatch({ type: GET_HOME, locale })
    const url = (version) ? `${API_URL}home/${locale}/${version}` : `${API_URL}home/${locale}`
    return window.fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: GET_HOME_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_HOME_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: GET_HOME_FAILURE, error }))
  }
}

export function getHomeVersions (locale) {
  return dispatch => {
    dispatch({ type: GET_HOME_VERSIONS, locale })

    return window.fetch(`${API_URL}home/${locale}/versions`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: GET_HOME_VERSIONS_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_HOME_VERSIONS_SUCCESS, data: res })
        return res
      })
  }
}

export function putHome (home) {
  return dispatch => {
    dispatch({ type: PUT_HOME, home })

    return window.fetch(`${API_URL}home/${home.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'PUT',
      credentials: 'include',
      body: JSON.stringify(home)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: PUT_HOME_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: PUT_HOME_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: PUT_HOME_FAILURE, error }))
  }
}
