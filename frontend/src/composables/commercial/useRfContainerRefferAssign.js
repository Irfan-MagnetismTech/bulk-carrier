import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";

export default function useRfContainerRefferAssign() {
    const router = useRouter();
    const voyage = ref([]);
    const $loading = useLoading();
    const errors = ref([]);
    const isLoading = ref(false);
    const notification = useNotification();
    const formParams = ref( {
        voyage: '',
    });

    const rfContainerParams = ref([]);

    async function getRfContainerList(form) {
        //NProgress.start();

        if(!form.voyage_id){
            notification.showError(422,null,'Please select a voyage');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            await Api.post("commercial/voyage-rf-containers", form)
                .then((response) => {
                    voyage.value = response.data.value;
                    if(voyage.value.length == 0){
                        if (!confirm('No FR Container Found. Do you want to update the voyage life cycle status for fr containers?')) {
                            return;
                        }
                        updateRfStatus(form);
                    }
                })
        } catch {
            errors.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function assignRfContainerReffer(form) {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/commercial/voyage-rf-containers/set-reffer', form);
            notification.showSuccess(status);
            //getRfContainerList(form);

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
        // try {
        //     Api.post("commercial/voyage-rf-containers/set-reffer", form)
        //         .then((response) => {
        //             router.push({ name: "commercial.voyage-rf-containers.voyage" });
        //         })
        // } catch {
        //     error.value = Error.showError(error);
        // } finally {
        //     isLoading.value = false;
        //     NProgress.done();
        // }
    }

    async function updateRfStatus(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        form.container_type = "RF";
        try {
            const { data, status } = await Api.post('commercial/update-voyage-containers-status', form);
            notification.showSuccess(status,data.message);
            router.push({ name: "commercial.voyage-rf-containers.voyage" });
        } catch {
            errors.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        voyage,
        formParams,
        getRfContainerList,
        assignRfContainerReffer,
        rfContainerParams,
        isLoading,
        errors,
    };
}
