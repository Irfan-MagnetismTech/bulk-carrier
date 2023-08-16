<script setup>
    import {ref, watch, computed, onBeforeUnmount} from 'vue';
    import Error from "../../Error.vue";
    import useCashSale from "../../../composables/revenue/useCashSale.js";
    import useService from "../../../composables/revenue/useService.js";
    import useSaleRate from "../../../composables/revenue/useSaleRate.js";
    import useServiceRate from "../../../composables/revenue/useServiceRate.js";
    import moment from "moment";
    import useAuth from '../../../services/auth';
    import useMaterial from '../../../composables/scm/useMaterial';
    import useHelper from '../../../composables/useHelper';
    import { onMounted } from '@vue/runtime-core';

    const { cashSales, searchCashSale, storeCashSale, serviceObject, updateCashSale } = useCashSale();
    const { material, materials, searchMobilWithRate } = useMaterial();
    const { service, services, searchService } = useService();
    const { serviceRate, showServiceRate } = useServiceRate(); 
    const { saleRate, showSaleRate } = useSaleRate(); 
    const { username, shift } = useAuth();
    const { numberFormat } = useHelper();

    const props = defineProps({
        cashSale: { type: Object, required: true },
        mobilModel: { type: Object, required: true },
        serviceModel: { type: Object, required: true },
        vehicles: { type: Object, required: true },
        units: { type: Object, required: true },
        paymentMethods: { type: Object, required: true },
        formType: { type: Object, required: true },
        cashSaleId: { type: String, required: true },
        shift: { type: String, required: true },
    });

    

    const now = moment();

    // function fetchCategory(query) {
    //     searchCashSale(query);
    // }
    // function addService() {
    //     let serviceObject = { ...props.serviceModel }
    //     props.cashSale.services.push(serviceObject);
    // }

    // function removeService(index){
    //     if(props.cashSale.services.length>1) {
    //         props.cashSale.services.splice(index, 1);
    //     }
    // }

    function addMobil() {
        let mobilObject = { ...props.mobilModel }
        props.cashSale.mobil.push(mobilObject);
    }

    function removeMobil(index){
        if(props.cashSale.mobil.length>1) {
            props.cashSale.mobil.splice(index, 1);
        }
    }

    function fetchService(query, loading) {
        searchService(query, loading);
        loading(true)
    }

    function fetchMobil(query, loading) {
        searchMobilWithRate(query, loading);
        loading(true)
    }


    watch(() => props.cashSale.services, (services) => {
        for (let index in services) {
            let item = services[index];
            props.cashSale.services[index].service_id = item?.service?.id
            props.cashSale.services[index].unit_price = item?.service?.service_rate?.rate ?? 0
            props.cashSale.services[index].service_rate_id = item?.service?.service_rate?.id ?? 0

        }
        // calculatedAmount();

    }, {deep : true});

    // watch(() => props.cashSale.mobil, (mobils) => {
    //     for (let index in mobils) {
    //         let item = mobils[index];
    //         props.cashSale.mobil[index].unit_price = item?.material?.sale_rate?.rate ?? 0
    //         props.cashSale.mobil[index].material_category_id = item?.material?.category_id ?? 0
    //         props.cashSale.mobil[index].material_id = item?.material?.id ?? 0
    //         props.cashSale.mobil[index].unit = item?.material?.unit
    //         props.cashSale.mobil[index].in_stock = item?.material?.stock_sum_quantity
            

    //         if(props.cashSale.mobil[index].in_stock < props.cashSale.mobil[index].quantity) {
    //             alert("You can't add more than in stock")
    //             props.cashSale.mobil[index].quantity = props.cashSale.mobil[index].in_stock
    //         }
    //     }
    //     calculatedAmount();

    // }, {deep : true});

    watch(() => props.cashSale.fuel, (fuels) => {
        for (let index in fuels) {

            if(index > 2 ) {
                let item = fuels[index];

                props.cashSale.fuel[index].unit_price = item?.material?.sale_rate?.rate ?? 0
                props.cashSale.fuel[index].sale_rate_id = item?.material?.sale_rate?.id ?? 0
                props.cashSale.fuel[index].material_category_id = item?.material?.category_id ?? 0
                props.cashSale.fuel[index].material_id = item?.material?.id ?? 0
                props.cashSale.fuel[index].unit = item?.material?.unit
                props.cashSale.fuel[index].in_stock = item?.material?.stock_sum_quantity
                

                if(props.cashSale.fuel[index].in_stock < props.cashSale.fuel[index].quantity) {
                    alert("You can't add more than in stock")
                    props.cashSale.fuel[index].quantity = props.cashSale.fuel[index].in_stock
                }
            }
            
            if(props.cashSale.fuel[index].in_stock < props.cashSale.fuel[index].quantity) {
                alert("You can't add more than in stock")
                props.cashSale.fuel[index].quantity = props.cashSale.fuel[index].in_stock
            }
        }
        calculatedAmount();

    }, {deep : true});

    watch(() => props.cashSale.parent_category_name, (value) => {
        console.log('category', value)
        props.cashSale.parent_category = value?.id;
    });

    watch(() => props.cashSale.payment, (value) => {
        props.cashSale.payment_method = (value == true) ? 'Card' : '';
    })

    const grandTotal = computed(() => {
        let total = 0;
        previewPanel.value.materials.forEach((fuel) => {
            let amount = (fuel.amount != '') ? fuel.amount : 0;
            total += parseFloat(amount);
        });

        previewPanel.value.services.forEach((service) => {
            let amount = (service.amount != '') ? service.amount : 0;
            total += parseFloat(amount);
        });

        props.cashSale.total_amount = total
        return parseFloat(total).toFixed(2);
    });

    const calculatedAmount = () => {
        for(let index in props.cashSale.fuel) {
            let qty = (props.cashSale.fuel[index].quantity != '') ? props.cashSale.fuel[index].quantity : 0;
            let unit_price = (props.cashSale.fuel[index].unit_price != '') ? props.cashSale.fuel[index].unit_price : 0;
            let amount = parseFloat(qty) * parseFloat(unit_price);

            if(isNaN(amount)) {
                amount = 0
            }
            props.cashSale.fuel[index].amount = parseFloat(amount).toFixed(2);
        }

        for(let index in props.cashSale.services) {
            let qty = (props.cashSale.services[index].quantity != '' || props.cashSale.services[index].quantity != undefined) ? props.cashSale.services[index].quantity : 0;
            let unit_price = (props.cashSale.services[index].unit_price != '' || props.cashSale.services[index].unit_price != undefined) ? props.cashSale.services[index].unit_price : 0;
            let amount = parseFloat(qty) * parseFloat(unit_price);
            if(isNaN(amount)) {
                amount = 0
            }
            props.cashSale.services[index].amount = parseFloat(amount).toFixed(2);
        }

    }


    onBeforeUnmount(() => {
      document.removeEventListener('keydown', handleEvent);
    });
    /**
     * Code for shortcut key for sales form
     */

    const openTab = ref(3);
    let keyPressed = "";
    let lastKeyPressTime = 0;
    const timeThreshold = 300;
    document.addEventListener("keydown", handleEvent);

    function handleEvent(event) {
      const key = event.key.toLowerCase();
      const currentTime = new Date().getTime();
      if (key === "d") {
        if (keyPressed === "d" && currentTime - lastKeyPressTime < timeThreshold) {
          openTab.value = 0;
          setTimeout(() => {
            document.getElementById("0").focus();
          }, 0);
          keyPressed = "";
        } else {
          keyPressed = "d";
          lastKeyPressTime = currentTime;
        }
      } else if(key === "o"){
        if (keyPressed === "o" && currentTime - lastKeyPressTime < timeThreshold) {
          openTab.value = 1;
          setTimeout(() => {
            document.getElementById("1").focus();
          }, 0);
          keyPressed = "";
        } else {
          keyPressed = "o";
          lastKeyPressTime = currentTime;
        }
      } else if(key === "p"){
        if (keyPressed === "p" && currentTime - lastKeyPressTime < timeThreshold) {
          openTab.value = 2;
          setTimeout(() => {
            document.getElementById("2").focus();
          }, 0);
          keyPressed = "";
        } else {
          keyPressed = "p";
          lastKeyPressTime = currentTime;
        }
      } else if(key === "l"){
        if (keyPressed === "l" && currentTime - lastKeyPressTime < timeThreshold) {
          openTab.value = 3;
          setTimeout(() => {
            document.getElementById("3").focus();
          }, 0);
          keyPressed = "";
        } else {
          keyPressed = "l";
          lastKeyPressTime = currentTime;
        }
      } else if(event.key === "Enter" || event.code === "Enter"){
        storeToPreview();
      }  else if(key === "x"){
        saveCashSale();


      } else {
        keyPressed = "";
      }
    }

    function saveCashSale() {
        
        let data = {
            ... props.cashSale,
            ...previewPanel.value
        }

         var newWindowContent = document.getElementById('printableArea').innerHTML;
         var mediaQuery = `
                <style>
                @media print {
                          body {
                              margin: 0;
                          }
                      }
                    #heading{
                    font-size: 22px;
                    }
                    #subHeading{
                    font-size: 13px;
                    }
                    #cashMemo{
                    display: block;
                    font-size: 15px;
                    }
                    #date{
                    margin-top: 0 !important;
                    }
                    .hideInPrint {
                        display: none;
                    }
                    span {
                        display: block
                    }
                    table {
                        width: 100%; 
                        margin: 0 auto;
                    }
                    
                    table, tr, td {
                        border: 1px solid black;
                        border-collapse: collapse;   
                    }
                    #totalAmount {
                        width: 100%;
                        margin: 0 auto;
                        font-size: 17px;
                    }

                    td:nth-child(1) {
                        width: 30%;

                    }

                    td:nth-child(2) {
                        width: 10%;
                        text-align: center
                    }

                    td:nth-child(3) {
                        width: 30%;
                        text-align: right;
                    }

                    td:nth-child(4) {
                        width: 30%;
                        text-align: right;
                        font-weight: bold !important;

                    }

                    thead tr td {
                        font-weight: bold;
                        text-align: center !important;
                    }

                    h4, #date {
                        margin: 2px;
                        padding: 0;
                    }
                   
                </style>
                `;
         newWindowContent = mediaQuery + newWindowContent;
        

        if(props.formType == 'edit') {
            updateCashSale(data, props.cashSaleId)
        } else {
            storeCashSale(data)
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print</title></head><body>');
            printWindow.document.write(newWindowContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        }
    }

    const previewPanel = ref({
        materials: [],
        services: [],

    });

    function storeToPreview() {
        for(let index in props.cashSale.fuel) {
            let item = props.cashSale.fuel[index];

            if(item.quantity > 0 || item.quantity < 0 && item.unit_price > 0) {
                if(previewPanel.value.materials.length > 0) {

                        const materialIndex = previewPanel.value.materials.findIndex(obj => obj.material_id === item.material_id);

                        if(materialIndex !== -1) {
                            previewPanel.value.materials[materialIndex] = { ...item }
                        } else {
                            previewPanel.value.materials.push({ ...item })
                        }
                } else {
                    if(item.quantity > 0 || item.quantity < 0) {
                        previewPanel.value.materials.push({ ...item })
                        item.quantity = null;
                    }
                }
                item.quantity = null;
                item.material = null;
                openTab.value = 3
            }
        }

        for(let index in props.cashSale.services) {
            let item = props.cashSale.services[index];
            item.service_rate_id = item?.service?.service_rate?.id

            if(item.quantity > 0 || item.quantity < 0 && item.unit_price > 0) {
                if(previewPanel.value.services.length > 0) {
                        const materialIndex = previewPanel.value.services.findIndex(obj => obj.service_id === item.service_id);

                        if(materialIndex !== -1) {
                            previewPanel.value.services[materialIndex] = { ...item }
                        } else {
                            previewPanel.value.services.push({ ...item })
                        }
                } else {
                    if(item.quantity > 0 || item.quantity < 0) {
                        previewPanel.value.services.push({ ...item })
                        item.quantity = null;
                    }
                }
                item.quantity = null;
                item.service = null;
                openTab.value = 3
            }

        }
    }

    function removeFuelPreviewPanel(index){
            previewPanel.value.materials.splice(index, 1);
    }

    function removeServicePreviewPanel(index){
            previewPanel.value.services.splice(index, 1);
    }

    const hasUpdated = ref(0);

    onMounted(() => {
        if(props.formType=='create') {
            props.cashSale.shift = shift
        }


    })

    watch(() => props.cashSale, (value) => {
        if(hasUpdated.value == 0) {

            if(props.cashSale.payment_method == 'Card') {
                props.cashSale.payment = true;
            }

            for(let index in props.cashSale.materials) {
                let item = props.cashSale.materials[index];

                if(item.quantity > 0 || item.quantity < 0) {
                    previewPanel.value.materials.push({ ...item })
                    openTab.value = 3
                    item.quantity = null
                }
            }

            for(let index in props.cashSale.services) {
                let item = props.cashSale.services[index];
                console.log(index, item)
                if(item.quantity > 0 || item.quantity < 0) {
                    previewPanel.value.services.push({ ...item })
                    // openTab.value = 3
                    item.quantity = null
                }
            }

            props.cashSale.services = [{...serviceObject}];

            hasUpdated.value = 1;
        }
    });

</script>
<template>
    <div>
        <div class="">
            <div class="flex py-2">
                <div class="w-8/12 p-3 shadow-lg rounded-md border border-gray-400">
                    <div class="flex justify-between mb-3">
                        <h2 class="text-md font-semibold text-gray-500 dark:text-white">Sale Date: {{ now.format("DD/MM/YYYY") }} at {{ now.format("hh:mm A") }}</h2>
                        <h2 class="text-md font-semibold text-gray-500 dark:text-white">Shift: {{ cashSale.shift }} Shift</h2>
                        <label for="paymentMethod" class="flex items-center">
                            <input type="checkbox" class="rounded-full !outline-none focus:!outline-none" id="paymentMethod" value="Card" v-model="cashSale.payment"> 
                            <span class="ml-2">Card Payment</span>
                        </label>
                    </div>
                    <table class="w-full whitespace-no-wrap">
                        <thead v-once>
                            <tr>
                                <th colspan="7" class="py-2 text-center !text-lg"><strong>FUEL &amp; LUBRICANTS</strong></th>
                            </tr>
                            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-2 py-1 w-44">Name </th>
                                <th class="px-2 py-1">In Stock</th>
                                <th class="px-2 py-1 w-24">Unit</th>
                                <th class="px-2 py-1 w-28">Rate</th>
                                <th class="px-2 py-1 w-20">Qty.</th>
                                <th class="px-2 py-1 w-36">Amount</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <template v-for="(fuel,index) in cashSale.fuel" :key="index">
                            <tr class="text-gray-700 dark:text-gray-400 text-center"  v-bind:class="{'hidden': openTab !== index, '': openTab === index}">
                                <td class="px-2 py-1 text-sm" v-if="index < 3">
                                    <input type="text" v-model="cashSale.fuel[index].name" readonly class="bg-gray-100 label-item-input" name=" " :id="' '" />
                                </td>
                                <td class="px-2 py-1 text-sm" v-if="index == 3">
                                    <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMobil"  v-model="cashSale.fuel[index].material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                                    <input type="hidden" v-model="cashSale.fuel[index].material_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>  
                               
                                <td class="px-2 py-1 text-sm">
                                    <input type="text" v-model="cashSale.fuel[index].in_stock" readonly class="bg-gray-100 label-item-input" name=" " :id="' '" />
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <input type="text" v-model="cashSale.fuel[index].unit" readonly class="bg-gray-100 label-item-input" name=" " :id="' '" />
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <div v-if="formType=='edit'">
                                        <input type="number" min="0" @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="cashSale.fuel[index].unit_price" class="label-item-input" name=" " :id="' '" />
                                    </div>
                                    <div v-else>
                                        <input type="number" min="0" readonly @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="cashSale.fuel[index].unit_price" class="bg-gray-100 label-item-input" name=" " :id="' '" />
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-sm w-1/6">
                                    <input type="number" @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="cashSale.fuel[index].quantity" class="label-item-input" name=" " :id="index" />
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <input type="text" readonly v-model="cashSale.fuel[index].amount" class="bg-gray-100 label-item-input" name=" " :id="' '" />
                                </td>
                            </tr>
                        </template>

                        </tbody>
                    </table>

                    <table class="w-full whitespace-no-wrap mt-5">
                        <thead v-once>
                            <tr>
                                <th colspan="7" class="py-2 text-center !text-lg uppercase"><strong>Service</strong></th>
                            </tr>
                            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-2 py-1 w-56">Service</th>
                                <th class="px-2 py-1 w-28">Rate</th>
                                <th class="px-2 py-1 w-24">Qty.</th>
                                <th class="px-2 py-1 w-36">Amount</th>
                                <!-- <th class="px-2 py-1">Action</th> -->
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100 divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr class="text-gray-700 bg-white dark:text-gray-400 text-center" v-for="(service,index) in cashSale.services" :key="index">
                                
                                <td class="px-2 py-1 text-sm">
                                    <v-select :options="services" placeholder="--Choose an option--" @search="fetchService"  v-model="cashSale.services[index].service" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                                    <input type="hidden" v-model="cashSale.services[index].service_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />

                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <div v-if="formType=='edit'">
                                        <input type="number" min="0" @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="cashSale.services[index].unit_price" class="label-item-input" name=" " :id="' '" />
                                    </div>
                                    <div v-else>
                                        <input type="number" min="0" readonly @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="cashSale.services[index].unit_price" class="bg-gray-100 label-item-input" name=" " :id="' '" />
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <input type="number" @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="cashSale.services[index].quantity" class="label-item-input" name=" " :id="' '" />
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <input type="text" readonly v-model="cashSale.services[index].amount" class="bg-gray-100 label-item-input" name=" " :id="' '" />
                                </td>
                                <!-- <td class="px-2 py-1 text-sm">
                                    <button v-if="index == 0" @click="addService(index)" type="button" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">+</button>
                                    <button v-else @click="removeService(index)" type="button" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">-</button>
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                   
                </div>
                <div class="w-4/12 ml-3 p-3 shadow-lg rounded-md border border-gray-400">
                    
                    
                    <div id="printableArea" class="w-full">
                        <div>
                            <h2 class="text-lg font-bold text-center" style="text-align: center; margin-bottom: 5px" id="heading">QC Filling Station</h2>
                            <div class="text-center" style="text-align: center" id="subHeading">
                                <span class="block text-sm">Dealer : Meghna Petroleum Ltd.</span>
                                <span class="block text-sm">College Road, Chittagong.</span>
                                <span class="block text-sm">Phone : 02333-356710</span>
                            </div>
                            <h4 class="text-sm font-bold text-center" style="text-align: center; margin-top: 15px" id="cashMemo">CASH MEMO</h4>
                            <div class="flex justify-between ml-2" style="overflow: hidden; margin-top: 0;" id="date">
                                <div style="width: 50%; float: left" class="ml-2">{{ now.format("DD/MM/YYYY") }}</div>
                                <div style="width: 50%; float: left; text-align: right" class="pr-4">{{ now.format("hh:mm:ss A") }}</div>
                            </div>
                        </div>
                        <div v-if="previewPanel.materials.length > 0">
                            <h4 class="text-md text-center my-2 font-semibold" style="text-align: center">Fuel &amp; Lubricants</h4>
                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <td class="text-sm text-center">Item</td>
                                        <td class="w-12 text-sm text-center">Qty.</td>
                                        <td class="w-12 text-sm text-center">Rate</td>
                                        <td class="w-20 text-sm text-center">Amount</td>
                                        <td class="w-12 text-sm text-center hideInPrint">Action</td>
                                    </tr>
                                </thead>
                                <tr v-for="(fuel,index) in previewPanel.materials" :key="index">
                                    <td class="text-center">{{ fuel.name ?? fuel.material.name }}</td>
                                    <td class="text-center">{{ fuel.quantity }}</td>
                                    <td class="text-center">{{ numberFormat(fuel.unit_price) }}</td>
                                    <td class="text-center">{{ numberFormat(fuel.amount) }}</td>
                                    <td class="hideInPrint">
                                        <button @click="removeFuelPreviewPanel(index)" type="button" class="my-2 mx-auto flex items-center justify-center text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-full active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div v-if="previewPanel.services.length > 0">
                            <h4 class="text-md text-center my-2 font-semibold" style="text-align: center">Services</h4>
                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <td class="text-sm text-center">Item</td>
                                        <td class="w-12 text-sm text-center">Qty.</td>
                                        <td class="w-12 text-sm text-center">Rate</td>
                                        <td class="w-20 text-sm text-center">Amount</td>
                                        <td class="w-12 text-sm text-center hideInPrint">Action</td>
                                    </tr>
                                </thead>
                                <tr v-for="(service,index) in previewPanel.services" :key="index">
                                    <td class="text-center">{{ service.name ?? service.service.name }}</td>
                                    <td class="text-center">{{ service.quantity }}</td>
                                    <td class="text-center">{{ numberFormat(service.unit_price) }}</td>
                                    <td class="text-center">{{ numberFormat(service.amount) }}</td>
                                    <td class="hideInPrint">
                                        <button @click="removeServicePreviewPanel(index)" type="button" class="my-2 mx-auto flex items-center justify-center text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-full active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <div class="my-2 ml-3 text-right pr-14" style="text-align: right" id="totalAmount">
                            <strong>Total Amount: {{ numberFormat(grandTotal) }}</strong> 
                        </div>

                    </div>

                    <div class="flex flex-col items-center" v-if="previewPanel.materials.length > 0 || previewPanel.services.length > 0">
                        
                        <button @click="saveCashSale()" type="button" class="my-2 flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
                            <span v-if="formType == 'create'">Save &amp; Print</span>
                            <span v-else>Update</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="flex justify-end mt-3 ml-2">
            <input type="hidden" name=" " :id="' '" v-model="cashSale.total_amount" >
        </div>
    </div>
</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
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
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    th {
        @apply text-xs
    }
    tr,td,th {
        @apply border
    }

    @media print {
        html  {
            display: none;
        }
        #printableArea {
            display: block;
        }
    }

</style>