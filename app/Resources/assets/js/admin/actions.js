/* PARAMS */

export const API_URL = '/api/'

/* ACTIONS */
export const GET_PAGES = 'GET_PAGES'
export const GET_PAGES_SUCCESS = 'GET_PAGES_SUCCESS'
export const GET_PAGES_FAILURE = 'GET_PAGES_FAILURE'

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
      .catch(error => dispatch({ type: GET_PAGES_FAILURE, error }))
  }
}

/* ACTIONS */
export const POST_PAGE = 'POST_PAGE'
export const POST_PAGE_SUCCESS = 'POST_PAGE_SUCCESS'
export const POST_PAGE_FAILURE = 'POST_PAGE_FAILURE'

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
