import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';
import useDropZone from '../../services/dropZone.js';

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
            description: '',
            sample_photo: null
        });

    const indexPage = ref(null);
    
    const errors = ref('');
    const isLoading = ref(false);

    async function getMaterials(page,columns = null, searchKey = null, table = null) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;

        indexPage.value = page;

        try {
            const {data, status} = await Api.get('/scm/materials', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
				},
			});
            materials.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
            console.log(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function storeMaterial(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;
        const formData = processFormData(form);

        try {
            const { data, status } = await Api.post('/scm/materials', formData);
            material.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material.index" });
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
        const formData = processFormData(form,true);
        try {
            const { data, status } = await Api.put(
                `/scm/materials/${materialId}`,
                formData
            );
            material.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.material.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteMaterial(materialId) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': 'purple'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/materials/${materialId}`);
            notification.showSuccess(status);
            await getMaterials(indexPage.value);
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

    function processFormData(form,is_update = false){
        let formData = new FormData();
        formData.append('sample_photo', form.sample_photo);
        formData.append('description', form.description);
        formData.append('store_category', form.store_category);
        formData.append('minimum_stock', form.minimum_stock);
        formData.append('hs_code', form.hs_code);
        formData.append('unit', form.unit);
        formData.append('scm_material_category_id', form.scm_material_category_id);
        formData.append('material_code', form.material_code);
        formData.append('name', form.name);
        if(is_update){
            formData.append('_method', 'PUT');
        }
        return formData;
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
        errors
    };
}
