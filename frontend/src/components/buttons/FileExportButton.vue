<script setup>
import { indexPdfExport, tableToExcel } from "../../utils/helper.js";
import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

const props = defineProps({
    businessUnit: {
      default: null,
      required: false
    },
    pageOrientation: {
      default: null,
      required: false
    },
    fileName: {
      default: null,
      required: false
    },
    tableId: {
      default: null,
      required: false
    },
    leftAlign: {
      default: null,
      required: false
    },
    rightAlign: {
      default: null,
      required: false
    },
    allPageLogoAndHeading: {
      default: false,
      required: false
    },
    signature: {
      default: false,
      required: false
    },
    allPageTableHeading: {
      default: false,
      required: false
    },
});
</script>
<template>
  <button title="Download PDF" class="pdf_button" @click="indexPdfExport(businessUnit, pageOrientation, fileName, tableId, leftAlign, rightAlign, allPageLogoAndHeading, signature, allPageTableHeading);">
    <span v-html="icons.PdfExportIcon"></span>
  </button>
  <button title="Download Excel" class="excel_button" @click="tableToExcel(tableId,fileName);">
    <span v-html="icons.ExcelExportIcon"></span>
  </button>
</template>