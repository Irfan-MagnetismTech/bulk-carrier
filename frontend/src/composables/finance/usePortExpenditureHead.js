import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function usePortExpenditureHead() {
    const router = useRouter();
    const $loading = useLoading();
    const portHeads = ref([]);
    const notification = useNotification();
	const isLoading = ref(false);
	const errors = ref(null);

    const portExpenditureHead = ref( {
        port: '',
        port_name: null,
        heads: [],
    });

    async function getPortHeads() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        try {
            const { data, status } = await Api.get('/finance/port-expenditure-heads');
            portHeads.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
			errors.value = notification.showError(status);
        } finally {
            //NProgress.done();
            loader.hide();
			isLoading.value = false;
        }
    }

    async function storePortHead(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/finance/port-expenditure-heads', form);
            portExpenditureHead.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.port-wise-head-generation.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function showPortHead(headId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/finance/port-expenditure-heads/${headId}`);
            portExpenditureHead.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function updatePortHead(form, headId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.put(
                `/finance/port-expenditure-heads/${headId}`,
                form
            );
            portExpenditureHead.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.port-wise-head-generation.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deletePortHead(headId) {
        if (!confirm('Are you sure you want to delete this port expenditure head?')) {
            return;
        }
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/finance/port-expenditure-heads/${headId}`);
            notification.showSuccess(status);
            await getPortHeads();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        portHeads,
        portExpenditureHead,
        storePortHead,
        showPortHead,
        updatePortHead,
        deletePortHead,
        isLoading,
        getPortHeads,
        errors
    }
}