<script setup>

const props = defineProps({
  customerLoadingInformations: {
    required: false,
    default: {}
  }
});

</script>
<template>
      <table class="w-full whitespace-no-wrap" id="customer-loading-information-table">
        <thead>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <td> #</td>
          <td> Vessel</td>
          <td> VOY</td>
          <td> SVC</td>
          <td> Bound</td>
          <td> Bill of Lading</td>
          <td> CID </td>
          <td> S/O</td>
          <td> Sector</td>
          <td> ATA (POL)</td>
          <td> ATB (POL)</td>
          <td> ATD (POL)</td>
          <td> 20 TTL (L) </td>
          <td> 40 TTL (L) </td>
          <td> 45 TTL (L) </td>
          <td> TTL Boxes (L) </td>
          <td> TTL TEUs (L) </td>
          <td> TTL WT  (L) </td>
          <td> WT Per TEU (M) </td>

          <td> 20 TTL (M) </td>
          <td> 40 TTL (M) </td>
          <td> 45 TTL (M) </td>
          <td> TTL Boxes (M) </td>
          <td> TTL TEUs (M) </td>
          <td> TTL WT  (M) </td>
          <td> WT Per TEU (M) </td>
          <td> Boxes (G. TTL) </td>
          <td> TEUs   (G. TTL) </td>
          <td> M/Tons (G. TTL) </td>
          <td> WT Per TEU (G.TTL) </td>
          <td> Grand TTL DG Tues </td>
          <td> Grand TTL DG Box </td>

        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"> 
          
          <tr v-if="customerLoadingInformations" v-for="(bl, index) in customerLoadingInformations">          
            <td>{{ index+1 }}</td>
            <td><nobr>{{ bl.vessel }}</nobr></td>
            <td><nobr>{{ bl.voyage }}</nobr></td>
            <td><nobr>{{ bl.service }}</nobr></td>
            <td><nobr>{{ bl.bound }}</nobr></td>
            <td><nobr>{{ bl.bl_no }}</nobr></td>
            <td><nobr>{{ bl.customer_code }}</nobr></td>
            <td><nobr>{{ bl.slot_partner }}</nobr></td>
            <td><nobr>{{ bl.sector }}</nobr></td>
            <td><nobr>{{ bl.ata }}</nobr></td>
            <td><nobr>{{ bl.atb }}</nobr></td>
            <td><nobr>{{ bl.atd }}</nobr></td>

            <td> {{ bl.fcl_containers.ttl_tues_20 }} </td>
            <td> {{ bl.fcl_containers.ttl_tues_40 }} </td>
            <td> {{ bl.fcl_containers.ttl_tues_45 }} </td>
            <td>{{ bl.fcl_containers.ttl_box ?? '--'}}</td>
            <td>{{ bl.fcl_containers.ttl_tues ?? '--'}}</td>
            <td>{{ bl.fcl_containers.ttl_wt_mt ?? '--'}}</td>
            <td>{{ bl.fcl_containers.ttl_per_box ?? '--'}}</td>

            <td> {{ bl.mty_containers.ttl_tues_20 }} </td>
            <td> {{ bl.mty_containers.ttl_tues_40 }} </td>
            <td> {{ bl.mty_containers.ttl_tues_45 }} </td>            
            <td>{{ bl.mty_containers.ttl_box ?? '--'}}</td>
            <td>{{ bl.mty_containers.ttl_tues ?? '--'}}</td>
            <td>{{ bl.mty_containers.ttl_wt_mt ?? '--'}}</td>
            <td>{{ bl.mty_containers.ttl_per_box ?? '--'}}</td>

            <td> {{ bl.grand_ttl_box }} </td>
            <td> {{ bl.grand_ttl_tues }} </td>
            <td> {{ bl.grand_ttl_wt_mt }} </td>
            <td> {{ bl.grand_per_box }} </td>

            <td> {{ bl.grand_ttl_dg_tues }} </td>
            <td> {{ bl.grand_ttl_dg_box }} </td>

          </tr>

        </tbody>
        <tfoot>
        </tfoot>
      </table>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .search {
    @apply float-right pr-10 text-sm border border-gray-600 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
  }

  .scroll-show::-webkit-scrollbar {
    height: 8px !important;
    border-radius: 15px !important;
    background: lightgray !important;
  }

  .scroll-show::-webkit-scrollbar-thumb {
    background: gray !important;
    border-radius: 10px !important;
  }
  th {
    @apply p-10 border-r text-center;
  }

  thead td{
    text-transform: capitalize;
  }

  tbody td,
  thead td,
  thead th {
    @apply px-1 py-1 text-xs border-r text-center;
    /*font-size: 10px !important;*/
  }
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