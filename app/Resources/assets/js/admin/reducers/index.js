import * as reducer from 'reduce-reducers'
import pageReducer from './page'
import commonReducer from './common'

export default reducer(commonReducer, pageReducer)
