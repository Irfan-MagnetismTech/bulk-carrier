import Store from './../store/index.js';
import supplyChain2 from './supply-chain2.js';
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
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-unit-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/unit/create`,
        name: `${BASE}.unit.create`,
        component: () => import(`../views/${PATH_BASE}/unit/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-unit-create' },
    },
    {
        path: `/${BASE}/unit/:unitId/edit`,
        name: `${BASE}.unit.edit`,
        component: () => import(`../views/${PATH_BASE}/unit/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-unit-edit' },
    },
    {
        path: `/${BASE}/unit/:unitId`,
        name: `${BASE}.unit.show`,
        component: () => import(`../views/${PATH_BASE}/unit/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-unit-view'  },
    },

    /* Unit Route end */

    /* Material Category Route start */
    
    {
        path: `/${BASE}/material-categories`,
        name: `${BASE}.material-category.index`,
        component: () => import(`../views/${PATH_BASE}/material-category/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-category-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material-category/create`,
        name: `${BASE}.material-category.create`,
        component: () => import(`../views/${PATH_BASE}/material-category/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-category-create' },
    },
    {
        path: `/${BASE}/material-category/:materialCategoryId/edit`,
        name: `${BASE}.material-category.edit`,
        component: () => import(`../views/${PATH_BASE}/material-category/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-category-edit' },
    },
    {
        path: `/${BASE}/material-category/:materialCategoryId`,
        name: `${BASE}.material-category.show`,
        component: () => import(`../views/${PATH_BASE}/material-category/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-category-view'  },
    },

    /* Material Category Route end */
    /* Warehouse Route start */

	{
		path: `/${BASE}/warehouses`,
		name: `${BASE}.warehouse.index`,
		component: () => import(`../views/${PATH_BASE}/warehouse/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'scm-warehouse-view' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/warehouse/create`,
		name: `${BASE}.warehouse.create`,
		component: () => import(`../views/${PATH_BASE}/warehouse/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'scm-warehouse-create' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId/edit`,
		name: `${BASE}.warehouse.edit`,
		component: () => import(`../views/${PATH_BASE}/warehouse/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'scm-warehouse-edit' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId`,
		name: `${BASE}.warehouse.show`,
		component: () => import(`../views/${PATH_BASE}/warehouse/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'scm-warehouse-view'  },
	},
	/* Warehouse Route end */
    /* Material Route start */

    {
        path: `/${BASE}/materials`,
        name: `${BASE}.material.index`,
        component: () => import(`../views/${PATH_BASE}/material/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material/create`,
        name: `${BASE}.material.create`,
        component: () => import(`../views/${PATH_BASE}/material/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-create' },
    },
    {
        path: `/${BASE}/material/:materialId/edit`,
        name: `${BASE}.material.edit`,
        component: () => import(`../views/${PATH_BASE}/material/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-edit' },
    },
    {
        path: `/${BASE}/material/:materialId`,
        name: `${BASE}.material.show`,
        component: () => import(`../views/${PATH_BASE}/material/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-view'  },
    },

    /* Material Route end */

    // /* Service Route start */

    // {
    //     path: `/${BASE}/services`,
    //     name: `${BASE}.service.index`,
    //     component: () => import(`../views/${PATH_BASE}/service/index.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'service-index' },
    //     props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    // },
    // {
    //     path: `/${BASE}/service/create`,
    //     name: `${BASE}.service.create`,
    //     component: () => import(`../views/${PATH_BASE}/service/create.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'service-create' },
    // },
    // {
    //     path: `/${BASE}/service/:serviceId/edit`,
    //     name: `${BASE}.service.edit`,
    //     component: () => import(`../views/${PATH_BASE}/service/edit.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'service-edit' },
    // },
    // {
    //     path: `/${BASE}/service/:serviceId`,
    //     name: `${BASE}.service.show`,
    //     component: () => import(`../views/${PATH_BASE}/service/show.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'service-show'  },
    // },

    // /* Service Route end */

    
    /* Vendor Route start */

    {
        path: `/${BASE}/vendors`,
        name: `${BASE}.vendor.index`,
        component: () => import(`../views/${PATH_BASE}/vendor/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-vendor-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/vendor/create`,
        name: `${BASE}.vendor.create`,
        component: () => import(`../views/${PATH_BASE}/vendor/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-vendor-create' },
    },
    {
        path: `/${BASE}/vendor/:vendorId/edit`,
        name: `${BASE}.vendor.edit`,
        component: () => import(`../views/${PATH_BASE}/vendor/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-vendor-edit' },
    },
    {
        path: `/${BASE}/vendor/:vendorId`,
        name: `${BASE}.vendor.show`,
        component: () => import(`../views/${PATH_BASE}/vendor/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-vendor-view'  },
    },

    /* Vendor Route end */

    /* Opening Stock Route start */

    {
        path: `/${BASE}/opening-stocks`,
        name: `${BASE}.opening-stock.index`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-opening-stock-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/opening-stock/create`,
        name: `${BASE}.opening-stock.create`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-opening-stock-create' },
    },
    {
        path: `/${BASE}/opening-stock/:openingStockId/edit`,
        name: `${BASE}.opening-stock.edit`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-opening-stock-edit' },
    },
    {
        path: `/${BASE}/opening-stock/:openingStockId`,
        name: `${BASE}.opening-stock.show`,
        component: () => import(`../views/${PATH_BASE}/opening-stock/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-opening-stock-view'  },
    },

    /* Opening Stock Route end */


    /* Purchase Requisition start */

    {
        path: `/${BASE}/purchase-requisitions`,
        name: `${BASE}.purchase-requisitions.index`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-requisition-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/purchase-requisitions/create`,
        name: `${BASE}.purchase-requisitions.create`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-requisition-create' },
    },
    {
        path: `/${BASE}/purchase-requisitions/:purchaseRequisitionId/edit`,
        name: `${BASE}.purchase-requisitions.edit`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-requisition-edit' },
    },
    {
        path: `/${BASE}/purchase-requisitions/:purchaseRequisitionId`,
        name: `${BASE}.purchase-requisitions.show`,
        component: () => import(`../views/${PATH_BASE}/purchase-requisitions/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-requisition-view'  },
    },

    /* Purchase Requisition end */

    /* Purchase Order start */

    {
        path: `/${BASE}/purchase-orders`,
        name: `${BASE}.purchase-orders.index`,
        component: () => import(`../views/${PATH_BASE}/purchase-orders/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-order-view' },
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
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-order-create' },
        props: (route) => ({
            pr_id: route.query.pr_id,
            cs_id: route.query.cs_id || null // Set to null if cs_id is not provided
        })
    },
    {
        path: `/${BASE}/purchase-orders/:purchaseOrderId/edit`,
        name: `${BASE}.purchase-orders.edit`,
        component: () => import(`../views/${PATH_BASE}/purchase-orders/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-order-edit' },
    },
    {
        path: `/${BASE}/purchase-orders/:purchaseOrderId`,
        name: `${BASE}.purchase-orders.show`,
        component: () => import(`../views/${PATH_BASE}/purchase-orders/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-purchase-order-view'  },
    },

    /* Purchase Requisition end */

    /* LC Record start */

    {
        path: `/${BASE}/lc-records`,
        name: `${BASE}.lc-records.index`,
        component: () => import(`../views/${PATH_BASE}/lc-records/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-lc-records-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/lc-records/create`,
        name: `${BASE}.lc-records.create`,
        component: () => import(`../views/${PATH_BASE}/lc-records/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-lc-records-create' },
    },
    {
        path: `/${BASE}/lc-records/:lcRecordId/edit`,
        name: `${BASE}.lc-records.edit`,
        component: () => import(`../views/${PATH_BASE}/lc-records/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-lc-records-edit' },
    },
    {
        path: `/${BASE}/lc-records/:lcRecordId`,
        name: `${BASE}.lc-records.show`,
        component: () => import(`../views/${PATH_BASE}/lc-records/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-lc-records-view'  },
    },

    /* LC Record end */

    
    /* LC Record end */
    /* Material Receipt Report start */

    {
        path: `/${BASE}/material-receipt-reports`,
        name: `${BASE}.material-receipt-reports.index`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-receipt-report-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    // {
    //     path: `/${BASE}/material-receipt-reports/create`,
    //     name: `${BASE}.material-receipt-reports.create`,
    //     component: () => import(`../views/${PATH_BASE}/material-receipt-reports/create.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'material-receipt-reports-create' },
    //     props: (route) => ({
    //         pr_id: route.query.pr_id,
    //         po_id: route.query.po_id || null // Set to null if po_id is not provided
    //     })
    // },
    {
        path: `/${BASE}/material-receipt-reports/create`,
        name: `${BASE}.material-receipt-reports.create`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-receipt-report-create' },
    },
    {
        path: `/${BASE}/material-receipt-reports/:materialReceiptReportId/edit`,
        name: `${BASE}.material-receipt-reports.edit`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-receipt-report-edit' },
    },
    {
        path: `/${BASE}/material-receipt-reports/:materialReceiptReportId`,
        name: `${BASE}.material-receipt-reports.show`,
        component: () => import(`../views/${PATH_BASE}/material-receipt-reports/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-receipt-report-view'  },
    },

    /* Material Receipt Report end */

    /* Store Requisition start */

    {
        path: `/${BASE}/store-requisitions`,
        name: `${BASE}.store-requisitions.index`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-requisition-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/store-requisitions/create`,
        name: `${BASE}.store-requisitions.create`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-requisition-create' },
    },
    {
        path: `/${BASE}/store-requisitions/:storeRequisitionId/edit`,
        name: `${BASE}.store-requisitions.edit`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-requisition-edit' },
    },
    {
        path: `/${BASE}/store-requisitions/:storeRequisitionId`,
        name: `${BASE}.store-requisitions.show`,
        component: () => import(`../views/${PATH_BASE}/store-requisitions/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-requisition-view'  },
    },
    /* Store Requisition end */

    /* Store Issue start */

    {
        path: `/${BASE}/store-issues`,
        name: `${BASE}.store-issues.index`,
        component: () => import(`../views/${PATH_BASE}/store-issues/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/store-issues/create`,
        name: `${BASE}.store-issues.create`,
        component: () => import(`../views/${PATH_BASE}/store-issues/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-create' },
        props: (route) => ({
            sr_id: route.query.sr_id,
        })

    },
    {
        path: `/${BASE}/store-issues/:storeIssueId/edit`,
        name: `${BASE}.store-issues.edit`,
        component: () => import(`../views/${PATH_BASE}/store-issues/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-edit' },
    },
    {
        path: `/${BASE}/store-issues/:storeIssueId`,
        name: `${BASE}.store-issues.show`,
        component: () => import(`../views/${PATH_BASE}/store-issues/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-view'  },
    },
    /* Store Issue end */
    
     /* Store Issue Return start */

     {
        path: `/${BASE}/store-issue-returns`,
        name: `${BASE}.store-issue-returns.index`,
        component: () => import(`../views/${PATH_BASE}/store-issue-returns/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-return-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/store-issue-returns/create`,
        name: `${BASE}.store-issue-returns.create`,
        component: () => import(`../views/${PATH_BASE}/store-issue-returns/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-return-create' },
    },
    {
        path: `/${BASE}/store-issue-returns/:storeIssueReturnId/edit`,
        name: `${BASE}.store-issue-returns.edit`,
        component: () => import(`../views/${PATH_BASE}/store-issue-returns/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-return-edit' },
    },
    {
        path: `/${BASE}/store-issue-returns/:storeIssueReturnId`,
        name: `${BASE}.store-issue-returns.show`,
        component: () => import(`../views/${PATH_BASE}/store-issue-returns/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-store-issue-return-view'  },
    },
    /* Store Issue Return end */


     /* Movement Requisition start */

     {
        path: `/${BASE}/movement-requisitions`,
        name: `${BASE}.movement-requisitions.index`,
        component: () => import(`../views/${PATH_BASE}/movement-requisitions/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-requisition-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/movement-requisitions/create`,
        name: `${BASE}.movement-requisitions.create`,
        component: () => import(`../views/${PATH_BASE}/movement-requisitions/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-requisition-create' },
    },
    {
        path: `/${BASE}/movement-requisitions/:movementRequisitionId/edit`,
        name: `${BASE}.movement-requisitions.edit`,
        component: () => import(`../views/${PATH_BASE}/movement-requisitions/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-requisition-edit' },
    },
    {
        path: `/${BASE}/movement-requisitions/:movementRequisitionId`,
        name: `${BASE}.movement-requisitions.show`,
        component: () => import(`../views/${PATH_BASE}/movement-requisitions/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-requisition-view'  },
    },
    /* Movement Requisition end */

    /* Movement Out start */

    {
        path: `/${BASE}/movement-outs`,
        name: `${BASE}.movement-outs.index`,
        component: () => import(`../views/${PATH_BASE}/movement-outs/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-out-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/movement-outs/create`,
        name: `${BASE}.movement-outs.create`,
        component: () => import(`../views/${PATH_BASE}/movement-outs/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-out-create' },
    },
    {
        path: `/${BASE}/movement-outs/:movementOutId/edit`,
        name: `${BASE}.movement-outs.edit`,
        component: () => import(`../views/${PATH_BASE}/movement-outs/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-out-edit' },
    },
    {
        path: `/${BASE}/movement-outs/:movementOutId`,
        name: `${BASE}.movement-outs.show`,
        component: () => import(`../views/${PATH_BASE}/movement-outs/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-out-view'  },
    },
    
    /* Movement Out end */
    
    /* Movement In start */

    {
        path: `/${BASE}/movement-ins`,
        name: `${BASE}.movement-ins.index`,
        component: () => import(`../views/${PATH_BASE}/movement-ins/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-in-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/movement-ins/create`,
        name: `${BASE}.movement-ins.create`,
        component: () => import(`../views/${PATH_BASE}/movement-ins/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-in-create' },
    },
    {
        path: `/${BASE}/movement-ins/:movementInId/edit`,
        name: `${BASE}.movement-ins.edit`,
        component: () => import(`../views/${PATH_BASE}/movement-ins/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-in-edit' },
    },
    {
        path: `/${BASE}/movement-ins/:movementInId`,
        name: `${BASE}.movement-ins.show`,
        component: () => import(`../views/${PATH_BASE}/movement-ins/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-movement-in-view'  },
    },
    
    /* Movement In end */

    /* Movement Requisition start */

    {
        path: `/${BASE}/material-adjustments`,
        name: `${BASE}.material-adjustments.index`,
        component: () => import(`../views/${PATH_BASE}/material-adjustments/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-adjustment-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material-adjustments/create`,
        name: `${BASE}.material-adjustments.create`,
        component: () => import(`../views/${PATH_BASE}/material-adjustments/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-adjustment-create' },
    },
    {
        path: `/${BASE}/material-adjustments/:materialAdjustmentId/edit`,
        name: `${BASE}.material-adjustments.edit`,
        component: () => import(`../views/${PATH_BASE}/material-adjustments/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-adjustment-edit' },
    },
    {
        path: `/${BASE}/material-adjustments/:materialAdjustmentId`,
        name: `${BASE}.material-adjustments.show`,
        component: () => import(`../views/${PATH_BASE}/material-adjustments/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-adjustment-view'  },
    },
    /* Movement Requisition end */

    /* Material Cs start */

    // {
    //     path: `/${BASE}/material-cs`,
    //     name: `${BASE}.material-cs.index`,
    //     component: () => import(`../views/${PATH_BASE}/material-cs/index.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'material-cs-index' },
    //     props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    // },
    {
        path: `/${BASE}/material-cs`,
        name: `${BASE}.material-cs.index`,
        component: () => import(`../views/${PATH_BASE}/material-cs/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-view' },
    },
    {
        path: `/${BASE}/material-cs/create`,
        name: `${BASE}.material-cs.create`,
        component: () => import(`../views/${PATH_BASE}/material-cs/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-create' },
        props: (route) => ({
            pr_id: route.query.pr_id
        })
    },
    {
        path: `/${BASE}/material-cs/:materialCsId/edit`,
        name: `${BASE}.material-cs.edit`,
        component: () => import(`../views/${PATH_BASE}/material-cs/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-edit' },
    },
    {
        path: `/${BASE}/material-cs/:materialCsId`,
        name: `${BASE}.material-cs.show`,
        component: () => import(`../views/${PATH_BASE}/material-cs/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-view'  },
    },
    
    {
        path: `/${BASE}/quotations/:csId/create`,
        name: `${BASE}.quotations.create`,
        component: () => import(`../views/${PATH_BASE}/quotations/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-qoutation-create' },
        props: (route) => ({
            cs_id: route.query.cs_id ?? null
        })
    },
    {
        path: `/${BASE}/quotations/:csId/:quotationId/edit`,
        name: `${BASE}.quotations.edit`,
        component: () => import(`../views/${PATH_BASE}/quotations/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-qoutation-edit' },
    },
    {
        path: `/${BASE}/quotations/:csId/:quotationId/show`,
        name: `${BASE}.quotations.show`,
        component: () => import(`../views/${PATH_BASE}/quotations/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-qoutation-view'  },
    },
    {
        path: `/${BASE}/quotations/:csId/index`,
        name: `${BASE}.quotations.index`,
        component: () => import(`../views/${PATH_BASE}/quotations/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-qoutation-view' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material-cs/:csId/supplier-selection`,
        name: `${BASE}.supplier-selection`,
        component: () => import(`../views/${PATH_BASE}/supplier-selection/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-supplier-selection' },
    },
    {
        path: `/${BASE}/material-cs/:csId/supplier-selection/show`,
        name: `${BASE}.supplier-selection.show`,
        component: () => import(`../views/${PATH_BASE}/supplier-selection/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-supplier-selection' },
    },
    
    
     {
        path: `/${BASE}/cs-cost-projection/:csId/create`,
        name: `${BASE}.cs-cost-projection.create`,
        component: () => import(`../views/${PATH_BASE}/cs-cost-projection/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-cost-projection' },
        props: (route) => ({
            cs_id: route.query.cs_id ?? null
        })
    },
    {
        path: `/${BASE}/cs-cost-projection/:csId/edit`,
        name: `${BASE}.cs-cost-projection.edit`,
        component: () => import(`../views/${PATH_BASE}/cs-cost-projection/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-cost-projection' },
        props: (route) => ({
            cs_id: route.query.cs_id ?? null
        })
    },
    {
        path: `/${BASE}/cs-cost-projection/:csId/:quotationId/show`,
        name: `${BASE}.cs-cost-projection.show`,
        component: () => import(`../views/${PATH_BASE}/cs-cost-projection/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'scm-material-cs-cost-projection'  },
    },

     /* Material Cs end */
    // {
    //     path: `/${BASE}/quotations/:csId/index`,
    //     name: `${BASE}.quotations.index`,
    //     component: () => import(`../views/${PATH_BASE}/quotations/index.vue`),
    //     meta: { requiresAuth: true, role: ROLE, permission: 'quotations-index' },
    //     props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    // },
    /* Vendor Bill start */

    {
        path: `/${BASE}/vendor-bills`,
        name: `${BASE}.vendor-bills.index`,
        component: () => import(`../views/${PATH_BASE}/vendor-bills/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-bills-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/vendor-bills/create`,
        name: `${BASE}.vendor-bills.create`,
        component: () => import(`../views/${PATH_BASE}/vendor-bills/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-bills-create' },
    },
    {
        path: `/${BASE}/vendor-bills/:vendorBillId/edit`,
        name: `${BASE}.vendor-bills.edit`,
        component: () => import(`../views/${PATH_BASE}/vendor-bills/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-bills-edit' },
    },
    {
        path: `/${BASE}/vendor-bills/:vendorBillId`,
        name: `${BASE}.vendor-bills.show`,
        component: () => import(`../views/${PATH_BASE}/vendor-bills/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'vendor-bills-show'  },
    },
    /* Vendor Bill end */
    /* Material Costing Start */
    {
        path: `/${BASE}/material-costings`,
        name: `${BASE}.material-costings.index`,
        component: () => import(`../views/${PATH_BASE}/material-costings/index.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-costings-index' },
        props: (route) => ({ page: parseInt(route.query.page) || 1 }),
    },
    {
        path: `/${BASE}/material-costings/create`,
        name: `${BASE}.material-costings.create`,
        component: () => import(`../views/${PATH_BASE}/material-costings/create.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-costings-create' },
    },
    {
        path: `/${BASE}/material-costings/:materialCostingId/edit`,
        name: `${BASE}.material-costings.edit`,
        component: () => import(`../views/${PATH_BASE}/material-costings/edit.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-costings-edit' },
    },
    {
        path: `/${BASE}/material-costings/:materialCostingId`,
        name: `${BASE}.material-costings.show`,
        component: () => import(`../views/${PATH_BASE}/material-costings/show.vue`),
        meta: { requiresAuth: true, role: ROLE, permission: 'material-costings-show'  },
    },
     /* Material Costing End */
    ...supplyChain2
];
