import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function usePolicy() {
    const router = useRouter();
    const policies = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const policy = ref( {
        business_unit: '',
        name: '',
        type: '',
        attachment: '',
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getPolicies(filterOptions) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/crw/crw-policies',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                 }
            });
            policies.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
        }
    }

    async function storePolicy(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        const formData = processFormData(form);

        try {
            const { data, status } = await Api.post('/crw/crw-policies', formData);
            policy.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.policies.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showPolicy(policyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-policies/${policyId}`);
            policy.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updatePolicy(form, policyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        const formData = processFormData(form);
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(
                `/crw/crw-policies/${policyId}`,
                formData
            );
            policy.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.policies.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deletePolicy(policyId) {

        //const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-policies/${policyId}`);
            notification.showSuccess(status);
            await getPolicies(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }
    }

    function processFormData(form){
        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('name', form.name);
        formData.append('type', form.type);
        formData.append('business_unit', form.business_unit);

        return formData;
    }

    return {
        policies,
        policy,
        getPolicies,
        storePolicy,
        showPolicy,
        updatePolicy,
        deletePolicy,
        isLoading,
        isTableLoading,
        errors,
    };
}
