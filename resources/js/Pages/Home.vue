<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Multi Tools" />

    <div class="container mx-auto px-4">
        <div class="py-5 text-center">
            <h1 class="text-4xl">Multi downloader tools</h1>
        </div>
        <div class="flex justify-center py-5 mt-8 gap-2">
            <Link
                href="/youtube"
                type="button"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >Youtube downloader</Link
            >
            <Link
                href="/facebook"
                type="button"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >Facebook downloader</Link
            >
            <Link
                href="/instagram"
                type="button"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >Instagram downloader</Link
            >
        </div>
    </div>
</template>
