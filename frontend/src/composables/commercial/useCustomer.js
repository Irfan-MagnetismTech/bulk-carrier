import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useCustomer() {
    const router = useRouter();
    const customers = ref([]);
    const $loading = useLoading();
    const customerCodeName = ref([]);
    const countryName = ref([]);
    const notification = useNotification();
    const customer = ref( {
        customer_code: '',
        similar_codes_name: '',
        similar_codes: '',
        customer_name: '',
        company_name: '',
        postal_address: '',
        city: '',
        post_code: '',
        country: '',
        etin_no: '',
        trade_license_no: '',
        bin_no: '',
        company_reg_no: '',
        phone: '',
        fax: '',
        customer_general_email: '',
        customer_agreement_email: '',
        customer_notice_email: '',
        // billing_party: '',
        // billing_address: '',
        // billing_emails: '',
        // billing_style: '',
        // bank_name: '',
        // account_no: '',
        // account_name: '',
        // branch: '',
        // routing_no: '',
        // currency: '',
        // special_instruction: '',
        agents: [{
            port_code: '',
            port_code_name: '',
            port_name: '',
            agent_name: '',
            // billing_name: '',
            billing_email: '',
            shipping_address: '',
            billing_style: '',
        }],
        contact_persons: [{
            name: '',
            email: '',
            mobile: '',
            purpose: '',
        }]
    });
    const errors = ref([]);
    const isLoading = ref(false);


    function storeCustomer(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.post("commercial/customers", form)
            .then(() => {
                customer.value = {};
                router.push({ name: "commercial.customers.index" });
            })
            .catch((error) => {
                errors.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    async function getCustomers(page, form = null) {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/commercial/customers', {
                params: {
                    page: page || 1,
                    similar_codes: form.similar_codes || null,
                    customer_code: form.customer_code || null,
                    country: form.country || null,
                    company_name: form.company_name || null,
                    page_view_type: form.page_view_type || null,
                },
            });
            customers.value = data.value;
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

    async function showCustomer(customerId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/commercial/customers/${customerId}`);
            customer.value = data.value;
            //notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            //notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }

        // Api.get(`commercial/customers/${customerId}`)
        //     .then((response) => {
        //         customer.value = response.data.value;
        //     })
        //     .catch((error) => {
        //         error.value = Error.showError(error);
        //     })
        //     .finally(() => {
        //         isLoading.value = false;
        //         NProgress.done();
        //     });
    }

    function updateCustomer(form, customerId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        Api.put(`commercial/customers/${customerId}`, form)
            .then((response) => {
                customer.value = response.data.value;
                console.log("Customer updated.");
                router.push({ name: "commercial.customers.index" });
            })
            .catch((error) => {
                errors.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    function deleteCustomer(serviceId) {
        if (!confirm('Are you sure you want to delete this customer?')) {
            return;
        }
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        Api.delete(`commercial/customers/${serviceId}`)
            .then(() => {
                getCustomers();
            })
            .catch((error) => {
                error.value = Error.showError(error);
            })
            .finally(() => {
                loader.hide();
                isLoading.value = false;
                //NProgress.done();
            });
    }

    async function getCustomerCode(customer_code) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                'commercial/get/customer/code',
                { customer_code }
            );
            customers.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function getCustomerWithoutPaginate() {
       // NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/commercial/customers/without/paginate');
            customers.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
           // NProgress.done();
        }
    }

    async function getCustomerByNameOrCode(name_or_code) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                'commercial/customers/get-customer-by-name-or-code',
                { name_or_code }
            );
            customerCodeName.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getCustomerCountryByName(name_or_code) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data } = await Api.post(
                'commercial/customers/get-customer-country-name',
                { name_or_code }
            );
            countryName.value = data.value;
        } catch (error) {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        customers,
        customer,
        storeCustomer,
        getCustomers,
        showCustomer,
        updateCustomer,
        deleteCustomer,
        getCustomerCode,
        countryName,
        getCustomerCountryByName,
        customerCodeName,
        getCustomerWithoutPaginate,
        getCustomerByNameOrCode,
        isLoading,
        errors,
    };
}
