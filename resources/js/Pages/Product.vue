<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { onMounted } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
const props = defineProps({
    product: { type: Object, required: true },
});

onMounted(() => {
    console.log(props);
});
</script>

<template>
    <Head :title="`Merch — ${product.name}`" />

    <GuestLayout>
        <div
            class="product max-w-5xl mx-auto p-5 flex justify-stretch bg-white my-5"
        >
            <div class="w-full max-w-sm">
                <img
                    :src="`/storage/images/${product.default_image.small}`"
                    :alt="product.name"
                    class=""
                />
            </div>
            <div class="px-5 flex-grow">
                <h1 class="product__name text-gray-700 text-2xl">
                    {{ product.name }}
                </h1>
                <div class="flex">
                    <div class="bg-green-200">
                        <div class="text-lg text-indigo-600">
                            <span class="">$</span>
                            <span class="">
                                {{ product.discounted_price }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            <span class="line-through">
                                <span class="">$</span>
                                <span class="">{{ product.price }}</span>
                            </span>
                            <span class="mx-1">-{{ product.discount }}%</span>
                        </div>
                    </div>
                    <div class="bg-red-500">
                        <button>Buy now</button>
                        <button>Add to cart</button>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
