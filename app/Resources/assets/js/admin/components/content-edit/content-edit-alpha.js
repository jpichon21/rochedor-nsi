import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { getContent, putContent, setTitle, setLocale, initStatus, getContentTranslations } from '../../actions'
import ContentForm from '../content-form/content-form'
import AppMenu from '../app-menu/app-menu'
import Alert from '../alert/alert'
import { locales } from '../../locales'
import update from 'immutability-helper'
import { Snackbar, Button } from '@material-ui/core'

import ContentEdit from './content-edit';

export default () => <ContentEdit goBack={'/content-list/alpha'} />
