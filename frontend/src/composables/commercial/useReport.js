import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useReport() {
    const router = useRouter();
    const $loading = useLoading();
    const voyageInfo = ref({});
    const error = ref([]);
    const isLoading = ref(false);
    const notification = useNotification();

    const formParams = ref( {
        voyage_id: '',
    });

    async function freightCargoManifest(form) {

        if(!form.voyage_id) {
            notification.showError(422,'','Please select a voyage');
            return;
        }

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('commercial/freight-cargo-manifest', form);
            voyageInfo.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function customerLoadCalculation(form) {
        NProgress.start();
        isLoading.value = true;
        try {
            let { data } = await Api.post(
                "commercial/customer-load-calculation",
                form
            );
            voyageInfo.value = await data;
        } catch {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        voyageInfo,
        freightCargoManifest,
        customerLoadCalculation,
        formParams,
        isLoading,
        error,
    };
}
