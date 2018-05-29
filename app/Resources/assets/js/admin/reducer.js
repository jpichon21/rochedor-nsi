import { 
  GET_PAGES,
  GET_PAGES_SUCCESS,
  GET_PAGES_FAILURE
} from './actions'

export const rootReducer = (state, action) => {
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
  }
  return state
}
