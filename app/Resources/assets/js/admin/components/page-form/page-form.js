import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { MenuItem, Menu, GridList, GridListTile, TextField, Button, Typography, Grid, ExpansionPanel, ExpansionPanelDetails, ExpansionPanelSummary, ExpansionPanelActions, Divider } from '@material-ui/core'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import AddIcon from '@material-ui/icons/Add'
import { withStyles } from '@material-ui/core/styles'
import { postPage, getPages } from '../../actions'
import RichEditor from './RichEditor'

export class PageForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: 'fr',
      history: '20180513',
      layout: '1-1-2',
      page: {
        title: '',
        sub_title: '',
        url: '',
        description: '',
        locale: 'fr',
        content: {
          intro: ''
        }
      },
      submitDisabled: true,
      anchorMenuLayout: null,
      menuLayoutOpened: false
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleLocaleChange = this.handleLocaleChange.bind(this)
    this.handleHistoryChange = this.handleHistoryChange.bind(this)
    this.handleLayoutMenu = this.handleLayoutMenu.bind(this)
    this.handleCloseLayoutMenu = this.handleCloseLayoutMenu.bind(this)
    this.handleChangeLayoutMenu = this.handleChangeLayoutMenu.bind(this)
  }

  handleLocaleChange (event) {
    this.setState({ locale: event.target.value }, () => {
      this.props.dispatch(getPages(this.state.locale))
    })
  }
  handleHistoryChange (event) {
    this.setState({ history: event.target.value }, () => {
      this.props.dispatch(getPages(this.state.history))
    })
  }

  handleInputChange (event) {
    const value = event.target.value
    const name = event.target.name
    this.setState(prevState => {
      return {
        page: {
          ...prevState.page,
          [name]: value
        }
      }
    }, () => {
      let disabled = false
      disabled = (this.state.page.title === '' || this.state.page.description === '')
      this.setState({ submitDisabled: disabled })
    })
  }

  handleSubmit (event) {
    if (!this.state.loading && !this.state.submitDisabled) {
      this.props.submitHandler(this.state.page)
    }
    event.preventDefault()
  }

  handleInputFilter (event) {
    const re = /[0-9A-Za-z-]+/g
    if (!re.test(event.key)) {
      event.preventDefault()
    }
  }

  handleLayoutMenu (event) {
    this.setState({ anchorMenuLayout: event.currentTarget })
  }

  handleCloseLayoutMenu () {
    this.setState({ anchorMenuLayout: null })
  }

  handleChangeLayoutMenu (event) {
    this.setState({
      layout: event.target.getAttribute('layout'),
      anchorMenuLayout: null
    })
  }

  render () {
    const { classes } = this.props
    const { anchorMenuLayout } = this.state
    const menuLayoutOpened = Boolean(anchorMenuLayout)
    const tileData = [{
      id: 0,
      img: 'http://via.placeholder.com/400x400',
      title: 'Image 1',
      cols: 1
    },
    {
      id: 1,
      img: 'http://via.placeholder.com/400x400',
      title: 'Image 2',
      cols: 1
    },
    {
      id: 2,
      img: 'http://via.placeholder.com/800x400',
      title: 'Image 3',
      cols: 2
    }]
    return (
      <div className={classes.container}>
        <Typography variant='display1' className={classes.title}>
          SEO
        </Typography>
        <form className={classes.form} noValidate onSubmit={this.handleSubmit}>
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            required
            id='title'
            name='title'
            label='Titre ligne 1'
            value={this.state.page.title}
            onChange={this.handleInputChange} />
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            id='sub_title'
            name='sub_title'
            label='Titre ligne 2'
            value={this.state.page.sub_title}
            onChange={this.handleInputChange} />
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            id='url'
            name='url'
            label='Url'
            value={this.state.page.url}
            onChange={this.handleInputChange}
            onKeyPress={this.handleInputFilter} />
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
            id='description'
            name='description'
            label='Meta-description'
            value={this.state.page.description}
            onChange={this.handleInputChange} />
        </form>
        <Typography variant='display1' className={classes.title}>
          Contenu
        </Typography>
        <form className={classes.form} noValidate onSubmit={this.handleSubmit}>
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
            id='intro'
            name='intro'
            label='Introduction'
            value={this.state.page.content.intro}
            onChange={this.handleInputChange} />
          <ExpansionPanel className={classes.expansion}>
            <ExpansionPanelSummary expandIcon={<ExpandMoreIcon />}>
              <Typography>
                Volet 1
              </Typography>
            </ExpansionPanelSummary>
            <ExpansionPanelDetails className={classes.details}>
              <Grid container spacing={32}>
                <Grid item xs={6}>
                  <RichEditor />
                </Grid>
                <Grid item xs={6}>
                  <GridList cellHeight={400} className={classes.gridList} cols={2}>
                    {tileData.map(tile => (
                      <GridListTile key={tile.id} cols={tile.cols || 1}>
                        <img src={tile.img} alt={tile.title} />
                      </GridListTile>
                    ))}
                  </GridList>
                  <div className={classes.options}>
                    <Button
                      aria-owns={menuLayoutOpened ? 'layout-menu' : null}
                      aria-haspopup='true'
                      onClick={this.handleLayoutMenu}
                      color='primary'
                      className={classes.option}>
                      Disposition
                    </Button>
                    <Button className={classes.option}>Supprimer</Button>
                    <Button color='secondary' className={classes.option}>Ajouter</Button>
                  </div>
                  <Menu
                    id='layout-menu'
                    anchorEl={anchorMenuLayout}
                    anchorOrigin={{
                      vertical: 'top',
                      horizontal: 'right'
                    }}
                    transformOrigin={{
                      vertical: 'top',
                      horizontal: 'right'
                    }}
                    open={menuLayoutOpened}
                    onClose={this.handleCloseLayoutMenu}>
                    <MenuItem onClick={this.handleChangeLayoutMenu} layout={'2'}>1 Image</MenuItem>
                    <MenuItem onClick={this.handleChangeLayoutMenu} layout={'2-2'}>2 Images horizontales</MenuItem>
                    <MenuItem onClick={this.handleChangeLayoutMenu} layout={'1-1'}>2 Images verticales</MenuItem>
                    <MenuItem onClick={this.handleChangeLayoutMenu} layout={'2-1-1'}>3 Images (Horizontale en haut)</MenuItem>
                    <MenuItem onClick={this.handleChangeLayoutMenu} layout={'1-1-2'}>3 Images (Horizontale en bas)</MenuItem>
                    <MenuItem onClick={this.handleChangeLayoutMenu} layout={'1-1-1-1'}>4 Images</MenuItem>
                  </Menu>
                </Grid>
              </Grid>
            </ExpansionPanelDetails>
            <Divider />
            <ExpansionPanelActions>
              <Button>Supprimer</Button>
              <Button color='secondary'>Sauvegarder</Button>
            </ExpansionPanelActions>
          </ExpansionPanel>
          <ExpansionPanel className={classes.expansion}>
            <ExpansionPanelSummary expandIcon={<ExpandMoreIcon />}>
              <Typography>
                Volet 2
              </Typography>
            </ExpansionPanelSummary>
            <ExpansionPanelDetails className={classes.details}>
              <Grid container>
                <Grid item xs={6}>
                  <RichEditor />
                </Grid>
              </Grid>
            </ExpansionPanelDetails>
            <Divider />
            <ExpansionPanelActions>
              <Button>Supprimer</Button>
              <Button color='secondary'>Sauvegarder</Button>
            </ExpansionPanelActions>
          </ExpansionPanel>
        </form>
        <div className={classes.buttons}>
          <Button
            className={classes.button}
            variant='fab'
            color='secondary'>
            <AddIcon />
          </Button>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme,
  paper: {
    padding: theme.myMarge
  },
  options: {
    marginTop: theme.myMarge,
    textAlign: 'center'
  },
  option: {
    marginRight: theme.myMarge / 3,
    marginLeft: theme.myMarge / 3
  }
})

const mapStateToProps = state => {
  return {
    loading: state.loading,
    status: state.postPageStatus
  }
}

PageForm.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(PageForm))
