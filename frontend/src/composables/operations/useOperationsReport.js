import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useOperationsReport() {
	const router = useRouter();
	const operationsReport = ref([]);
	const lighterVoyageReport = ref([]);
	const $loading = useLoading();
    const isTableLoading = ref(false);
	const notification = useNotification();

    const errors = ref('');
    const isLoading = ref(false);

	async function portWiseExpenseReport(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/port-wise-expense-report', form);

			operationsReport.value = data.value;
			// notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getLighterVoyageReport(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/lighter-voyage-report', form);

			operationsReport.value = data.value;
			// notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	return {
		operationsReport,
		lighterVoyageReport,
		portWiseExpenseReport,
		getLighterVoyageReport,
		isTableLoading,
		isLoading,
		errors,
	};
}
