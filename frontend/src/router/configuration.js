import Store from './../store/index.js';
const USER = Store.getters.getCurrentUser;

const BASE = "configuration";
const ROLE = USER?.role ?? null;
export default [

	/* Branch Routes Start */

	{
		path: `/${BASE}/branch`,
		name: `${BASE}.branch.index`,
		component: () => import(`../views/${BASE}/branch/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'branch-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/branch/create`,
		name: `${BASE}.branch.create`,
		component: () => import(`../views/${BASE}/branch/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'branch-create' },
	},
	{
		path: `/${BASE}/branch/:branchId/edit`,
		name: `${BASE}.branch.edit`,
		component: () => import(`../views/${BASE}/branch/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'branch-edit' },
	},
	{
		path: `/${BASE}/branch/:branchId`,
		name: `${BASE}.branch.show`,
		component: () => import(`../views/${BASE}/branch/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'branch-show' },
	},

	/* Branch Routes End */

	/* Warehouse Routes Start */

	{
		path: `/${BASE}/warehouses`,
		name: `${BASE}.warehouse.index`,
		component: () => import(`../views/${BASE}/warehouse/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/warehouse/create`,
		name: `${BASE}.warehouse.create`,
		component: () => import(`../views/${BASE}/warehouse/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-create' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId/edit`,
		name: `${BASE}.warehouse.edit`,
		component: () => import(`../views/${BASE}/warehouse/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-edit' },
	},
	{
		path: `/${BASE}/warehouse/:warehouseId`,
		name: `${BASE}.warehouse.show`,
		component: () => import(`../views/${BASE}/warehouse/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'warehouse-show' },
	},

	/* Warehouse Routes End */

	/* Fuels route start */

	{
		path: `/${BASE}/fuel`,
		name: `${BASE}.fuel.index`,
		component: () => import(`../views/${BASE}/fuel/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/fuel/create`,
		name: `${BASE}.fuel.create`,
		component: () => import(`../views/${BASE}/fuel/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/fuel/:fuelId/edit`,
		name: `${BASE}.fuel.edit`,
		component: () => import(`../views/${BASE}/fuel/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: '' },
	},
	{
		path: `/${BASE}/fuel/:fuelId`,
		name: `${BASE}.fuel.show`,
		component: () => import(`../views/${BASE}/fuel/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: ''  },
	},

	/* Fuels route end */


	/* Tank Routes Start */

	{
		path: `/${BASE}/tanks`,
		name: `${BASE}.tank.index`,
		component: () => import(`../views/${BASE}/tank/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'tank-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/tank/create`,
		name: `${BASE}.tank.create`,
		component: () => import(`../views/${BASE}/tank/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'tank-create' },
	},
	{
		path: `/${BASE}/tank/:tankId/edit`,
		name: `${BASE}.tank.edit`,
		component: () => import(`../views/${BASE}/tank/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'tank-edit' },
	},
	{
		path: `/${BASE}/tank/:tankId`,
		name: `${BASE}.tank.show`,
		component: () => import(`../views/${BASE}/tank/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'tank-show' },
	},

	/* Tank Routes End */

	/* Dispenser Routes Start */

	{
		path: `/${BASE}/dispensers`,
		name: `${BASE}.dispenser.index`,
		component: () => import(`../views/${BASE}/dispenser/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dispenser-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/dispenser/create`,
		name: `${BASE}.dispenser.create`,
		component: () => import(`../views/${BASE}/dispenser/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dispenser-create' },
	},
	{
		path: `/${BASE}/dispenser/:dispenserId/edit`,
		name: `${BASE}.dispenser.edit`,
		component: () => import(`../views/${BASE}/dispenser/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'dispenser-edit' },
	},
	
	/* Dispenser Routes End */

	/* Vendor Routes Start */

	{
		path: `/${BASE}/vendors`,
		name: `${BASE}.vendor.index`,
		component: () => import(`../views/${BASE}/vendor/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'vendor-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/vendor/create`,
		name: `${BASE}.vendor.create`,
		component: () => import(`../views/${BASE}/vendor/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'vendor-create' },
	},
	{
		path: `/${BASE}/vendor/:vendorId/edit`,
		name: `${BASE}.vendor.edit`,
		component: () => import(`../views/${BASE}/vendor/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'vendor-edit' },
	},
	{
		path: `/${BASE}/vendor/:vendorId`,
		name: `${BASE}.vendor.show`,
		component: () => import(`../views/${BASE}/vendor/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'vendor-show' },
	},
	
	/* Vendor Routes End */

	/* Feeding Unit Routes Start */

	{
		path: `/${BASE}/feeding-units`,
		name: `${BASE}.feeding-unit.index`,
		component: () => import(`../views/${BASE}/feeding-unit/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'feeding-unit-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/feeding-unit/create`,
		name: `${BASE}.feeding-unit.create`,
		component: () => import(`../views/${BASE}/feeding-unit/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'feeding-unit-create' },
	},
	{
		path: `/${BASE}/feeding-unit/:feedingUnitId/edit`,
		name: `${BASE}.feeding-unit.edit`,
		component: () => import(`../views/${BASE}/feeding-unit/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'feeding-unit-edit' },
	},
	{
		path: `/${BASE}/feeding-unit/:feedingUnitId`,
		name: `${BASE}.feeding-unit.show`,
		component: () => import(`../views/${BASE}/feeding-unit/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'feeding-unit-show' },
	},
	
	/* Feeding Unit Routes End */

	/* Bank Routes Start */

	{
		path: `/${BASE}/banks`,
		name: `${BASE}.bank.index`,
		component: () => import(`../views/${BASE}/bank/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},
	{
		path: `/${BASE}/bank/create`,
		name: `${BASE}.bank.create`,
		component: () => import(`../views/${BASE}/bank/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-create' },
	},
	{
		path: `/${BASE}/bank/:bankId/edit`,
		name: `${BASE}.bank.edit`,
		component: () => import(`../views/${BASE}/bank/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-edit' },
	},
	{
		path: `/${BASE}/bank/:bankId`,
		name: `${BASE}.bank.show`,
		component: () => import(`../views/${BASE}/bank/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-show' },
	},

	/* Bank Routes End */

	/* Bank Account Routes Start */

	{
		path: `/${BASE}/bank-accounts`,
		name: `${BASE}.bank-account.index`,
		component: () => import(`../views/${BASE}/bank-account/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-account-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/bank-account/create`,
		name: `${BASE}.bank-account.create`,
		component: () => import(`../views/${BASE}/bank-account/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-account-create' },
	},
	{
		path: `/${BASE}/bank-account/:bankAccountId/edit`,
		name: `${BASE}.bank-account.edit`,
		component: () => import(`../views/${BASE}/bank-account/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-account-edit' },
	},
	{
		path: `/${BASE}/bank-account/:bankAccountId`,
		name: `${BASE}.bank-account.show`,
		component: () => import(`../views/${BASE}/bank-account/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'bank-account-show' },
	},
	
	/* Bank Account Routes End */

	/* Customer Routes Start */

	{
		path: `/${BASE}/customer`,
		name: `${BASE}.customer.index`,
		component: () => import(`../views/${BASE}/customer/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/customer/create`,
		name: `${BASE}.customer.create`,
		component: () => import(`../views/${BASE}/customer/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-create' },
	},
	{
		path: `/${BASE}/customer/:customerId/edit`,
		name: `${BASE}.customer.edit`,
		component: () => import(`../views/${BASE}/customer/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-edit' },
	},
	{
		path: `/${BASE}/customer/:customerId`,
		name: `${BASE}.customer.show`,
		component: () => import(`../views/${BASE}/customer/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-show' },
	},
	
	/* Customer Routes End */

	/* Customer Vehicle Routes Start */

	{
		path: `/${BASE}/customer-vehicles`,
		name: `${BASE}.customer-vehicle.index`,
		component: () => import(`../views/${BASE}/customer-vehicle/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-vehicle-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/customer-vehicle/create`,
		name: `${BASE}.customer-vehicle.create`,
		component: () => import(`../views/${BASE}/customer-vehicle/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-vehicle-create' },
	},
	{
		path: `/${BASE}/customer-vehicle/:customerVehicleId/edit`,
		name: `${BASE}.customer-vehicle.edit`,
		component: () => import(`../views/${BASE}/customer-vehicle/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-vehicle-edit' },
	},
	{
		path: `/${BASE}/customer-vehicle/:customerVehicleId`,
		name: `${BASE}.customer-vehicle.show`,
		component: () => import(`../views/${BASE}/customer-vehicle/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'customer-vehicle-show' },
	},
	
	/* Customer Vehicle Routes End */

	/* Customer Vehicle Routes Start */

	{
		path: `/${BASE}/material-opening-stocks`,
		name: `${BASE}.material-opening-stocks.index`,
		component: () => import(`../views/${BASE}/material-opening-stocks/index.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-opening-stock-index' },
		props: (route) => ({ page: parseInt(route.query.page) || 1 }),
	},

   {
		path: `/${BASE}/material-opening-stocks/create`,
		name: `${BASE}.material-opening-stocks.create`,
		component: () => import(`../views/${BASE}/material-opening-stocks/create.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-opening-stock-create' },
	},
	{
		path: `/${BASE}/material-opening-stocks/:materialOpeningStockId/edit`,
		name: `${BASE}.material-opening-stocks.edit`,
		component: () => import(`../views/${BASE}/material-opening-stocks/edit.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-opening-stock-edit' },
	},
	{
		path: `/${BASE}/material-opening-stocks/:materialOpeningStockId`,
		name: `${BASE}.material-opening-stocks.show`,
		component: () => import(`../views/${BASE}/material-opening-stocks/show.vue`),
		meta: { requiresAuth: true, role: ROLE, permission: 'material-opening-stock-show' },
	},
	
	/* Customer Vehicle Routes End */

	
];
