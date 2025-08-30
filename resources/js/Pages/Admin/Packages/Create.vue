<template>
    <AdminLayout :title="title">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Flash Message if any -->
                <FlashMessage v-if="$page.props.flash.success" :message="$page.props.flash.success" class="mb-4" />

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <!-- Service Information Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Service Information
                            </h3>

                            <div class="space-y-6">
                                <!-- Service Name -->
                                <div>
                                    <InputLabel for="name" value="Service Name" required />
                                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                        required autofocus />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Description -->
                                <div>
                                    <InputLabel for="description" value="Description" required />
                                    <TextArea id="description" v-model="form.description" class="mt-1 block w-full"
                                        rows="4" placeholder="Enter service description..." required />
                                    <InputError :message="form.errors.description" class="mt-2" />
                                </div>

                                <!-- Price -->
                                <div>
                                    <InputLabel for="price" value="Price (৳)" required />
                                    <TextInput id="price" v-model="form.price" type="number" step="0.01" min="0"
                                        class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.price" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <Link :href="route('admin.package.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-700 active:bg-gray-300 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to List
                            </Link>

                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <i class="fas fa-save mr-2"></i>
                                {{ service ? 'Update Service' : 'Create Service' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from "vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FlashMessage from "@/Components/FlashMessage.vue";

const page = usePage();

const props = defineProps({
    service: {
        type: Object,
        required: false,
        default: null
    }
});

const title = computed(() =>
    props.service ? "Edit Service" : "Create Service"
);

const form = useForm({
    name: props.service?.name ?? "",
    description: props.service?.description ?? "",
    price: props.service?.price ?? ""
});

// Form submission
const submit = () => {
    if (props.service) {
        form.put(route("admin.package.update", props.service.id), {
            onSuccess: () => {
                form.clearErrors();
            },
        });
    } else {
        form.post(route("admin.package.create"), {
            onSuccess: () => {
                form.reset();
            },
        });
    }
};
</script>

<style scoped>
/* Custom styles for drag handle */
.drag-handle {
    cursor: move;
    touch-action: none;
}

/* Price input left padding for $ symbol */
.price-input {
    padding-left: 1.5rem;
}

/* Smooth transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Custom file input styling */
.file-input-label {
    cursor: pointer;
}

.file-input-label:hover {
    opacity: 0.9;
}

/* Responsive image preview */
@media (max-width: 640px) {
    .image-preview {
        width: 100%;
        height: auto;
    }
}
</style>
