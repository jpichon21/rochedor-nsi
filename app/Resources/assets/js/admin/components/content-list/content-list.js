import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getPages, initStatus, setLocale } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, CircularProgress, Paper, Typography } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink, Link } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'

export class ContentList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      alertOpen: false
    }
    this.onLocaleChange = this.onLocaleChange.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }

  componentWillMount () {
    this.props.dispatch(getPages(this.props.locale))
  }

  componentWillReceiveProps (nextProps) {
    if ((nextProps.status !== 'ok' && nextProps.status !== '' && nextProps.status !== 'Deleted successfully' && nextProps.status !== 'Page updated') || nextProps.error) {
      this.setState({ alertOpen: true })
    }
    this.props.dispatch(initStatus())
  }

  handleClose () {
    this.props.dispatch(initStatus())
    this.setState({ alertOpen: false })
  }

  onLocaleChange (locale) {
    this.props.dispatch(setLocale(locale))
    this.props.dispatch(getPages(locale))
  }

  render () {
    Moment.locale(this.props.locale)
    const { classes } = this.props
    const items = []
    this.props.pages.map(page => { items[page.immutableid] = page })
    const renderRow = (page) => {
      if (page) {
        return (
          <TableRow key={page.id}>
            <TableCell>{`${page.title} ${page.sub_title}`}<NavLink className={classes.link} to={`/content-edit/${page.id}`}>Modifier</NavLink></TableCell>
            <TableCell>{page.routes[0].static_prefix}<a className={classes.link} target='_blank' href={`${page.routes[0].static_prefix}`}>Aperçu</a></TableCell>
            <TableCell>{Moment(page.updated).format('DD/MM/YY')}</TableCell>
          </TableRow>
        )
      } else {
        return (
          <TableRow>
            <TableCell>Page inexistante</TableCell>
            <TableCell />
            <TableCell />
          </TableRow>
        )
      }
    }
    return (
      <div>
        <Alert open={this.state.alertOpen} content={this.props.status} onClose={this.handleClose} />
        <AppMenu title={'Liste des Contenus'} localeHandler={this.onLocaleChange} locales={locales} locale={this.props.locale} />
        <div className={classes.container}>
          <Typography variant='headline' className={classes.title}>
            La communauté et les Maisons
          </Typography>
          <Paper className={classes.list}>
            {
              this.props.loading
                ? <CircularProgress size={50} />
                : (
                  <Table>
                    <TableHead>
                      <TableRow>
                        <TableCell>Titre</TableCell>
                        <TableCell>URL</TableCell>
                        <TableCell>Dernière modification</TableCell>
                      </TableRow>
                    </TableHead>
                    <TableBody>
                      {renderRow(items['la-communaute'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['les-fondateurs'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['la-roche-dor'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['les-fontanilles'])}
                    </TableBody>
                  </Table>
                )
            }
          </Paper>
          <Typography variant='headline' className={classes.title}>
            Les Retraites
          </Typography>
          <Paper className={classes.list}>
            {
              this.props.loading
                ? <CircularProgress size={50} />
                : (
                  <Table>
                    <TableHead>
                      <TableRow>
                        <TableCell>Titre</TableCell>
                        <TableCell>URL</TableCell>
                        <TableCell>Dernière modification</TableCell>
                      </TableRow>
                    </TableHead>
                    <TableBody>
                      {renderRow(items['venir-en-retraite'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['retraites-a-la-roche-dor'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['retraites-aux-fontanilles'])}
                    </TableBody>
                  </Table>
                )
            }
          </Paper>
          <Typography variant='headline' className={classes.title}>
            Informations Pratiques
          </Typography>
          <Paper className={classes.list}>
            {
              this.props.loading
                ? <CircularProgress size={50} />
                : (
                  <Table>
                    <TableHead>
                      <TableRow>
                        <TableCell>Titre</TableCell>
                        <TableCell>URL</TableCell>
                        <TableCell>Dernière modification</TableCell>
                      </TableRow>
                    </TableHead>
                    <TableBody>
                      {renderRow(items['infos-pratiques-de-la-roche-dor'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['infos-pratiques-des-fontanilles'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['acceuil-des-enfants'])}
                    </TableBody>
                    <TableBody>
                      {renderRow(items['liens-amis'])}
                    </TableBody>
                  </Table>
                )
            }
          </Paper>
          <Typography variant='headline' className={classes.title}>
            Nous Soutenir
          </Typography>
          <Paper className={classes.list}>
            {
              this.props.loading
                ? <CircularProgress size={50} />
                : (
                  <Table>
                    <TableHead>
                      <TableRow>
                        <TableCell>Titre</TableCell>
                        <TableCell>URL</TableCell>
                        <TableCell>Dernière modification</TableCell>
                      </TableRow>
                    </TableHead>
                    <TableBody>
                      {renderRow(items['donner-du-temps'])}
                    </TableBody>
                  </Table>
                )
            }
          </Paper>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme,
  link: {
    marginLeft: 10,
    textDecoration: 'none',
    color: theme.palette.secondary.main
  },
  list: {
    marginBottom: theme.myMarge
  }
})

const mapStateToProps = state => {
  return {
    pages: state.pages,
    loading: state.loading,
    status: state.status,
    error: state.error,
    locale: state.locale
  }
}

ContentList.propTypes = {
  classes: PropTypes.object.isRequired
}

export default compose(withStyles(styles), connect(mapStateToProps))(ContentList)
