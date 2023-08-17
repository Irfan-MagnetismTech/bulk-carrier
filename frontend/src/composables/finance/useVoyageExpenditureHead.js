import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useVoyageExpenditureHead() {
    const router = useRouter();
    const $loading = useLoading();
    const heads = ref([]);
    const notification = useNotification();
	const isLoading = ref(false);
	const errors = ref(null);

    const expenditureHead = ref( {
        name: '',
        is_global: '0' ,
        subheads: [
            {
                head_id: '',
                head_id_name: '',
                name: '',
                billing_type: ''
            }
        ],
    });

    async function getHeads() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        
        try {
            const { data, status } = await Api.get('/finance/voyage-expenditure-heads');
            heads.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
			errors.value = notification.showError(status);
        } finally {
            //NProgress.done();
            loader.hide();
			isLoading.value = false;
        }
    }

    async function storeHead(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/finance/voyage-expenditure-heads', form);
            expenditureHead.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.voyageExpenditureHead.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function showHead(headId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/finance/voyage-expenditure-heads/${headId}`);
            expenditureHead.value = data.value;
            console.log(data.value)
            if(data.value.subheads.length == 0) 
            {
                expenditureHead.value.subheads = [{}]
            }
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function updateHead(form, headId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            const { data, status } = await Api.put(
                `/finance/voyage-expenditure-heads/${headId}`,
                form
            );
            expenditureHead.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.voyageExpenditureHead.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteHead(headId) {
        if (!confirm('Are you sure you want to delete this expenditure head?')) {
            return;
        }
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        //NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/finance/voyage-expenditure-heads/${headId}`);
            notification.showSuccess(status);
            await getHeads();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function getSubHead(HeadId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get( `/finance/voyage-expenditure-heads/${headId}`);
            notification.showSuccess(status);
            await getHeads();
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
        heads,
        expenditureHead,
        storeHead,
        showHead,
        updateHead,
        deleteHead,
        isLoading,
        getHeads,
        errors
    }
}