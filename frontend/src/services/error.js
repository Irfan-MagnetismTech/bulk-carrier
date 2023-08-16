const errorCodeWithMessages = {
    422: 'Credential error.',
    404: 'Api is not found.',
    500: 'Server error.'

};

function showError(error) {
    let status = error.response.status;

    console.error(errorCodeWithMessages[status]);

    if(status === 422) {
        return error.response.data.error;
    } 

    return [];
}

export default {
    showError,
};