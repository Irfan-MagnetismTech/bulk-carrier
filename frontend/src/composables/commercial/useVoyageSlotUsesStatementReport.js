import nprogress from "nprogress";
import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";
import useInvoice from "./useInvoice";

export default function useVoyageSlotUsesStatementReport() {
    const router = useRouter();
    const $loading = useLoading();
    const voyageInfo = ref({});
    const voyages = ref({});
    const voyageParticulars = ref(0);
    const isFinalInvoiceModalOpen = ref(0);
    const isVoyageExchangeRateModalOpen = ref(0);
    const isFinalInvoiceAlertModalOpen = ref(0);
    const notification = useNotification();
    const invoice = useInvoice();
    const errors = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        customer_id: null,
        voyage_id: null,
        sector: null,
        rate_type: null,
    });

    async function voyageSlotUsesStatementReport(form) {
        //NProgress.start();

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        voyageInfo.value = '';
        if(form.customer_id === null || form.voyage_id === null){
            //isLoading.value = false;
            //NProgress.done();
            notification.showError(422,'','Please fill all required fields');
            return;
        } else{
            try {

                let { data, status } = await Api.post(
                    "commercial/voyage-slot-uses-statement",
                    form
                );
                voyageInfo.value = await data;

                if(!Object.keys(voyageInfo?.value?.invoiceParticulars).length){
                    // alert
                    isFinalInvoiceModalOpen.value = 0;
                    notification.showError(422,'','Sorry! No related data found for this invoice')
                }else{
                    isFinalInvoiceModalOpen.value = 1;
                }

            } catch (error) {
                if(form.is_model_open){
                    notification.showSuccess(200, "Invoice Generated Successfully");
                }
                const { data, status } = error.response;
                notification.showError(status,'',data.message);
            } finally {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            }
        }
    }

    function downloadVoyageSlotUseStatement(form) {
        //NProgress.start();

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        axios({

            url: '/commercial/download-voyage-slot-use-statement',
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
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        });
    }

    return {
        formParams,
        voyageParticulars,
        isFinalInvoiceModalOpen,
        isVoyageExchangeRateModalOpen,
        voyageInfo,
        downloadVoyageSlotUseStatement,
        voyageSlotUsesStatementReport,
        isFinalInvoiceAlertModalOpen,
        voyages,
        isLoading,
        errors,
    };
}
