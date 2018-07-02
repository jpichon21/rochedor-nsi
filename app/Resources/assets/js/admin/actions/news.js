export const POST_NEWS = 'POST_NEWS'
export const POST_NEWS_SUCCESS = 'POST_NEWS_SUCCESS'
export const POST_NEWS_FAILURE = 'POST_NEWS_FAILURE'
export const GET_NEWSSET = 'GET_NEWSSET'
export const GET_NEWSSET_SUCCESS = 'GET_NEWSSET_SUCCESS'
export const GET_NEWSSET_FAILURE = 'GET_NEWSSET_FAILURE'
export const GET_NEWS = 'GET_NEWS'
export const GET_NEWS_SUCCESS = 'GET_NEWS_SUCCESS'
export const GET_NEWS_FAILURE = 'GET_NEWS_FAILURE'
export const GET_NEWS_VERSIONS = 'GET_NEWS_VERSIONS'
export const GET_NEWS_VERSIONS_SUCCESS = 'GET_NEWS_VERSIONS_SUCCESS'
export const GET_NEWS_VERSIONS_FAILURE = 'GET_NEWS_VERSIONS_FAILURE'
export const GET_NEWS_TRANSLATIONS = 'GET_NEWS_TRANSLATIONS'
export const GET_NEWS_TRANSLATIONS_SUCCESS = 'GET_NEWS_TRANSLATIONS_SUCCESS'
export const GET_NEWS_TRANSLATIONS_FAILURE = 'GET_NEWS_TRANSLATIONS_FAILURE'
export const PUT_NEWS = 'PUT_NEWS'
export const PUT_NEWS_SUCCESS = 'PUT_NEWS_SUCCESS'
export const PUT_NEWS_FAILURE = 'PUT_NEWS_FAILURE'
export const DELETE_NEWS = 'DELETE_NEWS'
export const DELETE_NEWS_SUCCESS = 'DELETE_NEWS_SUCCESS'
export const DELETE_NEWS_FAILURE = 'DELETE_NEWS_FAILURE'

const API_URL = '/api/'

export function postNews (attributes) {
  return dispatch => {
    dispatch({ type: POST_NEWS, attributes })

    return window.fetch(`${API_URL}news`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'POST',
      credentials: 'include',
      body: JSON.stringify(attributes)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: POST_NEWS_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: POST_NEWS_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: POST_NEWS_FAILURE, error }))
  }
}

export function getNewsSet (locale = 'fr') {
  return dispatch => {
    dispatch({ type: GET_NEWSSET, locale })

    return window.fetch(`${API_URL}news?locale=${locale}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_NEWSSET_SUCCESS, data: res })
      })
      .catch(error => dispatch({ type: GET_NEWSSET_FAILURE, data: error }))
  }
}

export function getNews (pageId, version = null) {
  return dispatch => {
    dispatch({ type: GET_NEWS, pageId })
    const url = (version) ? `${API_URL}news/${pageId}/${version}` : `${API_URL}news/${pageId}`
    return window.fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_NEWS_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch(getNewsVersions(res.id))
            dispatch({ type: GET_NEWS_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_NEWS_FAILURE, ...{ data: error } }))
  }
}

export function getNewsVersions (pageId) {
  return dispatch => {
    dispatch({ type: GET_NEWS_VERSIONS, pageId })

    return window.fetch(`${API_URL}news/${pageId}/versions`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_NEWS_VERSIONS_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch({ type: GET_NEWS_VERSIONS_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_NEWS_VERSIONS_FAILURE, ...{ data: error } }))
  }
}

export function putNews (page) {
  return dispatch => {
    dispatch({ type: PUT_NEWS, page })

    return window.fetch(`${API_URL}news/${page.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'PUT',
      credentials: 'include',
      body: JSON.stringify(page)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: PUT_NEWS_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: PUT_NEWS_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: PUT_NEWS_FAILURE, error }))
  }
}

export function deleteNews (page) {
  return dispatch => {
    dispatch({ type: DELETE_NEWS, page })

    return window.fetch(`${API_URL}news/${page.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'DELETE',
      credentials: 'include',
      body: JSON.stringify(page)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: DELETE_NEWS_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: DELETE_NEWS_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: DELETE_NEWS_FAILURE, error }))
  }
}
