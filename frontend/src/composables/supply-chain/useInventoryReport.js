import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useInventoryReport() {
    const router = useRouter();
    const inventory = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const formParams = ref( {
        scm_warehouse_id: null,
        scmWarehouse: '',
        from_date: null,
        to_date: null,
        business_unit: '',
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const showReport = ref(false);
    
    async function inventoryReport(form) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        showReport.value = true;
        try {
            const { data, status } = await Api.get('/scm/inventory-report', { params: form });
            inventory.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            
        }
    }

    function downloadInventoryReport(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        axios({
            url: '/mnt/download-inventory-report',
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
        inventory,
        inventoryReport,
        downloadInventoryReport,
        isLoading,
        showReport,
        errors,
    };
}