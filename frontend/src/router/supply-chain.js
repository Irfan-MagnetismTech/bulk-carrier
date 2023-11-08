import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "scm";
const PATH_BASE = "supply-chain";
const ROLE = USER?.role ?? null;
export default [

    /* Unit Route start */
    
    {
        path: `/${BASE}/units`,
        name: `${BASE}.units.index`,
        component: () => import(`../views/${PATH_BASE}/unit/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/unit/create`,
        name: `${BASE}.unit.create`,
        component: () => import(`../views/${PATH_BASE}/unit/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-create' },
    },
    {
        path: `/${BASE}/unit/:unitId/edit`,
        name: `${BASE}.unit.edit`,
        component: () => import(`../views/${PATH_BASE}/unit/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-edit' },
    },
    {
        path: `/${BASE}/unit/:unitId`,
        name: `${BASE}.unit.show`,
        component: () => import(`../views/${PATH_BASE}/unit/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'unit-show'  },
    },

    /* Unit Route end */

    /* Material Category Route start */
    
    {
        path: `/${BASE}/material-categories`,
        name: `${BASE}.material-category.index`,
        component: () => import(`../views/${PATH_BASE}/material-category/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material-category/create`,
        name: `${BASE}.material-category.create`,
        component: () => import(`../views/${PATH_BASE}/material-category/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-create' },
    },
    {
        path: `/${BASE}/material-category/:materialCategoryId/edit`,
        name: `${BASE}.material-category.edit`,
        component: () => import(`../views/${PATH_BASE}/material-category/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-edit' },
    },
    {
        path: `/${BASE}/material-category/:materialCategoryId`,
        name: `${BASE}.material-category.show`,
        component: () => import(`../views/${PATH_BASE}/material-category/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-category-show'  },
    },

    /* Material Category Route end */
    /* Warehouse Route start */

	{
		path: `/${BASE}/warehouses`,
		name: `${BASE}.warehouse.index`,
		component: () => import(`../views/${PATH_BASE}/warehouse/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/warehouse/create`,
		name: `${BASE}.warehouse.create`,
		component: () => import(`../views/${PATH_BASE}/warehouse/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-create' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId/edit`,
		name: `${BASE}.warehouse.edit`,
		component: () => import(`../views/${PATH_BASE}/warehouse/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-edit' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId`,
		name: `${BASE}.warehouse.show`,
		component: () => import(`../views/${PATH_BASE}/warehouse/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-show'  },
	},
	/* Warehouse Route end */
    /* Material Route start */

    {
        path: `/${BASE}/materials`,
        name: `${BASE}.material.index`,
        component: () => import(`../views/${PATH_BASE}/material/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material/create`,
        name: `${BASE}.material.create`,
        component: () => import(`../views/${PATH_BASE}/material/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-create' },
    },
    {
        path: `/${BASE}/material/:materialId/edit`,
        name: `${BASE}.material.edit`,
        component: () => import(`../views/${PATH_BASE}/material/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-edit' },
    },
    {
        path: `/${BASE}/material/:materialId`,
        name: `${BASE}.material.show`,
        component: () => import(`../views/${PATH_BASE}/material/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-show'  },
    },

    /* Material Route end */

    /* Service Route start */

    {
        path: `/${BASE}/services`,
        name: `${BASE}.service.index`,
        component: () => import(`../views/${PATH_BASE}/service/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'service-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/service/create`,
        name: `${BASE}.service.create`,
        component: () => import(`../views/${PATH_BASE}/service/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'service-create' },
    },
    {
        path: `/${BASE}/service/:serviceId/edit`,
        name: `${BASE}.service.edit`,
        component: () => import(`../views/${PATH_BASE}/service/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'service-edit' },
    },
    {
        path: `/${BASE}/service/:serviceId`,
        name: `${BASE}.service.show`,
        component: () => import(`../views/${PATH_BASE}/service/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'service-show'  },
    },

    /* Service Route end */

    
    /* Vendor Route start */

    {
        path: `/${BASE}/vendors`,
        name: `${BASE}.vendor.index`,
        component: () => import(`../views/${PATH_BASE}/vendor/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/vendor/create`,
        name: `${BASE}.vendor.create`,
        component: () => import(`../views/${PATH_BASE}/vendor/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-create' },
    },
    {
        path: `/${BASE}/vendor/:vendorId/edit`,
        name: `${BASE}.vendor.edit`,
        component: () => import(`../views/${PATH_BASE}/vendor/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-edit' },
    },
    {
        path: `/${BASE}/vendor/:vendorId`,
        name: `${BASE}.vendor.show`,
        component: () => import(`../views/${PATH_BASE}/vendor/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-show'  },
    },

    /* Vendor Route end */

    /* Opening Stock Route start */

    {
        path: `/${BASE}/opening-stocks`,
        name: `${BASE}.opening-stock.index`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'opening-stock-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/opening-stock/create`,
        name: `${BASE}.opening-stock.create`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'opening-stock-create' },
    },
    {
        path: `/${BASE}/opening-stock/:openingStockId/edit`,
        name: `${BASE}.opening-stock.edit`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'opening-stock-edit' },
    },
    {
        path: `/${BASE}/opening-stock/:openingStockId`,
        name: `${BASE}.opening-stock.show`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'opening-stock-show'  },
    },

    /* Opening Stock Route end */


    /* Purchase Requisition start */

    {
        path: `/${BASE}/purchase-requisitions`,
        name: `${BASE}.purchase-requisitions.index`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-requisitions-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/purchase-requisitions/create`,
        name: `${BASE}.purchase-requisitions.create`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-requisitions-create' },
    },
    {
        path: `/${BASE}/purchase-requisitions/:purchaseRequisitionId/edit`,
        name: `${BASE}.purchase-requisitions.edit`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-requisitions-edit' },
    },
    {
        path: `/${BASE}/purchase-requisitions/:purchaseRequisitionId`,
        name: `${BASE}.purchase-requisitions.show`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-requisitions-show'  },
    },

    /* Purchase Requisition end */

    /* Purchase Order start */

    {
        path: `/${BASE}/purchase-orders`,
        name: `${BASE}.purchase-orders.index`,
        component: () => import(`../views/${PATH_BASE}/purchase-orders/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-orders-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    // {
    //     path: `/${BASE}/purchase-orders/create`,
    //     name: `${BASE}.purchase-orders.create`,
    //     component: () => import(`../views/${PATH_BASE}/purchase-orders/create.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'purchase-orders-create' },
    //     props: route => ({
    //         pr_id: route.query.pr_id,
    //         cs_id: route.query.cs_id || null // Set to null if cs_id is not provided
    //     })
    // },
    {
        path: `/${BASE}/purchase-orders/create`,
        name: `${BASE}.purchase-orders.create`,
        component: () => import(`../views/${PATH_BASE}/purchase-orders/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-orders-create' },
        props: (route) => ({
            pr_id: route.query.pr_id,
            cs_id: route.query.cs_id || null // Set to null if cs_id is not provided
        })
    },
    {
        path: `/${BASE}/purchase-orders/:purchaseOrderId/edit`,
        name: `${BASE}.purchase-orders.edit`,
        component: () => import(`../views/${PATH_BASE}/purchase-orders/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-orders-edit' },
    },
    {
        path: `/${BASE}/purchase-orders/:purchaseRequisitionId`,
        name: `${BASE}.purchase-orders.show`,
        component: () => import(`../views/${PATH_BASE}/purchase-orders/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'purchase-orders-show'  },
    },

    /* Purchase Requisition end */

    /* LC Record start */

    {
        path: `/${BASE}/lc-records`,
        name: `${BASE}.lc-records.index`,
        component: () => import(`../views/${PATH_BASE}/lc-records/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'lc-records-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/lc-records/create`,
        name: `${BASE}.lc-records.create`,
        component: () => import(`../views/${PATH_BASE}/lc-records/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'lc-records-create' },
    },
    {
        path: `/${BASE}/lc-records/:lcRecordId/edit`,
        name: `${BASE}.lc-records.edit`,
        component: () => import(`../views/${PATH_BASE}/lc-records/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'lc-records-edit' },
    },
    {
        path: `/${BASE}/lc-records/:lcRecordId`,
        name: `${BASE}.lc-records.show`,
        component: () => import(`../views/${PATH_BASE}/lc-records/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'lc-records-show'  },
    },

    /* LC Record end */

    
    /* LC Record end */
    /* Material Receipt Report start */

    {
        path: `/${BASE}/material-receipt-reports`,
        name: `${BASE}.material-receipt-reports.index`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-receipt-reports-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material-receipt-reports/create`,
        name: `${BASE}.material-receipt-reports.create`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-receipt-reports-create' },
        props: (route) => ({
            pr_id: route.query.pr_id,
            po_id: route.query.po_id || null // Set to null if po_id is not provided
        })
    },
    // {
    //     path: `/${BASE}/material-receipt-reports/create`,
    //     name: `${BASE}.material-receipt-reports.create`,
    //     component: () => import(`../views/${PATH_BASE}/material-receipt-reports/create.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'material-receipt-reports-create' },
    // },
    {
        path: `/${BASE}/material-receipt-reports/:materialReceiptReportId/edit`,
        name: `${BASE}.material-receipt-reports.edit`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-receipt-reports-edit' },
    },
    {
        path: `/${BASE}/material-receipt-reports/:materialReceiptReportId`,
        name: `${BASE}.material-receipt-reports.show`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-receipt-reports-show'  },
    },

    /* Material Receipt Report end */

    /* Store Requisition start */

    {
        path: `/${BASE}/store-requisitions`,
        name: `${BASE}.store-requisitions.index`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-requisitions-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/store-requisitions/create`,
        name: `${BASE}.store-requisitions.create`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-requisitions-create' },
    },
    {
        path: `/${BASE}/store-requisitions/:storeRequisitionId/edit`,
        name: `${BASE}.store-requisitions.edit`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-requisitions-edit' },
    },
    {
        path: `/${BASE}/store-requisitions/:storeRequisitionId`,
        name: `${BASE}.store-requisitions.show`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-requisitions-show'  },
    },
    /* Store Requisition end */

    /* Store Issue start */

    {
        path: `/${BASE}/store-issues`,
        name: `${BASE}.store-issues.index`,
        component: () => import(`../views/${PATH_BASE}/store-issues/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-issues-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/store-issues/create`,
        name: `${BASE}.store-issues.create`,
        component: () => import(`../views/${PATH_BASE}/store-issues/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-issues-create' },
        props: (route) => ({
            sr_id: route.query.sr_id,
        })

    },
    {
        path: `/${BASE}/store-issues/:storeIssueId/edit`,
        name: `${BASE}.store-issues.edit`,
        component: () => import(`../views/${PATH_BASE}/store-issues/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-issues-edit' },
    },
    {
        path: `/${BASE}/store-issues/:storeIssueId`,
        name: `${BASE}.store-issues.show`,
        component: () => import(`../views/${PATH_BASE}/store-issues/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'store-issues-show'  },
    },
    /* Store Issue end */
    
];
