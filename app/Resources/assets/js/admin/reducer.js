import {
  GET_PAGES,
  GET_PAGES_SUCCESS,
  GET_PAGES_FAILURE,
  POST_PAGE,
  POST_PAGE_SUCCESS,
  POST_PAGE_FAILURE
} from './actions'

export const rootReducer = (state, action) => {
  console.log(action)
  switch (action.type) {
    case GET_PAGES:
      return {
        ...state,
        loading: true
      }
    case GET_PAGES_SUCCESS:
      return {
        ...state,
        pages: [...action.data],
        loading: false
      }
    case GET_PAGES_FAILURE:
      return {
        ...state,
        loading: false
      }
    case POST_PAGE:
      return {
        ...state,
        postPageStatus: null,
        loading: true
      }
    case POST_PAGE_SUCCESS:
      return {
        ...state,
        postPageStatus: action.res.status,
        pages: [...action.data],
        loading: false
      }
    case POST_PAGE_FAILURE:
      return {
        ...state,
        postPageStatus: action.res.status,
        loading: false
      }
  }
  return state
}
