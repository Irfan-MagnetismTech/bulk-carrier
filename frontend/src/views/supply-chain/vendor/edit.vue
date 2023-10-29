<script setup>
import VendorForm from '../../../components/supply-chain/vendor/VendorForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useVendor from "../../../composables/supply-chain/useVendor.js";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();
import { useRoute } from 'vue-router';
import { useStore } from "vuex";
const store = useStore();

const { vendor, showVendor, updateVendor,isLoading} = useVendor();
const { setTitle } = Title();
const route = useRoute();
const vendorId = route.params.vendorId;


setTitle('Edit Vendor');

onMounted(() => {
    showVendor(vendorId);
    
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Vendor</h2>
        <default-button :title="'Unit List'" :to="{ name: 'scm.vendor.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateVendor(vendor, vendorId)">
            <vendor-form v-model:form="vendor" :errors="errors"></vendor-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
