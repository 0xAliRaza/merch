<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
const form = useForm({
    name: "",
    email: "",
    username: "",
    password: "",
    password_confirmation: "",
    role: "admin",
});

const submit = () => {
    form.post(route("users.store"), {
        onSuccess: () => form.reset(),
        only: ["users"],
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="py-8 max-w-md mx-auto">
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="username" value="Username" />

                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            required
                            autocomplete="username"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.username"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="role" value="Role" />

                        <SelectInput
                            id="role"
                            class="mt-1 block w-full"
                            v-model="form.role"
                            required
                            autocomplete="role"
                        >
                            <option selected>admin</option>
                            <option>superadmin</option>
                        </SelectInput>

                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="email"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirm Password"
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Create
                        </PrimaryButton>
                    </div>
                </form>
            </div>

            <div class="flex flex-col py-12 max-w-6xl mx-auto">
                <div class="overflow-x-auto">
                    <table
                        class="w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">Role</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(user, index) in $page.props.users"
                                :key="user.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            >
                                <td class="px-6 py-4 text-xs">{{ index }}</td>
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
    </div>
</template>
