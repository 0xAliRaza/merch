<script setup>
import { ref } from "vue";
const open = ref(false);
</script>

<template>
    <div>
        <div class="flex">
            <div class="flex-1">
                <slot name="parent"> </slot>
            </div>
            <button
                class="collapse-button py-4 px-6 ml-auto text-gray-500 hover:text-gray-900"
                @click="open = !open"
            >
                <svg
                    id="icon-chevron-down"
                    viewBox="0 0 20 20"
                    class="icon"
                    v-if="open"
                >
                    <path
                        d="M9.293 12.95l0.707 0.707 5.657-5.657-1.414-1.414-4.243 4.242-4.243-4.242-1.414 1.414z"
                    ></path>
                </svg>
                <svg
                    id="icon-chevron-right"
                    viewBox="0 0 20 20"
                    class="icon"
                    v-else
                >
                    <path
                        d="M12.95 10.707l0.707-0.707-5.657-5.657-1.414 1.414 4.242 4.243-4.242 4.243 1.414 1.414 4.95-4.95z"
                    ></path>
                </svg>
            </button>
        </div>
        <transition
            enter-active-class="transition ease-in duration-200"
            enter-from-class="transform -translate-x-full opacity-0"
            enter-to-class="transform opacity-100 translate-x-0"
            leave-active-class="transition ease-out duration-200"
            leave-from-class="transform opacity-100 translate-x-0"
            leave-to-class="transform opacity-0 -translate-x-full"
        >
            <div v-show="open" class="pl-4 text-sm">
                <slot name="children"> </slot>
            </div>
        </transition>
    </div>
</template>
<style lang="scss">

.icon {
    display: inline-block;
    width: 1em;
    height: 1em;
    stroke-width: 0;
    stroke: currentColor;
    fill: currentColor;
}
</style>
