import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../../apis/Api';
import Error from '../../../services/error';
import {useLoading} from 'vue-loading-overlay';
import useNotification from '../../../composables/useNotification.js';

export default function useVoyageSof() {
	const router = useRouter();
	const voyageSofLists = ref([]);
	const isLoading = ref(false);
	const $loading = useLoading();
	const page = ref("create")
	const notification = useNotification();
	const isVoyageSofModalOpen = ref(0);
	const voyageSof = ref( {
		terminal_works: [
			{
			  description: null,
			  commence: null,
			  complete: null,
			  break_down: null,
			  meal: null,
			  other: null,
			  gross_rate: null,
			  net_rate: null,
			  berth_productivity: null
			},
		],
		sof_times: {
			arrival:null,
			berthing: null,
			departure: null,
			next_port: null,

			master_eta_pilot_station: null,
			anchored: null,
			anchored_aweigh: null,
			pilot_on_board: null,
			first_line_ashore: null,
			secured_berth: null,
			commenced_cargo: null,
			completed_cargo: null,
			pilot_on_board_departure: null,
			unberth: null,
			eta_next_port: null
		},
		usage: {
			arrival_rob_fwd: null,
			arrival_rob_fwd_2: null,
			arrival_rob_fo: null,
			arrival_rob_do: null,
			arrival_rob_lo: null,
			arrival_rob_fw: null,

			departure_rob_fwd: null,
			departure_rob_fwd_2: null,
			departure_rob_fo: null,
			departure_rob_do: null,
			departure_rob_lo: null,
			departure_rob_fw: null,

			arrival_bunker_fo: null,
			arrival_bunker_do: null,
			arrival_bunker_lsfo: null,
			arrival_bunker_lsdo: null,
			departure_bunker_fo: null,
			departure_bunker_do: null,
			departure_bunker_lsfo: null,
			departure_bunker_lsdo: null,

			water_supplied: null,
			arrival_gm: null,
			departure_gm: null,

			arrival_fuel: null,
			departure_fuel: null,
			arrival_diesel: null,
			departure_diesel: null,
			arrival_fresh_water: null,
			departure_fresh_water: null,
			arrival_ballast: null,
			departure_ballast: null,
			arrival_draft_fwd: null,
			departure_draft_fwd: null,
			arrival_draft_aft: null,
			departure_draft_aft: null,
			arrival_gm: null,
			departure_gm: null,
			arrival_no_of_tug: null,
			departure_no_of_tug: null,
			arrival_displacement: null,
			departure_displacement: null,
			arrival_dwt: null,
			departure_dwt: null,

			hatch_cover: null,
			gear_bin: null,

			supply_fuel: null,
			supply_diesel: null,
			supply_fresh_water: null,
			supply_discharging: null,
			supply_sludge: null,
			supply_garbage: null,
			departure: null,

			remarks: null,
			delays: null,
			ships_mail: null,
		},
		excel_file: {
			file: null
		}
	});
	const errors = ref(null);
	const sofs = ref(null);

	async function storeVoyageSofList(form, voyageId, sofType) {
		NProgress.start();
		isLoading.value = true;
		form.voyage_id = voyageId;
		form.sof_type = sofType;
		try {
			const { data, status } = await Api.post('/operation/sof-update', form);
			isVoyageSofModalOpen.value = 0;
			await getVoyageSofList(voyageId, sofType);
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function storeVoyageSof(form, voyageId, port, excelFile, sofId) {
		NProgress.start();
		isLoading.value = true;
		form.value.voyage_id = voyageId;
		form.value.port = port;

		// console.log(form)

		let formData = new FormData();
		formData.append('info', JSON.stringify(form.value));
		formData.append('file', excelFile?.file);

		try {
	
			if(sofId != null || sofId != undefined) {
				const { data, status } = await Api.post('/operation/sof-update/'+sofId, formData);
				notification.showSuccess(status,"Data Updated successfully.");
				router.push({ name: "operation.sof.index" });
			} else {
				const { data, status } = await Api.post('/operation/sofs', formData);
				// alert("Outside")
				notification.showSuccess(status,"Data saved successfully.");
				router.push({ name: "operation.sof.index" });

			}

		} catch (error) {
			const { data, status } = error.response;
			data.errors = [data.message, data.type, data.value];
			errors.value = notification.showError(status, data);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getVoyageSofList(voyageId, sofType) {
		NProgress.start();
		isLoading.value = true;

		try {
			const {data, status} = await Api.get('/operation/sof-list/' + voyageId + '/' + sofType);
			voyageSofLists.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function showVoyageSofList(voyageSofId) {
		NProgress.start();
		isLoading.value = true;

		try {
			// const { data, status } = await Api.get(`/operation/show-sof/${voyageSofId}`);
			const { data, status } = await Api.get(`/operation/sofs/${voyageSofId}`);
			voyageSof.value = data.value;
			voyageSof.value.excel_file = { file : null }
			isVoyageSofModalOpen.value = 1;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function deleteVoyageSofList(voyageSofId, voyageId, sofType) {
		if (!confirm('Are you sure you want to delete this voyage sof?')) {
			return;
		}
		NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/operation/sof/${voyageSofId}`);
			await getVoyageSofList(voyageId, sofType);
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getAllSofs() {
		NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		Api.get("operation/sofs" )
			.then((response) => {
				sofs.value = response.data.value.data
			})
			.catch((error) => {
				// error.value = Error.showError(error);
				console.log(error)
			})
			.finally(() => {
				loader.hide();
				isLoading.value = false;
				NProgress.done();
			});
	}

	return {
		voyageSof,
		page,
		voyageSofLists,
		storeVoyageSofList,
		storeVoyageSof,
		getVoyageSofList,
		showVoyageSofList,
		deleteVoyageSofList,
		isVoyageSofModalOpen,
		errors,
		sofs,
		getAllSofs,
		isLoading,
	};
}