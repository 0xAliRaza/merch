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
    <Head title="Multi Tools - Youtube" />
    <div class="container h-full flex items-center px-4 mx-auto">
        <div class="w-full flex justify-center">
            <form class="flex-1 flex justify-center align-center gap-2 max-w-2xl">
                <input
                    class=" bg-gray-100 h-10 appearance-none border-gray-300 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 placeholder:text-gray-500"
                    placeholder="Youtube video URL"
                    type="text"
                />
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded px-4 py-2"
                >
                    Download
                </button>
            </form>
        </div>
    </div>
</template>
