import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "operation";
const ROLE = USER?.role ?? null;
export default [];
