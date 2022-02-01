<template>
    <div v-on-clickaway="hide">
        <div
            class="flex flex-row items-center justify-between px-4 py-1 my-1 text-sm text-left transition duration-200 rounded-md hover:bg-green-300 focus:bg-green-300 focus:text-black focus:outline-none focus:shadow-outline hover:text-black status" @click="show">
            <div class="flex flex-row items-center">
                <slot name="icon"></slot>
                <span>{{ title }}</span>
            </div>
            <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-0': showMenu, '-rotate-90': !showMenu}"
                class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </div>
        
        <transition name="page">
            <div class="relative right-0 w-full my-2 origin-top-right rounded-md shadow-lg" v-show="showMenu">
                <ul class="px-2 py-2 bg-white rounded-md shadow dark-mode:bg-gray-800">
                    <slot name="list"></slot>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
import { directive as onClickaway } from "vue-clickaway";
export default {
    props: ["title"],
    directives: { onClickaway: onClickaway },
    data() {
        return {
            showMenu: false,
        };
    },
    methods: {
        show() {
            this.showMenu = !this.showMenu;
        },
        hide() {
            this.showMenu = false;
        },
    },
};
</script>

<style scoped>
</style>