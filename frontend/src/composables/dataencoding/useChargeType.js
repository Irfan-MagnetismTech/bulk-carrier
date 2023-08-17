import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';


export default function useChargeType() {
	const router = useRouter();
	const $loading = useLoading();
	const chargeTypes = ref([]);
	const chargeTypeData = ref([]);
	const chargeTypeCategory = ref([]);
	const notification = useNotification();
	const chargeType = ref({
		code: '',
		name: '',
		category: '',
		unit: '',
		remarks: '',
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getChargeTypes(page = 1, form = null) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});

		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/dataencoding/chargetypes', {
				params: {
					page: page || 1,
					code: form.code || null,
					name: form.name || null,
					category: form.category || null,
				},
			});
			chargeTypes.value = data.value;
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

	async function storeChargeType(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/dataencoding/chargetypes', form);
			chargeTypes.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.chargetypes.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showChargeType(chargeTypeId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/dataencoding/chargetypes/${chargeTypeId}`);
			chargeType.value = data.value;
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

	async function updateChargeType(form, chargeTypeId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/dataencoding/chargetypes/${chargeTypeId}`,
				form
			);
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.chargetypes.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteChargeType(chargeTypeId) {
        if (!confirm('Are you sure you want to delete this charge type?')) {
            return;
        }
        
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete(
				`/dataencoding/chargetypes/${chargeTypeId}`
			);
			notification.showSuccess(status);
            await getChargeTypes();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getChargeTypeWithoutPaginate() {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.get('/dataencoding/chargetypes/without/paginate');
			chargeTypes.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function getChargeTypeData(code_name_category) {
		NProgress.start();
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'/dataencoding/chargetypes/get-by-code-name-category',
				{ code_name_category }
			);
			chargeTypeData.value = data.value;
			chargeTypeCategory.value = data.value.filter((item, index) => data.value.findIndex((item2) => item2.category === item.category) === index);

		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	return {
		chargeTypes,
		chargeType,
		getChargeTypes,
		storeChargeType,
		chargeTypeData,
		chargeTypeCategory,
		getChargeTypeData,
		showChargeType,
		updateChargeType,
        deleteChargeType,
		getChargeTypeWithoutPaginate,
		isLoading,
		errors,
	};
}
