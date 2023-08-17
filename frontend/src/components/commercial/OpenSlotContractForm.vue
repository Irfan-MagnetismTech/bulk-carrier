<script setup>
import {ref, watch, onMounted, computed} from "vue";
import useService from "../../composables/commercial/useService";
import useCustomer from "../../composables/commercial/useCustomer";
import useCurrency from "../../composables/dataencoding/useCurrency";
import useChargeType from "../../composables/dataencoding/useChargeType";
import Editor from '@tinymce/tinymce-vue';
import usePort from "../../composables/usePort";
import useNotification from '../../composables/useNotification.js';
import useBank from "../../composables/useBank";
import MultipleDropZone from '../../components/MultipleDropZone.vue';
import {useStore} from "vuex";
import useOpenSlotContract from "../../composables/commercial/useOpenSlotContract";
const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

const props = defineProps({
  form: {
    type: [Object, Array],
    required: false,
    default: {}
  },
  customers: {
    type: [Object, Array],
    required: false,
    default: {}
  },
  services: {
    type: [Object, Array],
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  isLoading: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },

});
const notification = useNotification();
const { service, serviceGroupById, showService, uniqueServicePorts, getServiceUniquePorts } = useService();
const { customer, showCustomer } = useCustomer();
const { currencies, getCurrencyWithoutPaginate } = useCurrency();
const { chargeTypes, getChargeTypeWithoutPaginate } = useChargeType();
const { banks, bank, showBank, getBankWithoutPaginate } = useBank();

const { globalOsContractSurcharges } = useOpenSlotContract();

const { ports, portName, getPortsByNameOrCode } = usePort();

const openTab = ref(0);
const isDisable = ref(1);
const toggleTabs = (tabNumber,buttonType = null) => {
  openTab.value = tabNumber;
}

watch(() => openTab.value, (val) => {
  if(val == 1){

    if(!props.form.customer_code){
      document.getElementById('vs1__combobox')?.classList.add('vms-required-input-border');props.errors['customer_code'] = 'Customer code field is required';
    } else{
      document.getElementById('vs1__combobox')?.classList.remove('vms-required-input-border');props.errors['customer_code'] = '';
    }

    if(!props.form.service_id){
      document.getElementById('contract_service_id').classList.add('vms-required-input-border');props.errors['service_id'] = 'Service field is required';
    } else{
      document.getElementById('contract_service_id').classList.remove('vms-required-input-border');props.errors['service_id'] = '';
    }

    if(!props.form.contract_type){
      document.getElementById('contract_type').classList.add('vms-required-input-border');props.errors['contract_type'] = 'Contract type field is required';
    } else{
      document.getElementById('contract_type').classList.remove('vms-required-input-border');props.errors['contract_type'] = '';
    }

    if(!props.form.valid_from){
      document.getElementById('valid_from').classList.add('vms-required-input-border');props.errors['valid_from'] = 'Valid from field is required';
    } else{
      document.getElementById('valid_from').classList.remove('vms-required-input-border');props.errors['valid_from'] = '';
    }

    if(!props.form.valid_from_on){
      document.getElementById('valid_from_on').classList.add('vms-required-input-border');props.errors['valid_from_on'] = 'Valid from on field is required';
    } else{
      document.getElementById('valid_from_on').classList.remove('vms-required-input-border');props.errors['valid_from_on'] = '';
    }

    if(!props.form.valid_from_at){
      document.getElementById('valid_from_at').classList.add('vms-required-input-border');props.errors['valid_from_at'] = 'Valid from at field is required';
    } else{
      document.getElementById('valid_from_at').classList.remove('vms-required-input-border');props.errors['valid_from_at'] = '';
    }

    if(!props.form.valid_from_bound){
      document.getElementById('valid_from_bound').classList.add('vms-required-input-border');props.errors['valid_from_bound'] = 'Valid from bound field is required';
    } else{
      document.getElementById('valid_from_bound').classList.remove('vms-required-input-border');props.errors['valid_from_bound'] = '';
    }

    if(!props.form.valid_till){
      document.getElementById('valid_till').classList.add('vms-required-input-border');props.errors['valid_till'] = 'Valid till field is required';
    } else{
      document.getElementById('valid_till').classList.remove('vms-required-input-border');props.errors['valid_till'] = '';
    }

    if(!props.form.valid_till_on){
      document.getElementById('valid_till_on').classList.add('vms-required-input-border');props.errors['valid_till_on'] = 'Valid till on field is required';
    } else{
      document.getElementById('valid_till_on').classList.remove('vms-required-input-border');props.errors['valid_till_on'] = '';
    }

    if(!props.form.valid_till_at){
      document.getElementById('valid_till_at').classList.add('vms-required-input-border');props.errors['valid_till_at'] = 'Valid till at field is required';
    } else{
      document.getElementById('valid_till_at').classList.remove('vms-required-input-border');props.errors['valid_till_at'] = '';
    }

    if(!props.form.valid_till_bound){
      document.getElementById('valid_till_bound').classList.add('vms-required-input-border');props.errors['valid_till_bound'] = 'Valid till bound field is required';
    } else{
      document.getElementById('valid_till_bound').classList.remove('vms-required-input-border');props.errors['valid_till_bound'] = '';
    }

    if(!props.form.due_date){
      document.getElementById('due_date').classList.add('vms-required-input-border');props.errors['due_date'] = 'Due date field is required';
    } else{
      document.getElementById('due_date').classList.remove('vms-required-input-border');props.errors['due_date'] = '';
    }

    if(!props.form.billing_party){
      document.getElementById('billing_party').classList.add('vms-required-input-border');props.errors['billing_party'] = 'Billing party field is required';
    } else{
      document.getElementById('billing_party').classList.remove('vms-required-input-border');props.errors['billing_party'] = '';
    }

    if(!props.form.billing_address){
      document.getElementById('billing_address').classList.add('vms-required-input-border');props.errors['billing_address'] = 'Billing address field is required';
    } else{
      document.getElementById('billing_address').classList.remove('vms-required-input-border');props.errors['billing_address'] = '';
    }

    if(!props.form.billing_emails){
      document.getElementById('billing_emails').classList.add('vms-required-input-border');props.errors['billing_emails'] = 'Billing emails field is required';
    } else{
      document.getElementById('billing_emails').classList.remove('vms-required-input-border');props.errors['billing_emails'] = '';
    }

    if (!props.form.billing_party || !props.form.billing_address || !props.form.billing_emails || !props.form.customer_code || !props.form.service_id || !props.form.contract_type || !props.form.valid_from || !props.form.valid_from_on || !props.form.valid_from_at || !props.form.valid_from_bound || !props.form.valid_till || !props.form.valid_till_on || !props.form.valid_till_at || !props.form.valid_till_bound || !props.form.due_date) {
      notification.showError(422,'','Please fill all required fields');
      openTab.value = 0;
    }

  }
}, {
  deep: true
});

/* add and remove agent dynamic start */
function addAgent() {
  let obj = {
    port_code: '',
    name: '',
    billing_name: '',
    billing_email: '',
    billing_style: '',
  };
  props.form.agents.push(obj);
}

function removeAgent(index){
  props.form.agents.splice(index, 1);
}

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

function rateChargeTypeGroup(portIndex,rateIndex,e){
  let chargeTypeCode = e.target.value;
  chargeTypes?.value.forEach(function(chargeType){
    if(chargeType.code === chargeTypeCode){
      props.form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].charge_type_category = chargeType.category;
    }
  });
}

function addPortRate(portIndex,rateIndex){
  let obj = {
    contract_os_port_id: '',
    charge_type: '',
    charge_type_category: '',
    gp_20_ldn: 1,
    gp_40_ldn: 1,
    gp_45_ldn: 1,
    gp_20_mty: 1,
    gp_40_mty: 1,
    gp_45_mty: 1,
  };
  props.form.contract_os_ports[portIndex].contract_os_port_rates.push(obj);
}

function removePortRate(portIndex,rateIndex){
  props.form.contract_os_ports[portIndex].contract_os_port_rates.splice(rateIndex, 1);
}

function changePolPod(e,portIndex){
  props.form.contract_os_ports[portIndex].pol_pod = props.form.contract_os_ports[portIndex].pol + '-' + props.form.contract_os_ports[portIndex].pod;
}

function addPort() {
  let obj = {
    contract_id: '',
    input_type: '',
    pol: '',
    pod: '',
    pol_pod: '',
    term: '',
    f_payment_location: '',
    f_payment_currency: '',
    s_recovery: '',
    s_recovery_pol: 0,
    s_pol_bill_to: '',
    s_pol_bill_currency: '',
    s_recovery_pod: 0,
    s_pod_bill_to: '',
    s_pod_bill_currency: '',
    tues_45_container: '',
    weight_limit_20: '',
    weight_limit_40: '',
    weight_limit_45: '',
    contract_os_port_rates: [{
      contract_os_port_id: '',
      charge_type: '',
      charge_type_category: '',
      gp_20_ldn: 1,
      gp_40_ldn: 1,
      gp_45_ldn: 1,
      gp_20_mty: 1,
      gp_40_mty: 1,
      gp_45_mty: 1,
    }],
    contract_os_port_surcharges: [
      {
        contract_os_port_id: '',
        charge_type: 'DG-1',
        charge_type_category: 'surcharge',
        unit: null,
        ldn_20: 1,
        ldn_40: 1,
        ldn_45: 1,
        mty_20: 1,
        mty_40: 1,
        mty_45: 1,
      },
      {
        contract_os_port_id: '',
        charge_type: 'DG-2',
        charge_type_category: 'surcharge',
        unit: null,
        ldn_20: 1,
        ldn_40: 1,
        ldn_45: 1,
        mty_20: 1,
        mty_40: 1,
        mty_45: 1,
      },
      {
        contract_os_port_id: '',
        charge_type: 'DG-3',
        charge_type_category: 'surcharge',
        unit: null,
        ldn_20: 1,
        ldn_40: 1,
        ldn_45: 1,
        mty_20: 1,
        mty_40: 1,
        mty_45: 1,
      },
      {
        contract_os_port_id: '',
        charge_type: 'DG-C',
        charge_type_category: 'surcharge',
        unit: null,
        ldn_20: 1,
        ldn_40: 1,
        ldn_45: 1,
        mty_20: 1,
        mty_40: 1,
        mty_45: 1,
      },
      {
        contract_os_port_id: '',
        charge_type: 'RF',
        charge_type_category: 'surcharge',
        unit: null,
        ldn_20: 1,
        ldn_40: 1,
        ldn_45: 1,
        mty_20: 1,
        mty_40: 1,
        mty_45: 1,
      },
      {
        contract_os_port_id: '',
        charge_type: 'TK',
        charge_type_category: 'surcharge',
        unit: null,
        ldn_20: 1,
        ldn_40: 1,
        ldn_45: 1,
        mty_20: 1,
        mty_40: 1,
        mty_45: 1,
      },
      {
        contract_os_port_id: '',
        charge_type: 'OWS',
        charge_type_category: 'surcharge',
        unit: null,
        ldn_20: 1,
        ldn_40: 1,
        ldn_45: 1,
        mty_20: 1,
        mty_40: 1,
        mty_45: 1,
      },
    ],
  };
  props.form.contract_os_ports.push(obj);
}


watch(() => props.form.customer_code, (val) => {
  if (val?.id) {
    showCustomer(val?.id);
    props.form.customer_id = val?.id;
  }
});

watch(() => props.form.bank_id, (val) => {
  if(val){
    showBank(val);
  }
});

watch(() => bank.value, (val) => {
  if(val){
    props.form.bank_name = val?.beneficiary_bank;
    props.form.account_no = val?.beneficiary_account_no;
    props.form.account_name = val?.beneficiary_name;
    props.form.branch = val?.beneficiary_bank_branch;
    props.form.swift_code = val?.swift_code;
    props.form.routing_no = val?.beneficiary_routing_no;
    props.form.currency = val?.beneficiary_bank_currency;
  }
});

watch(() => props.form.service_id, (val) => {
  if (val) {
    serviceGroupById(val);
    getServiceUniquePorts(val);
  }
});

watch(dropZoneFile, (value) => {
  if (value !== null && value !== undefined) {
    props.form.attachment = value;
  }
}, {
  deep: true
});

function getTotalGp20Ldn(portIndex){
  let totalGp20Ldn = 0;
  props.form.contract_os_ports[portIndex].contract_os_port_rates.forEach((rate) => {
    totalGp20Ldn += parseFloat(rate.gp_20_ldn);
  });
  props.form.contract_os_ports[portIndex].total_gp_20_ldn = totalGp20Ldn.toFixed(2);
}

function getTotalGp40Ldn(portIndex){
  let totalGp40Ldn = 0;
  props.form.contract_os_ports[portIndex].contract_os_port_rates.forEach((rate) => {
    totalGp40Ldn += parseFloat(rate.gp_40_ldn);
  });
  props.form.contract_os_ports[portIndex].total_gp_40_ldn = totalGp40Ldn.toFixed(2);
}

function getTotalGp45Ldn(portIndex){
  let totalGp45Ldn = 0;
  props.form.contract_os_ports[portIndex].contract_os_port_rates.forEach((rate) => {
    totalGp45Ldn += parseFloat(rate.gp_45_ldn);
  });
  props.form.contract_os_ports[portIndex].total_gp_45_ldn = totalGp45Ldn.toFixed(2);
}

function getTotalGp20Mty(){
  let totalGp20Mty = 0;
  props.form.contract_os_ports.forEach((port) => {
    totalGp20Mty += parseFloat(port.total_gp_20_mty);
  });
  props.form.total_gp_20_mty = totalGp20Mty.toFixed(2);
}

function getTotalGp40Mty(portIndex){
  let totalGp40Mty = 0;
  props.form.contract_os_ports[portIndex].contract_os_port_rates.forEach((rate) => {
    totalGp40Mty += parseFloat(rate.gp_40_mty);
  });
  props.form.contract_os_ports[portIndex].total_gp_40_mty = totalGp40Mty.toFixed(2);
}

function getTotalGp45Mty(portIndex){
  let totalGp45Mty = 0;
  props.form.contract_os_ports[portIndex].contract_os_port_rates.forEach((rate) => {
    totalGp45Mty += parseFloat(rate.gp_45_mty);
  });
  props.form.contract_os_ports[portIndex].total_gp_45_mty = totalGp45Mty.toFixed(2);
}

watch(() => props.form.contract_os_ports, (val) => {
  val?.map(function(port, portIndex) {

    let totalGp20Ldn = 0;
    let totalGp40Ldn = 0;
    let totalGp45Ldn = 0;
    let totalGp20Mty = 0;
    let totalGp40Mty = 0;
    let totalGp45Mty = 0;

    port.contract_os_port_rates.forEach((rate, rateIndex) => {
      totalGp20Ldn += parseFloat(rate.gp_20_ldn);
      totalGp40Ldn += parseFloat(rate.gp_40_ldn);
      totalGp45Ldn += parseFloat(rate.gp_45_ldn);
      totalGp20Mty += parseFloat(rate.gp_20_mty);
      totalGp40Mty += parseFloat(rate.gp_40_mty);
      totalGp45Mty += parseFloat(rate.gp_45_mty);
    });
    props.form.contract_os_ports[portIndex].total_gp_20_ldn = isNaN(totalGp20Ldn) ? 0 : totalGp20Ldn.toFixed(2);
    props.form.contract_os_ports[portIndex].total_gp_40_ldn = isNaN(totalGp40Ldn) ? 0 : totalGp40Ldn.toFixed(2);
    props.form.contract_os_ports[portIndex].total_gp_45_ldn = isNaN(totalGp45Ldn) ? 0 : totalGp45Ldn.toFixed(2);
    props.form.contract_os_ports[portIndex].total_gp_20_mty = isNaN(totalGp20Mty) ? 0 : totalGp20Mty.toFixed(2);
    props.form.contract_os_ports[portIndex].total_gp_40_mty = isNaN(totalGp40Mty) ? 0 : totalGp40Mty.toFixed(2);
    props.form.contract_os_ports[portIndex].total_gp_45_mty = isNaN(totalGp45Mty) ? 0 : totalGp45Mty.toFixed(2);
  });
}, {
  deep: true
});

function removePort(portIndex){
  props.form.contract_os_ports.splice(portIndex, 1);
  getTotalGp20Ldn(portIndex);
  getTotalGp40Ldn(portIndex);
  getTotalGp45Ldn(portIndex);
  getTotalGp20Mty(portIndex);
  getTotalGp40Mty(portIndex);
  getTotalGp45Mty(portIndex);
}


onMounted(() => {
  getCurrencyWithoutPaginate();
  getBankWithoutPaginate();
  getChargeTypeWithoutPaginate();
});
</script>

<template xmlns="http://www.w3.org/1999/html">
  <div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(0)" v-bind:class="{'text-purple-600 bg-white': openTab !== 0, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 0}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Contract Info
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 capitalize border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Rate
        </a>
      </li>
    </ul>
    <div v-on:click="toggleTabs(0)" v-bind:class="{'hidden': openTab !== 0, 'block': openTab === 0}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Contract Info</legend>
        <div class="flex flex-col w-full md:flex-row md:gap-2">
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="text-red-500">*</span></span>
              <v-select :options="customers" :class="page==='update' ? 'vms-readonly-input' : ''" :disabled="page==='update'" placeholder="--Choose an option--" v-model="form.customer_code" class="mt-1" label="customer_code"></v-select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.customer_code">{{ errors?.customer_code }}</span>
          </div>
          <input type="hidden" v-model="form.customer_id">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Customer Legal Name</span>
            <input type="text" readonly placeholder="Customer Legal name" :value="customer.company_name" class="block w-full mt-1 text-xs rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Select a Service <span class="text-red-500">*</span></span>
              <select name="service_id" :class="page==='update' ? 'vms-readonly-input' : ''" :disabled="page==='update'" id="contract_service_id" v-model="form.service_id" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option v-for="(service,index) in services" :value="service.id" :key="index">{{ service.name }} - ({{ service.code }})</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.service_id">{{ errors?.service_id }}</span>
          </div>
        </div>
        <div class="flex flex-col w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Route</span>
            <input type="text" readonly placeholder="Service route" :value="service.route" class="block w-full mt-1 text-xs rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Contract Type <span class="text-red-500">*</span></span>
              <select :class="page==='update' ? 'vms-readonly-input' : ''" :disabled="page==='update'" name="contract_type" id="contract_type" v-model="form.contract_type" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Type--</option>
                <option value="master-contract">Master Contract</option>
                <option value="sub-contract">Sub Contract</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.contract_type">{{ errors?.contract_type }}</span>
          </div>
          <label class="block w-full mt-3 text-xs">
          </label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Contract Validity</legend>
        <div class="flex flex-col w-full md:flex-row md:gap-2">
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Valid From <span class="text-red-500">*</span></span>
              <input type="date" id="valid_from" v-model="form.valid_from" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_from">{{ errors?.valid_from }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">On <span class="text-red-500">*</span></span>
              <select v-model="form.valid_from_on" id="valid_from_on" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option value="voyage">Voyage</option>
                <option value="departure">Departure Date</option>
                <option value="arrival">Arrival Date</option>
                <option value="berthing">Berthing Date</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_from_on">{{ errors?.valid_from_on }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">At <span class="text-red-500">*</span></span>
              <select v-model="form.valid_from_at" id="valid_from_at" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option :value="port" v-for="(port,index) in uniqueServicePorts" :key="index">{{ port }}</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_from_at">{{ errors?.valid_from_at }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Bound <span class="text-red-500">*</span></span>
              <select v-model="form.valid_from_bound" id="valid_from_bound" required class="block w-full mt-1 text-xs capitalize rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option :value="bound" v-for="(bound,index) in service?.bounds" :key="index">{{ bound }}</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_from_bound">{{ errors?.valid_from_bound }}</span>
          </div>
        </div>
        <div class="flex flex-col w-full md:flex-row md:gap-2">
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Valid Till <span class="text-red-500">*</span></span>
              <input type="date" v-model="form.valid_till" id="valid_till" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_till">{{ errors?.valid_till }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">On <span class="text-red-500">*</span></span>
              <select v-model="form.valid_till_on" id="valid_till_on" required  class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option value="voyage">Voyage</option>
                <option value="departure">Departure Date</option>
                <option value="arrival">Arrival Date</option>
                <option value="berthing">Berthing Date</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_till_on">{{ errors?.valid_till_on }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">At <span class="text-red-500">*</span></span>
              <select v-model="form.valid_till_at" id="valid_till_at" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option :value="port" v-for="(port,index) in uniqueServicePorts" :key="index">{{ port }}</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_till_at">{{ errors?.valid_till_at }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Bound <span class="text-red-500">*</span></span>
              <select v-model="form.valid_till_bound" id="valid_till_bound" required class="block w-full mt-1 text-xs capitalize rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option :value="bound" v-for="(bound,index) in service?.bounds" :key="index">{{ bound }}</option>
              </select>
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.valid_till_bound">{{ errors?.valid_till_bound }}</span>
          </div>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Default Bank Information</legend>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Bank Name</span>
<!--            <input type="text" v-model="form.bank_name" name="bank_name" placeholder="Bank Name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
            <select v-model="form.bank_id" id="bank_id" class="block w-full mt-1 text-xs capitalize rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="" disabled>--Choose an Option--</option>
              <option :value="bank?.id" v-for="(bank,index) in banks" :key="index">{{ bank?.beneficiary_bank }} ({{ bank?.beneficiary_bank_branch}})</option>
            </select>
            <input type="hidden" v-model="form.bank_id">
          </label>
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Account No</span>
            <input type="text" v-model="form.account_no" name="account_no" placeholder="Account No" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Account Name</span>
            <input type="text" v-model="form.account_name" name="account_name" placeholder="Account Name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Branch</span>
            <input type="text" v-model="form.branch" name="branch" placeholder="Branch" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Swift Code</span>
            <input type="text" v-model="form.swift_code" name="swift_code" placeholder="Swift Code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Routing No</span>
            <input type="text" v-model="form.routing_no" name="routing_no" placeholder="Routing No" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>          
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Currency</span>
            <select class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" v-model="form.currency">
              <option value="" selected disabled>Choose Currency</option>
              <option :value="currency.code" v-for="(currency,index) in currencies">{{ currency.code }}</option>
            </select>
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Special Instructions</span>
            <input type="text" v-model="form.special_instruction" name="special_instruction" placeholder="Special Instructions" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Billing</legend>
        <div class="flex flex-col w-full md:flex-row md:gap-2">
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="block text-gray-700 dark:text-gray-300">Credit Days <strong class="text-red-700">*</strong></span>
              <input type="text" placeholder="Due date" id="due_date" required v-model="form.due_date" class="w-1/6 mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              <span style="font-size: 12px"> &nbsp; Days After Invoice Issue Date.</span>
            </label>
            <span class="flex text-red-500 py-1 w-full" v-if="errors?.due_date">{{ errors?.due_date }}</span>
          </div>
        </div>
        <div class="flex flex-col w-full md:flex-row md:gap-2">
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Billing Party <span class="text-red-500">*</span></span>
              <input type="text" placeholder="Billing party" id="billing_party" required v-model="form.billing_party" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.billing_party">{{ errors?.billing_party }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Billing Address <span class="text-red-500">*</span></span>
              <input type="text" placeholder="Billing address" id="billing_address" required v-model="form.billing_address" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.billing_address">{{ errors?.billing_address }}</span>
          </div>
          <div class="block w-full mt-3 text-xs">
            <label>
              <span class="text-gray-700 dark:text-gray-300">Billing Emails <span class="text-red-500">*</span></span>
              <input type="text" id="billing_emails" placeholder="a@gmail.com; b@gmail.com; c@gmail.com" required v-model="form.billing_emails" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <span class="text-red-500 py-1 w-full" v-if="errors?.billing_emails">{{ errors?.billing_emails }}</span>
          </div>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Attention </span>
            <input type="text" placeholder="Attention" v-model="form.billing_style" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
<!--            <editor v-model="form.billing_style" class="block w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" api-key="wljvu7gtfjb8h5ou2rcxw8d5tykej98zy10x8ot83jclsm3o" />-->
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Line 1 </span>
            <input type="text" placeholder="Line 1" v-model="form.line_1" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Line 2 </span>
            <input type="text" placeholder="Line 2" v-model="form.line_2" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Local Agent Info </legend>
        <div class="w-full border rounded-lg shadow-xs dark:border-0">
          <div class="w-full ">
            <table class="table w-full whitespace-no-wrap">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">Port</th>
                <th class="px-4 py-3">Agent Name </th>
                <th class="px-4 py-3">Billing Name</th>
                <th class="px-4 py-3">Billing Email</th>
                <th class="px-4 py-3">Billing Style</th>
                <th class="px-4 py-3 text-center align-bottom">Action</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400" v-for="(agent, index) in form.agents" :key="agent.id">
                <td class="px-1 py-1">
                  <table>
                    <tr>
                      <td class="md:w-2/6" style="border: 0px">
                        <v-select v-model="form.agents[index].port_code" @search="fetchOptions" value="id" :options="portName" :reduce="portName => portName.code" label="code_name" placeholder="Port code" class="w-full mt-1 placeholder-gray-600"></v-select>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table>
                    <tr>
                      <td class="md:w-2/6" style="border: 0px"><input type="text" v-model="form.agents[index].name" name="name" placeholder="Agent name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table>
                    <tr>
                      <td class="md:w-2/6" style="border: 0px"><input type="text" v-model="form.agents[index].billing_name" name="billing_name" placeholder="Billing name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table>
                    <tr>
                      <td class="md:w-2/6" style="border: 0px"><input type="text" v-model="form.agents[index].billing_email" name="billing_email" placeholder="Billing email" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table>
                    <tr>
                      <td class="md:w-2/6" style="border: 0px"><input type="text" v-model="form.agents[index].billing_style" name="billing_style" placeholder="Billing style" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1 text-center">
                  <button v-if="index!==0" type="button" @click="removeAgent(index)" class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                  <button v-else type="button" @click="addAgent()" class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Remarks & Short Note</legend>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Remarks</span>
            <textarea v-model="form.remarks" placeholder="Contract remarks" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Short Note</span>
            <textarea v-model="form.short_note" placeholder="Short note" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          </label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Attachment</legend>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <MultipleDropZone :form="form" :page="page"></MultipleDropZone>
        </div>
      </fieldset>
    </div>
    <div v-on:click="toggleTabs(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
      <fieldset v-for="(port,portIndex) in form.contract_os_ports" :key="portIndex" class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Port Wise Rate <span class="text-red-500">*</span></legend>
        <div class="mb-2 border rounded-lg dark:border-0">
          <div class="w-full mb-2 overflow-hidden shadow-xs">
            <div class="w-full overflow-x-auto">
              <table class="table w-full whitespace-no-wrap">
                <thead v-once>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-700 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="px-2 py-1 text-lg text-center">Port Information</th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
          <div class="flex flex-col items-center justify-center w-full pl-2 pr-2 mb-2 md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Input Type <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].input_type" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="" selected disabled>Choose</option>
                <option value="container">Container</option>
                <option value="tue">Tue</option>
              </select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Pol <span class="text-red-500">*</span></span>
              <v-select v-model="form.contract_os_ports[portIndex].pol" @change="changePolPod($event,portIndex)" required @search="fetchOptions" value="id" :reduce="portName => portName.code" :options="portName" label="code_name" placeholder="Port Code" class="w-full mt-1 placeholder-gray-600"></v-select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Pod <span class="text-red-500">*</span></span>
              <v-select v-model="form.contract_os_ports[portIndex].pod" @change="changePolPod($event,portIndex)" required @search="fetchOptions" value="id" :reduce="portName => portName.code" :options="portName" label="code_name" placeholder="Port Code" class="w-full mt-1 placeholder-gray-600"></v-select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Term <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].term" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">Select</option>
                <option value="FI-FO">FI-FO</option>
                <option value="FI-HK">FI-HK</option>
                <option value="FI-CY">FI-CY</option>
                <option value="HK-FO">HK-FO</option>
                <option value="HK-HK">HK-HK</option>
                <option value="HK-CY">HK-CY</option>
                <option value="CY-CY">CY-CY</option>
                <option value="CY-HK">CY-HK</option>
                <option value="CY-FO">CY-FO</option>
              </select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Payment Location <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].f_payment_location" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">Select</option>
                <option value="POL">POL</option>
                <option value="POD">POD</option>
                <option value="3rd">3rd</option>
              </select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Payment Currency <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].f_payment_currency" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="USD">USD</option>
                <option value="BDT">BDT</option>
              </select>
            </label>
          </div>
          <div class="flex flex-col items-center justify-center w-full pl-2 pr-2 mb-2 md:flex-row md:gap-2">
            <!--            <label class="block w-full mt-3 text-xs">-->
            <!--              <span class="text-gray-700 dark:text-gray-300">Steve. Recovery</span>-->
            <!--              <select v-model="form.contract_os_ports[portIndex].s_recovery" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
            <!--                <option value="" selected disabled>Choose</option>-->
            <!--                <option value="1">Yes</option>-->
            <!--                <option value="0">No</option>-->
            <!--              </select>-->
            <!--            </label>-->
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Steve. Recovery Pol <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].s_recovery_pol" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="0">No</option>
                <option value="1">Yes</option>
              </select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Steve. Pol Bill To <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].s_pol_bill_to" :disabled="form.contract_os_ports[portIndex].s_recovery_pol == '0'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="" selected disabled>Choose</option>
                <option value="customer">Customer</option>
                <option value="agent">Agent</option>
              </select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Steve. Pol Bill Currency <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].s_pol_bill_currency" :disabled="form.contract_os_ports[portIndex].s_recovery_pol == '0'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">Choose</option>
                <option value="USD">USD</option>
                <option value="BDT">BDT</option>
              </select>
            </label>
          </div>
          <div class="flex flex-col items-center justify-center w-full pl-2 pr-2 mb-2 md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Steve. Recovery Pod <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].s_recovery_pod" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="" selected disabled>Choose</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Steve. Pod Bill To <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].s_pod_bill_to" :disabled="form.contract_os_ports[portIndex].s_recovery_pod == '0'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="" selected disabled>Choose</option>
                <option value="customer">Customer</option>
                <option value="agent">Agent</option>
              </select>
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Steve. Pod Bill Currency <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].s_pod_bill_currency" :disabled="form.contract_os_ports[portIndex].s_recovery_pod == '0'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">Choose</option>
                <option value="USD">USD</option>
                <option value="BDT">BDT</option>
              </select>
            </label>
          </div>
          <div class="flex flex-col items-center justify-center w-full pl-2 pr-2 mb-2 md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Tues 45 Container <span class="text-red-500">*</span></span>
              <input v-model="form.contract_os_ports[portIndex].tues_45_container" required type="text" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </label>
            <label class="block w-full mt-3 text-xs">
              <span class="text-gray-700 dark:text-gray-300">Weight Cap. <span class="text-red-500">*</span></span>
              <select v-model="form.contract_os_ports[portIndex].weight_capacity" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </label>
            <!--            <label class="block w-full mt-3 text-xs">-->
            <!--              <span class="text-gray-700 dark:text-gray-300">Weight Limit 20</span>-->
            <!--              <input v-model="form.contract_os_ports[portIndex].weight_limit_20" type="text" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
            <!--            </label>-->
            <!--            <label class="block w-full mt-3 text-xs">-->
            <!--              <span class="text-gray-700 dark:text-gray-300">Weight Limit 40</span>-->
            <!--              <input v-model="form.contract_os_ports[portIndex].weight_limit_40" type="text" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
            <!--            </label>-->
            <!--            <label class="block w-full mt-3 text-xs">-->
            <!--              <span class="text-gray-700 dark:text-gray-300">Weight Limit 45</span>-->
            <!--              <input v-model="form.contract_os_ports[portIndex].weight_limit_45" type="text" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
            <!--            </label>-->
          </div>
        </div>
        <div v-if="form.contract_os_ports[portIndex].weight_capacity == 1" class="w-full mb-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-700 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-2 py-1 text-lg text-center" colspan="3">Weight Limit</th>
              </tr>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-2 py-1">Weight Limit 20</th>
                <th class="px-2 py-1">Weight Limit 40</th>
                <th class="px-2 py-1">Weight Limit 45</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="p-1">
                  <input v-model="form.contract_os_ports[portIndex].weight_limit_20" placeholder="weight limit 20" type="number" step=".01" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                </td>
                <td class="p-1">
                  <input v-model="form.contract_os_ports[portIndex].weight_limit_40" placeholder="weight limit 40" type="number" step=".01" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                </td>
                <td class="p-1">
                  <input v-model="form.contract_os_ports[portIndex].weight_limit_45" placeholder="weight limit 45" type="number" step=".01" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0 p-1">
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap container-wise-rate-chart">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-700 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-2 py-1 text-lg text-center" colspan="17">Container Wise Generated Rate Chart <span class="text-red-500">*</span></th>
              </tr>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-2 py-1" rowspan="2">Head</th>
                <th class="px-2 py-1" colspan="3">GP(LDN)[ISO "G"]</th>
                <th class="px-2 py-1" colspan="3">GP(MTY)[ISO "G"]</th>
                <th class="px-2 py-1 text-center" rowspan="2">Action</th>
              </tr>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-2 py-1">20'/20'<br>HC</th>
                <th class="px-2 py-1">40'/40'<br>HC</th>
                <th class="px-2 py-1">45'HC</th>
                <th class="px-2 py-1">20'/20'<br>HC</th>
                <th class="px-2 py-1">40'/40'<br>HC</th>
                <th class="px-2 py-1">45'HC</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400" v-for="(rate, rateIndex) in form.contract_os_ports[portIndex].contract_os_port_rates" :key="rateIndex">
                <td class="p-1">
                  <select v-model="form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].charge_type" required @change="rateChargeTypeGroup(portIndex,rateIndex,$event)" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="" selected disabled>--Select--</option>
                    <option :value="type.code" v-for="(type,index) in chargeTypes">{{ type.name }}</option>
                  </select>
                </td>
                <td class="p-1">
                  <input type="text" @input="getTotalGp20Ldn(portIndex)" v-model="form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].gp_20_ldn" required name="port_code" placeholder="20'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="p-1">
                  <input type="text" @input="getTotalGp40Ldn(portIndex)" v-model="form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].gp_40_ldn" required name="port_code" placeholder="40'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="p-1">
                  <input type="text" @input="getTotalGp45Ldn(portIndex)" v-model="form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].gp_45_ldn" required name="port_code" placeholder="45'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="p-1">
                  <input type="text" @input="getTotalGp20Mty(portIndex)" v-model="form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].gp_20_mty" required name="port_code" placeholder="20'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="p-1">
                  <input type="text" @input="getTotalGp40Mty(portIndex)" v-model="form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].gp_40_mty" required name="port_code" placeholder="40'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="p-1">
                  <input type="text" @input="getTotalGp45Mty(portIndex)" v-model="form.contract_os_ports[portIndex].contract_os_port_rates[rateIndex].gp_45_mty" name="port_code" placeholder="45'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1 text-center">
                  <button v-if="rateIndex!==0" type="button"  @click="removePortRate(portIndex,rateIndex)" class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                  <button v-else type="button"  @click="addPortRate(portIndex,rateIndex)"  class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </td>
              </tr>
              </tbody>
              <tfoot>
              <tr>
                <td class="p-1 text-center text-gray-600 font-bold">Total</td>
                <td class="px-4 py-3 text-gray-600">
                  <input type="text" v-model="form.contract_os_ports[portIndex].total_gp_20_ldn" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </td>
                <td class="p-1 text-gray-600">
                  <input type="text" v-model="form.contract_os_ports[portIndex].total_gp_40_ldn" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </td>
                <td class="p-1 text-gray-600">
                  <input type="text" v-model="form.contract_os_ports[portIndex].total_gp_45_ldn" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </td>
                <td class="p-1 text-gray-600">
                  <input type="text" v-model="form.contract_os_ports[portIndex].total_gp_20_mty" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </td>
                <td class="p-1 text-gray-600">
                  <input type="text" v-model="form.contract_os_ports[portIndex].total_gp_40_mty" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </td>
                <td class="p-1 text-gray-600">
                  <input type="text" v-model="form.contract_os_ports[portIndex].total_gp_45_mty" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </td>
                <td class="p-1 text-gray-600"></td>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="w-full mt-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-700 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-2 py-1 text-lg text-center" colspan="5">Surcharges</th>
              </tr>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">Charge Head</th>
                <th class="px-4 py-3">Unit</th>
                <th class="px-4 py-3">20 LDN</th>
                <th class="px-4 py-3">40 LDN</th>
                <th class="px-4 py-3">45 LDN</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400"  v-for="(charge, chargeIndex) in form.contract_os_ports[portIndex].contract_os_port_surcharges" :key="chargeIndex">
                <td>
                  <input type="text" v-model="form.contract_os_ports[portIndex].contract_os_port_surcharges[chargeIndex].charge_type" readonly class="block w-full mt-1 text-xs bg-gray-100 rounded dark:text-gray-300 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td>
                  <select v-model="form.contract_os_ports[portIndex].contract_os_port_surcharges[chargeIndex].unit" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="" selected>Choose Unit</option>
                    <option value="percentage">Per Tue (% FRT)</option>
                    <option value="tue">Per Tue</option>
                    <option value="box">Box / Plug</option>
                  </select>
<!--                  <input type="text" v-model="form.contract_os_ports[portIndex].contract_os_port_surcharges[chargeIndex].unit" readonly class="block w-full mt-1 text-xs bg-gray-100 rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
                </td>
                <td>
                  <input type="text" v-model="form.contract_os_ports[portIndex].contract_os_port_surcharges[chargeIndex].ldn_20" placeholder="20 LDN" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required />
                </td>
                <td>
                  <input type="text" v-model="form.contract_os_ports[portIndex].contract_os_port_surcharges[chargeIndex].ldn_40" placeholder="40 LDN" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required />
                </td>
                <td>
                  <input type="text" v-model="form.contract_os_ports[portIndex].contract_os_port_surcharges[chargeIndex].ldn_45" placeholder="45 LDN" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required />
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <center>
          <div class="mt-5 input-group">
            <button v-if="form.contract_os_ports.length == portIndex+1" type="button" @click="addPort()" class="px-3 py-1 mr-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              Add More Port
            </button>
            <button type="button" v-if="portIndex!=0" @click="removePort(portIndex)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              Remove Port
            </button>
          </div>
        </center>
      </fieldset>
    </div>
  </div>
  <button v-if="openTab==1" type="submit" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
  <button type="button" v-else v-on:click="toggleTabs(openTab + 1,'next')" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white uppercase transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Next
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
      <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
    </svg>
  </button>
  <button type="button" v-if="openTab!=0" v-on:click="toggleTabs(openTab - 1,'back')" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white uppercase transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Back
  </button>
</template>
<style lang="postcss" scoped>
/*#table, #table th, #table td{*/
/*  @apply border border-collapse border-gray-400 text-center text-gray-700 font-medium;*/
/*}*/
.text-xs {
  font-size: 0.7rem;
  line-height: 0.7rem;
}
.custom_input{
  padding-left: 10px;
  padding-right: 20px;
}

.vs__dropdown-menu {
  min-width: 250px;
}

.v-select.vs--single.vs--searchable.mt-1.placeholder-gray-600.w-full {
  min-width: 250px;
}

.form-input {
  height: 2.05rem;
}
.container-wise-rate-chart th,.container-wise-rate-chart tr,.container-wise-rate-chart td{
  border: 1px solid #939292;
}
.container-wise-rate-chart td:nth-child(2),.container-wise-rate-chart td:nth-child(3),.container-wise-rate-chart td:nth-child(4),
.container-wise-rate-chart th:nth-child(2),
.container-wise-rate-chart tr:nth-child(3) th:nth-child(1),.container-wise-rate-chart tr:nth-child(3) th:nth-child(3)

{
  background-color: #c5e882;
}

.container-wise-rate-chart td:nth-child(5),.container-wise-rate-chart td:nth-child(6),.container-wise-rate-chart td:nth-child(7),
.container-wise-rate-chart th:nth-child(3),
.container-wise-rate-chart tr:nth-child(3) th:nth-child(4),.container-wise-rate-chart tr:nth-child(3) th:nth-child(5),.container-wise-rate-chart tr:nth-child(3) th:nth-child(6)

{
  background-color: #dede4e;
}

.container-wise-rate-chart td:nth-child(8),.container-wise-rate-chart td:nth-child(9),.container-wise-rate-chart td:nth-child(10),
.container-wise-rate-chart th:nth-child(4),
.container-wise-rate-chart tr:nth-child(3) th:nth-child(7),.container-wise-rate-chart tr:nth-child(3) th:nth-child(8),.container-wise-rate-chart tr:nth-child(3) th:nth-child(9)

{
  background-color: #cfded5;
}

.container-wise-rate-chart td:nth-child(11),.container-wise-rate-chart td:nth-child(12),.container-wise-rate-chart td:nth-child(13),
.container-wise-rate-chart th:nth-child(5),
.container-wise-rate-chart tr:nth-child(3) th:nth-child(10),.container-wise-rate-chart tr:nth-child(3) th:nth-child(11),.container-wise-rate-chart tr:nth-child(3) th:nth-child(12)

{
  background-color: #dede4e;
}

.container-wise-rate-chart td:nth-child(14),.container-wise-rate-chart td:nth-child(15),.container-wise-rate-chart td:nth-child(16),
.container-wise-rate-chart th:nth-child(6),
.container-wise-rate-chart tr:nth-child(3) th:nth-child(13),.container-wise-rate-chart tr:nth-child(3) th:nth-child(14),.container-wise-rate-chart tr:nth-child(3) th:nth-child(15)

{
  background-color: #939292;
}

.container-wise-rate-chart td:nth-child(1),
.container-wise-rate-chart tr:nth-child(2) th:nth-child(1)
{
  background-color: #cfded5;
}

/*#container-wise-rate-chart th,td,tr{*/
/*  border: 1px solid black;*/
/*}*/

>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>