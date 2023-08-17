<script setup>
import {ref, watch, onMounted, computed} from "vue";
import useService from "../../composables/commercial/useService";
import useCustomer from "../../composables/commercial/useCustomer";
import useCurrency from "../../composables/dataencoding/useCurrency";
import useChargeType from "../../composables/dataencoding/useChargeType";
import Editor from '@tinymce/tinymce-vue';
import Error from "../Error.vue";
import usePort from "../../composables/usePort";
import useNotification from '../../composables/useNotification.js';
import useBank from "../../composables/useBank";
import MultipleDropZone from '../../components/MultipleDropZone.vue';
import {useStore} from "vuex";
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
const { ports, portName, getPortsByNameOrCode } = usePort();
const { banks, bank, showBank, getBankWithoutPaginate } = useBank();

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
    port_code: null,
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

function addBoundPort(boundIndex,portIndex){
  let obj = {
    unique_id: Math.random(),
    pol: '',
    pod: '',
    is_excess: 1,
    term: '',
    f_payment_location: '',
    f_payment_currency: 'USD',
    s_recovery: 1,
    s_recovery_pol: 1,
    s_pol_bill_to: '',
    s_pol_bill_currency: 'USD',
    s_recovery_pod: 1,
    s_pod_bill_to: '',
    s_pod_bill_currency: 'USD',
    additional_rate_laden: '',
    additional_rate_empty: '',
  };
  props.form.allocations[boundIndex].ports.push(obj);
}

function removeBoundPort(boundIndex,portIndex){
  props.form.allocations[boundIndex].ports.splice(portIndex, 1);
}

function addBoundRate(boundIndex,rateIndex){
  let obj = {
    unique_id: Math.random(),
    charge_type: '',
    charge_type_category: '',
    dead_freight: 1,
    excess_laden: 1,
    excess_empty: 1,
  };
  props.form.allocations[boundIndex].rates.push(obj);
}

function rateChargeTypeGroup(boundIndex,rateIndex,e){
  let chargeTypeCode = e.target.value;
  chargeTypes?.value.forEach(function(chargeType){
    if(chargeType.code === chargeTypeCode){
      props.form.allocations[boundIndex].rates[rateIndex].charge_type_category = chargeType.category;
    }
  });
}

function getTotalWeightMt(boundIndex){
  let weightLimitPerTue = props.form.allocations[boundIndex].weight_limit_per_tue ?? 1;
  let tues = props.form.allocations[boundIndex].tues ?? 1;
  props.form.allocations[boundIndex].ttl_weight_mt = (weightLimitPerTue * tues);
}

function getTotalDeadFreight(boundIndex){
  let totalDeadFreight = 0;
  props.form.allocations[boundIndex].rates.forEach(rate => {
    totalDeadFreight += parseFloat(rate.dead_freight);
  });
  props.form.allocations[boundIndex].total_dead_freight = totalDeadFreight.toFixed(2);
}
function getTotalExcessLdn(boundIndex){
  let totalExcessLdn = 0;
  props.form.allocations[boundIndex].rates.forEach(rate => {
    totalExcessLdn += parseFloat(rate.excess_laden);
  });
  props.form.allocations[boundIndex].total_excess_ldn = totalExcessLdn.toFixed(2);
}

function getTotalExcessEmpty(boundIndex){
  let totalExcessEmpty = 0;
  props.form.allocations[boundIndex].rates.forEach(rate => {
    totalExcessEmpty += parseFloat(rate.excess_empty);
  });
  props.form.allocations[boundIndex].total_excess_empty = totalExcessEmpty.toFixed(2);
}

watch(() => props.form.allocations, (val) => {
  val?.map(function(bound, boundIndex) {

    //calculate total weight MT start
    let weightLimitPerTue = props.form.allocations[boundIndex].weight_limit_per_tue ?? 1;
    let tues = props.form.allocations[boundIndex].tues ?? 1;
    props.form.allocations[boundIndex].ttl_weight_mt = (weightLimitPerTue * tues).toFixed(2);
    //calculate total weight MT end

    let totalDeadFreight = 0;
    let totalExcessLaden = 0;
    let totalExcessEmpty = 0;
    bound.rates.forEach((rate, rateIndex) => {
      totalDeadFreight += parseFloat(rate.dead_freight);
      totalExcessLaden += parseFloat(rate.excess_laden);
      totalExcessEmpty += parseFloat(rate.excess_empty);
    });
    props.form.allocations[boundIndex].total_dead_freight = isNaN(totalDeadFreight) ? 0 : totalDeadFreight.toFixed(2);
    props.form.allocations[boundIndex].total_excess_ldn = isNaN(totalExcessLaden) ? 0 : totalExcessLaden.toFixed(2);
    props.form.allocations[boundIndex].total_excess_empty = isNaN(totalExcessEmpty) ? 0 : totalExcessEmpty.toFixed(2);
  });
}, {
  deep: true
});

function removeBoundRate(boundIndex,rateIndex){
  props.form.allocations[boundIndex].rates.splice(rateIndex, 1);
  getTotalDeadFreight(boundIndex);
  getTotalExcessLdn(boundIndex);
  getTotalExcessEmpty(boundIndex);
}

function changeStevedorageStatus(e,boundIndex,portIndex){
  let term = e.target.value;
  if(term === 'FI-FO'){
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pol = 1;
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pod = 1;
  } else if(term === 'FI-HK' || term === 'FI-CY'){
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pol = 0;
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pod = 1;
  } else if(term === 'HK-FO'){
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pol = 1;
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pod = 0;
  }else{
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pol = 0;
    props.form.allocations[boundIndex].ports[portIndex].s_recovery_pod = 0;
  }
}

function changePolPod(e,boundIndex,portIndex){
  let term = e.target.value;
  props.form.allocations[boundIndex].ports[portIndex].pol_pod = props.form.allocations[boundIndex].ports[portIndex].pol + '-' + props.form.allocations[boundIndex].ports[portIndex].pod;

}

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

watch(() => props.form.customer_code, (val) => {
  if (val?.id) {
    showCustomer(val?.id);
    props.form.customer_id = val?.id;
  }
});

watch(() => props.form.service_id, (val) => {
  if (val) {
    serviceGroupById(val);
    getServiceUniquePorts(val);
  }
});

watch(() => customer.value, (val) => {
  if(props.page === "create"){
    props.form.customer_id = val.id;
    props.form.customer_code = val.customer_code;
    props.form.billing_party = val.billing_party;
    props.form.billing_address = val.billing_address;
    props.form.billing_emails = val.billing_emails;
    props.form.billing_style = val.billing_style;
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

watch(() => service.value, (val) => {
  if(props.page === "create"){
    props.form.allocations = [];
    val.bounds.forEach((bound)=> {
      let obj = {
        bound: bound,
        tues: 1,
        weight_limit_per_tue: 1,
        ttl_weight_mt: 1,
        hc: 1,
        reffer: 1,
        total_dead_freight: 1,
        total_excess_ldn: 1,
        total_excess_empty: 1,
        dg_unit: '',
        dg_group_1: 1,
        dg_group_2: 1,
        dg_group_3: 1,
        dg_group_common: 1,
        rf_unit: null,
        rf_per_plug_amount: 1,
        tues_45: 2.5,
        remarks: '',
        short_note: '',
        rates: [{
          unique_id: Math.random(),
          charge_type: '',
          charge_type_category: '',
          dead_freight: 1,
          excess_laden: 1,
          excess_empty: 1,
        }],
        ports: [{
          unique_id: Math.random(),
          pol: '',
          pod: '',
          pol_pod: '',
          is_excess: 1,
          term: '',
          f_payment_location: '',
          f_payment_currency: 'USD',
          s_recovery_pol: 1,
          s_pol_bill_to: '',
          s_pol_bill_currency: 'USD',
          s_recovery_pod: 1,
          s_pod_bill_to: '',
          s_pod_bill_currency: 'USD',
          additional_rate_laden: '',
          additional_rate_empty: '',
        }],
      };

      props.form.allocations.push(obj);
    });
  }
});

watch(dropZoneFile, (value) => {
  if (value !== null && value !== undefined) {
    props.form.attachment = value;
  }
}, {
  deep: true
});
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
      <li class="mr-2" v-for="(bound,index) in service.bounds" :key="index">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 capitalize border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(index + 1)" v-bind:class="{'text-purple-600 bg-white': openTab !== index+1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === index+1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ bound }} Bound
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
            <select name="service_id" id="contract_service_id" v-model="form.service_id" required :class="page==='update' ? 'vms-readonly-input' : ''" :disabled="page==='update'" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
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
            <select name="contract_type" id="contract_type" :class="page==='update' ? 'vms-readonly-input' : ''" :disabled="page==='update'" v-model="form.contract_type" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
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
                <option :value="form.allocations[index].bound" v-for="(bound,index) in form.allocations" :key="index">{{ form.allocations[index].bound }}</option>
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
                <option :value="form.allocations[index].bound" v-for="(bound,index) in form.allocations" :key="index">{{ form.allocations[index].bound }}</option>
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
            <select v-model="form.bank_id" id="bank_id" class="block w-full mt-1 text-xs capitalize rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="" disabled>--Choose an Option--</option>
              <option :value="bank?.id" v-for="(bank,index) in banks" :key="index">{{ bank?.beneficiary_bank }} ({{ bank?.beneficiary_bank_branch}})</option>
            </select>
            <input type="hidden" v-model="form.bank_name">
<!--            <input type="text" v-model="form.bank_name" name="bank_name" placeholder="Bank Name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
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
            <span class="text-gray-700 dark:text-gray-300">Swift No</span>
            <input type="text" v-model="form.swift_code" name="swift_code" placeholder="Swift No" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
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
<!--    Working here -->
    <div v-for="(sector,boundIndex) in form.allocations" :key="boundIndex" v-on:click="toggleTabs(boundIndex + 1)" v-bind:class="{'hidden': openTab !== boundIndex + 1, 'block': openTab === boundIndex + 1}">
      <div class="my-2 font-bold text-center text-white bg-teal-500 rounded">
        <span>{{ service?.code }} - {{ customer?.customer_name }} ({{ customer?.customer_code }})</span>
      </div>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Allocation <span class="text-red-500">*</span></legend>
        <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">Tues</th>
                <th class="px-4 py-3">Weight Per Tue (MT)</th>
                <th class="px-4 py-3">TTL Weight (MT)</th>
                <th class="px-4 py-3">HC(Unit)</th>
                <th class="px-4 py-3">Reffer(Unit)</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-1 py-1">
                  <input type="hidden" v-model="form.allocations[boundIndex].bound" name="bound" />
                  <input type="number" step=".01" required v-model="form.allocations[boundIndex].tues" @input="getTotalWeightMt(boundIndex)" name="tues" placeholder="Tues" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step=".01" required v-model="form.allocations[boundIndex].weight_limit_per_tue" @input="getTotalWeightMt(boundIndex)" name="weight_limit_per_tue" placeholder="Weight limit per tue" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input type="text" step=".01" v-model="form.allocations[boundIndex].ttl_weight_mt" required name="ttl_weight_mt" readonly class="block w-full mt-1 text-xs rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step=".01" v-model="form.allocations[boundIndex].hc" required name="hc" placeholder="HC" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step=".01" v-model="form.allocations[boundIndex].reffer" required name="reffer" placeholder="Reffer" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-1 overflow-hidden border rounded-lg shadow-xs w-6/6 dark:border-0">
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap">
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="flex items-center px-1 py-1">
                  <span style="color: darkslategray" class="mr-5">Per 45' Unit Container as</span>
                  <input type="number" step=".01" v-model="form.allocations[boundIndex].tues_45" required placeholder="Tues 45" class="block w-1/6 mt-1 mr-2 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                  <span style="color: darkslategray"> Tues </span>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Surcharges <span class="text-red-500">*</span></legend>
        <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="my-2 text-center rounded">
            <span style="color: #282c34;font-weight: 500">DG Surcharge</span>
          </div>
          <hr>
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">Unit</th>
                <th class="px-4 py-3">Group 1 <br>(Rate)</th>
                <th class="px-4 py-3">Group 2 <br>(Rate)</th>
                <th class="px-4 py-3">Group 3 <br>(Rate)</th>
                <th class="px-4 py-3">Common <br>(Rate)</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-1 py-1">
                  <select v-model="form.allocations[boundIndex].dg_unit" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="" selected>Choose Unit</option>
                    <option value="percentage">Per Tue (% FRT)</option>
                    <option value="tue">Per Tue</option>
                    <option value="box">Box</option>
                  </select>
                </td>
                <td class="px-1 py-1">
                  <input type="number" step="0.01" v-model="form.allocations[boundIndex].dg_group_1" required placeholder="Group 1" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step="0.01" v-model="form.allocations[boundIndex].dg_group_2" required placeholder="Group 2" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step="0.01" v-model="form.allocations[boundIndex].dg_group_3" required placeholder="Group 3" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step="0.01" v-model="form.allocations[boundIndex].dg_group_common" required placeholder="Group 4" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="w-full mt-1 overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="my-2 text-center rounded">
            <span style="color: #282c34;font-weight: 500">Reffer Surcharge <span class="text-red-500">*</span></span>
          </div>
          <hr>
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">RF Unit</th>
                <th class="px-4 py-3">RF Amount</th>
<!--                <th class="px-4 py-3">45 Tues</th>-->
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-1 py-1">
                  <select v-model="form.allocations[boundIndex].rf_unit" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="" selected>Choose Unit</option>
                    <option value="percentage">Per Tue (% FRT)</option>
                    <option value="tue">Per Tue</option>
                    <option value="box">Box</option>
                  </select>
                </td>
                <td class="px-1 py-1">
                  <input type="number" step=".01" required v-model="form.allocations[boundIndex].rf_per_plug_amount" placeholder="Rf per plug amount" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Connecting Ports <span class="text-red-500">*</span></legend>
        <div class="overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="">
            <table class="w-full whitespace-no-wrap border border-collapse border-slate-400" id="table">
              <thead >
              <tr class="font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th rowspan="3" class="p-0.5 text-xs capitalize">Pol</th>
                <th rowspan="3" class="p-0.5 text-xs capitalize">Pod</th>
                <th rowspan="3" class="p-0.5 text-xs capitalize">Additional/ <br> Extra Billing</th>
                <th rowspan="3" class="p-0.5 text-xs capitalize">Term</th>
                <th colspan="2" class="p-0.5 text-xs capitalize">Freight</th>
                <th colspan="6" class="p-0.5 text-xs capitalize">Stevedorage Recovery Billing</th>
                <th rowspan="3" class="p-0.5 text-xs capitalize">Action</th>
              </tr>
              <tr class="p-0.5 text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th rowspan="2" class="p-0.5 text-xs capitalize">Payment Location</th>
                <th rowspan="2" class="p-0.5 text-xs capitalize">Payment Currency</th>
<!--                <th rowspan="2" class="p-0.5 text-xs">Stevedorage Recovery</th>-->
                <th colspan="3" class="p-0.5 text-xs capitalize">Pol</th>
                <th colspan="3" class="p-0.5 text-xs capitalize">Pod</th>
              </tr>
              <tr class="p-0.5 text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th colspan="" class="p-0.5 text-xs capitalize">Stevedorage Recovery</th>
                <th colspan="" class="p-0.5 text-xs capitalize">Bill To</th>
                <th colspan="" class="p-0.5 text-xs capitalize">Currency</th>
                <th colspan="" class="p-0.5 text-xs capitalize">Stevedorage Recovery</th>
                <th colspan="" class="p-0.5 text-xs capitalize">Bill To</th>
                <th colspan="" class="p-0.5 text-xs capitalize">Currency</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr v-for="(port,portIndex) in form.allocations[boundIndex].ports" :key="port.unique_id" class="text-center text-gray-700 dark:text-gray-400">
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].pol" required @change="changePolPod($event,boundIndex,portIndex)" class="mt-1 text-xs rounded custom_input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="">Select</option>
                    <option :value="port" v-for="(port,index) in uniqueServicePorts" :key="index">{{ port }}</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].pod" required @change="changePolPod($event,boundIndex,portIndex)" class="mt-1 text-xs rounded custom_input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="">Select</option>
                    <option :value="port" v-for="(port,index) in uniqueServicePorts" :key="index">{{ port }}</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].is_excess" required class="w-full mt-1 text-xs rounded custom_input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="0">NO</option>
                    <option value="1">YES</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].term" required @change="changeStevedorageStatus($event,boundIndex,portIndex)" class="mt-1 text-xs rounded custom_input dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
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
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].f_payment_location" required class="w-full mt-1 text-xs rounded custom_input dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="">Select</option>
                    <option value="POL">POL</option>
                    <option value="POD">POD</option>
                    <option value="3rd">3rd</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].f_payment_currency" class="w-full mt-1 text-xs rounded custom_input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
                    <option :value="currency.code" v-for="(currency,index) in currencies">{{ currency.code }}</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].s_recovery_pol" required class="w-full mt-1 text-xs rounded custom_input dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="0">NO</option>
                    <option value="1">YES</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].s_pol_bill_to" :disabled="form.allocations[boundIndex].ports[portIndex].s_recovery_pol == '0'" class="mt-1 text-xs rounded custom_input dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="">Select</option>
                    <option value="customer">Customer</option>
                    <option value="agent">Agent</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select style="width: auto" v-model="form.allocations[boundIndex].ports[portIndex].s_pol_bill_currency" :disabled="form.allocations[boundIndex].ports[portIndex].s_recovery_pol == '0'" class="w-full mt-1 text-xs rounded custom_input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option :value="currency.code" v-for="(currency,index) in currencies">{{ currency.code }}</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].s_recovery_pod" required class="w-full mt-1 text-xs rounded custom_input dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="0">NO</option>
                    <option value="1">YES</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select v-model="form.allocations[boundIndex].ports[portIndex].s_pod_bill_to" :disabled="form.allocations[boundIndex].ports[portIndex].s_recovery_pod == '0'" class="mt-1 text-xs rounded custom_input dark:text-gray-300 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="">Select</option>
                    <option value="customer">Customer</option>
                    <option value="agent">Agent</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-xs">
                  <select style="width: auto" v-model="form.allocations[boundIndex].ports[portIndex].s_pod_bill_currency" :disabled="form.allocations[boundIndex].ports[portIndex].s_recovery_pod == '0'" class="w-full mt-1 text-xs rounded custom_input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option :value="currency.code" v-for="(currency,index) in currencies">{{ currency.code }}</option>
                  </select>
                </td>
                <td class="px-1 py-1 text-center">
                  <button v-if="portIndex!==0" type="button" @click="removeBoundPort(boundIndex,portIndex)" class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                  <button v-else type="button" @click="addBoundPort(boundIndex,portIndex)" class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Ocean Freight Rates <span class="text-red-500">*</span></legend>
        <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap">
              <thead v-once>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">Charge Type</th>
                <th class="px-4 py-3">Dead Freight</th>
                <th class="px-4 py-3">Excess (LDN)</th>
                <th class="px-4 py-3">Excess (MT)</th>
                <th class="px-4 py-3">Action</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr v-for="(rate,rateIndex) in form.allocations[boundIndex].rates" :key="rate.unique_id" class="text-gray-700 dark:text-gray-400">
                <td class="px-1 py-1">
                  <select v-model="form.allocations[boundIndex].rates[rateIndex].charge_type" required @change="rateChargeTypeGroup(boundIndex,rateIndex,$event)" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                      <option value="" selected disabled>--Select--</option>
                      <option :value="type.code" v-for="(type,index) in chargeTypes">{{ type.name }}</option>
                  </select>
                </td>
                <td class="px-1 py-1">
                  <input v-model="form.allocations[boundIndex].rates[rateIndex].dead_freight" required @input="getTotalDeadFreight(boundIndex)" type="number" step=".01" placeholder="Dead freight" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input v-model="form.allocations[boundIndex].rates[rateIndex].excess_laden" required @input="getTotalExcessLdn(boundIndex)" type="number" step=".01" placeholder="Excess laden" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <input v-model="form.allocations[boundIndex].rates[rateIndex].excess_empty" required @input="getTotalExcessEmpty(boundIndex)" type="number" step=".01" placeholder="Excess empty" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1 text-center">
                  <button v-if="rateIndex!==0" type="button" @click="removeBoundRate(boundIndex,rateIndex)" class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                  <button v-else type="button" @click="addBoundRate(boundIndex,rateIndex)" class="px-3 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </td>
              </tr>
              <tr>
                <th class="px-4 py-3 text-gray-600">Total</th>
                <th class="px-4 py-3 text-gray-600">
                  <input type="text" v-model="form.allocations[boundIndex].total_dead_freight" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </th>
                <th class="px-4 py-3 text-gray-600">
                  <input type="text" v-model="form.allocations[boundIndex].total_excess_ldn" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </th>
                <th class="px-4 py-3 text-gray-600">
                  <input type="text" v-model="form.allocations[boundIndex].total_excess_empty" class="block w-full mt-1 text-xs font-extrabold rounded vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" disabled>
                </th>
                <th class="px-4 py-3 text-gray-600"></th>
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
            <textarea v-model="form.allocations[boundIndex].remarks" placeholder="Contract remarks" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-xs">
            <span class="text-gray-700 dark:text-gray-300">Short Note</span>
            <textarea v-model="form.allocations[boundIndex].short_note" placeholder="Short note" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          </label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Additional Ports/Sectors</legend>
        <div class="overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="">
            <table class="w-full whitespace-no-wrap border border-collapse border-slate-400" id="table">
              <thead >
              <tr class="font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="p-0.5 text-xs capitalize">Pol</th>
                <th class="p-0.5 text-xs capitalize">POD</th>
                <th class="p-0.5 text-xs capitalize">Laden Rate</th>
                <th class="p-0.5 text-xs capitalize">Empty Rate</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr v-for="(port,portIndex) in form.allocations[boundIndex].ports" v-show="form.allocations[boundIndex].ports[portIndex]?.is_excess == 1" :key="port.unique_id" class="text-center text-gray-700 dark:text-gray-400">
                <td class="px-1 py-1 text-xs">
                  <input type="text" v-model="form.allocations[boundIndex].ports[portIndex].pol" readonly placeholder="POL" class="block w-full mt-1 text-xs bg-gray-200 rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1 text-xs">
                  <input type="text" readonly v-model="form.allocations[boundIndex].ports[portIndex].pod"  placeholder="POD" class="block w-full mt-1 text-xs bg-gray-200 rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1 text-xs">
                  <input type="number" step=".01" v-model="form.allocations[boundIndex].ports[portIndex].additional_rate_laden" placeholder="Laden Rate" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1 text-xs">
                  <input type="number" step=".01" v-model="form.allocations[boundIndex].ports[portIndex].additional_rate_empty" placeholder="Empty Rate" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </fieldset>
    </div>
  </div>
  <button v-if="openTab == service?.bounds?.length" type="submit" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
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
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 font-medium;
}
.text-xs {
  font-size: 0.7rem;
  line-height: 0.7rem;
}
.custom_input{
  padding-left: 10px;
  padding-right: 20px;
}

/* all input field height set */
.form-input {
  height: 2.05rem;
}

.vs__dropdown-menu {
  min-width: 250px;
}

.v-select.vs--single.vs--searchable.mt-1.placeholder-gray-600.w-full {
  min-width: 250px;
  margin-top: 4px;
}

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