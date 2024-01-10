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
	const bulkVoyageReport = ref([]);
	const vesselBunkerReport = ref([]);
	const grossBunkerReport = ref([]);
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
			errors.value = notification.showError(status, data);
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

			lighterVoyageReport.value = data.value;
			// notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getBulkVoyageReport(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/bulk-voyage-report', form);

			bulkVoyageReport.value = data.value;
			// notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getVesselBunkerReport(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/vessel-bunker-report', form);

			vesselBunkerReport.value = data.value;
			// notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getGrossBunkerReport(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/business-unit-wise-bunker-report', form);

			grossBunkerReport.value = data.value;
			// notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	return {
		operationsReport,
		lighterVoyageReport,
		bulkVoyageReport,
		vesselBunkerReport,
		grossBunkerReport,
		portWiseExpenseReport,
		getLighterVoyageReport,
		getBulkVoyageReport,
		getVesselBunkerReport,
		getGrossBunkerReport,
		isTableLoading,
		isLoading,
		errors,
	};
}
