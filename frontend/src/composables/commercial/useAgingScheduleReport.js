import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";

export default function useAgingScheduleReport() {
    const router = useRouter();
    const $loading = useLoading();
    const agingSchedules = ref({});
    const voyages = ref({});
    const error = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        customer_id: '',
        invoice_id: '',
    });

    async function getAgingSchedule(page, form = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            // let { data } = await Api.post(
            //     "finance/aging-schedule",
            // );

            const { data, status } = await Api.get('/finance/aging-schedule', {
                params: {
                    page: page || 1,
                    voyage_id: form.voyage_id || null,
                    invoice_number: form.invoice_number || null,
                    service_id: form.service_id || null,
                    customer_id: form.customer_id || null,
                    aging: form.aging || null,
                },
            });

            agingSchedules.value = await data.value;

        } catch {
            error.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        formParams,
        agingSchedules,
        getAgingSchedule,
        voyages,
        isLoading,
        error,
    };
}
