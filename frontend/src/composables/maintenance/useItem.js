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
    const vesselWiseJobItems = ref([]);    
    const itemGroupWiseHourlyItems = ref([]);
    const itemGroupWiseItems = ref([]);
    const item = ref( {
        mnt_ship_department_id: '',
        mnt_ship_department_name: '',
        mnt_item_group_id: '',
        mnt_item_group_name: '',
        mnt_item_groups:[],
        name: '',
        item_code: '',
        description: [{ key: '', value: '' }],
        has_run_hour: false,
        business_unit: '',
        form_type: 'create'
    });

    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isItemLoading = ref(false);
    const isTableLoading = ref(false);

    async function getItems(filterOptions) {
        //NProgress.start();
        let loader = null;
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;

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
            const {data, status} = await Api.get('/mnt/items',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            items.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            //NProgress.done();
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

    async function storeItem(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/mnt/items', form);
            item.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "mnt.items.index" });
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
            await router.push({ name: "mnt.items.index" });
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
            await getItems(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            // notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getItemCodeByGroupId(formData, mntItemGroupId){
        // NProgress.start();
        isLoading.value = true;
        isItemLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-mnt-item-code/${mntItemGroupId}`);
            formData.item_code = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            isItemLoading.value = false;
            // NProgress.done();
        }
    }

    
    async function getShipDepartmentWiseItems(mntShipDepartmentId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        isItemLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-mnt-ship-department-wise-items/${mntShipDepartmentId}`);
            shipDepartmentWiseItems.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            isItemLoading.value = false;
            //NProgress.done();
        }
    }

    async function getItemGroupWiseHourlyItems(mntItemGroupId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        isItemLoading.value = true;

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
            isItemLoading.value = false;
            //NProgress.done();
        }
    }

    async function getItemGroupWiseItems(mntItemGroupId){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        isItemLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-mnt-item-group-wise-items/${mntItemGroupId}`);
            itemGroupWiseItems.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            isItemLoading.value = false;
            //NProgress.done();
        }
    }

    
    async function getVesselWiseJobItems(businessUnit, opsVesselId, mntShipDepartmentId = null, mntItemGroupId = null ){
        //NProgress.start();
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        isItemLoading.value = true;

        try {
            const { data, status } = await Api.get(`/mnt/get-vessel-wise-jobs`, {
                params: {
                    business_unit: businessUnit,
                    ops_vessel_id: opsVesselId,
                    mnt_ship_department_id: mntShipDepartmentId,
                    mnt_item_group_id: mntItemGroupId,
                    return_field: 'mntItem'
                },
            });
            vesselWiseJobItems.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            isLoading.value = false;
            isItemLoading.value = false;
            //NProgress.done();
        }
    }    
    

    return {
        items,
        item,
        shipDepartmentWiseItems,
        itemGroupWiseHourlyItems,
        itemGroupWiseItems,
        vesselWiseJobItems,
        getItems,
        storeItem,
        showItem,
        updateItem,
        deleteItem,
        getItemCodeByGroupId,
        getShipDepartmentWiseItems,
        getItemGroupWiseHourlyItems,
        getItemGroupWiseItems,
        getVesselWiseJobItems,
        isLoading,
        isTableLoading,
        isItemLoading,
        errors,
    };
}
