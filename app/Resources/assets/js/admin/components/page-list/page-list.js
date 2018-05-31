import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getPages, setTitle } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Select, MenuItem, Button, CircularProgress, Paper, Typography } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { withRouter, NavLink } from 'react-router-dom'

export class PageList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: 'fr'
    }

    this.handleLocaleChange = this.handleLocaleChange.bind(this)
  }
  componentWillMount () {
    this.props.dispatch(getPages(this.state.locale))
  }
  componentDidMount () {
    this.props.dispatch(setTitle('Liste des pages'))
  }
  handleLocaleChange (event) {
    this.setState({locale: event.target.value}, () => {
      this.props.dispatch(getPages(this.state.locale))
    })
  }
  goTo (path) {
    this.props.history.push(path)
  }
  render () {
    Moment.locale('fr')
    const { classes } = this.props
    const items = this.props.pages.map(p => {
      return (
        <TableRow key={p.id}>
          <TableCell><NavLink to={`/page-edit/${p.id}`}>{p.title}</NavLink></TableCell>
          <TableCell><NavLink to={`/page-edit/${p.id}`}>{Moment(p.updated).format('DD/MM/YY')}</NavLink></TableCell>
        </TableRow>
      )
    })
    return (
      <div className={classes.container}>
        <div className={classes.options}>
          <Select
            value={this.state.locale}
            onChange={this.handleLocaleChange}
            inputProps={{
              name: 'langue',
              id: 'locale'
            }}>
            <MenuItem value={'fr'}>FR</MenuItem>
            <MenuItem value={'en'}>EN</MenuItem>
            <MenuItem value={'es'}>ES</MenuItem>
            <MenuItem value={'de'}>DE</MenuItem>
            <MenuItem value={'it'}>IT</MenuItem>
          </Select>
        </div>
        <Paper className={classes.paper}>
          <Typography variant='headline' className={classes.title} component='h2'>
            Pages
          </Typography>
          {
            this.props.loading
              ? <CircularProgress size={50} />
              : (
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>Intitulé</TableCell>
                      <TableCell>Dernière modification</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {items}
                  </TableBody>
                </Table>
              )
          }
          <div className={classes.buttons}>
            <Button variant='raised' color='primary' aria-label='Ajouter' onClick={() => this.goTo('/page-create')} >Ajouter</Button>
          </div>
        </Paper>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    pages: state.pages,
    loading: state.loading
  }
}

PageList.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(PageList))
