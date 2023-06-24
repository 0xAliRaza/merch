<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { onMounted, ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    product: { type: Object, required: true },
    cartCount: Number
});

// const { route } = usePage();
const productQuantity = ref(1);

const selectedImage = ref(null);

const addToCart = () => {
    router.post(
        route("carts.addToCart"),
        {
            product: props.product.id,
            quantity: productQuantity.value,
        },
        {
            onError: (errors) => {
                console.log(
                    "%cerror Product.vue line:25 ",
                    "color: red; display: block; width: 100%;",
                    errors
                );
            },
                preserveState: true,
            preserveScroll: true
        }
    );
};

onMounted(() => {
    if (props.product.default_image) {
        selectedImage.value = props.product.default_image.medium;
    }
});
</script>

<template>
    <Head :title="`Merch — ${product.name}`" />

    <PublicLayout :cartCount="props.cartCount">
        <div class="product max-w-5xl mx-auto p-5 bg-white my-5">
            <div class="flex">
                <div class="w-full max-w-sm">
                    <div>
                        <img
                            v-if="selectedImage"
                            :src="`/storage/images/${selectedImage}`"
                            :alt="product.name"
                            class=""
                        />
                    </div>
                    <div
                        class="p-2 flex justify-start items-center gap-1 w-full overflow-scroll [&::-webkit-scrollbar]:hidden [-ms-overflow-style:'none'] [scrollbar-width:'none']"
                    >
                        <div
                            v-for="img in product.images"
                            class="p-1 border rounded border-gray-200 flex-shrink-0"
                            :class="{
                                'border-indigo-400':
                                    selectedImage === img.medium,
                            }"
                            @mouseover="selectedImage = img.medium"
                        >
                            <img
                                class="object-cover h-[40px] w-[40px]"
                                :src="`/storage/images/${img.small}`"
                            />
                        </div>
                    </div>
                </div>
                <div class="px-5 flex-grow">
                    <h1 class="product__name text-gray-700 text-2xl">
                        {{ product.name }}
                    </h1>
                    <div class="">
                        <div class="py-4">
                            <div class="text-3xl text-indigo-600">
                                <span class="">$</span>
                                <span class="">
                                    {{ product.discounted_price }}
                                </span>
                            </div>
                            <div class="text-base text-gray-500">
                                <span class="line-through">
                                    <span class="">$</span>
                                    <span class="">{{ product.price }}</span>
                                </span>
                                <span class="mx-1"
                                    >-{{ product.discount }}%</span
                                >
                            </div>
                        </div>
                        <div
                            class="flex justify-start items-center gap-10 py-4"
                        >
                            <div>
                                <h6 class="text-sm text-gray-600">Quantity</h6>
                            </div>
                            <div>
                                <button
                                    class="text-xl text-gray-600 w-7 h-7 bg-gray-200 hover:bg-gray-300 hover:text-gray-700 disabled:text-gray-400 disabled:bg-gray-100 disabled:hover:bg-gray-100 disabled:hover:text-gray-400 inline-flex justify-center items-center"
                                    @click="productQuantity--"
                                    :disabled="productQuantity <= 1"
                                >
                                    -
                                </button>
                                <input
                                    type="number"
                                    v-model="productQuantity"
                                    class="p-1 border-none h-7 w-11 text-base text-gray-700 text-center focus:border-gray-300 outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                />
                                <button
                                    class="text-xl text-gray-600 w-7 h-7 bg-gray-200 hover:bg-gray-300 hover:text-gray-700 disabled:text-gray-400 disabled:bg-gray-100 disabled:hover:bg-gray-100 disabled:hover:text-gray-400 inline-flex justify-center items-center"
                                    @click="productQuantity++"
                                    :disabled="productQuantity >= 3"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex gap-2 pt-1 pb-4 justify-center align-middle"
                        >
                            <PrimaryButton
                                type="button"
                                class="bg-indigo-600 hover:bg-indigo-700"
                                >Buy now</PrimaryButton
                            >
                            <PrimaryButton
                                type="button"
                                class="bg-pink-600 hover:bg-pink-700"
                                @click.prevent="addToCart"
                                >Add to cart</PrimaryButton
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="prose p-10">
                {{ product.description }}
            </div>
        </div>
    </PublicLayout>
</template>
