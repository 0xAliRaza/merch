<script setup>
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="flex flex-column py-12 max-w-4xl mx-auto">
                <!-- <div>{{ $page.props.auth }}test</div> -->
                <table
                    class="w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Username</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in $page.props.users"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                {{ user.name }}
                                {{
                                    $page.props.auth.user.id === user.id
                                        ? "(you)"
                                        : ""
                                }}
                            </th>
                            <td class="px-6 py-4">{{ user.email }}</td>
                            <td class="px-6 py-4">{{ user.username }}</td>
                            <td class="px-6 py-4">
                                <span class="border p-1 rounded text-xs">{{
                                    user.role
                                }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="inline-flex justify-center items-center gap-1"
                                >
                                    <button
                                        type="button"
                                        class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded uppercase"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded uppercase"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                John Doe
                            </th>
                            <td class="px-6 py-4">sample@gmail.com</td>
                            <td class="px-6 py-4">johndoe</td>
                            <td class="px-6 py-4">
                                <span class="border p-1 rounded text-xs"
                                    >superadmin</span
                                >
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="inline-flex justify-center items-center gap-1"
                                >
                                    <button
                                        type="button"
                                        class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded uppercase"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded uppercase"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-white dark:bg-gray-800">
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                Magic Mouse 2
                            </th>
                            <td class="px-6 py-4">Black</td>
                            <td class="px-6 py-4">Accessories</td>
                            <td class="px-6 py-4">$99</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
