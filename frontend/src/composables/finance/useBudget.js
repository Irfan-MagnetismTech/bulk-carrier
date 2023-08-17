import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useBudget() {
    const router = useRouter();
    const expenses = ref([]);
    const presets = ref([]);
    const entryType = ref('');
    const notification = useNotification();
	const isLoading = ref(false);
	const errors = ref(null);
    const categories = ref([]);
    const budget = ref({
        title: null,
        is_active: null,
        service_id: null,
        vessel_id: null,
        start_date: null,
        end_date: null,
    });
    const budgets = ref([]);

    const expense = ref( {
        voyage_id: '',
        port: null,
        date: null,
        type: 'expense',
        entries: [
            // {
            //     voyage_expenditure_sub_head: '',
            //     voyage_expenditure_sub_head_id: '',
            //     invoice_no: '',
            //     invoice_date: '',
            //     currency: '',
            //     amount: '',
            //     remarks: '',
            // }
        ],
    });

    const headWiseExpenseFormParams = ref( {
        expense_head: null,
        date: null,
        entries: [
            {
                port_code_name: '',
                port: '',
                voyage_id: '',
                invoice_date: '',
                invoice_no: '',
                currency: 'USD',
                amount: '',
            }
        ],
    });

    async function storeBudgetEntry(category) {
        NProgress.start();
        isLoading.value = true;
        categories.value = []

        try {
            const { data, status } = await Api.post(
                '/finance/search-expenditure-head', 
            {
                    subhead: category
                }
            );
            categories.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
			notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    

    return {
        budget,
        storeBudgetEntry
    }
}