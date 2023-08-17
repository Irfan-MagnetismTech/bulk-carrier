import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';

export default function useVoyageContainer() {
    const router = useRouter();
	const error = ref([]);
	const isLoading = ref(false);
	const voyageContainer = ref({
		source: '',
		voyage_id: '',
		account_id: '',
		mlo: '',
		mlo_2l: '',
		local_agent: '',
		voyage_customer_id: '',
		mlo_voyage_container: '',
		container: '',
		shipping_type: '',
		is_force_loaded: '',
		is_loaded: '',
		iso: '',
		mode_of_transport: '',
		transport_type: '',
		transport_operator: '',
		terminal: '',
		transport_id: '',
		por_code: '',
		pol_code: '',
		pod_code: '',
		delivery_port_code: '',
		fpod_code: '',
		gross_wt: '',
		actual_wt: '',
		category_e_t: '',
		status: '',
		stow: '',
		remarks: '',
		is_dg: '',
		group: '',
		imco: '',
		un: '',
		is_refer: '',
		ref_no: '',
		temp: '',
		min_temp: '',
		max_temp: '',
		temp_unit: '',
		oog: '',
		oog_overweight: '',
		oog_overwidth_left: '',
		oog_overwidth_right: '',
		oog_overwidth_front: '',
		oog_overwidth_back: '',
		break_bulk: '',
		bundle_1: '',
		bundle_2: '',
		bundle_3: '',
		bundle_4: '',
		seal: '',
		vat_non_vat: '',
		dg_note: '',
		oog_note: '',
		killed_tues: '',
		tues: '',
		gross_wt_mt: '',
		commodity_id: '',
		pol_pod: '',
		is_hold: 0,
		hold_date: '',
		release_date: '',
		tdr_remarks: ''
	});

	async function showVoyageContainer(voyageContainerId) {
		NProgress.start();
		isLoading.value = true;

		try {
			const { data } = await Api.get(`/operation/containers/${voyageContainerId}`);
			voyageContainer.value = data.value;
			voyageContainer.value.local_agent = data.value?.mlo_agents?.code
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	function updateVoyageContainer(form, voyageContainerId) {
		NProgress.start();
		isLoading.value = true;

		Api.post(`/operation/containers/${voyageContainerId}`, form)
			.then((response) => {
				voyageContainer.value = response.data.value;
				router.push({ name: 'operation.voyages.show', params: { voyageId: form?.voyage_id ?? -1 } });
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

	return {
		voyageContainer,
		showVoyageContainer,
		updateVoyageContainer,
		isLoading,
		error,
	};
}
