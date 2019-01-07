export const GET_CONTENTS = 'GET_CONTENTS'
export const GET_CONTENTS_SUCCESS = 'GET_CONTENTS_SUCCESS'
export const GET_CONTENTS_FAILURE = 'GET_CONTENTS_FAILURE'
export const GET_CONTENT = 'GET_CONTENT'
export const GET_CONTENT_SUCCESS = 'GET_CONTENT_SUCCESS'
export const GET_CONTENT_FAILURE = 'GET_CONTENT_FAILURE'
export const GET_CONTENT_VERSIONS = 'GET_CONTENT_VERSIONS'
export const GET_CONTENT_VERSIONS_SUCCESS = 'GET_CONTENT_VERSIONS_SUCCESS'
export const GET_CONTENT_VERSIONS_FAILURE = 'GET_CONTENT_VERSIONS_FAILURE'
export const GET_CONTENT_TRANSLATIONS = 'GET_CONTENT_TRANSLATIONS'
export const GET_CONTENT_TRANSLATIONS_SUCCESS = 'GET_CONTENT_TRANSLATIONS_SUCCESS'
export const GET_CONTENT_TRANSLATIONS_FAILURE = 'GET_CONTENT_TRANSLATIONS_FAILURE'
export const PUT_CONTENT = 'PUT_CONTENT'
export const PUT_CONTENT_SUCCESS = 'PUT_CONTENT_SUCCESS'
export const PUT_CONTENT_FAILURE = 'PUT_CONTENT_FAILURE'

const API_URL = '/api/'

export function getContents (locale = 'fr') {
  return dispatch => {
    dispatch({ type: GET_CONTENTS, locale })

    return window.fetch(`${API_URL}content?locale=${locale}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_CONTENTS_SUCCESS, data: res })
      })
      .catch(error => dispatch({ type: GET_CONTENTS_FAILURE, data: error }))
  }
}

export function getContent (contentId, version = null) {
  return dispatch => {
    dispatch({ type: GET_CONTENT, contentId })
    const url = (version) ? `${API_URL}content/${contentId}/${version}` : `${API_URL}content/${contentId}`
    return window.fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_CONTENT_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch(getContentVersions(res.id))
            dispatch({ type: GET_CONTENT_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_CONTENT_FAILURE, ...{ data: error } }))
  }
}

export function getContentVersions (contentId) {
  return dispatch => {
    dispatch({ type: GET_CONTENT_VERSIONS, contentId })

    return window.fetch(`${API_URL}content/${contentId}/versions`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_CONTENT_VERSIONS_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch({ type: GET_CONTENT_VERSIONS_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_CONTENT_VERSIONS_FAILURE, ...{ data: error } }))
  }
}

export function getContentTranslations (contentId) {
  return dispatch => {
    dispatch({ type: GET_CONTENT_TRANSLATIONS, contentId })
    const url = `${API_URL}content/${contentId}/translations`
    return window.fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_CONTENT_TRANSLATIONS_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch({ type: GET_CONTENT_TRANSLATIONS_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_CONTENT_TRANSLATIONS_FAILURE, ...{ data: error } }))
  }
}

export function putContent (content) {
  return dispatch => {
    dispatch({ type: PUT_CONTENT, content })

    return window.fetch(`${API_URL}content/${content.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'PUT',
      credentials: 'include',
      body: JSON.stringify(content)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: PUT_CONTENT_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: PUT_CONTENT_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: PUT_CONTENT_FAILURE, error }))
  }
}
