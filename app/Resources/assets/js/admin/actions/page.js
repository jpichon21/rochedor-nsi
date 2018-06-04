export const POST_PAGE = 'POST_PAGE'
export const POST_PAGE_SUCCESS = 'POST_PAGE_SUCCESS'
export const POST_PAGE_FAILURE = 'POST_PAGE_FAILURE'
export const GET_PAGES = 'GET_PAGES'
export const GET_PAGES_SUCCESS = 'GET_PAGES_SUCCESS'
export const GET_PAGES_FAILURE = 'GET_PAGES_FAILURE'
export const GET_PAGE = 'GET_PAGE'
export const GET_PAGE_SUCCESS = 'GET_PAGE_SUCCESS'
export const GET_PAGE_FAILURE = 'GET_PAGE_FAILURE'
export const GET_PAGE_VERSIONS = 'GET_PAGE_VERSIONS'
export const GET_PAGE_VERSIONS_SUCCESS = 'GET_PAGE_VERSIONS_SUCCESS'
export const GET_PAGE_VERSIONS_FAILURE = 'GET_PAGE_VERSIONS_FAILURE'
export const GET_PAGE_TRANSLATIONS = 'GET_PAGE_TRANSLATIONS'
export const GET_PAGE_TRANSLATIONS_SUCCESS = 'GET_PAGE_TRANSLATIONS_SUCCESS'
export const GET_PAGE_TRANSLATIONS_FAILURE = 'GET_PAGE_TRANSLATIONS_FAILURE'
export const PUT_PAGE = 'PUT_PAGE'
export const PUT_PAGE_SUCCESS = 'PUT_PAGE_SUCCESS'
export const PUT_PAGE_FAILURE = 'PUT_PAGE_FAILURE'
export const DELETE_PAGE = 'DELETE_PAGE'
export const DELETE_PAGE_SUCCESS = 'DELETE_PAGE_SUCCESS'
export const DELETE_PAGE_FAILURE = 'DELETE_PAGE_FAILURE'

const API_URL = '/api/'

export function postPage (attributes) {
  return dispatch => {
    dispatch({ type: POST_PAGE, attributes })

    return window.fetch(`${API_URL}pages`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'POST',
      credentials: 'include',
      body: JSON.stringify(attributes)
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: POST_PAGE_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch({ type: POST_PAGE_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: POST_PAGE_FAILURE, ...{ data: error } }))
  }
}

export function getPages (locale = 'fr') {
  return dispatch => {
    dispatch({ type: GET_PAGES, locale })

    return window.fetch(`${API_URL}pages?locale=${locale}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_PAGES_SUCCESS, data: res })
      })
      .catch(error => dispatch({ type: GET_PAGES_FAILURE, data: error }))
  }
}

export function getPage (pageId, version = null) {
  return dispatch => {
    dispatch({ type: GET_PAGE, pageId })
    const url = (version) ? `${API_URL}pages/${pageId}/${version}` : `${API_URL}pages/${pageId}`
    return window.fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_PAGE_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch(getPageVersions(res.id))
            dispatch({ type: GET_PAGE_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_PAGE_FAILURE, ...{ data: error } }))
  }
}

export function getPageVersions (pageId) {
  return dispatch => {
    dispatch({ type: GET_PAGE_VERSIONS, pageId })

    return window.fetch(`${API_URL}pages/${pageId}/versions`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_PAGE_VERSIONS_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch({ type: GET_PAGE_VERSIONS_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_PAGE_VERSIONS_FAILURE, ...{ data: error } }))
  }
}

export function getPageTranslations (pageId) {
  return dispatch => {
    dispatch({ type: GET_PAGE_TRANSLATIONS, pageId })
    const url = `${API_URL}pages/${pageId}/translation`
    return window.fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: GET_PAGE_TRANSLATIONS_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch(getPageVersions(res.id))
            dispatch({ type: GET_PAGE_TRANSLATIONS_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: GET_PAGE_TRANSLATIONS_FAILURE, ...{ data: error } }))
  }
}

export function putPage (page) {
  return dispatch => {
    dispatch({ type: PUT_PAGE, page })

    return window.fetch(`${API_URL}pages/${page.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'PUT',
      credentials: 'include',
      body: JSON.stringify(page)
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: PUT_PAGE_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch({ type: PUT_PAGE_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: PUT_PAGE_FAILURE, ...{ data: error } }))
  }
}

export function deletePage (page) {
  return dispatch => {
    dispatch({ type: DELETE_PAGE, page })

    return window.fetch(`${API_URL}pages/${page.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'DELETE',
      credentials: 'include',
      body: JSON.stringify(page)
    })
      .then(res => {
        if (res.status >= 400) {
          res.json().then(res => {
            dispatch({ type: DELETE_PAGE_FAILURE, ...{ data: res } })
          })
        } else {
          res.json().then(res => {
            dispatch({ type: DELETE_PAGE_SUCCESS, ...{ data: res } })
          })
        }
      })
      .catch(error => dispatch({ type: DELETE_PAGE_FAILURE, ...{ data: error } }))
  }
}