<script setup>
import { watch } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    errors: {
        type: [Object],
        default: () => {},
    },
    
})
watch(
    () => props.errors,
    (value) => {
        let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
        if (Object.keys(value).length) {
            for (const property in value) {
                rawHtml += `<li> ${value[property]} </li>`
            }
            rawHtml += `</ul>`;

            Swal.fire({
                icon: "",
                title: "Correct Please!",
                html: `
                ${rawHtml}
                        `,
                customClass: "swal-width",
            });
        }
    }
);
</script>
<template>
    
</template>
<style lang="postcss" scoped>

</style>