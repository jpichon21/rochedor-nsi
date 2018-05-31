import React from 'react'
import { connect } from 'react-redux'
import { getPages } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Select, MenuItem, Button } from '@material-ui/core'
import AddIcon from '@material-ui/icons/Add'
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
    const items = this.props.pages.map(p => {
      return (
        <TableRow key={p.id}>
          <TableCell>{p.title}</TableCell>
          <TableCell>{Moment(p.updated).format('D/MM/YY')}</TableCell>
        </TableRow>
      )
    })

    return (
      <div>
        <h1>Liste des pages</h1>
        <Select
          value={this.state.locale}
          onChange={this.handleLocaleChange}
          inputProps={{
            name: 'langue',
            id: 'locale'
          }}
        >
          <MenuItem value={'fr'}>fr</MenuItem>
          <MenuItem value={'en'}>en</MenuItem>
          <MenuItem value={'es'}>es</MenuItem>
          <MenuItem value={'de'}>de</MenuItem>
          <MenuItem value={'it'}>it</MenuItem>
        </Select>

        {
          this.props.loading ? (
            <div>'Chargement...'</div>
          )
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
        <Button variant='fab' color='primary' aria-label='Ajouter' onClick={() => this.goTo('/page-create')} >
          <AddIcon />
        </Button>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    pages: state.pages,
    loading: state.loading
  }
}

export default withRouter(connect(mapStateToProps)(PageList))
