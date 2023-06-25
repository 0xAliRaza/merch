<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
const showingNavigationDropdown = ref(false);

const props = defineProps({ cartCount: Number });
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
        <nav
            class="bg-gray-200 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
        >
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16">
                    <div class="flex">
                        <!-- Navigation Links -->
                        <header
                            class="h-16 flex p-4 justify-center items-center font-extrabold text-indigo-600 hover:text-indigo-800"
                        >
                            <Link href="/">Merch</Link>
                        </header>
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                        >
                            <NavLink
                                v-if="$page?.props?.auth?.user"
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                            >
                                Dashboard
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-auto">
                        <Link :href="route('carts.index')" class="px-3 py-2">
                            <!-- :active="route().current('dashboard')" -->
                            {{ props.cartCount }}
                            <svg
                                id="icon-cart"
                                viewBox="0 0 32 32"
                                class="icon"
                            >
                                <path
                                    d="M12 29c0 1.657-1.343 3-3 3s-3-1.343-3-3c0-1.657 1.343-3 3-3s3 1.343 3 3z"
                                ></path>
                                <path
                                    d="M32 29c0 1.657-1.343 3-3 3s-3-1.343-3-3c0-1.657 1.343-3 3-3s3 1.343 3 3z"
                                ></path>
                                <path
                                    d="M32 16v-12h-24c0-1.105-0.895-2-2-2h-6v2h4l1.502 12.877c-0.915 0.733-1.502 1.859-1.502 3.123 0 2.209 1.791 4 4 4h24v-2h-24c-1.105 0-2-0.895-2-2 0-0.007 0-0.014 0-0.020l26-3.98z"
                                ></path>
                            </svg>
                        </Link>
                        <!-- Settings Dropdown -->
                        <div
                            v-if="$page?.props?.auth?.user"
                            class="ml-3 relative"
                        >
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                    <!-- TODO: Stylize login/signup buttons -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavLink
                            v-if="!$page?.props?.auth?.user"
                            :href="route('login')"
                            :active="route().current('login')"
                        >
                            Login
                        </NavLink>
                        <NavLink
                            v-if="!$page?.props?.auth?.user"
                            :href="route('register')"
                            :active="route().current('register')"
                        >
                            Register
                        </NavLink>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="sm:hidden"
            >
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink
                        v-if="$page?.props?.auth?.user"
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                    >
                        Dashboard
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div
                    v-if="$page?.props?.auth?.user"
                    class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600"
                >
                    <div class="px-4">
                        <div
                            class="font-medium text-base text-gray-800 dark:text-gray-200"
                        >
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="font-medium text-sm text-gray-500">
                            {{ $page.props.auth.user.email }}
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>
        <main>
            <slot />
        </main>
    </div>
</template>

<style lang="scss">
.icon {
    display: inline-block;
    width: 1em;
    height: 1em;
    stroke-width: 0;
    stroke: currentColor;
    fill: currentColor;
}
</style>
