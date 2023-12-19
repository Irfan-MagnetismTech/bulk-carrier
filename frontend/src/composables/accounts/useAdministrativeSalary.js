import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useAdministrativeSalary() {
    const router = useRouter();
    const administrativeSalaries = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const administrativeSalary = ref( {
        acc_cost_center_id: '',
        year_month: '',
        total_salary: '',
        remarks: '',
        business_unit : '',
        accSalaryLines: [
            {
                particular: '',
                amount: '',
                isParticularDuplicate: false
            }
        ]
    });

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getAdministrativeSalaries(filterOptions) {
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
            const {data, status} = await Api.get('/acc/acc-salaries',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
            });
            administrativeSalaries.value = data.value;
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

    async function storeAdministrativeSalary(form) {

        const isUnique = checkUniqueArray(form);
        if(isUnique){

            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.post('/acc/acc-salaries', form);
                administrativeSalary.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "acc.administrative-salaries.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function showAdministrativeSalary(administrativeSalaryId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-salaries/${administrativeSalaryId}`);
            administrativeSalary.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateAdministrativeSalary(form, administrativeSalaryId) {

        const isUnique = checkUniqueArray(form);

        if(isUnique){
            const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;

            try {
                const { data, status } = await Api.put(`/acc/acc-salaries/${administrativeSalaryId}`, form);
                administrativeSalary.value = data.value;
                notification.showSuccess(status);
                await router.push({ name: "acc.administrative-salaries.index" });
            } catch (error) {
                const { data, status } = error.response;
                errors.value = notification.showError(status, data);
            } finally {
                loader.hide();
                isLoading.value = false;
            }
        }
    }

    async function deleteAdministrativeSalary(administrativeSalaryId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-salaries/${administrativeSalaryId}`);
            notification.showSuccess(status);
            await getAdministrativeSalaries(filterParams.value);
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
        const hasDuplicates = form.accSalaryLines.some((item,index) => {
            if (itemNamesSet.has(item.particular)) {
                let data = `Duplicate Particular [line no: ${index + 1}]`;
                messages.value.push(data);
                form.accSalaryLines[index].isParticularDuplicate = true;
            } else {
                form.accSalaryLines[index].isParticularDuplicate = false;
            }
            itemNamesSet.add(item.particular);
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
        administrativeSalaries,
        administrativeSalary,
        getAdministrativeSalaries,
        storeAdministrativeSalary,
        showAdministrativeSalary,
        updateAdministrativeSalary,
        deleteAdministrativeSalary,
        checkUniqueArray,
        isLoading,
        isTableLoading,
        errors,
    };
}
