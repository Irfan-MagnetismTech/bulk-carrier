import moment from 'moment';

export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';

export const formatDateTime = dateTime => dateTime ? moment(dateTime).format('DD/MM/YYYY, hh:mm A') : '';