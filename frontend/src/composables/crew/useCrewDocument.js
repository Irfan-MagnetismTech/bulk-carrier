import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useCrewDocument() {
    const router = useRouter();
    const crewDocuments = ref([]);
    const crewRenewDocuments = ref([]);
    const isTableLoading = ref(false);
    const crewRenewDocument = ref();
    const isCrewDocumentAddModalOpen = ref(0);
    const isCrewDocumentRenewModalOpen = ref(0);
    const isDocumentEditModal = ref(0);
    const currentCrewDocRenewData = ref(null);
    const $loading = useLoading();
    const notification = useNotification();
    const crewDocument = ref( {
        business_unit: '',
        id: '',
        crw_crew_id: '',
        crw_crew_profile_id: '',
        crw_crew_name: '',
        crw_crew_rank: '',
        crw_crew_contact: '',
        crw_crew_email: '',
        document_name: '',
        issuing_authority: '',
        validity_period: '',
        issue_date: null,
        expire_date: null,
        reference_no: null,
        validity_period_in_month: '',
        attachment: '',
        // crwCrewDocumentRenewals: [
        //     {
        //         crw_crew_id: '',
        //         name: '',
        //         issuing_authority: '',
        //         validity_period: '',
        //         issue_date: '',
        //         reference_no: '',
        //         validity_period_in_month: '',
        //         attachment: '',
        //     }
        // ]
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getCrewDocuments(filterOptions) {

        let loader = null;
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
            const {data, status} = await Api.get('/crw/crw-crew-documents',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            crewDocuments.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
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

    async function storeCrewDocument(form,crewDocuments) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        if(form.attachment){
            formData.append('attachment', form.attachment);
        }
        formData.append('data', JSON.stringify(form));

        try {
            if(form.id){
                const { data, status } = await Api.put(`/crw/crw-crew-documents/${form.id}`, form);
                let crewDoc = crewDocuments.find(doc => doc.id === form.id);
                crewDoc.name = data.value.name;
                crewDoc.issuing_authority = data.value.issuing_authority;
                crewDoc.validity_period = data.value.validity_period;
                isDocumentEditModal.value = 0;
            } else {
                const { data, status } = await Api.post('/crw/crw-crew-documents', formData);
                crewDocuments.push(data.value);
            }
            isCrewDocumentAddModalOpen.value = 0;
            notification.showSuccess(status);
            //await router.push({ name: "crw.documents.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function showCrewDocument(documentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-crew-documents/${documentId}`);
            crewDocument.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCrewDocument(form, documentId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();

        if(form.attachment){
            formData.append('attachment', form.attachment);
        }

        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(
                `/crw/crw-crew-documents/${documentId}`,
                formData
            );
            crewDocument.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.documents.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewDocument(docDataId,docDataIndex,crewDocuments) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-crew-documents/${docDataId}`);
            notification.showSuccess(status);
            await getCrewDocuments(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCrewRenewDocument(form, currentCrewDocRenewData) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('/crw/crw-crew-document-renewals', formData);
            currentCrewDocRenewData.unshift(data.value);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function updateCrewRenewDocument(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(
                `/crw/crw-crew-document-renewals/${form.id}`,
                formData
            );
            //currentCrewDocRenewData.crwCrewDocumentRenewals[index] = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCrewRenewDocument(renewDataId,renewDataIndex,crewDocumentRenewals) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-crew-document-renewals/${renewDataId}`);
            crewDocumentRenewals.splice(renewDataIndex, 1);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        crewDocuments,
        crewDocument,
        isCrewDocumentAddModalOpen,
        isCrewDocumentRenewModalOpen,
        crewRenewDocuments,
        crewRenewDocument,
        currentCrewDocRenewData,
        isDocumentEditModal,
        getCrewDocuments,
        storeCrewDocument,
        storeCrewRenewDocument,
        updateCrewRenewDocument,
        showCrewDocument,
        updateCrewDocument,
        deleteCrewDocument,
        deleteCrewRenewDocument,
        isTableLoading,
        isLoading,
        errors,
    };
}
