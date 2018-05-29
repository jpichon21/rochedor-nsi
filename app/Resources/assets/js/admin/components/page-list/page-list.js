import React from 'react'
import { connect } from 'react-redux'
import { getPages } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow } from '@material-ui/core'
import Moment from 'moment'

export class PageList extends React.Component {
  constructor (props) {
    super(props)
    this.state = {

    }
  }
  componentWillMount () {
    this.props.dispatch(getPages())
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
