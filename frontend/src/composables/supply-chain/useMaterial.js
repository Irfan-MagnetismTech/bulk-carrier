import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useMaterial() {
    const router = useRouter();
    const materials = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const material = ref( {
        name: '',
        material_code: '',
        scm_material_category_id: '',
        scm_material_category_name: '',
        unit: '',
        hs_code: '',
        minimum_stock: 0,
        store_category: '',
        description: ''
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterials() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/materials');
            materials.value = data.value;
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

    async function storeMaterial(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/materials', form);
            material.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "supply-chain.material.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showMaterial(materialId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/materials/${materialId}`);
            material.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateMaterial(form, materialId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/materials/${materialId}`,
                form
            );
            material.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "supply-chain.material.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterial(materialId) {

        if (!confirm('Are you sure you want to delete this material?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/materials/${materialId}`);
            notification.showSuccess(status);
            await getMaterials();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchMaterial(searchParam, loading) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`scm/search-material`, {params: { searchParam: searchParam }});
            materials.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            loading(false)
        }
    }

    return {
        materials,
        material,
        getMaterials,
        searchMaterial,
        storeMaterial,
        showMaterial,
        updateMaterial,
        deleteMaterial,
        isLoading,
        errors,
    };
}
