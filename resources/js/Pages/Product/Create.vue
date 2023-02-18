<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { onMounted, ref } from "vue";
import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";

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
        acceptedFiles: "image/png,image/jpeg,image/jpg",
        maxFiles: 1,
    });
    dropzone.on("success", (file, response) => {
        form.images.push(response.filename);
        form.main_image = form.images.length - 1;
    });
    dropzone.on("error", (file, response) => {
        if (file.previewElement) {
            file.previewElement.classList.add("dz-error");
            if (response.message) {
                response = response.message;
            } else if (typeof response !== "string" && response.error) {
                response = response.error;
            }
            for (let node of file.previewElement.querySelectorAll(
                "[data-dz-errormessage]"
            )) {
                node.textContent = response;
            }
        }
    });
});
let form = useForm({
    name: "",
    description: "",
    price: null,
    discount: null,
    images: [],
    main_image: null, // index of main image im images array
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
            if (errors.main_image) {
                form.errors.images.push(errors.main_image);
            }
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
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

                        <InputError
                            class="mt-2"
                            :message="form.errors.discount"
                        />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="image" value="Image" />

                        <div ref="dropzoneRef" class="dropzone mt-1"></div>
                        <!-- <Input
                            id="image"
                            type="file"
                            class="mt-1 block w-full"
                            v-model="form.image"
                            required
                        /> -->
                        <div v-for="err in form.errors.images" :key="err">
                            {{ err }}
                        </div>
                        <!-- {{ form.errors.images }} {{ "hello" }}
                        <InputError
                            v-for="err in form.errors.images"
                            :key="err"
                            class="mt-2"
                            :message="err"
                        /> -->
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
        </div>
    </div>
</template>