import axios from 'axios';
import env from '../config/env';

const API = axios;
API.defaults.baseURL = env.BASE_API_URL + 'api/';

export default API;