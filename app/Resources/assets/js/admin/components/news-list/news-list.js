import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getNewsSet, initStatus, setLocale } from '../../actions'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableRow,
  Tooltip,
  Button,
  CircularProgress,
  Paper,
  Typography
} from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Link } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'
import IsAuthorized, { ACTION_NEWS_VIEW, ACTION_NEWS_CREATE, ACTION_NEWS_EDIT } from '../../isauthorized/isauthorized'
import { Redirect } from 'react-router'

export class NewsList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }

    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getNewsSet(this.props.locale))
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully' && nextProps.status !== 'News Updated') || nextProps.error) {
      this.setState({alertOpen: true})
    }
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({alertOpen: false})
  }

  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
    this.props.dispatch(getNewsSet(locale))
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props

    this.props.newsSet.forEach(news => {
      console.log(news.intro, news.start, news.stop, Moment().isBetween(Moment(news.start), Moment(news.stop)))
    })

    const categories = ['Actuelles', 'Futures', 'Anciennes']
    return (
      <div>
        <IsAuthorized action={ACTION_NEWS_VIEW} alternaative={<Redirect to={'/'} />}>
          <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
          <AppMenu title={'Liste des nouveautés'} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
          <div className={classes.container}>
            {categories.map(category => (
              <div key={category} style={{marginBottom: '30px'}}>
                <Typography variant='headline' className={classes.title}>
                  {category}
                </Typography>
                <Paper className={classes.list}>
                  {
                    this.props.loading
                      ? <CircularProgress size={50} />
                      : (
                        <Table>
                          <TableHead>
                            <TableRow>
                              <TableCell>Introduction</TableCell>
                              <TableCell>Début</TableCell>
                              <TableCell>Fin</TableCell>
                            </TableRow>
                          </TableHead>
                          <TableBody>
                            {this.props.newsSet.filter(news => {
                              switch (category) {
                                case 'Actuelles':
                                  return Moment().isBetween(Moment(news.start), Moment(news.stop))
                                case 'Futures':
                                  return Moment(news.start).isAfter(Moment())
                                case 'Anciennes':
                                  return Moment(news.stop).isBefore(Moment())
                              }
                            }).map(news => {
                              return (
                                <TableRow key={news.id}>
                                  <TableCell><IsAuthorized action={ACTION_NEWS_EDIT} alternative={news.intro}><NavLink className={classes.link} to={`/news-edit/${news.id}`}>{news.intro}</NavLink></IsAuthorized></TableCell>
                                  <TableCell><IsAuthorized action={ACTION_NEWS_EDIT} alternative={Moment(news.start).format('DD/MM/YY')}><NavLink className={classes.link} to={`/news-edit/${news.id}`}>{Moment(news.start).format('DD/MM/YY')}</NavLink></IsAuthorized></TableCell>
                                  <TableCell><IsAuthorized action={ACTION_NEWS_EDIT} alternative={Moment(news.stop).format('DD/MM/YY')}><NavLink className={classes.link} to={`/news-edit/${news.id}`}>{Moment(news.stop).format('DD/MM/YY')}</NavLink></IsAuthorized></TableCell>
                                </TableRow>
                              )
                            })}
                          </TableBody>
                        </Table>
                      )
                  }
                </Paper>
              </div>
            ))}
            <div className={classes.buttons}>
              <IsAuthorized action={ACTION_NEWS_CREATE}>
                <Tooltip
                  enterDelay={300}
                  id='tooltip-controlled'
                  leaveDelay={100}
                  onClose={this.handleTooltipClose}
                  onOpen={this.handleTooltipOpen}
                  open={this.state.open}
                  placement='bottom'
                  title='Ajouter une nouveauté'
                >
                  <Button component={Link} variant='fab' color='secondary' aria-label='Ajouter' to={'/news-create'}>
                    <AddIcon />
                  </Button>
                </Tooltip>
              </IsAuthorized>
            </div>
          </div>
        </IsAuthorized>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    newsSet: state.newsSet,
    loading: state.loading,
    status: state.status,
    error: state.error,
    locale: state.locale
  }
}

NewsList.propTypes = {
  classes: PropTypes.object.isRequired
}

NewsList.defaultProps = {
  locale: 'fr',
  newsSet: []
}

export default compose(withStyles(styles), connect(mapStateToProps))(NewsList)
