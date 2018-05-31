import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getPages } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Select, MenuItem, Button, CircularProgress, Paper } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { withRouter } from 'react-router-dom'

export class PageList extends React.Component {
  constructor (props) {
    super(props)
    this.setTitle = this.setTitle.bind(this)
    this.state = {
      locale: 'fr'
    }

    this.handleLocaleChange = this.handleLocaleChange.bind(this)
  }
  componentWillMount () {
    this.props.dispatch(getPages(this.state.locale))
  }
  componentDidMount () {
    this.setTitle()
  }
  setTitle () {
    this.props.title('Liste des pages')
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
          <TableCell>{p.title}</TableCell>
          <TableCell>{Moment(p.updated).format('DD/MM/YY')}</TableCell>
        </TableRow>
      )
    })
    return (
      <div className={classes.container}>
        <div className={classes.languages}>
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
        <Paper>
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
        </Paper>
        <div className={classes.buttons}>
          <Button variant='fab' color='primary' aria-label='Ajouter' onClick={() => this.goTo('/page-create')} >
            <AddIcon />
          </Button>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  container: theme.container,
  languages: {
    display: 'flex',
    justifyContent: 'flex-end',
    marginBottom: 20
  },
  buttons: {
    display: 'flex',
    justifyContent: 'flex-end',
    marginTop: -30,
    marginRight: 20
  }
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
