import moment from 'moment';

export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';

export const formatDateTime = dateTime => dateTime ? moment(dateTime).format('DD/MM/YYYY, hh:mm A') : '';

export const formatMonthYear = dateString => {
    const dateObject = moment(dateString, 'YYYY-MM');
    return dateObject.isValid() ? dateObject.format('MMMM, YYYY') : '';
};

export const formatMonthYearWithTime = dateString => {
    const dateObject = moment(dateString);

    return dateObject.isValid() ? dateObject.format('DD/MM/YYYY, HH:mm:ss') : '';
};
