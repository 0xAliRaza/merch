<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { onMounted, ref, computed } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    cartCount: Number,
    carts: Array,
    total: Number,
});

const toggleCartItem = (cart) => {
    router.patch(route("carts.edit"), {
        product_id: cart.product_id,
        selected: !cart.selected,
    });
};

const updateProductQuantity = (cart, newQuantity) => {
    const data = { product_id: cart.product_id, quantity: newQuantity };

    router.patch(route("carts.edit"), data, {
        onError: (errors) => {
            console.log(
                "%cerror Product.vue line:25 ",
                "color: red; display: block; width: 100%;",
                errors
            );
        },
    });
};

const checkout = () => {
    // router.post(route());
};
</script>

<template>
    <Head :title="`Merch — Cart`" />
    <!-- TODO: Use price as a separate component -->
    <!-- TODO: Use dynamic currency -->
    <PublicLayout :cartCount="props.cartCount">
        <div class="max-w-7xl mx-auto my-5 p-6 flex justify-between">
            <div class="p-6 flex flex-col gap-4 max-w-4xl">
                <div v-for="cart in carts" class="product bg-white p-5 flex">
                    <div class="product__data flex gap-3">
                        <div class="mr-2 my-auto">
                            <label
                                :for="`product-check-${cart.product_id}`"
                            ></label>

                            <input
                                type="checkbox"
                                @change.prevent="toggleCartItem(cart)"
                                :checked="cart.selected"
                                id="`product-check-${cart.product_id}`"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            />
                        </div>
                        <div class="max-w-[80px]">
                            <Link :href="route('product', cart.product_id)">
                                <img
                                    :src="`/storage/images/${cart.product.default_image.small}`"
                                    :alt="cart.product.name"
                                    class="h-auto max-w-full"
                                />
                            </Link>
                        </div>
                        <div class="flex">
                            <Link
                                :href="route('product', cart.product_id)"
                                class="text-indigo-400 hover:text-indigo-500"
                            >
                                {{ cart.product.name }}
                            </Link>
                        </div>
                    </div>
                    <div class="product__price px-5 ml-auto whitespace-nowrap">
                        <div class="text-xl text-indigo-600">
                            <span class="">$</span>
                            <span class="">
                                {{ cart.product.discounted_price }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            <span class="line-through">
                                <span class="">$</span>
                                <span class="">{{ cart.product.price }}</span>
                            </span>
                            <span class="mx-1"
                                >-{{ cart.product.discount }}%</span
                            >
                        </div>
                    </div>
                    <div class="product__quantity flex align-center mx-5">
                        <button
                            class="text-xl text-gray-600 w-7 h-7 bg-gray-200 hover:bg-gray-300 hover:text-gray-700 disabled:text-gray-400 disabled:bg-gray-100 disabled:hover:bg-gray-100 disabled:hover:text-gray-400 inline-flex justify-center items-center"
                            :disabled="cart.quantity - 1 < 1"
                            @click.prevent="
                                updateProductQuantity(cart, cart.quantity - 1)
                            "
                        >
                            -
                        </button>
                        <input
                            type="number"
                            :value="cart.quantity"
                            class="p-1 border-none h-7 w-11 text-base text-gray-700 text-center focus:border-gray-300 outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        />
                        <button
                            class="text-xl text-gray-600 w-7 h-7 bg-gray-200 hover:bg-gray-300 hover:text-gray-700 disabled:text-gray-400 disabled:bg-gray-100 disabled:hover:bg-gray-100 disabled:hover:text-gray-400 inline-flex justify-center items-center"
                            :disabled="cart.quantity + 1 > 3"
                            @click.prevent="
                                updateProductQuantity(cart, cart.quantity + 1)
                            "
                        >
                            +
                        </button>
                    </div>
                </div>
            </div>
            <div class="max-w-xs flex-1">
                <div class="py-4">
                    <h3>
                        Total: <span class="">$</span>
                        <span class="">
                            {{ total }}
                        </span>
                    </h3>
                </div>
                <PrimaryButton
                    class="w-full justify-center"
                    @click.prevent="checkout"
                    >Proceed To Checkout</PrimaryButton
                >
            </div>
        </div>
    </PublicLayout>
</template>
