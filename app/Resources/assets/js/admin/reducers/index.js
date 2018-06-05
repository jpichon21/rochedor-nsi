import reduceReducers from 'reduce-reducers'
import commonReducer from './common'
import pageReducer from './page'
import newsReducer from './news'
import fileReducer from './file'

export default reduceReducers(commonReducer, pageReducer, newsReducer, fileReducer)
