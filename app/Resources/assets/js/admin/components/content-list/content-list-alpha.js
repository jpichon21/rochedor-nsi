import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getContents, initStatus, setLocale } from '../../actions'
import { Table, TableBody, TableCell, TableHead, TableRow, CircularProgress, Paper, Typography } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import Moment from 'moment'
import { NavLink } from 'react-router-dom'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'
import IsAuthorized, {
  ACTION_CONTENT_ASSOCIATION_VIEW,
  ACTION_CONTENT_EDITION_VIEW,
  ACTION_CONTENT_ASSOCIATION_EDIT,
  ACTION_CONTENT_EDITION_EDIT
} from '../../isauthorized/isauthorized'
import Redirect from 'react-router-dom/Redirect'
import Tab from '@material-ui/core/Tab'
import ContentList from './content-list'

export default () => <ContentList currentTabValue={'alpha'} routePrefix={'/content-edit/alpha/'} />;