import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCheckList() {
    const router = useRouter();
    const checklists = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const checkList = ref( {
        effective_date: '',
        remarks: '',
        crwCrewChecklistLines: [
            {
                item_name: '',
                remarks: '',
            }
        ]
    });

    const errors = ref(null);
    const isLoading = ref(false);

    async function getCheckLists(page) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/crw/crw-crew-checklists',{
                params: {
                    page: page || 1,
                },
            });
            checklists.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCheckList(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-crew-checklists', form);
            checklists.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.crw-crew-checklists.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCheckList(checkListId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-crew-checklists/${checkListId}`);
            checkList.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCheckList(form, checkListId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/crw-crew-checklists/${checkListId}`,
                form
            );
            checkList.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.checklists.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCheckList(checkListId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-crew-checklists/${checkListId}`);
            notification.showSuccess(status);
            await getCheckLists();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        checklists,
        checkList,
        getCheckLists,
        storeCheckList,
        showCheckList,
        updateCheckList,
        deleteCheckList,
        isLoading,
        errors,
    };
}
