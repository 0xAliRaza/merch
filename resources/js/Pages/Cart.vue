<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { onMounted, ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    cartCount: Number,
    carts: Array,
});
</script>

<template>
    <Head :title="`Merch — Cart`" />

    <PublicLayout :cartCount="props.cartCount">
        <div class="max-w-7xl mx-auto my-5 p-6 flex">
            <div class="p-6  flex flex-col gap-4 max-w-3xl">
                <div v-for="cart in carts" class="bg-white  p-5">
                    {{ cart }}
                </div>
                <hr />
            </div>
            <div class="w-full">
                <PrimaryButton class="flex w-full">Checkout</PrimaryButton>
            </div>

        </div>
    </PublicLayout>
</template>
