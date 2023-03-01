<script setup>
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { TabulatorFull as Tabulator } from "tabulator-tables"; //import Tabulator library

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { onMounted, ref, watch } from "vue";

const editingUser = ref(false);
const { auth } = usePage().props;
let userForm = useForm({
    name: "",
    email: "",
    username: "",
    password: "",
    password_confirmation: "",
    role: "admin",
});
let editUserForm = useForm({
    id: null,
    name: "",
    email: "",
    username: "",
    password: "",
    password_confirmation: "",
    role: "",
});

const onUserFormSubmit = () => {
    userForm.clearErrors();
    userForm.post(route("users.store"), {
        onSuccess: () => userForm.reset(),
        preserveScroll: true,
        only: ["errors"],
    });
};

const onEditUserFormSubmit = (user) => {
    editUserForm.clearErrors();
    editUserForm
        .transform((data) => {
            // Remove empty values
            return Object.fromEntries(
                Object.entries(data).filter(
                    ([key, value]) => value !== null && value !== ""
                )
            );
        })
        .patch(route("users.update", editUserForm.id), {
            onSuccess: () => hideEditUserForm(),
            preserveScroll: true,
            only: ["errors"],
        });
};
const editUser = (user) => {
    editUserForm.clearErrors();
    editingUser.value = user.id;
    editUserForm.defaults(user);
    editUserForm.reset();
};

const hideEditUserForm = () => {
    editUserForm.defaults({
        id: null,
        name: "",
        email: "",
        username: "",
        password: "",
        password_confirmation: "",
        role: "",
    });
    editUserForm.reset();
    editUserForm.clearErrors();
    editingUser.value = false;
};

const deleteUser = (user) => {
    router.delete(route("users.destroy", { user }), {
        only: ["errors"],
        onBefore: () => confirm("Are you sure you want to delete this user?"),
        onSuccess: () => tabulator.value.setPage(1),
    });
};

const searchTerm = ref("");
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func(...args);
        }, delay);
    };
};

const updateTableFilter = () => {
    if (!searchTerm.value) {
        tabulator.value.clearFilter();
    } else {
        tabulator.value.setFilter("name", "like", searchTerm.value);
    }
};

watch(searchTerm, debounce(updateTableFilter, 300));

const table = ref(null);

const tabulator = ref(null);
const usersURI = "/users/paginated";
onMounted(() => {
    //instantiate Tabulator when element is mounted
    tabulator.value = new Tabulator(table.value, {
        ajaxFiltering: true,
        filterMode: "remote",
        layout: "fitColumns",
        paginationSize: 10, //allow 10 rows per page of data
        paginationCounter: "rows", //display count of paginated rows in footer
        movableColumns: true, //allow column order to be changed
        pagination: true, //enable pagination
        paginationMode: "remote", //enable remote pagination
        ajaxURL: usersURI, //set url for ajax request
        paginationSize: 10, //optional parameter to request a certain number of rows per page
        paginationSizeSelector: [10, 20, 50, 100],
        columns: [
            // {
            //     // Index column
            //     title: "#",
            //     field: "index",
            //     formatter: "rownum",
            //     widthShrink: true,
            //     hozAlign: "center",
            //     headerSort: false,
            //     cssClass: "tabulator-index-column",
            // },
            {
                title: "Name",
                field: "name",
                cssClass: "tabulator-name-column",
                formatter(cell) {
                    const divEl = document.createElement("div");
                    const nameEl = document.createElement("span");

                    nameEl.textContent = cell.getValue();

                    if (cell.getRow().getData().id === auth.user.id) {
                        nameEl.classList.add("font-bold");
                        nameEl.textContent += " (you)";
                    }
                    divEl.appendChild(nameEl);
                    return divEl;
                },
            },
            { title: "Email", field: "email" },
            { title: "Username", field: "username" },
            {
                title: "Role",
                field: "role",
                formatter: function (cell) {
                    const spanEl = document.createElement("span");
                    spanEl.classList.add("border", "p-1", "rounded", "text-xs");
                    spanEl.innerText = cell.getValue();
                    return spanEl;
                },
            },
            {
                title: "Actions",
                formatter: function (cell) {
                    return createActionBtns(auth.user, cell.getRow().getData());
                },
                headerSort: false,
            },
        ], //define table columns
    });
    // // Reset search input when page size changes
    // tabulator.value.on("pageSizeChanged", () => (searchTerm.value = ""));
});

const createActionBtns = (authUser, cellUser) => {
    // create the container div
    const container = document.createElement("div");

    // create the first button
    const editBtn = document.createElement("button");
    editBtn.type = "button";
    editBtn.classList.add(
        "bg-emerald-500",
        "text-white",
        "text-xs",
        "font-bold",
        "px-2",
        "py-1",
        "rounded",
        "uppercase",
        "disabled:opacity-50"
    );
    editBtn.innerText = "Edit";
    editBtn.disabled =
        authUser.role !== "superadmin" && cellUser.id !== authUser.id;
    editBtn.addEventListener("click", () => editUser(cellUser));
    // create the second button
    const deleteBtn = document.createElement("button");
    deleteBtn.type = "button";
    deleteBtn.classList.add(
        "bg-red-500",
        "text-white",
        "text-xs",
        "font-bold",
        "px-2",
        "py-1",
        "rounded",
        "uppercase",
        "disabled:opacity-50",
        "ml-2"
    );
    deleteBtn.innerText = "Delete";
    deleteBtn.disabled =
        authUser.role !== "superadmin" && cellUser.id !== authUser.id;
    deleteBtn.addEventListener("click", () => deleteUser(cellUser));

    // append the buttons to the container div
    container.appendChild(editBtn);
    container.appendChild(deleteBtn);

    // return the container div
    return container;
};
</script>

<template>
    <Head title="Dashboard" />

    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="flex flex-col py-12 max-w-6xl mx-auto px-4">
                <div class="flex flex-col py-12 w-full">
                    <div class="p-4 flex justify-between items-center">
                        <h1
                            class="mb-4 text-3xl font-bold leading-none tracking-tight text-gray-900"
                        >
                            Users
                        </h1>
                        <TextInput
                            type="search"
                            class="ml-1 inline-flex placeholder:text-sm"
                            autocomplete="searchname"
                            placeholder="Type to search by name..."
                            v-model="searchTerm"
                        />
                    </div>
                    <div ref="table" id="users-table"></div>
                </div>
                <!-- <div class="overflow-x-auto">
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
                                    :class="{
                                        'font-bold':
                                            $page.props.auth.user.id ===
                                            user.id,
                                    }"
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
                                         TODO: AVOID USING HARDCODED roles
                                        <button
                                            type="button"
                                            class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded uppercase disabled:opacity-50"
                                            @click="editUser(user)"
                                            :disabled="
                                                $page.props.auth.user.role !==
                                                    'superadmin' &&
                                                user.id !==
                                                    $page.props.auth.user.id
                                            "
                                        >
                                            Edit
                                        </button>
                                        <button
                                            type="button"
                                            class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded uppercase disabled:opacity-50"
                                            :disabled="
                                                $page.props.auth.user.role !==
                                                    'superadmin' &&
                                                user.id !==
                                                    $page.props.auth.user.id
                                            "
                                            @click="deleteUser(user)"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
            </div>
            <div v-if="!editingUser" class="py-8 max-w-md mx-auto">
                <form @submit.prevent="onUserFormSubmit">
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="userForm.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError
                            class="mt-2"
                            :message="userForm.errors.name"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="username" value="Username" />

                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="userForm.username"
                            required
                            autocomplete="username"
                        />

                        <InputError
                            class="mt-2"
                            :message="userForm.errors.username"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="role" value="Role" />

                        <SelectInput
                            id="role"
                            class="mt-1 block w-full"
                            v-model="userForm.role"
                            required
                            autocomplete="role"
                        >
                            <option>admin</option>
                            <option>superadmin</option>
                        </SelectInput>

                        <InputError
                            class="mt-2"
                            :message="userForm.errors.role"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="userForm.email"
                            required
                            autocomplete="email"
                        />

                        <InputError
                            class="mt-2"
                            :message="userForm.errors.email"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="userForm.password"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="userForm.errors.password"
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
                            v-model="userForm.password_confirmation"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="userForm.errors.password_confirmation"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton
                            class="ml-4"
                            :class="{ 'opacity-25': userForm.processing }"
                            :disabled="userForm.processing"
                        >
                            Create
                        </PrimaryButton>
                    </div>
                </form>
            </div>
            <div v-else class="py-8 max-w-md mx-auto">
                <form @submit.prevent="onEditUserFormSubmit">
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="editUserForm.name"
                            autofocus
                            autocomplete="name"
                        />

                        <InputError
                            class="mt-2"
                            :message="editUserForm.errors.name"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="username" value="Username" />

                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="editUserForm.username"
                            autocomplete="username"
                        />

                        <InputError
                            class="mt-2"
                            :message="editUserForm.errors.username"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="role" value="Role" />

                        <SelectInput
                            id="role"
                            class="mt-1 block w-full"
                            v-model="editUserForm.role"
                            autocomplete="role"
                            :disabled="
                                editUserForm.id === $page.props.auth.user.id
                            "
                        >
                            <option selected>admin</option>
                            <option>superadmin</option>
                        </SelectInput>

                        <InputError
                            class="mt-2"
                            :message="editUserForm.errors.role"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="editUserForm.email"
                            autocomplete="email"
                        />

                        <InputError
                            class="mt-2"
                            :message="editUserForm.errors.email"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="editUserForm.password"
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="editUserForm.errors.password"
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
                            v-model="editUserForm.password_confirmation"
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="editUserForm.errors.password_confirmation"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <SecondaryButton
                            :class="{ 'opacity-25': editUserForm.processing }"
                            :disabled="editUserForm.processing"
                            @click="hideEditUserForm"
                        >
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton
                            class="ml-4"
                            :class="{ 'opacity-25': editUserForm.processing }"
                            :disabled="editUserForm.processing"
                        >
                            Update
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
// $headerBorderColor: transparent;
$borderColor: transparent;
@import "tabulator-tables/src/scss/themes/tabulator_simple.scss";

.tabulator#users-table {
    // @apply w-full;
    @apply rounded;
    @apply bg-transparent;
    // height: 1000px;
    @apply w-full;
    @apply text-gray-500;
    @apply mx-auto;
    .tabulator-table {
        @apply text-gray-500;
    }
    .tabulator-header {
        @apply text-gray-700 font-bold uppercase text-xs;
    }

    .tabulator-row {
        @apply border-gray-200;
        @apply bg-white;
        .tabulator-cell {
            @apply px-6 py-2;
            @apply border-r-gray-100;
        }
    }
    .tabulator-header {
        // @apply red;
        border-bottom: none;
        .tabulator-col.tabulator-sortable {
            .tabulator-arrow {
                @apply border-b-gray-400;
            }
            &[aria-sort="descending"] .tabulator-arrow {
                @apply border-t-gray-700;
            }
            &[aria-sort="ascending"] .tabulator-arrow {
                @apply border-b-gray-700;
            }
        }
    }
    .tabulator-col {
        @apply border-r-gray-100;
        @apply bg-gray-50;
        .tabulator-col-content {
            @apply px-6 py-3;
        }
    }
    .tabulator-index-column {
        @apply text-xs;
    }
    .tabulator-name-column {
        @apply text-gray-900;
    }
    .tabulator-footer .tabulator-page-size {
        padding-right: 2.5rem;
    }
}
</style>
