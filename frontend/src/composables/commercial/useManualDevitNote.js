import NProgress from "nprogress";
import { reactive, ref } from "vue";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';


export default function useManualDevitNote() {
    const devitNotes = reactive({
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
            url: '/commercial/manual-debit-note',
            data: formData,
            method: 'POST',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', "Debit-Note" + dateTime.toJSON().slice(0,10).split('-').reverse().join('-') + ".pdf");
            document.body.appendChild(link);
            link.click();
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            NProgress.done();
            isLoading.value = false;
        });
    }

    return {
        devitNotes,
        process,
        isLoading,
        errors,
    };
}
