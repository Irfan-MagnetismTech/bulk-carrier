import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";

export default function useFrContainerTuesAssign() {
    const router = useRouter();
    const voyage = ref([]);
    const $loading = useLoading();
    const errors = ref(null);
    const isLoading = ref(false);
    const notification = useNotification();
    const formParams = ref( {
        voyage: '',
    });

    const frContainerParams = ref([]);

    async function getFrContainerList(form) {

        if(!form.voyage_id){
            notification.showError(422,null,'Please select a voyage');
            return;
        }

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            await Api.post("commercial/voyage-fr-containers", form)
                .then((response) => {
                    voyage.value = response.data.value;
                    if(voyage.value.length == 0){
                        if (!confirm('No FR Container Found. Do you want to update the voyage life cycle status for fr containers?')) {
                            return;
                        }
                        updateFrStatus(form);
                    }
                })
        } catch {
            errors.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function assignFrContainerTues(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('/commercial/voyage-fr-containers/set-tues', form);
            notification.showSuccess(status);
            //getFrContainerList(form);

        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }

        // try {
        //     Api.post("commercial/voyage-fr-containers/set-tues", form)
        //         .then((response) => {
        //             //router.push({ name: "commercial.voyage-fr-containers.voyage" });
        //         })
        // } catch {
        //     errors.value = Error.showError(error);
        // }
        // finally {
        //     isLoading.value = false;
        //     NProgress.done();
        // }
    }

    async function updateFrStatus(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        form.container_type = "FR";
        try {
            const { data, status } = await Api.post('commercial/update-voyage-containers-status', form);
            notification.showSuccess(status,data.message);
            router.push({ name: "commercial.voyage-fr-containers.voyage" });
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
        getFrContainerList,
        assignFrContainerTues,
        frContainerParams,
        isLoading,
        errors,
    };
}
