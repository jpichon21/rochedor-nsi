const participant = {
  nom: '',
  prenom: '',
  codco: '',
  ident: '',
  civil: '',
  civil2: '',
  adresse: '',
  cp: '',
  ville: '',
  pays: '',
  tel: '',
  mobil: '',
  email: '',
  profession: '',
  datnaiss: '',
  coltyp: '',
  colp: '',
  transport: '',
  memo: '',
  check: false
}

const delivery = {
  adliv: {
    adresse: '',
    zipcode: '',
    city: ''
  },
  destliv: '',
  paysliv: '',
  modpaie: '',
  modliv: '',
  validpaie: '',
  datliv: '',
  paysip: '',
  dateenreg: '',
  cartId: 1
}

const clone = (obj) => {
  var tmp = JSON.stringify(obj)
  return JSON.parse(tmp)
}
export const getDelivery = () => {
  return delivery
}

export const getParticipant = () => {
  return clone(participant)
}
