import { useToast } from 'vue-toast-notification';

const successCodeWithMessages = {
  200: 'Data retrieved successfully.',
  201: 'Data created successfully.',
  202: 'Data updated successfully.',
  204: 'Data deleted successfully.'
};
const errorCodeWithMessages = {
  204: 'Sorry! Data not found.',
  422: 'Credential error.',
  404: "Can't process your request.",
  500: 'Internal server error.'
};

const isEmpty = (object) => {
  for (const property in object) {
    return false;
  }
  return true;
};

export default function useNotification() {
  const toast = useToast();
  const config =  {
    position: "bottom-right",
    duration: 3000,
  }
  const showSuccess = (status = null, message = null) => {
    if(successCodeWithMessages[status] === undefined) return;
    if(parseInt(status) === 200 && message === null) return;
    if(message == null) message = successCodeWithMessages[status];

    toast.success(message, config);
  };

  const showError = (status = null, data = {}, message = null) => {
    if(errorCodeWithMessages[status] === undefined) message = 'Something went wrong.';
    else if(parseInt(status) === 204) message = 'Sorry! No data found';
    else if(parseInt(status) === 422 && !isEmpty(data)) return data.errors;
    else if(message == null) message = errorCodeWithMessages[status];
    toast.error(message, config);
    return null;
  };

  return {
    showSuccess,
    showError,
  };
}