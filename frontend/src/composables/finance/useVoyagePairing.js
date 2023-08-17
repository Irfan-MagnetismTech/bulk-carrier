import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useVoyagePairing() {
    const router = useRouter();
    const notification = useNotification();
	const isLoading = ref(false);
	const errors = ref(null);
    const pairList = ref([]);
    const unpairedVoyages = ref([]);

    const pairs = ref( {});

    async function storeVoyagePair() {
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('/finance/voyage-pairings', pairs.value);
            notification.showSuccess(status);
            router.push({ name: "finance.voyage-pairing.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        }
        isLoading.value = false;
    }

    async function getAllPairs()
    {
        isLoading.value = true;
        try {
            const { data, status } = await Api.get('/finance/voyage-pairings');
            notification.showSuccess(status);
            pairList.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        }
        isLoading.value = false;
    }

    async function getUnpairedVoyages()
    {
        isLoading.value = true;
        try {
            const { data, status } = await Api.get('/finance/unpaired-voyages');
            notification.showSuccess(status);
            unpairedVoyages.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        }
        isLoading.value = false;
    }

    async function showVoyagePair(pairId) {
        isLoading.value = true;
		NProgress.start();

        try {
            const { data, status } = await Api.get('/finance/voyage-pairings/' + pairId);
            notification.showSuccess(status);
            pairs.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        }
        isLoading.value = false;
		NProgress.done();

    }

    async function updateVoyagePair(pairs, pairId) {
        isLoading.value = true;
        // alert(pairs);
        try {
            const { data, status } = await Api.put(`/finance/voyage-pairings/${pairId}`, pairs);
            notification.showSuccess(status);
            router.push({ name: "finance.voyage-pairing.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        }
        isLoading.value = false;
    }

    async function deletePair(pairId) {
        if (!confirm('Are you sure you want to delete this pair?')) {
			return;
		}
		NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/finance/voyage-pairings/${pairId}`);
			notification.showSuccess(status);
			await getAllPairs();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    async function getPairedVoyageName(searchTerm) {
		NProgress.start();
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'finance/get/voyage-pairs',
				{ searchTerm }
			);
			pairList.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

    return {
        pairs,
        errors,
        storeVoyagePair,
        updateVoyagePair,
        pairList,
        getAllPairs,
        showVoyagePair,
        deletePair,
        getPairedVoyageName,
        getUnpairedVoyages,
        unpairedVoyages
    }
}