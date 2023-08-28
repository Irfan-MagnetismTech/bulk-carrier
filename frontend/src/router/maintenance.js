import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "maintenance";
const ROLE = USER?.role ?? null;
export default [];
