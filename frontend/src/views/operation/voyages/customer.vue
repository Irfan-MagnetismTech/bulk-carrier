<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import useVoyage from '../../../composables/operation/useVoyage';
import Title from '../../../services/title';
import moment from 'moment';
import Heading from '../../../components/Heading.vue';
import IconExcel from '../../../components/icons/IconExcel.vue';
import useFileUploader from '../../../composables/useFileUploader';

const route = useRoute();
const { setTitle } = Title();
const voyageId = route.params.voyageId;
const { voyage, selectedCustomerAgents, advisory_agents, advisory_customer, advisory_customer_container, getVoyageDetails, isLoading, sendNotification, getCustomerAgentsAndContainers, getCustomerAgentsAndContainersByAdvisoryType } = useVoyage();
const { filePath, uploadedFileId, removeFile, process } = useFileUploader();
const voyageCustomers = ref([]);
const isALlSelected = ref(false);
const numberOfSelected = ref(0);
const message = ref("");
const isModalOpen = ref(false);
const chosenCustomer = ref('');
const customerId = ref('');
const advisory_type = ref('')
const filePusherIndex = ref('');
const filePusherUserType = ref('');
const advisory_agents_object = [];

// Check if all customers are selected

// function advisoryCollection(id, type)
// {
//     if(type === 'agent')
//     {
//         selectedCustomerAgents.value.agents = id
//     } else {
//         selectedCustomerAgents.value.customer_id = id
//     }
    
// }
// Send notification to all selected customers
function send() {
    // const customer_ids = voyageCustomers.value.filter(voyageCustomer => voyageCustomer.isSelected).map(voyageCustomer => voyageCustomer.customer.id);
    const voyage_id = voyage.value.id;
    const voyage_customer = chosenCustomer.value
    const type = advisory_type.value
    let customerNotify = false;
    if(selectedCustomerAgents.value.customer_id.length == 1)
    {
        customerNotify = true;
    } else {
        customerNotify = false;
    }
    const agents = selectedCustomerAgents.value.agents;

    console.log("Agents: " +agents);

    // taking object of advisory_agents by looping agents array
    selectedCustomerAgents.value.agents.forEach(agent => {
        advisory_agents_object[agent] = JSON.parse(JSON.stringify(advisory_agents.value[agent]));
    });

    console.log("Below is selected agents details");
    console.log("Advisory: " + advisory_agents_object);

    const customer_id = customerId.value;
    // if (customer_ids.length === 0) {
    //     message.value = "Please select at least one customer.";
    //     return;
    // }


    sendNotification({ voyage_id, type, voyage_customer, customerNotify, customer_id, agents });

    // message.value = "";
    // isALlSelected.value = false;
    // checkAll();
}

function chooseCustomer(customerCode)
{
    chosenCustomer.value = customerCode
}

function popup()
{
    const customer = chosenCustomer.value;
    if (customer == '') {
        message.value = "Please select a customer.";
        return;
    } else {
        isModalOpen.value = true;
        const voyage_id = voyage.value.id;
        getCustomerAgentsAndContainers({ customer, voyage_id });

        message.value = "";
    }
}

function removeAttachment(fileid, notificationId)
{
    console.log(fileid, notificationId);
    // console.log("Filename Below");
    removeFile(fileid);

}

function chooseAdvisoryType(advisoryType)
{   advisory_type.value = advisoryType;
    let voyageId = voyage.value.id;
    let customer = chosenCustomer.value;

    let type = advisoryType;
    if (customer == '' || type == '') {
        message.value = "Please select a customer.";
        return;
    } else {
        advisory_agents.value = [];
		advisory_customer.value = '';
		advisory_customer_container.value = [];
        isModalOpen.value = true;
        getCustomerAgentsAndContainersByAdvisoryType({ customer, voyageId, type });
        message.value = "";
    }
}

function closeModal()
{
    isModalOpen.value = false;
}

function selectedFile(event, type, index)
{
    const currentFile = event.target.files[0];
    process(currentFile, 'files');

    filePusherIndex.value = index
    filePusherUserType.value = type;
}

function attachmentName(attachment)
{
    if(attachment)
    {
        let name = attachment.split('/').pop();
        let dot = (name.length > 15) ? '...' : '';
        return name.substr(0,15) + dot;
    } else {
        return null;
    }
}

watch(() => filePath, (value) => {
    
        const index = filePusherIndex.value;
        const userType = filePusherUserType.value;

        if (userType == 'agent') {
        
            if(advisory_agents.value[index].mail_attachment == null)
            {
                let attachments = {
                    attachments: []
                };
                advisory_agents.value[index].mail_attachment = attachments;
            }
            //alert(uploadedFileId);
        
            advisory_agents.value[index].mail_attachment?.attachments?.push({id: uploadedFileId.value, name: value.value});
        }
},{deep: true});

// watch(filePath, (newValue) => {
//     if (newValue != '') {
//         const index = filePusherIndex.value;
//         const userType = filePusherUserType.value;

//         if (userType == 'agent') {
//             if(advisory_agents.value[index].mail_attachment == null)
//             {
//                 let attachments = {
//                     attachments: []
//                 };
//                 advisory_agents.value[index].mail_attachment = attachments;
//             }
//             a
//             advisory_agents.value[index].mail_attachment?.attachments?.push({id: uploadedFileId, name: newValue});
//         } 
//         // else 
//         // {
//         //     if(advisory_customer.value.mail_attachment == null)
//         //     {
//         //         let attachments = {
//         //             attachments: []
//         //         };
//         //         advisory_customer.value.mail_attachment = attachments;
//         //     }
//         //     advisory_customer.value.mail_attachment.attachments.push(newValue);
//         // }
//     }
// });

watch(advisory_customer, (value) => {
    if(value)
    {
        customerId.value = value.id
    }
});

watch(voyage, (value) => {
    if (value === null || value === undefined) {
        setTitle('Customers');
    } else {
        setTitle(`${value.voyage} - Customers`);
    }

    if (value.voyage_customers.length > 0) {
        voyageCustomers.value = value.voyage_customers;

        voyageCustomers.value.forEach(voyageCustomer => {
            voyageCustomer.isSelected = false;
            voyageCustomer.isShowingNotifications = false;
        });
    }
});

onMounted(() => {
    getVoyageDetails(voyageId);
});
</script>

<template>
    <Heading :label="`Customers: #${voyage?.voyage}`" type="none"></Heading>
    <!-- Send Button -->
    <div class="flex flex-row-reverse items-center justify-between mb-2">
        <button @click="popup()" :disabled="isLoading || voyageCustomers?.length == 0" class="inline-flex gap-1 px-3 py-1 text-sm text-center bg-purple-500 rounded btn text-purple-50 hover:bg-purple-600 active:bg-purple-500 disabled:bg-purple-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45 text-purple-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
            Process
        </button>
        <div class="flex items-center gap-1 text-xs text-red-500" v-if="message">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--bx" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                <path fill="currentColor" d="M11.001 10h2v5h-2zM11 16h2v2h-2z" />
                <path fill="currentColor" d="M13.768 4.2C13.42 3.545 12.742 3.138 12 3.138s-1.42.407-1.768 1.063L2.894 18.064a1.986 1.986 0 0 0 .054 1.968A1.984 1.984 0 0 0 4.661 21h14.678c.708 0 1.349-.362 1.714-.968a1.989 1.989 0 0 0 .054-1.968L13.768 4.2zM4.661 19L12 5.137L19.344 19H4.661z" />
            </svg>
            {{ message }}
        </div>
        <p class="text-sm font-semibold tracking-tighter text-gray-400">Selected: {{ numberOfSelected }} of {{ voyageCustomers.length }}</p>
    </div>

    <!-- Table -->
    <div class="w-full mb-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr>
                        <th>
                        </th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Notice Email</th>
                        <th>CC Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="voyageCustomer in voyageCustomers" :key="voyageCustomer.id">
                        <td class="w-5">
                            <input type="radio" class="focus:ring-0 focus:border-none focus:border-transparent" name="customer" @change="chooseCustomer(voyageCustomer?.id)"/>
                        </td>
                        <td class="td-border">{{ voyageCustomer?.customer?.customer_code }}</td>
                        <td class="td-border bold">{{ voyageCustomer?.customer?.customer_name }}</td>
                        <td class="td-border">
                            <a :href="`mailto:${voyageCustomer?.customer?.customer_notice_email}`" class="email">{{ voyageCustomer?.customer?.customer_notice_email }}</a>
                        </td>
                        <td class="td-border">
                            <a :href="`mailto:${voyageCustomer?.customer?.customer_general_email}`" class="email">{{ voyageCustomer?.customer?.customer_general_email }}</a>
                        </td>
                        <td class="td-border">
                            <a :href="`tel:${voyageCustomer?.customer?.phone}`" class="tel">{{ voyageCustomer?.customer?.phone }}</a>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="voyageCustomers.length === 0" class="bg-white">
                    <tr>
                        <td colspan="7" class="text-center">
                            <span v-if="isLoading" class="text-sm text-gray-500">Loading...</span>
                            <span v-else-if="voyageCustomers.length === 0" class="text-sm text-gray-500">No customers found</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div v-show="isModalOpen" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center rounded-md w-full">
        
        <form action="" class="bg-white px-6 py-4 w-9/12">
            <div class="flex justify-between">
                <div class="flex">
                <button type="button" @click="send()" :disabled="isLoading || voyageCustomers?.length == 0" class="inline-flex gap-1 my-2 px-3 py-1 text-sm text-center bg-purple-500 rounded btn text-purple-50 hover:bg-purple-600 active:bg-purple-500 disabled:bg-purple-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45 text-purple-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Send E-mail
                </button>
                <div class="flex items-center ml-3">
                    <input type="radio" id="export_advisory" class="focus:ring-0 focus:border-none focus:border-transparent" name="advisory_type" @change="chooseAdvisoryType('export')"/>
                    <label for="export_advisory" class="pl-2"> Export Advisory</label>
                    <input type="radio" id="import_advisory" class="ml-5 focus:ring-0 focus:border-none focus:border-transparent" name="advisory_type" @change="chooseAdvisoryType('import')"/>
                    <label for="import_advisory" class="pl-2"> Import Advisory</label>
                </div>
                </div>


                <button type="button" @click="closeModal()" class="text-sm text-center text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
            
            <div class="w-full mb-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        
                        <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Notice Email</th>
                                <th>CC Email</th>
                                <th>Containers</th>
                                <th>Attachments</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                            <tr>
                                <td class="w-5">
                                    <input type="checkbox" class="check" :value="advisory_customer?.id" v-model="selectedCustomerAgents.customer_id"/>

                                    <span class="mail mail-sent" v-if="advisory_customer?.mail_status=='sent'">
                                        Sent
                                    </span>
                                    <span class="mail mail-processing" v-if="advisory_customer?.mail_status=='pending'">
                                        Processing
                                    </span>
                                    <span class="mail mail-failed" v-if="advisory_customer?.mail_status=='failed'">
                                        Failed
                                    </span>
                                </td>
                                <td class="td-border">
                                    <span class="bg-green-200 px-3 py-1 rounded-full">{{ advisory_customer?.customer_code }}</span></td>
                                <td class="td-border bold">{{ advisory_customer?.company_name }}</td>
                                <td class="td-border">{{ advisory_customer?.customer_notice_email }}</td>
                                <td class="td-border">{{ advisory_customer?.customer_general_email }}</td>
                                <td class="td-border">
                                    <span class="text-gray-700 dark:text-gray-300 font-semibold">{{ advisory_customer_container ? Object.keys(advisory_customer_container).length : 0 }} Containers</span>
                                    <div class="max-h-8 my-2 overflow-y-scroll block break-all hyphens-all">
                                         {{ advisory_customer_container ? Object.keys(advisory_customer_container).map(key => `${advisory_customer_container[key]}`).join(",") : ''}}
                                    </div>
                                </td>
                                <td class="td-border">
                                    <label class="border border-purple-600 border-dashed cursor-pointer p-1">
                                        <input type="file" class="hidden" @change="selectedFile($event, 'customer', index)"/>
                                        Choose File
                                    </label>
                                </td>
                            </tr>
                            <tr v-for="(agent, index) in advisory_agents">
                                <td class="w-5">
                                    <input type="checkbox" class="check" :value="agent?.mlo_info?.code" v-model="selectedCustomerAgents.agents"/>
                                    <span class="mail mail-sent" v-if="agent?.mail=='sent'">
                                        Sent
                                    </span>

                                    <span class="mail mail-processing" v-if="agent?.mail=='pending'">
                                        Processing
                                    </span>

                                    <span class="mail mail-failed" v-if="agent?.mail=='failed'">
                                        Failed
                                    </span>

                                </td>
                                <td class="td-border "><span class="bg-gray-200 px-3 py-1 rounded-full">{{ index }}</span></td>
                                <td class="td-border bold">{{ agent?.mlo_info?.name }}</td>
                                <td class="td-border">{{ agent?.mlo_info?.primary_email }}</td>
                                <td class="td-border">{{ agent?.mlo_info?.cc_email }}</td>
                                <td class="td-border">
                                    <span class="text-gray-700 dark:text-gray-300 font-semibold">{{ agent?.total }} Containers</span>
                                    <div class="max-h-8 my-2 overflow-y-scroll block break-all hyphens-all">
                                        {{ agent.containers ? Object.keys(agent.containers).map(key => `${agent.containers[key]}`).join(",") : ''}}
                                    </div>
                                </td>
                                <td class="td-border">
                                    <label class="border border-purple-600 border-dashed cursor-pointer p-1">
                                        <input type="file" class="hidden" @change="selectedFile($event, 'agent', index)"/>
                                        Choose File
                                    </label>

                                    <div v-for="(attachment, attachmentIndex) in agent?.mail_attachment?.attachments" class="flex items-center mt-2 hover:bg-cool-gray-100 px-1 py-1 cursor-pointer rounded-md justify-between">
                                        <span>
                                            {{ attachmentName(attachment.name) }}
                                            
                                        </span>
                                        <button type="button" @click="removeAttachment(attachment.id, agent?.notificationId)" class="cursor-pointer text-sm text-center text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" data-v-3b751c1b=""><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" data-v-3b751c1b=""></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-white hidden">
                            <tr>
                                <td colspan="7" class="text-center">
                                    <span class="text-sm text-gray-500">Loading...</span>
                                    <span class="text-sm text-gray-500">No customers found</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
    </div>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .hyphens-all {
        hyphens: all;
    }
    th {
        @apply px-4 py-3;
    }
    td {
        @apply px-4 py-3 text-xs border-r-0;
    }
    thead tr {
        @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    tbody tr {
        @apply text-gray-700 dark:text-gray-400;
    }
    .check {
        @apply rounded text-purple-500 focus:ring-purple-500 dark:bg-gray-600;
    }
    .email {
        @apply text-purple-500 hover:underline dark:text-purple-600;
    }
    .tel {
        @apply hover:underline;
    }
    .pill {
        @apply px-2 py-1 font-semibold leading-tight rounded-full;
    }
    .pill-success {
        @apply text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100;
    }
    .pill-warning {
        @apply text-orange-700 bg-orange-100 dark:text-white dark:bg-orange-600;
    }
    .tr-active {
        @apply bg-green-200 dark:bg-gray-700 dark:text-gray-50;
    }
    .bold {
        @apply font-semibold dark:text-gray-200 capitalize;
    }
    .td-border {
        @apply border-l dark:border-gray-700;
    }

    .mail {
        @apply text-center block mt-2 px-2 py-1 font-semibold leading-tight  rounded-full 
    }
    .mail-sent {
        @apply text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100
    }

    .mail-failed {
        @apply text-red-700 bg-red-100 dark:text-red-100 dark:bg-red-700
    }

    .mail-processing {
        @apply text-gray-700 bg-gray-100 dark:text-gray-100 dark:bg-gray-700
    }
}
</style>