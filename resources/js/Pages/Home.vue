<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { onMounted } from "vue";
const props = defineProps({
    products: Array,
});

onMounted(() => {
    console.log(props);
});
</script>

<template>
    <Head title="Merch - Home" />

    <GuestLayout>
        <div
            class="max-w-5xl mx-auto p-5 grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3"
        >
            <Link
                v-for="product in products"
                :href="`/product/${product.id}`"
                class="product shadow hover:shadow-md hover:-translate-y-1 transition duration-100 ease-out"
            >
                <img
                    :src="`/storage/images/${product.default_image.small}`"
                    alt="product.name"
                    class="product__image"
                />

                <div class="p-3">
                    <h3 class="product__title text-gray-700 text-base">
                        {{ product.name }}
                    </h3>
                    <div class="product__meta">
                        <div
                            class="product__price text-base text-indigo-600 font-semibold"
                        >
                            <span class="product__price--currency">$</span>
                            <span class="product__price--price">{{
                                product.discounted_price
                            }}</span>
                        </div>
                        <div class="product__discount text-sm text-gray-500">
                            <span
                                class="product__discount--discount line-through"
                            >
                                <span class="product__discount--currency"
                                    >$</span
                                >
                                <span class="product__discount--price">{{
                                    product.price
                                }}</span>
                            </span>
                            <span class="product__discount--discount mx-1"
                                >-{{ product.discount }}%</span
                            >
                        </div>
                    </div>
                </div>
            </Link>
        </div>
    </GuestLayout>
</template>
