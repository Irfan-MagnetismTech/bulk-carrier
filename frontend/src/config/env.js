class env {
    static PRODUCTION = 'https://bulk-erp-backend.magrepo.com/';
    static DEVELOPMENT_WITH_IP = 'http://192.168.88.178/bulk-carrier-erp/backend/public/';/*'http://bulk-carrier-erp.test/';*/
    static DEVELOPMENT = 'http://bulk-carrier-erp.test/';
    static MOBILEURL = 'http://192.168.88.173:1223/';
    static DEMO = '';
    static SUMON_URL = 'http://192.168.88.222/bulk-carrier-erp/backend/public/';

    static BASE_API_URL = env.PRODUCTION;

    static CLIENT_ID = 2
    static GRANT_TYPE = "password"
    static CLIENT_SECRET = "cCh0pcWvPsLHL08uFgSjNMaJO6HwvyttwBJpnqRU"
}

export default env;