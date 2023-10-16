import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from 'sweetalert2';

export default function useItem() {
    const router = useRouter();
    const items = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const shipDepartmentWiseItems = ref([]);    
    const itemGroupWiseHourlyItems = ref([]);
    const item = ref( {
        mnt_ship_department_id: '',
        mnt_item_group_id: '',
        name: '',
        item_code: '',
        description: [{ key: '', value: '' }],
        has_run_hour: false,
        present_run_hour: 0,
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getItems(page) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/mnt/items',{
                params: {
                    page: page || 1,
                },
            });
            items.value = data.value;
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

    async function storeItem(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/items', form);
            item.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "maintenance.item.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showItem(itemId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/items/${itemId}`);
            item.value = data.value;
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

    async function updateItem(form, itemId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/mnt/items/${itemId}`,
                form
            );
            item.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "maintenance.item.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteItem(itemId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/mnt/items/${itemId}`);
            notification.showSuccess(status);
            await getItems();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getItemCodeByGroupId(formData, mntItemGroupId){
        // NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-mnt-item-code/${mntItemGroupId}`);
            formData.item_code = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            // NProgress.done();
        }
    }

    
    async function getShipDepartmentWiseItems(mntShipDepartmentId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-mnt-ship-department-wise-items/${mntShipDepartmentId}`);
            shipDepartmentWiseItems.value = data.value;
            console.log(shipDepartmentWiseItems);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getItemGroupWiseHourlyItems(mntItemGroupId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-mnt-item-group-wise-hourly-items/${mntItemGroupId}`);
            itemGroupWiseHourlyItems.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }
    

    return {
        items,
        item,
        shipDepartmentWiseItems,
        itemGroupWiseHourlyItems,
        getItems,
        storeItem,
        showItem,
        updateItem,
        deleteItem,
        getItemCodeByGroupId,
        getShipDepartmentWiseItems,
        getItemGroupWiseHourlyItems,
        isLoading,
        errors,
    };
}
