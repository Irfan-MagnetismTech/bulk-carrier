<template>
  <!--  <form class="" @submit.prevent="login(user)">-->
  <!--  <div class="w-5/6" style="position: absolute;z-index: 999;display: flex;align-items: center;margin-left: 10px;margin-top: 15px">-->
  <!--&lt;!&ndash;    <label style="font-weight: bolder" class="block mb-1 text-sm leading-relaxed tracking-tight text-white dark:text-gray-100">&ndash;&gt;-->
  <!--&lt;!&ndash;      <nobr>USERNAME/UID: </nobr>&ndash;&gt;-->
  <!--&lt;!&ndash;    </label>&ndash;&gt;-->
  <!--    <input type="email" v-model="user.email" required placeholder="Your Email" class="w-2/6 ml-2 px-4 py-2 transition duration-300 ease-in-out transform bg-gray-100 border-transparent rounded-md dark:text-gray-100 dark:bg-gray-600 dark:focus:border-gray-600 dark:focus:bg-gray-500 focus:ring-blue-600 focus:outline-none" />-->

  <!--&lt;!&ndash;    <label style="font-weight: bolder" class="block mb-1 ml-2 text-sm leading-relaxed tracking-tight text-white dark:text-gray-100">PASSWORD: </label>&ndash;&gt;-->
  <!--    <input type="password" v-model="user.password" required minlength="6" placeholder="Your Password" class="w-2/6 ml-2 px-4 py-2 transition duration-300 ease-in-out transform bg-gray-100 border-transparent rounded-md dark:text-gray-100 dark:bg-gray-600 dark:focus:border-gray-600 dark:focus:bg-gray-500 focus:ring-blue-600 focus:outline-none" />-->

  <!--    <button type="submit" :disabled="isLoading" class="w-1/6 px-4 py-2 ml-2 text-sm font-semibold leading-6 text-gray-100 transition-colors duration-300 bg-gray-900 border border-transparent rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-600 focus:outline-none">-->
  <!--      <span v-if="!isLoading">Login</span>-->
  <!--      <svg v-else-if="isLoading" class="iconify iconify&#45;&#45;eos-icons w-6 h-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">-->
  <!--        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />-->
  <!--        <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">-->
  <!--          <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />-->
  <!--        </path>-->
  <!--      </svg>-->
  <!--    </button>-->
  <!--  </div>-->
  <!--  </form>-->
  <div id="login_page_body" class="flex items-center justify-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
    <div class="overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800" style="z-index: 999;width: 30%">
      <div class="flex items-center justify-center">
        <img aria-hidden="true" class="object-cover align-middle py-4 w-2/4" src="/tsll-logo.gif" alt="Toggi Shipping & Logistics Limited">
      </div>
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="flex items-center justify-center p-6 sm:p-8 w-full">
          <div class="w-full">
            <form class="" @submit.prevent="login(user)">
              <div>
                <label class="block mb-1 text-sm leading-relaxed tracking-tight text-gray-700 dark:text-gray-100">Email Address</label>
                <input type="email" v-model="user.email" required placeholder="Your Email" class="w-full px-4 py-2 transition duration-300 ease-in-out transform bg-gray-100 border-transparent rounded-md dark:text-gray-100 dark:bg-gray-600 dark:focus:border-gray-600 dark:focus:bg-gray-500 focus:ring-blue-600 focus:outline-none" />
              </div>
              <div class="mt-4">
                <label class="block mb-1 text-sm leading-relaxed tracking-tight text-gray-700 dark:text-gray-100">Password</label>
                <input type="password" v-model="user.password" required minlength="6" placeholder="Your Password" class="w-full px-4 py-2 transition duration-300 ease-in-out transform bg-gray-100 border-transparent rounded-md dark:text-gray-100 dark:bg-gray-600 dark:focus:border-gray-600 dark:focus:bg-gray-500 focus:ring-blue-600 focus:outline-none" />
              </div>
              <!--                            <div class="mt-4">-->
              <!--                              <div class="label-group">-->
              <!--                                  <span class="label-item-title">Shift</span>-->
              <!--                                  <div class="my-3 flex">-->
              <!--                                      <div class="flex items-center">-->
              <!--                                          <input type="radio" value="A" required v-model="user.shift" class="" name="shift" :id="'shift_a'" />-->
              <!--                                          <label class="ml-2" for="shift_a">A</label>-->
              <!--                                      </div>-->
              <!--                                      <div class="flex items-center ml-8">-->
              <!--                                          <input type="radio" value="B" required v-model="user.shift" class="" name="shift" :id="'shift_b'" />-->
              <!--                                          <label class="ml-2" for="shift_b">B</label>-->
              <!--                                      </div>-->

              <!--                                  </div>-->
              <!--                              </div>-->
              <!--                            </div>-->
              <button type="submit" :disabled="isLoading" class="inline-flex items-center justify-center flex-none w-full px-4 py-2 mt-6 text-sm font-semibold leading-6 text-gray-100 transition-colors duration-300 bg-gray-900 border border-transparent rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-600 focus:outline-none">
                <span v-if="!isLoading">Login</span>
                <svg v-else-if="isLoading" class="iconify iconify--eos-icons w-6 h-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                  <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                  <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                    <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                  </path>
                </svg>
              </button>
            </form>

            <backend-error :errors="errors"></backend-error>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="absolute inset-y-0 flex items-center pl-2 group_logo">
    <!--        <ul class="flex items-center flex-shrink-0 space-x-6">-->
    <!--          <li>-->
    <!--            <img aria-hidden="true" class="object-cover w-full align-middle dark:block w-32" src="/hr-line-header-small-logo.png" alt="Office">-->
    <!--          </li>-->
    <!--          <li>-->
    <!--            <img aria-hidden="true" class="object-cover w-full align-middle dark:block w-14" src="/karnafully-logo.png" alt="Office">-->
    <!--          </li>-->
    <!--        </ul>-->
    <img aria-hidden="true" class="object-cover w-full align-middle" src="/b-group.png" alt="Office">
  </div>
  <!--  <div class="absolute inset-y-0 flex items-center rounded-lg bg-danger">-->
  <!--    <img aria-hidden="true" class="object-cover w-full align-middle dark:block" src="/hr-lines-shosti.png" alt="Office">-->
  <!--  </div>-->
  <div class="overlay_div"></div>
</template>

<script setup>
import { ref } from "vue";
import BackendError from "../components/BackendError.vue";
import useAuth from "../services/auth";

// const user = ref({ email: "sadmin@gmail.com", password: "sadmin@gmail.com" });
const user = ref({ email: "", password: "", shift: "A" })
const { login, isLoading, errors } = useAuth();
import Title from "../services/title";

const { setTitle } = Title();

setTitle('Login');
</script>