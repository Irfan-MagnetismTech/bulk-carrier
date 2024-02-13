<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useChartOfAccount from "../../../composables/accounts/useChartOfAccount";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";

const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);
const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});
const icons = useHeroIcon();
const { chartOfAccounts, getChartOfAccounts, deleteChartOfAccount, isLoading, isTableLoading  } = useChartOfAccount();
const { setTitle } = Title();
setTitle('Chart of Accounts List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const rightAlign = [];
const leftAlign = [1,5];

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteChartOfAccount(id);
    }
  })
}

// watch(
//     () => businessUnit.value,
//     (newBusinessUnit, oldBusinessUnit) => {
//       if (newBusinessUnit !== oldBusinessUnit) {
//         router.push({ name: "acc.chart-of-accounts.index", query: { page: 1 } })
//       }
//     }
// );


let isTableLoader = ref(false);
let showFilter = ref(false);
function swapFilter() {
  showFilter.value = !showFilter.value;
}
function clearFilter(){
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = "";
    filterOptions.value.filter_options[index].order_by = null;
  });
}

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "balanceIncome",
      "field_name": "line_text",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "balanceIncome",
      "field_name": "line_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "parent",
      "field_name": "account_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "account_code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "account_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "account_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    }
  ]
});

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

const currentPage = ref(1);
const paginatedPage = ref(1);

onMounted(() => {
  watchPostEffect(() => {
    
  if(currentPage.value == props.page && currentPage.value != 1) {
    filterOptions.value.page = 1;
  } else {
    filterOptions.value.page = props.page;
  }
    currentPage.value = props.page;

    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }

  getChartOfAccounts(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;

        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
        isTableLoader.value = true;
      })
      .catch((error) => {
        console.error("Error fetching ranks:", error);
      });
  });

  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });

});

import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import env from "../../../config/env";


function imgToBase64(url, callback) {
    if (!window.FileReader) {
      callback(null);
      return;
    }
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function() {
      var reader = new FileReader();
      reader.onloadend = function() {
        callback(reader.result.replace('text/xml', 'image/jpeg'));
      };
      reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.send();
  }

  var base64Img;

  // Convert the image to base64
  imgToBase64('../torony-logo.png', function(base64) {
    base64Img = base64;
  });
  import { indexPdfExport } from "../../../utils/helper.js";
function generate(){
  indexPdfExport(businessUnit,'l', 'Lorem Ipsum','exampleTable', leftAlign, rightAlign, true, true, false);
  return;
    var doc = new jsPDF('l','pt', "a4");
    var htmlstring = '';
    var tempVarToCheckPageHeight = 0;
    var pageHeight = 0; //pending
    pageHeight = doc.internal.pageSize.height; //pending

    
           var margins = {
                top: 250,
                bottom: 200,
                left: 40,
                right: 40,
                width: 90
            };
            var y = 20;
            doc.setLineWidth(2);
              var pageSize = doc.internal.pageSize;
                  var width = pageSize.width ? pageSize.width : pageSize.getWidth();
                  var height = pageSize.height ? pageSize.height : pageSize.getHeight();
                  if (base64Img) {
                    doc.addImage(base64Img, 'JPEG', width/2 - 40, 5, 80, 80);
                  }
            // doc.text(330, y = y + 100, "Cash Book By Incomehead");
            var text = "Chart of Accounts List";
    
            // var xOffset = (doc.internal.pageSize.width / 2) - (doc.getStringUnitWidth(text) * doc.internal.getFontSize() / 2); 
            doc.text((doc.internal.pageSize.width / 2) - (doc.getStringUnitWidth(text) * doc.internal.getFontSize() / 2), y = y + 100, text);
            
            // doc.setMargins(200, 300, 400, 500);
            // doc.text(330, y = y + 30, " {!! $from_year !!} - {!! $to_year !!}");
            
            // var res = doc.autoTableHtmlToJson(document.getElementById("bascExample2"), true);
            // console.log("res" , res);
            // console.log("col" , res.columns);
            // console.log("data" , res.data);
            // doc.autoTable( {
            //     head: [[{content: res.columns[0], colSpan: 2, styles: { halign: 'center' }}]],
            //     body: res.data,
            //     margin: {
            //         top: 100
            //     }
            // });
            var res = doc.autoTableHtmlToJson(document.getElementById('exampleTable'), true );
            console.log(res);
            // var base64Img = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyBAMAAADsEZWCAAAAG1BMVEXMzMyWlpaqqqq3t7exsbGcnJy+vr6jo6PFxcUFpPI/AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAQUlEQVQ4jWNgGAWjgP6ASdncAEaiAhaGiACmFhCJLsMaIiDAEQEi0WXYEiMCOCJAJIY9KuYGTC0gknpuHwXDGwAA5fsIZw0iYWYAAAAASUVORK5CYII='
            var totalPagesExp = '{total_pages_count_string}'
            doc.autoTable({ 
              head: [res.columns.slice(0, -1)],
              body: res.data,
              theme: 'grid', 
              margin: {
                    bottom: 100
                },
                columnStyles:{
                  4:{
                      halign: 'center'
                    },
                },
              startY: 150,
                headStyles: {
                  fillColor : '#F2F2F7',
                  lineWidth: 0.5,
                  lineColor: '#E2E4E8',
                  textColor: '#4c4f52',
                  halign: 'center'

              },
                useCss: true, 
                rowPageBreak: 'auto',
                didDrawPage: function(data) {
                  // image start
                  // var pageSize = doc.internal.pageSize;
                  // var width = pageSize.width ? pageSize.width : pageSize.getWidth();
                  // var height = pageSize.height ? pageSize.height : pageSize.getHeight();
                  // if (base64Img) {
                  //   doc.addImage(base64Img, 'JPEG', width/2 - 20, 5, 80, 80);
                  // }
                  // image end

                  // Signature Start

                
                  let startSignX, signWidth, signRectY = height - 90, rectHeight = 60, gap = 8, total_sign = 4;
                  let rectWidth = (width -data.settings.margin.left-data.settings.margin.right - (gap*(total_sign+1)))/total_sign;
                  doc.setLineWidth(1);
                  doc.setFontSize(12);

                  //sign 1 start
                  startSignX = data.settings.margin.left + gap;
                  doc.rect(startSignX, signRectY, rectWidth, rectHeight );
                  doc.line(startSignX + 2, signRectY + 40, startSignX + rectWidth - 2, signRectY + 40  )
                  var signText = "Signature";
                  var textWidth = doc.getStringUnitWidth(signText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
                  var startTextX = ((startSignX + (startSignX+rectWidth)) - textWidth) / 2;
                  console.log("startSignX =>", startSignX, "rectWidth =>",rectWidth, "textWidth=>", textWidth, "startTextX =>" ,startTextX);
                  doc.text(signText,startTextX, signRectY + 50);
                  //sign 1 end

                  
                  //sign 2 start
                  console.log(startSignX, rectWidth);
                  startSignX = startSignX + gap + rectWidth;
                  doc.rect(startSignX, signRectY, rectWidth, rectHeight );
                  doc.line(startSignX + 2, signRectY + 40, startSignX + rectWidth - 2, signRectY + 40  )
                  var signText = "Signature2";
                  var textWidth = doc.getStringUnitWidth(signText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
                  var startTextX = ((startSignX + (startSignX+rectWidth)) - textWidth) / 2;
                  console.log("startSignX =>", startSignX, "rectWidth =>",rectWidth, "textWidth=>", textWidth, "startTextX =>" ,startTextX);
                  doc.text(signText,startTextX, signRectY + 50);
                  //sign 2 end

                  
                  
                  //sign 2 start
                  console.log(startSignX, rectWidth);
                  startSignX = startSignX + gap + rectWidth;
                  doc.rect(startSignX, signRectY, rectWidth, rectHeight );
                  doc.line(startSignX + 2, signRectY + 40, startSignX + rectWidth - 2, signRectY + 40  )
                  var signText = "Signature3";
                  var textWidth = doc.getStringUnitWidth(signText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
                  var startTextX = ((startSignX + (startSignX+rectWidth)) - textWidth) / 2;
                  console.log("startSignX =>", startSignX, "rectWidth =>",rectWidth, "textWidth=>", textWidth, "startTextX =>" ,startTextX);
                  doc.text(signText,startTextX, signRectY + 50);
                  //sign 2 end

                  
                  
                  //sign 2 start
                  console.log(startSignX, rectWidth);
                  startSignX = startSignX + gap + rectWidth;
                  doc.rect(startSignX, signRectY, rectWidth, rectHeight );
                  doc.line(startSignX + 2, signRectY + 40, startSignX + rectWidth - 2, signRectY + 40  )
                  var signText = "Signature4";
                  var textWidth = doc.getStringUnitWidth(signText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
                  var startTextX = ((startSignX + (startSignX+rectWidth)) - textWidth) / 2;
                  console.log("startSignX =>", startSignX, "rectWidth =>",rectWidth, "textWidth=>", textWidth, "startTextX =>" ,startTextX);
                  doc.text(signText,startTextX, signRectY + 50);
                  //sign 2 end

                  
                  







                  
                  // doc.setLineWidth(1);
                  // doc.rect(data.settings.margin.left+signWidth+5, height - 90, 50, 60);
                  // doc.setFontSize(12);
                  // doc.line(data.settings.margin.left+signWidth+7, height-50, data.settings.margin.left+signWidth+7+46, height-50);
                  // doc.text("Signature",data.settings.margin.left+signWidth+5,height - 40);
                  // Signature End
                  // Footer
      var str = 'Page ' + doc.internal.getNumberOfPages()
      // Total page number plugin only available in jspdf v1.0+
      if (typeof doc.putTotalPages === 'function') {
        str = str + ' of ' + totalPagesExp
      }
      doc.setFontSize(10)

      // jsPDF 1.4+ uses getHeight, <1.4 uses .height
      var pageSize = doc.internal.pageSize
      var pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight()
      doc.text(str, data.settings.margin.left, pageHeight - 10)
                },

                didParseCell: function (data) {
                  if (data.column.dataKey === 2 && data.row.section === 'body') {
                    data.cell.styles.textColor = 'red';
                  }

                  if (
                    (data.row.section === 'head' || data.row.section === 'foot') &&
                    data.column.dataKey === 'expenses'
                  ) {
                    data.cell.text = '' // Use an icon in didDrawCell instead
                  }

                  if (data.column.dataKey === 'city') {
                    data.cell.styles.font = 'mitubachi'
                    if (data.row.section === 'head') {
                      data.cell.text = 'シティ'
                    }
                    if (data.row.index === 0 && data.row.section === 'body') {
                      data.cell.text = 'とうきょう'
                    }
                  }
                },
                willDrawCell: function (data) {
                  if (data.row.section === 'head') {
                    
                    // if (data.pageCount > 1) {
                    //   return false;
                    // }
                  }
                  if (data.row.section === 'body' ) {
                    console.log("object", data.column);
                    if(data.pageCount == 1){
                      
                    }
                  }
                },
      });
      if (typeof doc.putTotalPages === 'function') {
        doc.putTotalPages(totalPagesExp)
      }
                
            // doc.autoTable({ html: '#bascExample2', margin: {
            //         top: 50
            //     } });
            // var res2 = doc.autoTableHtmlToJson(document.getElementById("bascExample2"), true);
            // doc.autoTable(res2.columns, res2.data, {
            //     margin: {
            //         top: 20
            //     }
            // });
            doc.save('Chart_of_Accounts_List.pdf');
}


</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3">
    <h2 class="text-2xl font-semibold text-gray-700">Chart of Accounts List</h2>
    <default-button :title="'Create Chart of Accounts'" :to="{ name: 'acc.chart-of-accounts.create' }" :icon="icons.AddIcon"></default-button>
    <button @click="indexPdfExport(businessUnit,'l', 'Lorem Ipsum','exampleTable', leftAlign, rightAlign, true, true, false);">Generate</button>
  </div>
<!--  <div class="flex items-center justify-between mb-2 select-none">-->
<!--    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>-->
<!--    &lt;!&ndash; Search &ndash;&gt;-->
<!--    <div class="relative w-full">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <input type="text" placeholder="Search..." class="search" />-->
<!--    </div>-->
<!--  </div>-->

<!--  <p v-if="showFilter">{{ showFilter }}</p>-->

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen">
      
      <table class="w-full whitespace-no-wrap"  id="exampleTable">
          <thead>
            <tr class="w-full">
              <th class="w-16">
                <div class="w-full flex items-center justify-between">
                  # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Balance/Income Line</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Balance/Income Line Type</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Parent Account</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Account Code</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Account Name</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Account Type</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Business Unit</nobr></span>
                </div>
              </th>
              <th class="w-20" id="bypassme">Action</th>
            </tr>
            <tr class="w-full" v-if="showFilter">
              <th>
                <select v-model="filterOptions.items_per_page" class="filter_input">
                  <option value="15">15</option>
                  <option value="30">30</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </th>
              <th><input v-model="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[4].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th>
                <select class="filter_input" v-model="filterOptions.filter_options[5].search_param" autocomplete="off">
                  <option value="" disabled selected>Select</option>
                  <option value="1"> Assets </option>
                  <option value="2"> Liabilities </option>
                  <option value="3"> Equity </option>
                  <option value="4"> Revenues </option>
                  <option value="5"> Expenses </option>
                </select>
              </th>
              <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
              <th><button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button></th>
            </tr>
          </thead>
          <tbody  class="relative">
          <tr v-for="(chartAccountData,index) in chartOfAccounts?.data" :key="index">
            <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
            <td :class= "{'!text-left' : leftAlign.indexOf(this.cellIndex)}">{{ chartAccountData?.balanceIncome?.line_text }}</td>
            <td>{{ chartAccountData?.balanceIncome?.line_type }}</td>
            <td>{{ chartAccountData?.parent?.account_name ?? '---' }}</td>
            <td>{{ chartAccountData?.account_code }}</td>
            <td :class= "{'!text-left' : leftAlign.indexOf(this.cellIndex)}">{{ chartAccountData?.account_name }}</td>
            <td>
              <span v-if="chartAccountData?.account_type===1" class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-200 rounded-full dark-disabled:text-gray-100 dark-disabled:bg-gray-700">Assets</span>
              <span v-if="chartAccountData?.account_type===2" class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-green-200 rounded-full dark-disabled:text-gray-100 dark-disabled:bg-gray-700">Liabilities</span>
              <span v-if="chartAccountData?.account_type===3" class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-orange-200 rounded-full dark-disabled:text-gray-100 dark-disabled:bg-gray-700">Equity</span>
              <span v-if="chartAccountData?.account_type===4" class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-yellow-200 rounded-full dark-disabled:text-gray-100 dark-disabled:bg-gray-700">Revenues</span>
              <span v-if="chartAccountData?.account_type===5" class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-pink-200 rounded-full dark-disabled:text-gray-100 dark-disabled:bg-gray-700">Expenses</span>
            </td>
            <td>
              <span :class="chartAccountData?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ chartAccountData?.business_unit }}</span>
            </td>
            <td>
              <nobr>
                <action-button :action="'edit'" :to="{ name: 'acc.chart-of-accounts.edit', params: { chartOfAccountId: chartAccountData?.id } }"></action-button>
                <action-button @click="confirmDelete(chartAccountData?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && chartOfAccounts?.data?.length"></LoaderComponent>
          </tbody>
        <tfoot v-if="!chartOfAccounts?.data?.length" class="relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="9">Loading...</td>
        </tr>
        <tr v-else-if="isTableLoading">
              <td colspan="9">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
        <tr v-else-if="!chartOfAccounts?.data?.length">
          <td colspan="9">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="chartOfAccounts" to="acc.chart-of-accounts.index" :page="page"></Paginate>
  </div>
</template>