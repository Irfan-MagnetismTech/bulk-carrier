import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useRank() {
    const router = useRouter();
    const ranks = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const rank = ref( {
        name: '',
        short_name: '',
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getRanks(page) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/crw/crw-ranks',{
                params: {
                    page: page || 1,
                },
            });
            rank.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeRank(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-ranks', form);
            rank.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.ranks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showRank(rankId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/ranks/${rankId}`);
            rank.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateRank(form, rankId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/ranks/${rankId}`,
                form
            );
            rank.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.ranks.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteRank(rankId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/ranks/${rankId}`);
            notification.showSuccess(status);
            await getRanks();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        ranks,
        rank,
        getRanks,
        storeRank,
        showRank,
        updateRank,
        deleteRank,
        isLoading,
        errors,
    };
}
