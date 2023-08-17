import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../apis/Api";
import Error from "../services/error";
import useNotification from '../composables/useNotification.js';

export default function useContainerType() {
    const router = useRouter();
    const containerTypes = ref([]);
    const $loading = useLoading();
    const containerData = ref([]);
    const containerGroup = ref([]);
    const containerTues = ref([]);
    const notification = useNotification();
    const containerType = ref( {
        iso_code: '',
        tues: '',
        dimension: '',
        group: '',
        description: '',
        arche_type: '',
        arch_iso: '',
        tare_weight: '',
        safe_weight: '',
        class: '',
        refer_type: '',
        container_length: '',
        container_height: '',
        length_mm: '',
        height_mm: '',
        width_mm: '',
    });
    const errors = ref(null);
    const isLoading = ref(false);

    async function getContainerType(page, form = null) {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});

        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/dataencoding/container-types', {
                params: {
                    page: page || 1,
                    iso_code: form.iso_code || null,
                    group: form.group || null,
                    tues: form.tues || null,
                },
            });
            containerTypes.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            //notification.showError(status);
        } finally {
            //NProgress.done();
            loader.hide();
            isLoading.value = false;
        }
    }

     async function storeContainerType(form) {
        //NProgress.start();
         const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/dataencoding/container-types', form);
            containerType.value = data.value;
            router.push({ name: "containertype.index" });
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function showContainerType(containerTypeId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/dataencoding/container-types/${containerTypeId}`);
            containerType.value = data.value;
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

    async function updateContainerType(form, containerTypeId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/dataencoding/container-types/${containerTypeId}`,
                form
            );
            containerType.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "containertype.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function deleteContainerType(containerTypeId) {
        if (!confirm('Are you sure you want to delete this Container Type?')) {
            return;
        }
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/dataencoding/container-types/${containerTypeId}`);
            notification.showSuccess(status);
            await getContainerType();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getContainerIsoCode(iso_code) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(
                'dataencoding/containertypes/iso-code',
                { iso_code }
            );
            containerTypes.value = data.value;
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

    async function getContainerData(code_group_tues) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                '/dataencoding/container-types/get-by-code-group-tues',
                { code_group_tues }
            );
            containerData.value = data.value;
            containerGroup.value = data.value.filter((item, index) => data.value.findIndex((item2) => item2.group === item.group) === index);
            containerTues.value = data.value.filter((item, index)  =>  data.value.findIndex((item2) => item2.tues === item.tues) === index);

            //console.log(data.message);
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        containerType,
        containerTypes,
        getContainerType,
        storeContainerType,
        containerGroup,
        containerTues,
        getContainerData,
        containerData,
        updateContainerType,
        showContainerType,
        deleteContainerType,
        getContainerIsoCode,
        isLoading,
        errors,
    };
}
