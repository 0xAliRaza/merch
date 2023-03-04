<script setup>
import { Head, router, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { onMounted, ref } from "vue";
import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const dropzoneRef = ref(null);

onMounted(() => {
    const csrfToken = document.head.querySelector(
        'meta[name="csrf-token"]'
    ).content;

    const dropzone = new Dropzone(dropzoneRef.value, {
        url: route("products.imageUpload"),
        method: "post",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        addRemoveLinks: true,
        dictRemoveFile: "Remove",
        acceptedFiles: "image/png,image/jpeg,image/jpg",
        maxFiles: 5,
    });
    dropzone.on("success", (file, response) => {
        file.filename = response.filename;
        form.images.push(response.filename);
        if (form.images.length === 1) {
            form.default_image_index = 0;
            const btn = document.querySelector(".set-default-image-btn");
            btn.innerHTML = "default";
            btn.setAttribute("disabled", true);
        }
        form.errors.images = {};
    });
    dropzone.on("removedfile", (file) => {
        if (file.status === "success" && file.filename) {
            router.delete(`/products/image/${file.filename}`);
        }
    });
    const resetDefaultImageBtn = () => {
        document.querySelectorAll(".set-default-image-btn").forEach((btn) => {
            btn.removeAttribute("disabled");
            btn.innerText = "Set as default";
        });
    };
    dropzone.on("thumbnail", (file) => {
        const setDefaultButton = Dropzone.createElement(
            '<button type="button" class="set-default-image-btn !cursor-pointer text-emerald-500 text-white text-xs font-bold px-2 py-1 rounded uppercase w-full disabled:opacity-50 hover:underline disabled:pointer-events-none">Set as default</button>'
        );
        setDefaultButton.addEventListener("click", (event) => {
            // debugger;
            if (file.status === "success") {
                const imgIndex = form.images.findIndex(
                    (val) => file.filename === val
                );
                resetDefaultImageBtn();
                form.default_image_index = imgIndex;
                event.target.innerText = "Default";
                event.target.setAttribute("disabled", true);
            }
        });
        file.previewElement.appendChild(setDefaultButton);
    });

    dropzone.on("error", (file, err) => {
        if (err.message) {
            err = err.message;
        } else if (typeof err !== "string" && err.file) {
            err = err.file[0] ?? err.file;
        }
        form.errors.images = {};
        form.errors.images["imageUploadError"] = err;
        dropzone.removeFile(file);
    });
});
let form = useForm({
    name: "",
    description: "",
    price: null,
    discount: null,
    images: [],
    default_image_index: null, // index of default image im images array
});

const onFormSubmit = () => {
    form.post(route("products.store"), {
        onSuccess: () => form.reset(),
        onError: (errors) => {
            form.errors.images = {};

            // merge image related errors
            for (const key in errors) {
                if (key.startsWith("images")) {
                    form.errors.images[key] = errors[key];
                }
            }
            if (errors.default_image_index) {
                form.errors.images["default_image_index"] =
                    errors.default_image_index;
            }
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <!-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> -->
        <div class="py-8 max-w-md mx-auto">
            <form @submit.prevent="onFormSubmit">
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
                    <InputLabel for="description" value="Description" />

                    <TextInput
                        id="description"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.description"
                        required
                        autocomplete="description"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.description"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="price" value="Price" />
                    <div class="relative mt-1">
                        <input
                            type="number"
                            class="w-full pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="0"
                            min="0"
                            step="0.01"
                            v-model="form.price"
                        />
                        <!-- TODO: write the default applications currency instead of hardcoding it -->
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-400"
                        >
                            USD
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.price" />
                </div>
                <div class="mt-4">
                    <InputLabel for="discount" value="Discount" />
                    <div class="relative mt-1">
                        <input
                            type="number"
                            class="w-full pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="0"
                            min="0"
                            max="100"
                            v-model="form.discount"
                        />
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-400"
                        >
                            %
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.discount" />
                </div>
                <div class="mt-4">
                    <InputLabel for="image" value="Image" />

                    <div ref="dropzoneRef" class="dropzone mt-1 bg-white"></div>

                    <InputError
                        v-for="err in form.errors.images"
                        :key="err"
                        class="mt-2"
                        :message="err"
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
        <!-- </div> -->
    </AdminLayout>
</template>
