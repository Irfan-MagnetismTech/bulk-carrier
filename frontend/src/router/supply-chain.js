import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "supply-chain";
const ROLE = USER?.role ?? null;
export default [];
