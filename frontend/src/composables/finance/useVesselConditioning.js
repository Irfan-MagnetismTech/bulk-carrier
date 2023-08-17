import NProgress from "nprogress";
import { useLoading } from "vue-loading-overlay";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../useNotification.js';


export default function useVesselConditioning() {
  const router = useRouter();
  const notification = useNotification();
  const vesselConditionings = ref([]);
  const $loading = useLoading();
  const errors = ref([]);
  const isLoading = ref(false);
  const page = ref("create")
  const vesselConditioning = ref({
    voyages_id: "",
    port: "",
    voyage: "",
    terminal: "",
    remark: "",
    vessel_info: {
      first_pilot_on: null,
      arrival_anchorage: null,
      berth: null,
      next_port: "",
      eta_next_port: null,
      departure_unberth: null,
      departure_anchorage: null,
      last_pilot_off: null,
      average_number_of_used_crane: "",
      hatch_cover_handing: "",
      gear_box_handing: "",
      total_container_handing_moves: "",
      gross_working_hours: "",
      net_working_hours: "",
      lost_time_by_g_crane: "",
      gross_gang_hours: "",
      net_gang_hours: "",
      terminal_gross_productivity: "",
      terminal_net_productivity: "",
      gc_gross_productivity: "",
      gc_net_productivity: "",
    },
    terminal_works: [
      {
        description: "",
        commence: null,
        complete: null,
        break_down: "",
        meal: "",
        weather: "",
        other: "",
      },
    ],
    vessel_usage: {
      arrival_draft_fwd: "",
      arrival_draft_aft: "",
      departure_draft_fwd: "",
      departure_draft_aft: "",
      arrival_ballast: "",
      departure_ballast: "",
      arrival_rob_fo: "",
      arrival_rob_do: "",
      arrival_rob_fw: "",
      arrival_rob_lsfo: "",
      arrival_rob_lsdo: "",
      departure_rob_fo: "",
      departure_rob_do: "",
      departure_rob_fw: "",
      departure_rob_lsfo: "",
      departure_rob_lsdo: "",
      arrival_bunker_fo: "",
      arrival_bunker_do: "",
      departure_bunker_fo: "",
      departure_bunker_do: "",
      departure_bunker_lsfo: "",
      departure_bunker_lsdo: "",
      arrival_fw_supply: "",
      departure_fw_supply: "",
      arrival_dead_weight: "",
      departure_dead_weight: "",
      arrival_displacement: "",
      departure_displacement: "",
      arrival_stability: "",
      departure_stability: "",
      arrival_no_of_tug: "",
      departure_no_of_tug: "",
    },
  });

  const agentTdrs = ref([]);

  const customTdr = ref({
    file: null
  });

  async function storeVesselConditioning(form, customTdr) {
    NProgress.start();
    const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
    isLoading.value = true;

    console.log("storeVesselConditioning", form);


    let formData = new FormData();
    formData.append('info', JSON.stringify(form));
    formData.append('file', customTdr.file);

    try {

      const { data, status } = await Api.post('finance/agent-tdr', formData);
      notification.showSuccess(status,"Data saved successfully.");
      router.push({ name: "finance.vessel-conditioning.index" });

    } catch (error) {
      const { data, status } = error.response;
      data.errors = [data.message, data.type, data.value];
      errors.value = notification.showError(status, data);
    } finally {
        isLoading.value = false;
			  loader.hide();
        NProgress.done();
    }
}

    // Api.post("finance/agent-tdr", formData )
    //     .then(() => {
    //         router.push({ name: "finance.vessel-conditioning.index" });
    //         notification.showSuccess(status,"Information saved successfully");
    //     })
    //     .catch((error) => {
    //         error.value = Error.showError(error);
    //     })
    //     .finally(() => {
    //         loader.hide();

    //         isLoading.value = false;
    //         NProgress.done();
    //     });
    // }

  async function updateVesselConditioning(form, customTdr, agentTdrId) {

    
    const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

    let formData = new FormData();
    formData.append('info', JSON.stringify(form));
    formData.append('file', customTdr.file);

		try {
			const { data, status } = await Api.post('finance/agent-tdr/'+agentTdrId, formData);
			notification.showSuccess(status, 'Data updated!');
			await router.push({ name: "finance.vessel-conditioning.index" });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
  }

  async function getAllAgentTdr() {

    NProgress.start();
    const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
    isLoading.value = true;

    Api.get("finance/agent-tdr" )
        .then((response) => {
            agentTdrs.value = response.data.value.data
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

  async function updateAgentTdrStatus(agentTdrId, statusCode) {
    if (!confirm('Are you sure you want to change this TDR status?')) {
			return;
		}
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get( `/finance/update-agent-tdr-status/${agentTdrId}/${statusCode}`);
			notification.showSuccess(status);
			await getAllAgentTdr();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}

  }

  async function getTdrDetails(agentTdrId) {
    NProgress.start();
    const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
    isLoading.value = true;

    Api.get("finance/agent-tdr/"+agentTdrId )
        .then((response) => {
          vesselConditioning.value = response.data.value
          page.value = "edit"
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

  async function deleteAgentTdr(agentTdrId) {
    if (!confirm('Are you sure you want to delete this TDR and its all data?')) {
			return;
		}
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/finance/agent-tdr/${agentTdrId}`);
			notification.showSuccess(status);
			await getAllAgentTdr();
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
    errors,
    customTdr,
    agentTdrs,
    page,
    getTdrDetails,
    getAllAgentTdr,
    vesselConditioning,
    deleteAgentTdr,
    storeVesselConditioning,
    updateVesselConditioning,
    updateAgentTdrStatus
  };
}
