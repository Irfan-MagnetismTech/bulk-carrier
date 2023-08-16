import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useComparativeStatement() {
    const router = useRouter();
    const comparativeStatements = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const comparativeStatement = ref( {
        reference_no: '',
        effective_date: '',
        expiry_date: '',
        remarks: '',
        status: '',
        comparative_statement_rates: [
            {
                comparative_statement_id: '',
                supplier_name: '',
                supplier_id: '',
                material_name: '',
                material_id: '',
                price: [
                    {
                        price: '',
                    }
                ],
            }
        ],
        comparative_statement_suppliers: [
            {
                comparative_statement_id: '',
                supplier_id: '',
                supplier_name: '',
                collection_way: '',
                grade: '',
                is_vat_included: '',
                is_tax_included: '',
                credit_period: '',
                material_availability: '',
                delivery_condition: '',
                required_date: '',
                is_selected: 0,
            }
        ],
        comparative_statement_materials: [
            {
                comparative_statement_id: '',
                material_id: '',
                unit: '',
            }
        ],
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getComparativeStatements(page,isPaginate = null) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/scm/comparative-statements',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || null,
                },
            });
            comparativeStatements.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }

    }

    async function storeComparativeStatement(form) {

        let comparativeStatementRates = [];
        form.comparative_statement_rates.forEach((item, index) => {
            item.price.forEach((itemPrice, indexPrice) => {
                let data = {
                    supplier_id: form.comparative_statement_suppliers[indexPrice].supplier_id,
                    material_id: item.material_id,
                    price: itemPrice,
                };
                comparativeStatementRates.push(data);
            });
        });
        form['comparative_statement_rates'] = null;
        form['comparative_statement_rates'] = comparativeStatementRates;

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/scm/comparative-statements', form);
            comparativeStatement.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.comparative-statement.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showComparativeStatement(comparativeStatementId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/scm/comparative-statements/${comparativeStatementId}`);
            comparativeStatement.value = data.value;
            comparativeStatement.value.comparative_statement_rates = comparativeStatement.value.processed_rates;

            comparativeStatement.value.comparative_statement_materials.forEach((item, index) => {
                comparativeStatement.value.comparative_statement_materials[index].unit = item.material.unit;
            });

            comparativeStatement.value.comparative_statement_suppliers.forEach((item, index) => {
                item['supplier_name'] = item.vendor.name;
            });

        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateComparativeStatement(form, comparativeStatementId) {

        let comparativeStatementRates = [];
        form.comparative_statement_rates.forEach((item, index) => {
            item.price.forEach((itemPrice, indexPrice) => {
                let data = {
                    supplier_id: form.comparative_statement_suppliers[indexPrice].supplier_id,
                    material_id: item.material_id,
                    price: itemPrice,
                };
                comparativeStatementRates.push(data);
            });
        });
        form['comparative_statement_rates'] = null;
        form['comparative_statement_rates'] = comparativeStatementRates;

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/scm/comparative-statements/${comparativeStatementId}`,
                form
            );
            comparativeStatement.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "scm.comparative-statement.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteComparativeStatement(comparativeStatementId) {

        if (!confirm('Are you sure you want to delete this comparative statement?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/scm/comparative-statements/${comparativeStatementId}`);
            notification.showSuccess(status);
            await getComparativeStatements();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        comparativeStatements,
        comparativeStatement,
        getComparativeStatements,
        storeComparativeStatement,
        showComparativeStatement,
        updateComparativeStatement,
        deleteComparativeStatement,
        isLoading,
        errors,
    };
}
