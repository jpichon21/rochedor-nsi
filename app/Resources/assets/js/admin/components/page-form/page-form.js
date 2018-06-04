import React, { Fragment } from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Link } from 'react-router-dom'
import { convertToRaw } from 'draft-js'
import update from 'immutability-helper'  
import draftToHtml from 'draftjs-to-html'
import { MenuItem, Menu, GridList, GridListTile, TextField, Button, Typography, Grid, ExpansionPanel, ExpansionPanelDetails, ExpansionPanelSummary, ExpansionPanelActions, Divider, Select, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle } from '@material-ui/core'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import WrapTextIcon from '@material-ui/icons/WrapText'
import SaveIcon from '@material-ui/icons/Save'
import DeleteIcon from '@material-ui/icons/Delete'
import { withStyles } from '@material-ui/core/styles'
import RichEditor from './RichEditor'
import { tileData } from './tileData'

export class PageForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: this.props.lang,
      page: {
        locale: 'fr',
        title: '',
        sub_title: '',
        url: '',
        description: '',
        content: {
          intro: '',
          sections: {
            title: '',
            body: '',
            slides: []
          }
        }
      },
      versionCount: 0,
      submitDisabled: true,
      anchorMenuLayout: null,
      menuLayoutOpened: false,
      layout: '1-1-2',
      showDeleteAlert: false
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleLayoutMenu = this.handleLayoutMenu.bind(this)
    this.handleCloseLayoutMenu = this.handleCloseLayoutMenu.bind(this)
    this.handleChangeLayoutMenu = this.handleChangeLayoutMenu.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleParent = this.handleParent.bind(this)
    this.handleChangeTextArea = this.handleChangeTextArea.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
  }

  handleVersion (event) {
    this.setState({ versionCount: event.target.value })
    this.props.versionHandler(this.state.page, this.props.versions[event.target.value].version)
    event.preventDefault()
  }

  handleParent (event) {
    const parentKey = event.target.value
    this.setState((prevState) => {
      return {
        page: {
          ...prevState.page,
          parent_id: this.props.parents[parentKey].id
        },
        parentKey: parentKey
      }
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
      this.setState({ submitDisabled: (this.state.page.title === '' || this.state.page.description === '') })
    })
  }

  handleSubmit (event) {
    event.preventDefault()
    this.props.submitHandler(this.state.page)
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
  handleDelete () {
    this.setState({ showDeleteAlert: true })
  }
  handleDeleteClose () {
    this.setState({ showDeleteAlert: false })
  }
  
  handleDeleteConfirm () {
    this.props.deleteHandler(this.state.page)
    this.setState({ showDeleteAlert: false })
  }

  handleChangeTextArea (editorState) {
    const rawContentState = convertToRaw(editorState.getCurrentContent())
    const markup = draftToHtml(rawContentState)
    this.setState(prevState => {
      return {
        ...prevState,
        page: {
          ...prevState.page,
          sections: {
            ...prevState.page.sections,
            body: markup
          }
        }
      }
    })
  }

  isSubmitEnabled () {
    const p = this.state.page
    if (p.title === '' || p.description === '' || p.url === '') {
      return false
    }
    if (p.locale !== 'fr' && !p.parent_id) {
      return false
    }
    if (p.locale === 'fr' && p.parent_id) {
      return false
    }
    return true
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.page) {
      const p = nextProps.page
      const state = update(this.state, {
        page: {
          id: {
            $set: p.id
          },
          title: {
            $set: p.title
          },
          sub_title: {
            $set: p.sub_title
          },
          description: {
            $set: p.description
          },
          url: {
            $set: p.url
          },
          content: {
            $merge: p.content
          }
        }
      })
      this.setState(state)
    }
    if (nextProps.locale) {
      this.setState((prevState) => {
        return {
          page: {
            ...prevState.page,
            locale: nextProps.locale,
            parent_id: null
          }
        }
      })
    }
  }
  render () {
    const { classes } = this.props
    const { anchorMenuLayout } = this.state
    const menuLayoutOpened = Boolean(anchorMenuLayout)
    const versions = (this.props.versions.length > 0)
      ? this.props.versions.map((v, k) => {
        return (
          <MenuItem value={k} key={v.id}>{v.logged_at}</MenuItem>
        )
      })
      : null
    const parents = (this.props.parents.length > 0)
      ? this.props.parents.map((p, k) => {
        return (
          <MenuItem value={k} key={k}>{p.title}</MenuItem>
        )
      })
      : null
    return (
      <div className={classes.container}>
        {
          this.props.edit
            ? (
              <Select
                className={classes.option}
                value={this.state.versionCount}
                onChange={this.handleVersion}
                inputProps={{
                  name: 'historique',
                  id: 'version'
                }}>
                {versions}
              </Select>
            )
            : (
              ''
            )
        }
        <Typography variant='display1' className={classes.title}>
          SEO
        </Typography>
        <form className={classes.form}>
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='title'
            label='Titre ligne 1'
            value={this.state.page.title}
            onChange={this.handleInputChange} />
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='sub_title'
            label='Titre ligne 2'
            value={this.state.page.sub_title}
            onChange={this.handleInputChange} />
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='url'
            label='Url'
            value={this.state.page.url}
            onChange={this.handleInputChange}
            onKeyPress={this.handleInputFilter} />
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
            name='description'
            label='Meta-description'
            value={this.state.page.description}
            onChange={this.handleInputChange} />
            {
          (!this.props.edit && this.props.parents.length > 0 && this.state.page.locale !== 'fr')
            ? (
              <Select
                placeholder={'Page parente'}
                className={classes.option}
                value={this.state.parentKey}
                onChange={this.handleParent}
                inputProps={{
                  name: 'parent_key',
                  id: 'parent_key'
                }}>
                {parents}
              </Select>
            )
            : (
              ''
            )
        }
        </form>
        <Typography variant='display1' className={classes.title}>
          Contenu
        </Typography>
        <form className={classes.form}>
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
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
                  <TextField
                    autoComplete='off'
                    InputLabelProps={{ shrink: true }}
                    className={classes.textfield}
                    fullWidth
                    multiline
                    name='title'
                    label='Titre'
                    value={this.state.page.content.sections.title}
                    onChange={this.handleInputChange} />
                  <RichEditor onChange={this.handleChangeTextArea} />
                </Grid>
                <Grid item xs={6}>
                  <GridList className={classes.gridList} cols={2} rows={2}>
                    {
                      tileData[this.state.layout].map(tile => (
                        <GridListTile key={tile.id} cols={tile.cols} rows={tile.rows}>
                          <img src={tile.img} alt={tile.title} />
                        </GridListTile>
                      ))
                    }
                  </GridList>
                  <div className={classes.options}>
                    <Button
                      variant='outlined'
                      aria-owns={menuLayoutOpened ? 'layout-menu' : null}
                      aria-haspopup='true'
                      onClick={this.handleLayoutMenu}
                      className={classes.option}>
                      Disposition
                    </Button>
                    <Button variant='outlined' disabled className={classes.option}>Supprimer</Button>
                    <Button variant='outlined' color='primary' className={classes.option}>Ajouter</Button>
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
              <Button disabled>Supprimer</Button>
            </ExpansionPanelActions>
          </ExpansionPanel>
        </form>
        <div className={classes.buttons}>
          <Button component={Link} to={'/page-list'}
            className={classes.button}
            variant='fab'
            color='primary'>
            <WrapTextIcon />
          </Button>
          {
          (this.props.edit)
          ? (
            <div>
              <Button
                onClick={this.handleDelete}
                className={classes.button}
                variant='fab'
                color='secondary'>
                <DeleteIcon />
              </Button>
              <Dialog
              open={this.state.showDeleteAlert}
              onClose={this.handleDeleteClose}
              aria-labelledby='alert-dialog-title'
              aria-describedby='alert-dialog-description'>
                <DialogTitle id='alert-dialog-title'>
                  {'Êtes-vous sure?'}
                </DialogTitle>
                <DialogContent>
                  <DialogContentText id='alert-dialog-description'>
                    Cette action est irréversible, souhaitez-vous continuer?
                  </DialogContentText>
                </DialogContent>
                <DialogActions>
                  <Button onClick={this.handleDeleteConfirm} color='secondary' autoFocus>Oui</Button>
                  <Button onClick={this.handleDeleteClose} color='primary' autoFocus>Annuler</Button>
                </DialogActions>
              </Dialog>
            </div>
          )
          : (
            ''
          )
          }
          <Button
            disabled={!this.isSubmitEnabled()}
            onClick={this.handleSubmit}
            className={classes.button}
            variant='fab'
            color='secondary'>
            <SaveIcon />
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
  },
  expansion: {
    marginBottom: theme.myMarge
  }
})

const mapStateToProps = state => {
  return {
    status: state.postPageStatus,
    versions: state.pageVersions,
    version: state.pageVersion,
    locale: state.locale
  }
}

PageForm.propTypes = {
  classes: PropTypes.object.isRequired
}

PageForm.defaultProps = {
  parents: {},
  parentKey: 0,
  page: {
    locale: 'fr',
    title: '',
    sub_title: '',
    url: '',
    description: '',
    parent_id: null,
    content: {
      intro: '',
      sections: {
        title: '',
        body: '',
        slides: []
      }
    }
  }
}

export default compose(withStyles(styles), connect(mapStateToProps))(PageForm)
