import administration from "./administration";
import supplyChain from "./supply-chain";
import operation from "./operation";
import crew from "./crew";
import maintenance from "./maintenance";
import account from "./account";
import {ref} from "vue";

const sidebarElements = ref([
    ...administration,
    ...operation,
    ...crew,
    ...maintenance,
    ...supplyChain,
    ...account,
]);

export default sidebarElements;