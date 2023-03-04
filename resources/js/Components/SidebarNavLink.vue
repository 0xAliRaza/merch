<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps(["href", "active"]);

const classes = computed(() =>
    props.active
        ? "flex items-center py-4 px-6 font-medium leading-5 text-gray-900 hover:text-gray-900 focus:outline-none  focus:text-gray-900 transition duration-150 ease-in-out"
        : "flex items-center py-4 px-6 font-medium leading-5 text-gray-500 hover:text-gray-900 focus:outline-none  focus:text-gray-900 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
