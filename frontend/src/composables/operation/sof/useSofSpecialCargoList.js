import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../../apis/Api';
import Error from '../../../services/error';
import useNotification from '../../useNotification.js';

export default function useSofSpecialCargoList() {
	const items = ref([]);
	const isLoading = ref(false);
	const notification = useNotification();
	const isSpecialCargoListModalOpen = ref(0);
	const singleData = ref( {
		id: '',
		sector: '',
		incident_date: '',
		container: '',
		class: '',
		un: '',
		stow: '',
		approval_code: '',
		remarks: '',
	});
	const errors = ref(null);

	async function storeData(form, voyageId) {
		NProgress.start();
		isLoading.value = true;
		form.voyage_id = voyageId;
		form.sof_type = "dg";
		try {
			const { data, status } = await Api.post('/operation/sof-update', form);
			isSpecialCargoListModalOpen.value = 0;
			await getList(voyageId, "special_cargo");
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getList(voyageId, sofType) {
		NProgress.start();
		isLoading.value = true;

		try {
			const {data, status} = await Api.get('/operation/sof-list/' + voyageId + '/' + sofType);
			items.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function showVoyageSofDgList(voyageSofId) {
		NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/operation/show-sof/${voyageSofId}`);
			singleData.value = data.value;
			isSpecialCargoListModalOpen.value = 1;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function deleteVoyageSofDgList(voyageSofId, voyageId) {
		if (!confirm('Are you sure you want to delete this voyage sof?')) {
			return;
		}
		NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/operation/sof/${voyageSofId}`);
			await getList(voyageId, "special_cargo");
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
		singleData,
		items,
		storeData,
		getList,
		showVoyageSofDgList,
		deleteVoyageSofDgList,
		isSpecialCargoListModalOpen,
		errors,
		isLoading,
	};
}