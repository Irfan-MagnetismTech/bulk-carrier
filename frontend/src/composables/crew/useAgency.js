import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';
import Swal from "sweetalert2";

export default function useAgency() {
    const router = useRouter();
    const agencies = ref([]);
    const $loading = useLoading();
    const isTableLoading = ref(false);
    const notification = useNotification();
    const agency = ref( {
        agency_name: '',
        legal_name: '',
        tax_identification: '',
        business_license_no: '',
        company_reg_no: '',
        address: '',
        phone: '',
        email: '',
        website: '',
        logo: '',
        country: '',
        business_unit: '',
        crwAgencyContactPersons: [
            {
                name: '',
                contact_no: '',
                email: '',
                position: '',
                purpose: '',
                isContactNoDuplicate: '',
            }
        ]
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getAgencies(filterOptions) {

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
            const {data, status} = await Api.get('/crw/crw-agencies',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            agencies.value = data.value;
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

    async function storeAgency(form) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            let formData = new FormData();
            if(form.logo){
                formData.append('logo', form.logo);
            }
            formData.append('data', JSON.stringify(form));

            try {
                const { data, status } = await Api.post('/crw/crw-agencies', formData);
                agency.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.agencies.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function showAgency(agencyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-agencies/${agencyId}`);
            agency.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateAgency(form, agencyId) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            let formData = new FormData();
            if(form.logo){
                formData.append('logo', form.logo);
            }
            formData.append('data', JSON.stringify(form));
            formData.append('_method', 'PUT');

            try {
                const { data, status } = await Api.post(
                    `/crw/crw-agencies/${agencyId}`,
                    formData
                );
                agency.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "crw.agencies.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function deleteAgency(agencyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-agencies/${agencyId}`);
            notification.showSuccess(status);
            await getAgencies(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    function checkUniqueArray(form){
        const itemNamesSet = new Set();
        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.crwAgencyContactPersons.some((item,index) => {
            if (itemNamesSet.has(item.contact_no)) {
                let data = `Duplicate Contact no [line no: ${index + 1}]`;
                messages.value.push(data);
                form.crwAgencyContactPersons[index].isContactNoDuplicate = true;
            } else {
                form.crwAgencyContactPersons[index].isContactNoDuplicate = false;
            }
            itemNamesSet.add(item.contact_no);
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
        agencies,
        agency,
        getAgencies,
        storeAgency,
        showAgency,
        updateAgency,
        deleteAgency,
        isTableLoading,
        checkUniqueArray,
        isLoading,
        errors,
    };
}
