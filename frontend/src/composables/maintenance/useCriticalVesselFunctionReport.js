import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Swal from 'sweetalert2';

export default function useCriticalVesselFunctionReport() {
    const router = useRouter();
    const criticalVesselFunctions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const formParams = ref( {
        ops_vessel_id: null,
        ops_vessel: '',
        business_unit: '',
    });


    const errors = ref(null);
    const isLoading = ref(false);
    const showReport = ref(false);
    
    async function criticalVesselFunctionsReport(form) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        showReport.value = true;
        try {
            const { data, status } = await Api.get('/mnt/get-critical-vessel-functions', { params: form });
            criticalVesselFunctions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            
        }
    }

    function downloadCriticalVesselFunctionsReport(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        axios({
            url: '/mnt/download-report-critical-vessel-functions',
            data: form,
            method: 'POST',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();

            //stream pdf file to new tab start
            let fileURL = URL.createObjectURL(response.data);
            let a = document.createElement('a');
            a.href = fileURL;
            a.target = '_blank';
            a.click();
            //stream pdf file to new tab end

            // const url = window.URL.createObjectURL(new Blob([response.data]));
            // const link = document.createElement('a');
            // link.href = url;
            // link.setAttribute('download', "Invoice-" + dateTime.toJSON().slice(0,10).split('-').reverse().join('-') + ".pdf");
            // document.body.appendChild(link);
            // link.click();
        }).catch((error) => {
            console.log(error);
        }).finally(() => {
            loader.hide();
            isLoading.value = false;
        });
    }

    return {
        formParams,
        criticalVesselFunctions,
        criticalVesselFunctionsReport,
        downloadCriticalVesselFunctionsReport,
        isLoading,
        showReport,
        errors,
    };
}
