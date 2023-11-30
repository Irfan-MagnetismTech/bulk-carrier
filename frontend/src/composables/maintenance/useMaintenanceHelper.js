
import { ref } from "vue";

export default function useMaintenanceHelper() {
    
    const maintenanceTypes = ref([
        {'key': 'Schedule', 'value' : 'Schedule'},
        {'key': 'Breakdown', 'value' : 'Breakdown'},
        {'key': 'Dry Dock', 'value' : 'Dry Dock'},
    ]
        // {
        //     'Schedule' : 'Schedule',
        //     'Breakdown' : 'Breakdown',
        //     'Dry Dock' : 'Dry Dock'
        // }
    );

    const assignTo = ref(
        [
            {'key': 'Team', 'value': 'Team'},
            {'key': 'Vendor', 'value': 'Vendor'},
        ]
    );
    
    const workRequisitionStatus = ref(
        [
            {'key': "0", 'value' : 'Pending'},
            {'key': "1", 'value' : 'WIP'},
            {'key': "2", 'value' : 'Done'},
        ]
        // {
        //     "0" : 'Pending',
        //     "1" : 'WIP',
        //     "2" : 'Done'
        // }
    );


    


    

    return {
        maintenanceTypes,
        workRequisitionStatus,
        assignTo
    };
}
