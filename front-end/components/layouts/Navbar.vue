<template>
    <div>
        <slot></slot>
        <div class="p-3">
            <div>
                <button @click="showDropdown">
                    <div class="w-8 h-8 ml-2 bg-blue-200 rounded-full">1</div>
                </button>
            </div>
            <div class="absolute w-48 p-4 mt-2 ml-2 text-black origin-top-left bg-white rounded-md shadow-lg xl:mr-2 xl:right-0 xl:object-top-right hover:text-gray-500 ring-1 ring-black ring-opacity-5 focus:outline-none" tabindex="-1" v-if="navDropdown" v-on-clickaway="awayDropdown">
                <ul>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 mt-2 text-sm font-semibold text-indigo-800 bg-transparent rounded-lg md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">Profile</a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex flex-row items-center justify-between px-4 py-2 mt-2 text-sm font-semibold text-indigo-800 bg-transparent rounded-lg md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                            Settings
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <hr class="my-2">
                    </li>
                    <li>
                        <a href="#"
                            class="flex flex-row items-center justify-between px-4 py-2 mt-2 text-sm font-semibold text-indigo-800 bg-transparent rounded-lg xl:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                            Logout
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import { directive as onClickaway } from "vue-clickaway";
export default {
  data() {
    return {
      navDropdown: false,
    };
  },
  directives: { onClickaway: onClickaway },
  methods: {
    showDropdown() {
      this.navDropdown = !this.navDropdown;
    },
    awayDropdown() {
      this.navDropdown = false;
    },
  },
};
</script>

<style></style>
