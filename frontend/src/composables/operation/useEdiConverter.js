import NProgress from "nprogress";
import { reactive, ref } from "vue";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';


export default function useEdiConverter() {
    const ediFile = reactive({
        file: null,
    });
    const notification = useNotification();
    const errors = ref(null);
    const isLoading = ref(false);

    async function process(form) {
        console.log("Everything is ok");
        NProgress.start();
        isLoading.value = true;
        let formData = new FormData();
        formData.append('file', form.file);
        axios({
            url: '/operation/edi-converter',
            data: formData,
            method: 'POST',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', "Container List " + dateTime.toJSON().slice(0,10).split('-').reverse().join('-') + ".xlsx");
            document.body.appendChild(link);
            link.click();
        }).catch((error) => {
            if (error.response.status === 422) {
                const reader = new FileReader();
                reader.onload = function() {
                    const data = JSON.parse(reader.result);
                    const message = data.message;
                    console.log("Response message: " + message);
                    notification.showError(error.response.status, '', message);
                    errors.value = [data.message, data.type, data.value];
                }
                reader.readAsText(error.response.data);
            } else {
                notification.showError(error.response.status, '', error.response.statusText);
            }
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    return {
        ediFile,
        process,
        isLoading,
        errors,
    };
}
