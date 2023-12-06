class env {
    static PRODUCTION = 'https://bulk-erp-backend.magrepo.com/';
    static STAGING = 'https://bgdev-backend.magrepo.com/';
    static DEVELOPMENT_WITH_IP = 'http://192.168.88.178/bulk-carrier-erp/backend/public/';/*'http://bulk-carrier-erp.test/';*/
    static DEVELOPMENT = 'http://bulk-carrier-erp.test/';
    static MOBILEURL = 'http://192.168.88.173:1223/';
    static DEMO = 'http://192.168.88.77/';
    static SUMON_URL_OFFICE_LAN = 'http://192.168.88.222/bulk-carrier-erp/backend/public/';
    static SUMON_URL_TEMP = 'http://192.168.43.252/bulk-carrier-erp/backend/public/'; 
    static RABIUL_URL = 'http://192.168.88.40/bulk-carrier-erp/backend/public/'; 
    static SUMON_URL = 'http://192.168.88.222/bulk-carrier-erp/backend/public/';
    static DELOWAR_WSL = 'http://192.168.88.173:1337/'

    static BASE_API_URL = env.DEVELOPMENT_WITH_IP;

    static CLIENT_ID = 2
    static GRANT_TYPE = "password"
    static CLIENT_SECRET = "cCh0pcWvPsLHL08uFgSjNMaJO6HwvyttwBJpnqRU"
}

export default env;