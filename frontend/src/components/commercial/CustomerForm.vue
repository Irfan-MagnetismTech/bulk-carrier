<script setup>
import {ref, watch} from "vue";
import usePort from "../../composables/usePort";
import useCurrency from "../../composables/dataencoding/useCurrency";
import {onMounted} from "vue";
import Editor from '@tinymce/tinymce-vue';
import Error from "../Error.vue";
import useNotification from '../../composables/useNotification.js';
import useSlotPartner from "../../composables/dataencoding/useSlotPartner";
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  currencies: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const notification = useNotification();

const validationErrors = ref({});

const { ports, getPortsByNameOrCode } = usePort();
const { currencies, getCurrencyWithoutPaginate } = useCurrency();
const { slotPartnerCode, getSlotPartnerByNameOrCode } = useSlotPartner();

const isAgentBillingStyleModalOpen = ref(0);
const openTab = ref(1);
const toggleTabs = (tabNumber, buttonType = null) => {
  if(buttonType === 'back') {
    openTab.value = tabNumber;
  } else {
    // check required fields is empty or not
    if (openTab.value === 1) {
      if (props.form.customer_code === "" || props.form.customer_name === "" || props.form.company_name === "" || props.form.country === "" || props.form.similar_codes === "") {

        // form validation start for customer code start
        if(props.form.customer_code === ""){
          document.getElementById('customer_code').classList.add('vms-required-input-border');
        }else{
          document.getElementById('customer_code').classList.remove('vms-required-input-border');
        }

        if(props.form.company_name === ""){
          document.getElementById('company_name').classList.add('vms-required-input-border');
        }else{
          document.getElementById('company_name').classList.remove('vms-required-input-border');
        }

        if(props.form.country === ""){
          document.getElementById('country').classList.add('vms-required-input-border');
        }else{
          document.getElementById('country').classList.remove('vms-required-input-border');
        }
        // form validation start for customer code end

        if(buttonType === 'next') {
          notification.showError(422,'','Please fill all required fields');
        }
        return;
      }
    }
    if(openTab.value === 2) {
      if (props.form.customer_general_email === "") {

        if(props.form.customer_general_email === ""){
          document.getElementById('customer_general_email').classList.add('vms-required-input-border');
        }else{
          document.getElementById('customer_general_email').classList.remove('vms-required-input-border');
        }
        // return with a message
        if(buttonType === 'next') {
          notification.showError(422,'','Please fill all required fields');
        }
        return;
      }
    }
    openTab.value = tabNumber;
  }
}
/* add and remove contact dynamic start */
function addContact() {
  let obj = {
    name: '',
    email: '',
    mobile: '',
    purpose: '',
  };
  props.form.contact_persons.push(obj);
}
function removeContact(index){
  props.form.contact_persons.splice(index, 1);
}

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}
/* add and remove contact dynamic end */

function fetchSlotPartner(search, loading) {
  getSlotPartnerByNameOrCode(search);
}

watch(() => props.form, (value) => {
  if(value?.agents?.length) {
    value.agents.forEach((agent, index) => {
      props.form.agents[index].port_code = agent?.port_code_name?.code ?? '';
    });
  }
}, {deep: true});

/* add and remove agent dynamic start */
function addAgent() {
  let obj = {
    port_code: '',
    port_code_name: '',
    port_name: '',
    agent_name: '',
    // billing_name: '',
    billing_email: '',
    billing_style: '',
  };
  props.form.agents.push(obj);
}
function removeAgent(index){
  props.form.agents.splice(index, 1);
}
/* add and remove agent dynamic end */

function openAgentBillingStyleModal() {
  isAgentBillingStyleModalOpen.value = 1;
}

function closeAgentBillingStyleModal() {
  isAgentBillingStyleModalOpen.value = 0;
}

watch(() => props.form.similar_codes_name, (val) => {
  if (val?.name) {
    props.form.similar_codes = val?.code;
    props.form.customer_name = val?.name;
  }
}, { deep: true});


onMounted(() => {
  getCurrencyWithoutPaginate();
  //getSlotPartnerWithoutPaginate();
});

</script>

<template xmlns="http://www.w3.org/1999/html">
  <div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Customer Information
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 2}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Contact Information
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(3)" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 3}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Local Agents
        </a>
      </li>
    </ul>
    <div v-on:click="toggleTabs(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Customer Info</legend>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Unique Customer Code <span class="text-red-500">*</span></span>
            <input type="text" autocomplete="off" id="customer_code" v-model="form.customer_code" name="customer_code" required placeholder="Customer code" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
<!--            <span v-if="validationErrors?.customer_code" class="text-red-600">{{ validationErrors?.customer_code }}</span>-->
            <Error v-if="errors?.customer_code" :errors="errors.customer_code" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Company Legal Name <span class="text-red-500">*</span></span>
            <input type="text" autocomplete="company_name" id="company_name" v-model="form.company_name" required placeholder="Company legal name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.company_name" :errors="errors.company_name" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Slot Partner Code <span class="text-red-500">*</span></span>
            <v-select placeholder="--Choose an option--" :options="slotPartnerCode" @search="fetchSlotPartner" v-model="form.similar_codes_name" label="code_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
            <input type="hidden" v-model="form.similar_codes">
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Customer Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.customer_name" name="customer_name" required placeholder="Customer name" class="block vms-readonly-input w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" autocomplete="nope" readonly />
            <Error v-if="errors?.customer_name" :errors="errors.customer_name" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Postal Address</span>
            <input type="text" autocomplete="postal_address" v-model="form.postal_address" name="postal_address" placeholder="Postal address" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">City</span>
            <input type="text" autocomplete="city" v-model="form.city" name="city" placeholder="City" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Post Code</span>
            <input type="text" autocomplete="post_code" v-model="form.post_code" name="post_code" placeholder="Post code" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Country <span class="text-red-500">*</span></span>
            <input type="text" autocomplete="nope" id="country" v-model="form.country" name="country" required placeholder="Country" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.country" :errors="errors.country" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Tax Identification</span>
            <input type="text" autocomplete="off" v-model="form.etin_no" name="etin_no" placeholder="Tax Identification" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Business License No </span>
            <input type="text" autocomplete="trade_license_no" v-model="form.trade_license_no" name="trade_license_no" placeholder="Business License No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">BIN/GST/SST No </span>
            <input type="text" autocomplete="off" v-model="form.bin_no" name="bin_no" placeholder="BIN/GST/SST" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Company Registration No</span>
            <input type="text" autocomplete="company_reg_no" v-model="form.company_reg_no" name="company_reg_no" placeholder="Company Registration No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
      </fieldset>
    </div>
    <div v-on:click="toggleTabs(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Contact Info</legend>
      <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Phone</span>
          <input type="text" autocomplete="nope" v-model="form.phone" name="phone" placeholder="Contact phone no." class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Fax</span>
          <input type="text" autocomplete="off" v-model="form.fax" name="fax" placeholder="Contact fax no." class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
      </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Customer Email (General) <span class="text-red-500">*</span></span>
            <input type="text" autocomplete="customer_general_email" id="customer_general_email" v-model="form.customer_general_email" name="customer_general_email" required placeholder="Customer Email (General)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.customer_general_email" :errors="errors.customer_general_email" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Customer Email (Agreement)</span>
            <input type="text" autocomplete="customer_agreement_email" v-model="form.customer_agreement_email" name="customer_agreement_email" placeholder="Customer Email (Agreement)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Customer Email (Invoice)</span>
            <input type="text" autocomplete="customer_notice_email" v-model="form.customer_notice_email" name="customer_notice_email" placeholder="Customer Email (Notice)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Contact Person</legend>
        <table class="table w-full whitespace-no-wrap" id="">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3 align-bottom">Contact Name</th>
            <th class="px-4 py-3 align-bottom">Mobile No</th>
            <th class="px-4 py-3 align-bottom">Email</th>
            <th class="px-4 py-3 align-bottom">Purpose</th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(contact, index) in form.contact_persons" :key="contact.id">
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0"><input type="text" autocomplete="contact_persons[index].name" v-model="form.contact_persons[index].name" name="name" placeholder="Contact name" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0"><input type="text" autocomplete="contact_persons[index].mobile" v-model="form.contact_persons[index].mobile" name="mobile" placeholder="Contact mobile no" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0"><input type="email" autocomplete="contact_persons[index].email" v-model="form.contact_persons[index].email" name="email" placeholder="Contact email" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0"><input type="text" autocomplete="contact_persons[index].purpose" v-model="form.contact_persons[index].purpose" name="purpose" placeholder="Contact Purpose" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!=0" type="button" @click="removeContact(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="addContact()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
    <div v-on:click="toggleTabs(3)" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Agent Information</legend>
        <table class="table w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3 align-bottom">Port Code</th>
            <th class="px-4 py-3 align-bottom">Agent Legal Name</th>
            <th class="px-4 py-3 align-bottom">Billing Email</th>
            <th class="px-4 py-3 align-bottom">General Email</th>
            <th class="px-4 py-3 align-bottom">Billing Style</th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(agent, index) in form.agents" :key="agent.id">
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="" style="border: 0px">
                    <v-select v-model="form.agents[index].port_code_name" :id="'port_code_name' + index" name="port_code_name" @search="fetchOptions" value="id" :options="ports" label="code_name" placeholder="Enter Port Code or Name" class="w-full mt-1 placeholder-gray-600"></v-select>
                    <input type="hidden" v-model="form.agents[index].port_code" autocomplete="off" name="port_code" :id="'port_code' + index" />
                  </td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0px"><input type="text" autocomplete="off" v-model="form.agents[index].agent_name" name="agent_name" placeholder="Agent name" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
<!--            <td class="px-1 py-1">-->
<!--              <table>-->
<!--                <tr>-->
<!--                  <td class="md:w-2/6" style="border: 0px"><input type="text" v-model="form.agents[index].billing_name" required name="billing_name" placeholder="Billing name" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>-->
<!--                </tr>-->
<!--              </table>-->
<!--            </td>-->
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0px"><input type="email" autocomplete="agents[index].billing_email" v-model="form.agents[index].billing_email" name="billing_email" placeholder="Billing email" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0px"><input type="text" autocomplete="nope" v-model="form.agents[index].shipping_address" name="shipping_address" placeholder="General email" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table>
                <tr>
                  <td class="md:w-2/6" style="border: 0px"><input type="text" style="max-width: 115px;" autocomplete="off" v-model="form.agents[index].billing_style" @focus="openAgentBillingStyleModal" name="billing_style" placeholder="Billing style" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!=0" type="button" @click="removeAgent(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="addAgent()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
            <div v-show="isAgentBillingStyleModalOpen" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
              <!-- Modal -->
              <div class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
                <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
                <header class="flex justify-end">
                  <button type="button" class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700" aria-label="close" @click="closeAgentBillingStyleModal">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
                      <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                  </button>
                </header>
                <!-- Modal body -->
                <div class="mt-4 mb-6">
                  <editor v-model="form.agents[index].billing_style" api-key="wljvu7gtfjb8h5ou2rcxw8d5tykej98zy10x8ot83jclsm3o"/>
                </div>
                <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
                  <button type="button" @click="closeAgentBillingStyleModal" style="color: #6b6e6c" class="w-full px-5 py-3 text-sm font-medium leading-5 text-gray-700 border border-gray-300 rounded-lg dark:text-gray-600 sm:px-4 sm:py-2 sm:w-auto hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
                    Cancel
                  </button>
                  <button type="button" @click="closeAgentBillingStyleModal" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Save
                  </button>
                </footer>
              </div>
            </div>
          </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
  </div>
  <button v-if="openTab==3" type="submit" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
    <span v-if="page=='create'">Create Customer</span>
    <span v-else>Update Customer</span>
  </button>
  <button type="button" v-else v-on:click="toggleTabs(openTab + 1,'next')" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white uppercase transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Next
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
      <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
    </svg>
  </button>
  <button type="button" v-if="openTab!=1" v-on:click="toggleTabs(openTab - 1, 'back')" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white uppercase transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Back
  </button>
</template>

<style lang="postcss" scoped>
.table, .table th, .table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}
.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
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
<style>
/*.vs__search{*/
/*  width: 100px !important;*/
/*}*/
/*.vs__selected-options{*/
/*  min-width: 200px !important;*/
/*}*/

/*.v-select {*/
/*  width: 250px !important;*/
/*}*/
</style>