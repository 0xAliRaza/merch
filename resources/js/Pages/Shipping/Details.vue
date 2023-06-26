<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { onMounted, ref, computed } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
const props = defineProps({
    cartCount: Number,
});
</script>

<template>
    <Head :title="`Merch — Shipping`" />
    <PublicLayout :cartCount="props.cartCount">
        <div class="max-w-7xl mx-auto my-5 p-6 flex justify-between">
            <div class="p-6 flex flex-col gap-4 max-w-4xl">
                <div>
                    <SecondaryButton>Add New Address</SecondaryButton>
                </div>
            </div>
            <div class="max-w-xs flex-1">
                <div class="py-4">
                    <h3>
                        Total: <span class="">$</span>
                        <span class="">
                            test
                        </span>
                    </h3>
                </div>
                <!-- TODO: create a link button and use it here (follow DRY) -->
                <Link class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" :href="'#'"
                    >Proceed To Payment</Link
                >
            </div>
        </div>
    </PublicLayout>
</template>
