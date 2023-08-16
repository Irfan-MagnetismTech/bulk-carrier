<template>
    <div class="py-2 text-gray-500 dark:text-gray-400">
<!--      <div class="HeaderLogoWrapper">-->
<!--        <div class="HeaderLogo">-->
<!--          <img aria-hidden="true" class="object-cover align-middle dark:block w-12" src="/hr-lines-shosti.png" alt="Office">-->
<!--        </div>-->
<!--      </div>-->
<!--        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">-->
<!--          <img aria-hidden="true" class="object-cover w-full align-middle dark:block w-32" src="/hr-line-test.png" alt="Office">-->
<!--        </a>-->
        <ul class="mt-6">
            <span>
                <li class="relative px-6 py-3" v-for="sidebarElement in sidebarElements.routes" @click="togglePagesMenu(0)" :class="{ 'active': isActive === sidebarElement.label }" :key="sidebarElement.route">
                    <span v-if="sidebarElement.route === currentRoute.name" class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: sidebarElement.route }" @click="addActiveClass(sidebarElement.label)" :class="{ ' dark:text-gray-100': sidebarElement.route === currentRoute.name }" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                        <div v-html="sidebarElement.icon"></div>
                        <span class="ml-4">{{ sidebarElement.label }}</span>
                    </router-link>
                </li>
            </span>
          <a @click="toggleModuleMenu(1)" v-if="checkSidebarParentMenuPermission(['user-show','role-show','permission-show','approval-management-show'])" class="flex p-2 mb-1 cursor-pointer justify-between text-sm items-center font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Control Users</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>
          <template v-if="isControlUserMenuOpen">
            <li v-if="checkSidebarMenuPermission('user-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'User' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'authorization.user.index' }" @click="addActiveClass('User')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="ml-2">User</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('role-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Role' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'authorization.user.role.index' }" @click="addActiveClass('Role')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="ml-2">Role</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('permission-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Permission' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'authorization.user.permission.index' }" @click="addActiveClass('Permission')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="ml-2">Permission</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('approval-management-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Approval Management' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'authorization.approval.management.index' }" @click="addActiveClass('Approval Management')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-6 h-6 ml-4" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <span class="ml-2">Approval Management</span>
              </router-link>
            </li>
          </template>
          <a @click="toggleModuleMenu(2)" v-if="checkSidebarParentMenuPermission(['dataencoding-vessel-show','commercial-service-show','dataencoding-port-show','dataencoding-container-type-show','dataencoding-chargetype-show','dataencoding-voyage-expenditure-head-show','dataencoding-port-expenditure-head-show','dataencoding-currency-show','dataencoding-bank-show','dataencoding-mail-template-show','dataencoding-tariff-show'])" class="flex p-2 cursor-pointer justify-between items-center mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Data Encoding</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>

          <template v-if="isDataEncodingMenuOpen">
            <li v-if="checkSidebarMenuPermission('dataencoding-vessel-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Vessel' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'dataencoding.vessels.index' }" @click="addActiveClass('Vessel')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-6 h-6 ml-4 iconify iconify--fluent" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16"><g fill="none"><path d="M6 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V4h2.5a.5.5 0 0 1 .5.5v3.522l1.199.52a.5.5 0 0 1 .238.7l-1.145 2.061c-.155-.283-.35-.577-.745-.718l.748-1.346l-4.699-2.036a1.5 1.5 0 0 0-1.192 0L2.705 9.24l.748 1.346c-.394.141-.59.435-.745.718l-1.145-2.06A.5.5 0 0 1 1.8 8.54L3 8.021V4.5a.5.5 0 0 1 .5-.5H6V2.5zM4 7.588l3.006-1.302a2.5 2.5 0 0 1 1.988 0L12 7.588V5H4v2.588zM9 3H7v1h2V3zm-5.398 8.698a.5.5 0 0 0-.034.05a1.1 1.1 0 0 0-.06.127a1.21 1.21 0 0 1-.054.117a1.85 1.85 0 0 1-.308.404C2.86 12.684 2.36 13 1.5 13a.5.5 0 0 0 0 1c1.141 0 1.891-.434 2.354-.896c.043-.044.084-.087.122-.13c.025.031.05.064.077.096C4.43 13.522 5.06 14 6 14c.94 0 1.57-.478 1.947-.93L8 13.004l.053.066C8.43 13.522 9.06 14 10 14c.94 0 1.57-.478 1.947-.93c.027-.032.052-.065.077-.097c.038.043.079.087.122.13c.463.463 1.213.897 2.354.897a.5.5 0 1 0 0-1c-.859 0-1.359-.316-1.646-.604a1.747 1.747 0 0 1-.38-.556a.501.501 0 0 0-.947 0a2.194 2.194 0 0 1-.348.59c-.249.298-.62.57-1.18.57c-.559 0-.93-.272-1.178-.57a2.197 2.197 0 0 1-.346-.585a.497.497 0 0 0-.658-.31a.497.497 0 0 0-.293.31a2.198 2.198 0 0 1-.346.585C6.93 12.728 6.56 13 6 13c-.56 0-.93-.272-1.178-.57a2.197 2.197 0 0 1-.273-.42a1.444 1.444 0 0 1-.05-.113c-.02-.05-.04-.102-.067-.148a.5.5 0 0 0-.274-.223l-.005-.002a.497.497 0 0 0-.551.174z" fill="currentColor"></path></g></svg>
                <span class="ml-2">Vessel</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('commercial-service-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Service' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.services.index' }" @click="addActiveClass('Service')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Service</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('dataencoding-port-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Ports' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'ports.index' }" @click="addActiveClass('Ports')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Ports</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('dataencoding-container-type-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Container Types' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'containertype.index' }" @click="addActiveClass('Container Types')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Container Types</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('dataencoding-chargetype-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Revenue Head' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'dataencoding.chargetypes.index' }" @click="addActiveClass('Revenue Head')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Revenue Head</span>
              </router-link>
            </li>

            <li v-if="checkSidebarParentMenuPermission(['dataencoding-voyage-expenditure-head-show','dataencoding-port-expenditure-head-show'])" class="relative px-6 py-1">
              <button @click="togglePagesMenu(1)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                        <span class="ml-2">Cost & Expenditure</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isCostExpenditureMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('dataencoding-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Cost Group Creation' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.voyageExpenditureHead.index' }" @click="addActiveClass('Cost Group Creation')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Cost Group Creation</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('dataencoding-port-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Cost Group Assign' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.port-wise-head-generation.index' }" @click="addActiveClass('Cost Group Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Cost Group Assign</span>
                    </router-link>
                  </li>

                </ul>
              </template>
            </li>
            <li v-if="checkSidebarMenuPermission('dataencoding-currency-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Currency' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'dataencoding.currency.index' }" @click="addActiveClass('Currency')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="ml-2">Currency</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('dataencoding-bank-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Bank' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'banks.index' }" @click="addActiveClass('Bank')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                <span class="ml-2">Bank</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('dataencoding-mail-template-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Mail Template' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'dataencoding.mail-templates.index' }" @click="addActiveClass('Mail Template')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                </svg>
                <span class="ml-2">Mail Template</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('dataencoding-tariff-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Tariff' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'dataencoding.tariffs.index' }" @click="addActiveClass('Tariff')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Tariff</span>
              </router-link>
            </li>
          </template>

          <a @click="toggleModuleMenu(3)" v-if="checkSidebarParentMenuPermission(['dataencoding-slot-partner-show','commercial-customer-show','operation-mlo-agents-show','operation-external-email-show'])" class="flex p-2 cursor-pointer justify-between items-center mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Contact</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>

          <template v-if="isContactMenuOpen">
            <li v-if="checkSidebarMenuPermission('dataencoding-slot-partner-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Slot Partner' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'dataencoding.slot-partner.index' }" @click="addActiveClass('Slot Partner')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg class="w-6 h-6 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-2">Slot Partner</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('commercial-customer-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Customers' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.customers.index' }" @click="addActiveClass('Customers')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg class="w-6 h-6 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-2">Customers</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('operation-mlo-agents-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'MLO Agents' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'operation.mlo.agents.index' }" @click="addActiveClass('MLO Agents')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg class="w-6 h-6 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-2">MLO Agents</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('operation-external-email-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'External Email & CC' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'operation.mlo.agents.external-email' }" @click="addActiveClass('External Email & CC')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg class="w-6 h-6 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-2">External Email & CC</span>
              </router-link>
            </li>
          </template>

          <a @click="toggleModuleMenu(4)" v-if="checkSidebarParentMenuPermission(['operation-voyages-show','operation-edi-converter','operation-advisory-agents','commercial-dg-container-show','commercial-fr-container-show','commercial-rf-container-show'])" class="flex cursor-pointer justify-between items-center p-2 mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Vessel & Cargo Operation</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>

          <template v-if="isVesselCargoMenuOpen">
            <li v-if="checkSidebarMenuPermission('operation-voyages-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Voyages' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'operation.voyages.index' }" @click="addActiveClass('Voyages')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="ml-4 iconify iconify--mdi" width="26" height="26" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                  <path fill="currentColor" d="m2 11l2.05.1a7.96 7.96 0 0 1 3.2-5.54L6.13 3.84c-.27-.48-.13-1.09.37-1.37c.5-.27 1.09-.11 1.37.37l.93 1.82a8.102 8.102 0 0 1 6.4 0l.93-1.82c.28-.48.87-.64 1.37-.37c.5.28.64.89.37 1.37l-1.12 1.72a7.96 7.96 0 0 1 3.2 5.54L22 11a1 1 0 0 1 1 1a1 1 0 0 1-1 1l-2.05-.1a7.96 7.96 0 0 1-3.2 5.54l1.12 1.72c.27.48.13 1.09-.37 1.37c-.5.27-1.09.11-1.37-.37l-.93-1.82a8.102 8.102 0 0 1-6.4 0l-.93 1.82c-.28.48-.87.64-1.37.37c-.5-.28-.64-.89-.37-1.37l1.12-1.72a7.96 7.96 0 0 1-3.2-5.54L2 13a1 1 0 0 1-1-1a1 1 0 0 1 1-1m7.07.35c.13-.61.46-1.15.93-1.56L8.34 7.25a5.993 5.993 0 0 0-2.29 3.95l3.02.15M12 9c.32 0 .62.05.9.14l1.38-2.69C13.58 6.16 12.81 6 12 6c-.81 0-1.58.16-2.28.45l1.38 2.69c.28-.09.58-.14.9-.14m2.93 2.35l3.02-.15a5.993 5.993 0 0 0-2.29-3.95L14 9.79c.47.41.8.95.93 1.56m0 1.3c-.13.61-.46 1.15-.93 1.56l1.66 2.54a5.993 5.993 0 0 0 2.29-3.95l-3.02-.15M12 15c-.32 0-.62-.05-.91-.14l-1.37 2.69c.7.29 1.47.45 2.28.45c.81 0 1.58-.16 2.28-.45l-1.37-2.69c-.29.09-.59.14-.91.14m-2.93-2.35l-3.02.15c.22 1.6 1.06 3.01 2.29 3.95L10 14.21c-.47-.41-.8-.95-.93-1.56Z"/>
                </svg>
                <span class="ml-2">Voyages</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('operation-edi-converter')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'EDI Converter' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'operation.edi-converter' }" @click="addActiveClass('EDI Converter')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                <span class="ml-2">EDI Converter</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('operation-advisory-agents')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Customer Advisory' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'operation.send-advisory.index' }" @click="addActiveClass('Customer Advisory')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="ml-2">Customer Advisory</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'TDR' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'finance.vessel-conditioning.index' }" @click="addActiveClass('TDR')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">TDR</span>
              </router-link>
            </li>

            <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Statement of Fact' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'operation.sof.index' }" @click="addActiveClass('Statement of Fact')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Statement of Fact</span>
              </router-link>
            </li>


            <li v-if="checkSidebarParentMenuPermission(['commercial-contract-create','commercial-open-slot-contract-show','commercial-force-load-contract-show','commercial-contract-show'])" class="relative px-6 py-1">
              <button @click="togglePagesMenu(2)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg class="w-6 h-6" viewBox="0 0 24 24"><path fill="currentColor" d="M1 6v11h1a2 2 0 1 0 4 0h12a2 2 0 1 0 4 0h1V6H1m20 9h-2V9h-2v6h-2V9h-2v6h-2V9H9v6H7V9H5v6H3V8h18v7Z"/></svg>
                        <span class="ml-2">Special Containers</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isSpecialContainerMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('commercial-dg-container-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'DG Assign' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.voyage-dg-containers.voyage' }" @click="addActiveClass('DG Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">DG Assign</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-fr-container-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Flat Rack Assign' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.voyage-fr-containers.voyage' }" @click="addActiveClass('Flat Rack Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">OG Assign</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-rf-container-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Reffer Assign' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.voyage-rf-containers.voyage' }" @click="addActiveClass('Reffer Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">RF Assign</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>
            <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Load Assign' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'operation.load-assign.index' }" @click="addActiveClass('Load Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Load Assign</span>
              </router-link>
            </li>
          </template>

          <a @click="toggleModuleMenu(5)" v-if="checkSidebarParentMenuPermission(['commercial-booking-show','commercial-voyage-slot-partner-list','commercial-contract-assign-voyages','commercial-voyage-exchange-rate','documentation-particular-customer-report','documentation-schedule-report','documentation-service-report','documentation-monthwise-service-report','documentation-customer-loading-performance-report'])" class="flex cursor-pointer justify-between items-center p-2 mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Commercial</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>

          <template v-if="isCommercialMenuOpen">
            <li v-if="checkSidebarMenuPermission('commercial-booking-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Booking Creation' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.bookings.index' }" @click="addActiveClass('Booking Creation')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="ml-2">Booking Creation</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('commercial-voyage-slot-partner-list')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Customer Assign' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.voyages.slot-partners' }" @click="addActiveClass('Customer Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="ml-2">Customer Assign</span>
              </router-link>
            </li>
            <li v-if="checkSidebarParentMenuPermission(['commercial-contract-create','commercial-open-slot-contract-show','commercial-force-load-contract-show','commercial-contract-show'])" class="relative px-6 py-1">
              <button @click="togglePagesMenu(3)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2">Schedule</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isCommercialScheduleMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('documentation-schedule-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Voyage Schedule Report(Actual)' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'documentation.voyage-schedule-report' }" @click="addActiveClass('Voyage Schedule Report(Actual)')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Voyage Schedule Report(Actual)</span>
                    </router-link>
                  </li>

                  <li v-if="checkSidebarMenuPermission('documentation-schedule-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Schedule' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.schedule.website.layout' }" @click="addActiveClass('Schedule')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Schedule</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>

            <li v-if="checkSidebarParentMenuPermission(['commercial-contract-create','commercial-open-slot-contract-show','commercial-force-load-contract-show','commercial-contract-show', 'commercial-customer-loading-information'])" class="relative px-6 py-3">
              <button @click="togglePagesMenu(4)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2">Reports</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isCommercialReportsMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('documentation-service-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Service Report' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'documentation.service-report' }" @click="addActiveClass('Service Report')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Service Report</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('documentation-monthwise-service-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Month Wise Service Report' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'documentation.monthwise-service-report' }" @click="addActiveClass('Month Wise Service Report')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Month Wise Service Report</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('documentation-customer-loading-performance-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Loading Performance Report' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'documentation.customer-loading-performance-report' }" @click="addActiveClass('Loading Performance Report')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Customer Loading Performance</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-customer-loading-information')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Loading Information By Customer' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.customer-loading-information' }" @click="addActiveClass('Loading Information By Customer')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Loading Information By Customer</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-voyage-customer-allocation')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Booking Vs Allocation' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.voyages.booking-allocation' }" @click="addActiveClass('Booking Vs Allocation')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Booking Vs Allocation</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>
          </template>

<!--          <li v-if="checkSidebarMenuPermission('documentation-particular-customer-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Particular Customer Report' }">-->
<!--            <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>-->
<!--            <router-link :to="{ name: 'documentation.particular-customer-report' }" @click="addActiveClass('Particular Customer Report')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">-->
<!--              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>-->
<!--              <span class="ml-2">Particular Customer Report</span>-->
<!--            </router-link>-->
<!--          </li>-->

          <a @click="toggleModuleMenu(6)" v-if="checkSidebarParentMenuPermission(['documentation-bl-generate','commercial-freight-manifest-report'])" class="flex cursor-pointer p-2 justify-between items-center mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Documentation</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>

          <template v-if="isDocumentationMenuOpen">
            <li v-if="checkSidebarMenuPermission('documentation-bl-generate')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'BL List' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'documentation.generate-bl.report.list' }" @click="addActiveClass('BL List')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">BL List</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('commercial-freight-manifest-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Freight Manifest' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.freight-manifest.index' }" @click="addActiveClass('Freight Manifest')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Freight Manifest</span>
              </router-link>
            </li>
          </template>

          <a @click="toggleModuleMenu(7)" v-if="checkSidebarParentMenuPermission(['documentation-bl-generate','commercial-freight-manifest-report'])" class="flex cursor-pointer p-2 justify-between items-center mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Pricing & Invoicing</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>

          <template v-if="isPricingInvoicingMenuOpen">
            <li v-if="checkSidebarParentMenuPermission(['commercial-contract-create','commercial-open-slot-contract-show','commercial-force-load-contract-show','commercial-contract-show'])" class="relative px-6 py-3">
              <button @click="togglePagesMenu(5)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2">Contract</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isPriceInvoiceContractMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('commercial-contract-create')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'New Fixed Contract' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.contracts.create' }" @click="addActiveClass('New Fixed Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">New Fixed Contract</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-open-slot-contract-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'New Open Contract' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.open-slot-contract.create' }" @click="addActiveClass('New Open Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">New Open Contract</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-force-load-contract-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Force Load Contract' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.force-load-contract.create' }" @click="addActiveClass('Force Load Contract')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Force Load Contract</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-contract-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Contract List' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.contracts.index' }" @click="addActiveClass('Contract List')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Contract List</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>

            <li v-if="checkSidebarMenuPermission('commercial-contract-assign-voyages')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Contract Assign' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.contract-assigns.voyages' }" @click="addActiveClass('Contract Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="ml-2">Contract Assign</span>
              </router-link>
            </li>

            <li v-if="checkSidebarMenuPermission('commercial-tariff-assign-voyages')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Tariff Assign' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.assign-tariff.voyages' }" @click="addActiveClass('Tariff Assign')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="ml-2">Tariff Assign</span>
              </router-link>
            </li>

            <li v-if="checkSidebarParentMenuPermission(['commercial-advanced-invoice-create','commercial-pending-invoice-create','commercial-invoice-list'])" class="relative px-6 py-3">
              <button @click="togglePagesMenu(6)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                        <span class="ml-2">Invoicing</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isPriceInvoiceListMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('commercial-advanced-invoice-create')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Debit Note (Adv.)' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.advance.invoice.create' }" @click="addActiveClass('Debit Note (Adv.)')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Debit Note (Adv.)</span>
                    </router-link>
                  </li>

                  <li v-if="checkSidebarMenuPermission('commercial-invoice-create')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Freight Invoice' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.pending.invoice' }" @click="addActiveClass('Freight Invoice')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Freight Invoice</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-invoice-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Invoice List' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.invoice.list' }" @click="addActiveClass('Invoice List')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Invoice List</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>
          </template>

          <a @click="toggleModuleMenu(8)" v-if="checkSidebarParentMenuPermission(['commercial-voyage-exchange-rate'])" class="flex cursor-pointer justify-between items-center p-2 mb-1 text-sm font-semibold text-purple-100 bg-purple-600 shadow-md focus:outline-none focus:shadow-outline-purple">
            <div class="flex ml-2">
              <span class="">Finance</span>
            </div>
            <svg class="w-6 h-6 cursor-pointer" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" data-v-46e9fe5b=""><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" data-v-46e9fe5b=""></path></svg>
          </a>


          <template v-if="isFinanceMenuOpen">
            <li v-if="checkSidebarMenuPermission('operation-update-exchange-rate')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Exchange Rate' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.voyage.exchange-rate' }" @click="addActiveClass('Exchange Rate')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-2">Exchange Rate</span>
              </router-link>
            </li>
            <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Voyage Pairing' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'finance.voyage-pairing.index' }" @click="addActiveClass('Voyage Pairing')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="ml-2">Voyage Pairing</span>
              </router-link>
            </li>

            <li v-if="checkSidebarMenuPermission('commercial-voyage-slot-uses-statement')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Voyage Slot Uses Statement' }">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
              <router-link :to="{ name: 'commercial.voyage-slot-uses-statement' }" @click="addActiveClass('Voyage Slot Uses Statement')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-6 h-6 ml-4 iconify iconify--iconoir"  preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.5" d="M9 21h6m-6 0v-5m0 5H3.6a.6.6 0 0 1-.6-.6v-3.8a.6.6 0 0 1 .6-.6H9m6 5V9m0 12h5.4a.6.6 0 0 0 .6-.6V3.6a.6.6 0 0 0-.6-.6h-4.8a.6.6 0 0 0-.6.6V9m0 0H9.6a.6.6 0 0 0-.6.6V16"></path></svg>
                <span class="ml-2">Slot Usages Statement</span>
              </router-link>
            </li>

            <li v-if="checkSidebarParentMenuPermission(['commercial-money-receipt-show'])" class="relative px-6 py-1">
              <button @click="togglePagesMenu(7)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                        <span class="ml-2">Cost & Budget</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isFinanceCostBudgetMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('operation-voyages-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Expense Entry' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.voyages.index' }" @click="addActiveClass('Expense Entry')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Expense Entry</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Expense Invoices' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.expense-invoices.index' }" @click="addActiveClass('Expense Invoices')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Expense Invoices</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Expenditure Report' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.expenditure-report' }" @click="addActiveClass('Expenditure Report')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Expenditure Report</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Budgets' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.budget.index' }" @click="addActiveClass('Budgets')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Budgets</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>

            <li v-if="checkSidebarParentMenuPermission(['commercial-money-receipt-show'])" class="relative px-6 py-1">
              <button @click="togglePagesMenu(8)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                        <span class="ml-2">Money Receipt</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isFinanceMoneyReceiptMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('commercial-money-receipt-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Create MR' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'money.receipt.create' }" @click="addActiveClass('Create MR')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Create MR</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-money-receipt-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'MR List' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'money.receipt.index' }" @click="addActiveClass('MR List')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">MR List</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>

            <li v-if="checkSidebarParentMenuPermission(['commercial-money-receipt-show'])" class="relative px-6 py-1">
              <button @click="togglePagesMenu(9)" class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                        <span class="ml-2">Reports</span>
                    </span>
                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-if="isFinanceReportsMenuOpen">
                <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner sub-menu bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                  <li v-if="checkSidebarMenuPermission('finance-voyage-expenditure-head-show')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Budget-Report' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.profitability-report' }" @click="addActiveClass('Budget-Report')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Budget vs Expense Report</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-revenue-vs-expense-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Revenue and Expense Report' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.revenue-and-expense' }" @click="addActiveClass('Revenue and Expense Report')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Voyage Profitability Report</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-aging-schedule')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Receivable Aging Schedule' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.aging-schedule' }" @click="addActiveClass('Receivable Aging Schedule')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Receivable Aging Schedule</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('commercial-freight-manifest-report')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Freight & Cargo Manifest' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.freight-cargo-manifest' }" @click="addActiveClass('Freight & Cargo Manifest')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Freight & Cargo Manifest</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-customer-each-container-amount')" class="px-f2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Container Wise Freight Calculation' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'commercial.container-wise-freight-calculation' }" @click="addActiveClass('Container Wise Freight Calculation')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Container Wise Freight Calculation</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-voyage-job-status')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Voyage Job Status' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.voyage-job-status' }" @click="addActiveClass('Voyage Job Status')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Voyage Job Status</span>
                    </router-link>
                  </li>
                  <li v-if="checkSidebarMenuPermission('finance-customer-ledger')" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{ 'active': isActive === 'Customer Ledger' }">
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                    <router-link :to="{ name: 'finance.customer-ledger' }" @click="addActiveClass('Customer Ledger')" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                      <span class="ml-2">Customer Ledger</span>
                    </router-link>
                  </li>
                </ul>
              </template>
            </li>
          </template>

        </ul>
    </div>
</template>
<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import { useRouter } from 'vue-router';
import useHeroIcon from '../assets/heroIcon';

const router = useRouter();
const currentRoute = ref(computed(() => { return router.currentRoute.value; }));
const icons = useHeroIcon();
const isActive = ref('');

const permissionKey = ref('');

function checkSidebarParentMenuPermission(permissionArray){
  let count = 0;
  let role = Store?.getters?.getCurrentUser?.role;
  if(role == 'super-admin'){
    return true;
  }
  permissionArray.map(function(value, key) {
    if(count == 0){
      if(checkSidebarMenuPermission(value)){
        count = count+1;
      } else{
        count = 0;
      }
    }
  });
  if(count == 0){
    return false;
  }else{
    return true;
  }
}

function checkSidebarMenuPermission(permissionKey){
  let role = Store?.getters?.getCurrentUser?.role;
  if(role == 'super-admin'){
    return true;
  }

  if(Store?.getters?.getCurrentUser?.permissions.indexOf(permissionKey) > -1){
    return true;
  }else{
    return false;
  }
}

const role = ref(computed(() => {
    return Store?.getters?.getCurrentUser?.role ?? null;
}));

const checkRole = (obj) => {
    return obj.role.includes(role.value) || obj.role === '';
}

const isCostExpenditureMenuOpen = ref(0); // Data encoding menu
const isSpecialContainerMenuOpen = ref(0); // vessel & cargo operation menu
const isCommercialScheduleMenuOpen = ref(0); // commercial menu
const isCommercialReportsMenuOpen = ref(0); // commercial menu
const isPriceInvoiceContractMenuOpen = ref(0); // pricing & invoicing menu
const isPriceInvoiceListMenuOpen = ref(0); // pricing & invoicing menu
const isFinanceCostBudgetMenuOpen = ref(0); // finance menu
const isFinanceMoneyReceiptMenuOpen = ref(0); // finance menu
const isFinanceReportsMenuOpen = ref(0); // finance menu


const isControlUserMenuOpen = ref(0); // control user menu
const isDataEncodingMenuOpen = ref(0); // Data encoding menu
const isContactMenuOpen = ref(0); // contact menu
const isVesselCargoMenuOpen = ref(0); // vessel & cargo operation menu
const isCommercialMenuOpen = ref(0); // commercial menu
const isDocumentationMenuOpen = ref(0); // documentation menu
const isPricingInvoicingMenuOpen = ref(0); // pricing & invoicing menu
const isFinanceMenuOpen = ref(0); // finance menu


function toggleModuleMenu(moduleKey){
  if(moduleKey === 1) {
    isControlUserMenuOpen.value = !isControlUserMenuOpen.value;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
  } else if(moduleKey === 2){
    isDataEncodingMenuOpen.value = !isDataEncodingMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
  } else if(moduleKey === 3){
    isContactMenuOpen.value = !isContactMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
  } else if(moduleKey === 4){
    isVesselCargoMenuOpen.value = !isVesselCargoMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
  } else if(moduleKey === 5){
    isCommercialMenuOpen.value = !isCommercialMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
  } else if(moduleKey === 6){
    isDocumentationMenuOpen.value = !isDocumentationMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
  } else if(moduleKey === 7){
    isPricingInvoicingMenuOpen.value = !isPricingInvoicingMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isFinanceMenuOpen.value = false;
  } else {
    isFinanceMenuOpen.value = !isFinanceMenuOpen.value;
    isControlUserMenuOpen.value = false;
    isDataEncodingMenuOpen.value = false;
    isContactMenuOpen.value = false;
    isVesselCargoMenuOpen.value = false;
    isCommercialMenuOpen.value = false;
    isDocumentationMenuOpen.value = false;
    isPricingInvoicingMenuOpen.value = false;
  }
}


function togglePagesMenu(sidebarElement) {
  if(sidebarElement === 1) {
    isCostExpenditureMenuOpen.value = !isCostExpenditureMenuOpen.value;
    isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 2){
    isSpecialContainerMenuOpen.value = !isSpecialContainerMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 3){
    isCommercialScheduleMenuOpen.value = !isCommercialScheduleMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 4) {
    isCommercialReportsMenuOpen.value = !isCommercialReportsMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 5) {
    isPriceInvoiceContractMenuOpen.value = !isPriceInvoiceContractMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 6) {
    isPriceInvoiceListMenuOpen.value = !isPriceInvoiceListMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 7) {
    isFinanceCostBudgetMenuOpen.value = !isFinanceCostBudgetMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 8) {
    isFinanceMoneyReceiptMenuOpen.value = !isFinanceMoneyReceiptMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  } else if(sidebarElement === 9) {
    isFinanceReportsMenuOpen.value = !isFinanceReportsMenuOpen.value;
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;
  } else{
    isCostExpenditureMenuOpen.value = false;isSpecialContainerMenuOpen.value = false;isCommercialScheduleMenuOpen.value = false;isCommercialReportsMenuOpen.value = false;isPriceInvoiceContractMenuOpen.value = false;isPriceInvoiceListMenuOpen.value = false;isFinanceCostBudgetMenuOpen.value = false;isFinanceMoneyReceiptMenuOpen.value = false;isFinanceReportsMenuOpen.value = false;
  }
}

function addActiveClass(label){
  isActive.value = label;
}


// const dataEncodingSidebarElements = {
//     role: "super-admin,admin,dataencoding,operation",
//     routes: [
//         {
//           route: 'dataencoding.vessels.index',
//           label: 'Vessels',
//           icon: icons.VehicleShip,
//
//         },
//         {
//           route: 'commercial.services.index',
//           label: 'Services',
//           icon: icons.List,
//         },
//         {
//           route: 'ports.index',
//           label: 'Ports',
//           icon: icons.List,
//         },
//         {
//           route: 'containertype.index',
//           label: 'Container Types',
//           icon: icons.List,
//         },
//         {
//           route: 'dataencoding.chargetypes.index',
//           label: 'Revenue Head',
//           icon: icons.List,
//         },
//
//         // Cost & Expenditure Menu Start
//         {
//           route: 'finance.voyageExpenditureHead.index',
//           label: 'Cost Head Creation',
//           icon: icons.List,
//         },
//         {
//           route: 'finance.port-wise-head-generation.index',
//           label: 'Cost Head Assign',
//           icon: icons.List,
//         },
//         // Cost & Expenditure Menu End
//
//         {
//             route: 'dataencoding.currency.index',
//             label: 'Currency',
//             icon: icons.Currency,
//         },
//         {
//             route: 'banks.index',
//             label: 'Banks',
//             icon: icons.List,
//         },
//         {
//             route: 'dataencoding.mail-templates.index',
//             label: 'Mail Templates',
//             icon: icons.List,
//         },
//     ],
// };
//
// const operationSidebarElements = {
//     role: "super-admin,admin,operation",
//     routes: [
//         {
//             route: 'operation.voyages.index',
//             label: 'Voyages',
//             icon: icons.List,
//         },
//         //SOF MENU
//         // {
//         //   route: 'operation.mlo.agents.index',
//         //   label: 'MLO Agents',
//         //   icon: icons.User,
//         // },
//         {
//           route: 'operation.edi-converter',
//           label: 'EDI Converter',
//           icon: icons.File,
//         },
//       {
//         route: 'commercial.voyage-dg-containers.voyage',
//         label: 'DG Assign',
//         icon: icons.List,
//       },
//       {
//         route: 'commercial.voyage-fr-containers.voyage',
//         label: 'FR Assign',
//         icon: icons.List,
//       },
//       {
//         route: 'commercial.voyage-rf-containers.voyage',
//         label: 'RF Assign',
//         icon: icons.List,
//       },
//     ],
// };
//
// const financeSidebarElements = {
//     role: "super-admin,admin",
//     routes: [
//         {
//             route: 'finance.voyages.index',
//             label: 'Voyage Closing (FIN)',
//             icon: icons.List,
//         },
//         {
//           route: 'commercial.advance.invoice',
//           label: 'Debit Note (Adv.)',
//           icon: icons.List,
//         },
//         {
//           route: 'commercial.pending.invoice',
//           label: 'Freight Invoice',
//           icon: icons.List,
//         },
//         {
//           route: 'money.receipt.create',
//           label: 'Create MR',
//           icon: icons.List,
//         },
//         {
//           route: 'money.receipt.index',
//           label: 'MR List',
//           icon: icons.List,
//         },
//         {
//           route: 'commercial.aging-schedule',
//           label: 'Aging Schedule',
//           icon: icons.Report,
//         },
//         {
//           route: 'commercial.freight-cargo-manifest',
//           label: 'Freight & Cargo Manifest',
//           icon: icons.Report,
//         },
//     ],
// };
//
// const commercialSidebarElements = {
//     role: "super-admin,admin,dataencoding",
//     routes: [
//         {
//           route: 'commercial.bookings.index',
//           label: 'Booking Creation',
//           icon: icons.BookOpen,
//         },
//         {
//           route: 'commercial.contracts.create',
//           label: 'Contract Creation',
//           icon: icons.List,
//         },
//         {
//           route: 'commercial.contracts.index',
//           label: 'Contract List',
//           icon: icons.List,
//         },
//         {
//             route: 'commercial.contract-assigns.voyages',
//             label: 'Contract Assigns',
//             icon: icons.List,
//         },
//       {
//         route: 'documentation.particular-customer-report',
//         label: 'Particular Customer Report',
//         icon: icons.List,
//       },
//       {
//         route: 'documentation.schedule-report',
//         label: 'Voyage Schedule Report',
//         icon: icons.List,
//       },
//       {
//         route: 'documentation.service-report',
//         label: 'Service Report',
//         icon: icons.List,
//       },
//       {
//         route: 'documentation.monthwise-service-report',
//         label: 'Month Wise Service Report',
//         icon: icons.List,
//       },
//       {
//         route: 'documentation.customer-loading-performance-report',
//         label: 'Customer Loading Performance',
//         icon: icons.List,
//       },
//     ]
// };
//
// const reportSidebarElements = {
//     role: "super-admin,admin,report",
//     routes: [
//         {
//             route: 'commercial.freight-cargo-manifest',
//             label: 'Cargo Manifest',
//             icon: icons.Report,
//         },
//         {
//             route: 'commercial.customer-load-calculation',
//             label: 'Load Summary',
//             icon: icons.Report,
//         },
//         // {
//         //   route: 'commercial.slot-uses-statement',
//         //   label: 'Slot Uses Statement',
//         //   icon: icons.Report,
//         // },
//         {
//           route: 'commercial.voyage-slot-uses-statement',
//           label: 'Voyage Slot Uses Statement',
//           icon: icons.Report,
//         },
//         {
//           route: 'commercial.manual-debit-note',
//           label: 'Manual Debit Note',
//           icon: icons.Report,
//         },
//     ]
// };
//
// const invoiceSidebarElements = {
//     role: "super-admin,admin,invoice",
//     routes: [
//         {
//           route: 'commercial.advance.invoice',
//           label: 'Advance Invoice',
//           icon: icons.List,
//         },
//         {
//             route: 'commercial.pending.invoice',
//             label: 'Final Invoice',
//             icon: icons.List,
//         },
//         {
//             route: 'commercial.invoice.list',
//             label: 'List',
//             icon: icons.List,
//         },
//     ]
// };
//
// const documentationSidebarElements = {
//     role: "super-admin,admin,documentation",
//     routes: [
//         {
//             route: 'documentation.generate-bl.report.index',
//             label: 'Bill of Ladings',
//             icon: icons.List,
//         },
//         {
//           route: 'commercial.freight-cargo-manifest',
//           label: 'Freight Manifest',
//           icon: icons.Report,
//         },
//     ],
// };
//
// const contactSidebarElements = {
//   role: "",
//   routes: [
//     {
//         route: 'commercial.customers.index',
//         label: 'Customers',
//         icon: icons.User,
//     },
//     {
//       route: 'operation.mlo.agents.index',
//       label: 'MLO Agents',
//       icon: icons.User,
//     },
//     // {
//     //   route: 'money.receipt.create',
//     //   label: 'Create Receipt',
//     //   icon: icons.List,
//     // },
//     // {
//     //   route: 'money.receipt.index',
//     //   label: 'List',
//     //   icon: icons.List,
//     // },
//   ]
// };

const sidebarElements = {
    role: "",
    routes: [
        {
            route: 'dashboard',
            label: 'Dashboard',
            icon: icons.HomeSolid,
        },
    ]
};
</script>
<style lang="postcss" scoped>
.active{
  color: blueviolet;
}
</style>