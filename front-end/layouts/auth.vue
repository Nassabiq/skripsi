<template>
  <div class="relative min-h-screen md:flex bg-cyan-50">
    <!-- v-show="sidebar" -->
    <Sidebar 
      class="absolute inset-y-0 left-0 z-10 w-4/5 px-2 space-y-6 text-blue-100 transition duration-200 ease-in-out transform bg-green-600 divide-y divide-green-300 sm:w-60 py-7 xl:relative" :class="{ '' : !sidebar, 'xl:translate-x-0 -translate-x-full' : sidebar   }" />
    <!-- Page content -->
    <div class="flex-1 bg-image">
      <Navbar class="flex flex-row-reverse justify-between text-green-500 bg-white shadow-md xl:flex-row xl:justify-end">
          <button class="p-4 focus:outline-none focus:bg-green-200 xl:hidden" @click="showSidebar">
              <!-- Logo Hamburger Menu -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" v-if="sidebar">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <!-- Logo X -->
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" v-if="!sidebar">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
          </button>
      </Navbar>
      <Nuxt />
    </div>
  </div>
</template>

<script>
import Sidebar from "../components/layouts/Sidebar.vue";
import Navbar from "../components/layouts/Navbar.vue";

export default {
    components: {
        Sidebar,
        Navbar,
    },
    data() {
        return {
            sidebar: true,
        };
    },
    methods: {
        showSidebar() {
            this.sidebar = !this.sidebar;
        },
    },
};
</script>