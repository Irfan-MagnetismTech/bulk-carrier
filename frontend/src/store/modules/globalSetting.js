export const globalSetting = {
    state: {
        vueDatePickerTextInputFormat : {
            date : [
                'dd.MM.yyyy',
                'dd MM yyyy',
                'dd-MM-yyyy',
                'ddMMyyyy',
                'dd/MM/yyyy',

                'dd.MM.yy',
                'dd MM yy',
                'dd-MM-yy',
                'ddMMyy',
                'dd/MM/yy'
            ],

            
            dateTime : [
                'dd.MM.yyyy HH:mm',
                'dd MM yyyy HH:mm',
                'dd-MM-yyyy HH:mm',
                'ddMMyyyy HH:mm',
                'dd/MM/yyyy HH:mm',

                
                'dd.MM.yyyyHH:mm',
                'dd MM yyyy HH:mm',
                'dd-MM-yyyyHH:mm',
                'ddMMyyyyHH:mm',
                'dd/MM/yyyyHH:mm',

                'dd.MM.yyyy hh:mm a',
                'dd MM yyyy hh:mm a',
                'dd-MM-yyyy hh:mm a',
                'ddMMyyyy hh:mm a',
                'dd/MM/yyyy hh:mm a',

                
                'dd.MM.yyyyhh:mm a',
                'dd MM yyyyhh:mm a',
                'dd-MM-yyyyhh:mm a',
                'ddMMyyyyhh:mm a',
                'dd/MM/yyyyhh:mm a',



                'dd.MM.yy HH:mm',
                'dd MM yy HH:mm',
                'dd-MM-yy HH:mm',
                'ddMMyy HH:mm',
                'dd/MM/yy HH:mm',

              
                'dd.MM.yyHH:mm',
                'dd MM yyHH:mm',
                'dd-MM-yyHH:mm',
                'ddMMyyHH:mm',
                'dd/MM/yyHH:mm',

                'dd.MM.yy hh:mm a',
                'dd MM yy hh:mm a',
                'dd-MM-yy hh:mm a',
                'ddMMyy hh:mm a',
                'dd/MM/yy hh:mm a',

                
                'dd.MM.yyhh:mm a',
                'dd MM yyhh:mm a',
                'dd-MM-yyhh:mm a',
                'ddMMyyhh:mm a',
                'dd/MM/yyhh:mm a',

            ],


            
            month : [
                'MM.yyyy',
                'MM yyyy',
                'MM-yyyy',
                'MMyyyy',
                'MM/yyyy',

                'MM.yy',
                'MM yy',
                'MM-yy',
                'MMyy',
                'MM/yy'
            ],

            
            year : [
                'yyyy',
                'yyyy',
                'yyyy',
                'yyyy',

                'yy',
                'yy',
                'yy',
                'yy'
            ]



        },
        companyLogo : {
            logo : "../../../torony-logo.png"
        }
    },
    mutations: {
        
    },

    getters: {
        getVueDatePickerTextInputFormat(state) {
            return state.vueDatePickerTextInputFormat;
        },
        getCompanyLogo(state){
            return state.companyLogo;
        }
    }
};