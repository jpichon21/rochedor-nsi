import reduceReducers from 'reduce-reducers'
import pageReducer from './page'
import commonReducer from './common'

export default reduceReducers(commonReducer, pageReducer)
