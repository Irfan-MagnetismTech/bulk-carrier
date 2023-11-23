
import { ref } from "vue";

export default function useMaintenanceHelper() {
    
    const maintenanceTypes = ref(
        {
            'Schedule' : 'Schedule',
            'Breakdown' : 'Breakdown',
            'Dry Dock' : 'Dry Dock'
        }
    );
    
    const workRequisitionStatus = ref(
        {
            "0" : 'Pending',
            "1" : 'WIP',
            "2" : 'Done'
        }
    );


    


    

    return {
        maintenanceTypes,
        workRequisitionStatus
    };
}
