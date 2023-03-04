<script setup>
import { Head } from "@inertiajs/vue3";
import { TabulatorFull as Tabulator } from "tabulator-tables"; //import Tabulator library
import { router } from "@inertiajs/vue3";

import TextInput from "@/Components/TextInput.vue";
import { onMounted, ref, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

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
const usersURI = "/products/paginated";
onMounted(() => {
    //instantiate Tabulator when element is mounted
    tabulator.value = new Tabulator(table.value, {
        ajaxFiltering: true,
        filterMode: "remote",
        layout: "fitColumns",
        minHeight: 300,
        paginationSize: 10, //allow 10 rows per page of data
        paginationCounter: "rows", //display count of paginated rows in footer
        movableColumns: true, //allow column order to be changed
        pagination: true, //enable pagination
        paginationMode: "remote", //enable remote pagination
        ajaxURL: usersURI, //set url for ajax request
        paginationSize: 10, //optional parameter to request a certain number of rows per page
        paginationSizeSelector: [10, 20, 50, 100],
        columns: [
            {
                field: "default_image.small",
                formatter: "image",
                headerSort: false,
                maxWidth: 150,
            },
            {
                title: "Name",
                field: "name",
                formatter: (cell) => {
                    const uri = `/products/${cell.getRow().getData()["id"]}`;
                    const anchorEl = document.createElement("a");
                    anchorEl.setAttribute("href", uri);
                    anchorEl.addEventListener("click", (event) => {
                        event.preventDefault();
                        router.visit(uri);
                    });
                    anchorEl.innerText = cell.getValue();
                    anchorEl.classList.add(
                        "text-blue-400",
                        "underline",
                        "hover:text-blue-500"
                    );
                    return anchorEl;
                },
            },
            {
                title: "Price",
                field: "price",
                formatter: function (cell, formatterParams, onRendered) {
                    console.log(formatterParams, onRendered);
                    // TODO: add the actuall currency symbol instead of hardcoding
                    return `$${cell.getValue()}`;
                },
            },
            {
                title: "Discount",
                field: "discount",
                formatter: function (cell) {
                    // TODO: add the actuall currency symbol instead of hardcoding
                    return `${cell.getValue()}%`;
                },
            },
            {
                title: "Last updated",
                field: "updated_at",
                formatter: function (cell) {
                    const date = new Date(cell.getValue());
                    return `<span title="${date.toString()}">${date.toLocaleDateString()}</span>`;
                },
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
    <Head title="Products" />

    <AdminLayout>
        <div class="flex flex-col py-12 max-w-6xl mx-auto px-4">
            <div class="w-full">
                <div class="p-4 flex justify-between items-center">
                    <h1
                        class="mb-4 text-3xl font-bold leading-none tracking-tight text-gray-900"
                    >
                        Products
                    </h1>
                    <TextInput
                        type="search"
                        class="ml-1 inline-flex placeholder:text-sm"
                        autocomplete="searchname"
                        placeholder="Type to search by name..."
                        v-model="searchTerm"
                    />
                </div>
                <div ref="table" id="products-table"></div>
            </div>
        </div>
    </AdminLayout>
</template>
<style lang="scss">
// $headerBorderColor: transparent;
$borderColor: transparent;
@import "tabulator-tables/src/scss/themes/tabulator_simple.scss";

.tabulator#products-table {
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
            @apply border-r-transparent;
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
