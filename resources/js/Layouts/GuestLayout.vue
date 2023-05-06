<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto my-auto min-w-lg">
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
