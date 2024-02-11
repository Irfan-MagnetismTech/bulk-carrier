<?php

return [
    'store_category' => [
        'Deck Store' => 'Deck Store',
        'Bond Store' => 'Bond Store',
        'Saloon Store' => 'Saloon Store',
        'Provision Store' => 'Provision Store',
        'Engine Store' => 'Engine Store',
    ],

    'product_type' => [
        'Bunker',
        'Lube Oil',
        'Saloon Store',
        'Provision Store',
        'Engine Store',
    ],

    'currencies' => [
        'BDT',
        'USD',
        'INR'
    ],

    'lc_cost_heads' => [
        'Bank Commision and Charges (BDT)(a)',
        'VAT (BDT)(b)',
        'Others (BDT)(c)',
        'Insurance Premium Amount (BDT)(d)',
    ],

    'material_costing_head' => [
            'cfr' => [
                'Ex-Works (EXW) / Freight on Board (FOB)',
                'Freight (Import)',
            ],
            'total_cfr' => [
                'Total CFR',
            ],
            'cif' => [
                'Insurance',
            ],
            'total_cif' => [
                'Total CIF',
            ],
            'a' => [
                'Landing Charge',
            ],
            'total_assessable' => [
                'Total Assessable Value',
            ],
            'tti' => [
                'Customs Duty (CD)',
                'Regulatoey Duty (RD)',
                'Supplimentary Duty (SD)',
                'Value Added Tax (VAT)',
                'Advance Tax (AT)',
                'Advance Income Tax (AIT)',
            ],
            'total_tti' => [
                'Total Tax Incidence',
            ],
            'tc' => [
                'Port Charges',
                'Miscellaneous Custom Charges',
                'C & F Agent Commission',
                'Transport Charges',
                'Unloading Charge',
                'Survey Cost',
                'Other Charges',
            ],
            'total_tc' => [
                'Total Cost',
            ],
            'total_lc_cost' => [
                'Total LC Cost(From LC Record)',
            ],
            'grand_total' => [
                'Grand Total',
            ],
        ],

    'bunker_consumption_used_heads' => [
        'ME',
        'GE',
        'Blr',
        'IG',
        'Main',
        'Aux',
        'ME Cyl. Oil',
        'GE Sys. Oil'
    ],

    'engine_temparature_types' => [
        'ME TC Exh. In',
        'ME TC Exh. Out',
        'ME TC LO OUT',
        'ME Scv. Temp.',
        'ME Scv. Press',
        'ME FW Out Temp.',
        'ME FW In Temp.'
    ]

];
