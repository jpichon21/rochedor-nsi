import React from 'react'
import { connect } from 'react-redux'
import { getPages } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, Select, MenuItem } from '@material-ui/core'
import Moment from 'moment'

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

export default connect(mapStateToProps)(PageList)
