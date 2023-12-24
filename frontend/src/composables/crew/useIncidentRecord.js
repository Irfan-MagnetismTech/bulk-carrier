import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from "sweetalert2";

export default function useIncidentRecord() {
    const router = useRouter();
    const incidentRecords = ref([]);
    const isTableLoading = ref(false);
    const $loading = useLoading();
    const notification = useNotification();
    const incidentRecord = ref( {
        business_unit: '',
        ops_vessel_id: '',
        ops_vessel_name: '',
        date_time: '',
        type: '',
        location: '',
        reported_by: '',
        attachment: '',
        description: '',
        crwIncidentParticipants: [
            {
                crw_crew_id: '',
                crw_crew_name: null,
                crw_crew_contact: '',
                injury_status: '',
                notes: '',
                isCrewNameDuplicate: false,
            }
        ]
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getIncidentRecords(filterOptions) {

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
            const {data, status} = await Api.get('/crw/crw-incidents',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            incidentRecords.value = data.value;
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

    async function storeIncidentRecord(form) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            let formData = new FormData();
            formData.append('attachment', form.attachment);
            formData.append('data', JSON.stringify(form));

            try {
                const { data, status } = await Api.post('/crw/crw-incidents', formData);
                incidentRecord.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.incidentRecords.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function showIncidentRecord(incidentRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-incidents/${incidentRecordId}`);
            incidentRecord.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateIncidentRecord(form, incidentRecordId) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            let formData = new FormData();
            formData.append('attachment', form.attachment);
            formData.append('data', JSON.stringify(form));
            formData.append('_method', 'PUT');

            try {
                const { data, status } = await Api.post(
                    `/crw/crw-incidents/${incidentRecordId}`,
                    formData
                );
                incidentRecord.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.incidentRecords.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function deleteIncidentRecord(incidentRecordId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-incidents/${incidentRecordId}`);
            notification.showSuccess(status);
            await getIncidentRecords(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    function checkUniqueArray(form){
        const itemNamesSet = new Set();
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.crwIncidentParticipants.some((item,index) => {
            if (itemNamesSet.has(item.crw_crew_name)) {
                let data = `Duplicate Crew Name [line no: ${index + 1}]`;
                messages.value.push(data);
                form.crwIncidentParticipants[index].isCrewNameDuplicate = true;
            } else {
                form.crwIncidentParticipants[index].isCrewNameDuplicate = false;
            }
            itemNamesSet.add(item.crw_crew_name);
        });

        if (messages.value.length > 0) {
            let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
            if (Object.keys(messages.value).length) {
                for (const property in messages.value) {
                    rawHtml += `<li> ${messages.value[property]} </li>`
                }
                rawHtml += `</ul>`;

                Swal.fire({
                    icon: "",
                    title: "Correct Please!",
                    html: `
                ${rawHtml}
                        `,
                    customClass: "swal-width",
                });
                return false;
            }
        } else {
            return true;
        }
    }

    return {
        incidentRecords,
        incidentRecord,
        getIncidentRecords,
        storeIncidentRecord,
        showIncidentRecord,
        updateIncidentRecord,
        deleteIncidentRecord,
        checkUniqueArray,
        isTableLoading,
        isLoading,
        errors,
    };
}
