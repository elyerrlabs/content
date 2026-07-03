<template>
    <v-layout>
        <template #aside>
            <!-- Dashboards Section -->
            <v-item-menu
                :items="page.props.admin_dashboard"
                :title="__('Administrator')"
                icon="mdi mdi-shield-crown-outline text-2xl me-2"
            />

            <v-item-menu
                :items="page.props.developers"
                :title="__('Developers')"
                icon="mdi mdi-application-brackets-outline text-2xl me-2"
            />

            <v-item-menu
                :items="page.props?.user_routes"
                :title="__('My apps')"
                icon="mdi mdi-apps text-2xl me-2"
            />

            <v-item-menu
                :items="page.props.user_settings"
                :title="__('Settings')"
                icon="mdi mdi-account-lock-outline text-2xl me-2"
            />
        </template>
        <template #main>
            <!-- Header -->
            <header
                class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0"
            >
                <div class="flex-1">
                    <h1
                        class="text-2xl font-semibold text-gray-800 dark:text-gray-100"
                    >
                        {{ __("Admin Dashboard") }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __("Example admin view provided by Elymod") }}
                    </p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <v-theme />
                    <v-profile />
                </div>
            </header>

            <!-- Welcome Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6 mb-6"
            >
                <h1 class="text-lg md:text-xl">
                    <span class="font-bold text-gray-800 dark:text-gray-100">{{
                        __("Welcome")
                    }}</span
                    >,
                    <span class="text-gray-600 dark:text-gray-300 ml-2">
                        {{ $page.props?.user.name }}
                    </span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{
                        __(
                            "Manage your module settings and features from this dashboard",
                        )
                    }}
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Left Column: Stats & Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Stats Grid -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <!-- Card 1 -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 transition-transform hover:scale-[1.02]"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2
                                        class="text-lg font-medium text-gray-700 dark:text-gray-200"
                                    >
                                        {{ __("Module Status") }}
                                    </h2>
                                    <p
                                        class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Active and running") }}
                                    </p>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center"
                                >
                                    <i
                                        class="fas fa-check text-green-600 dark:text-green-400"
                                    ></i>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300"
                                >
                                    {{ __("Active") }}
                                </span>
                                <span
                                    class="text-2xl font-bold text-gray-800 dark:text-gray-100"
                                    >100%</span
                                >
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 transition-transform hover:scale-[1.02]"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2
                                        class="text-lg font-medium text-gray-700 dark:text-gray-200"
                                    >
                                        {{ __("Resources") }}
                                    </h2>
                                    <p
                                        class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Total items") }}
                                    </p>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"
                                >
                                    <i
                                        class="fas fa-box text-blue-600 dark:text-blue-400"
                                    ></i>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <span
                                    class="text-gray-500 dark:text-gray-400 text-sm"
                                    >{{ __("Count") }}</span
                                >
                                <span
                                    class="text-2xl font-bold text-gray-800 dark:text-gray-100"
                                    >42</span
                                >
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 transition-transform hover:scale-[1.02]"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2
                                        class="text-lg font-medium text-gray-700 dark:text-gray-200"
                                    >
                                        {{ __("Users") }}
                                    </h2>
                                    <p
                                        class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Active users") }}
                                    </p>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center"
                                >
                                    <i
                                        class="fas fa-users text-purple-600 dark:text-purple-400"
                                    ></i>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <span
                                    class="text-gray-500 dark:text-gray-400 text-sm"
                                    >{{ __("Online") }}</span
                                >
                                <span
                                    class="text-2xl font-bold text-gray-800 dark:text-gray-100"
                                    >24</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Form Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Quick Actions -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow p-5"
                        >
                            <h2
                                class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4"
                            >
                                {{ __("Quick Actions") }}
                            </h2>
                            <div class="space-y-3">
                                <button
                                    class="w-full flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg transition-colors"
                                >
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        {{ __("Create New Resource") }}
                                    </span>
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                                <button
                                    class="w-full flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg transition-colors"
                                >
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-upload"></i>
                                        {{ __("Import Data") }}
                                    </span>
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                                <button
                                    class="w-full flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg transition-colors"
                                >
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-cog"></i>
                                        {{ __("Settings") }}
                                    </span>
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Form Inputs -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow p-5"
                        >
                            <h2
                                class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4"
                            >
                                {{ __("Form Example") }}
                            </h2>
                            <div class="space-y-4">
                                <v-input
                                    :label="__('Name')"
                                    v-model="form.name"
                                    placeholder="Enter your name"
                                    class="w-full"
                                />
                                <v-textarea
                                    :label="__('Description')"
                                    v-model="textarea"
                                    placeholder="Enter description"
                                    class="w-full"
                                />
                                <v-select
                                    :label="__('Category')"
                                    :options="select"
                                    placeholder="Select an option"
                                    class="w-full"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- File Upload & Errors Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- File Upload -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow p-5"
                        >
                            <h2
                                class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4"
                            >
                                {{ __("File Management") }}
                            </h2>
                            <div class="space-y-4">
                                <v-file-uploader v-model="files" />
                                <div v-if="files.length > 0" class="mt-4">
                                    <h3
                                        class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2"
                                    >
                                        {{ __("Uploaded Files") }} ({{
                                            files.length
                                        }})
                                    </h3>
                                    <div class="space-y-2">
                                        <div
                                            v-for="(file, index) in files"
                                            :key="index"
                                            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded"
                                        >
                                            <span
                                                class="text-sm text-gray-600 dark:text-gray-300"
                                                >{{ file.name }}</span
                                            >
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                                >{{ file.size }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Errors Display -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow p-5"
                        >
                            <h2
                                class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4"
                            >
                                {{ __("System Status") }}
                            </h2>
                            <v-error :error="errors" />
                            <div class="mt-6">
                                <h3
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-3"
                                >
                                    {{ __("Recent Activity") }}
                                </h3>
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400"
                                    >
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{
                                            __(
                                                "Module initialized successfully",
                                            )
                                        }}</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400"
                                    >
                                        <i class="fas fa-sync"></i>
                                        <span>{{
                                            __(
                                                "Database connection established",
                                            )
                                        }}</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 text-sm text-yellow-600 dark:text-yellow-400"
                                    >
                                        <i
                                            class="fas fa-exclamation-triangle"
                                        ></i>
                                        <span>{{
                                            __("Check configuration settings")
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Developer Notes & Modal -->
                <div class="space-y-6">
                    <!-- Developer Notes -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow p-5"
                    >
                        <h2
                            class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4"
                        >
                            {{ __("Developer Notes") }}
                        </h2>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2">
                                <i
                                    class="fas fa-code text-blue-500 dark:text-blue-400 mt-1"
                                ></i>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Keep logic inside the module") }}
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i
                                    class="fas fa-database text-green-500 dark:text-green-400 mt-1"
                                ></i>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Use Inertia props for data") }}
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i
                                    class="fas fa-shield-alt text-purple-500 dark:text-purple-400 mt-1"
                                ></i>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{
                                        __(
                                            "Avoid global state unless necessary",
                                        )
                                    }}
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i
                                    class="fas fa-language text-yellow-500 dark:text-yellow-400 mt-1"
                                ></i>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{
                                        __("Use __() function for translations")
                                    }}
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i
                                    class="fas fa-palette text-indigo-500 dark:text-indigo-400 mt-1"
                                ></i>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ __("Support dark mode with Tailwind") }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <!-- Modal Trigger & Pagination -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow p-5"
                    >
                        <h2
                            class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-4"
                        >
                            {{ __("Controls") }}
                        </h2>

                        <!-- Modal Trigger -->
                        <button
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all hover:shadow-lg mb-6"
                            @click="dialog = true"
                        >
                            <i class="fas fa-external-link-alt"></i>
                            {{ __("Open Modal Example") }}
                        </button>

                        <!-- Modal Component -->
                        <v-modal v-model="dialog" :title="__('Example Modal')">
                            <template #body>
                                <div class="space-y-4">
                                    <h3
                                        class="text-lg font-medium text-gray-700 dark:text-gray-200"
                                    >
                                        {{ __("Modal Content") }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{
                                            __(
                                                "This is an example modal component. You can put any content here.",
                                            )
                                        }}
                                    </p>
                                    <div class="flex justify-end gap-3 pt-4">
                                        <button
                                            @click="dialog = false"
                                            class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition-colors"
                                        >
                                            {{ __("Cancel") }}
                                        </button>
                                        <button
                                            @click="dialog = false"
                                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
                                        >
                                            {{ __("Confirm") }}
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </v-modal>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <h3
                                class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-3"
                            >
                                {{ __("Navigation") }}
                            </h3>
                            <v-paginate
                                :total-pages="pages.total_pages"
                                v-model="pages.current_page"
                                class="flex justify-center"
                            />
                            <div
                                class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Page") }} {{ pages.current_page }}
                                {{ __("of") }}
                                {{ pages.total_pages }}
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Info -->
                    <div
                        class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-700 dark:to-gray-800 rounded-lg shadow p-5"
                    >
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center"
                            >
                                <i class="fas fa-moon text-blue-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-100">
                                {{ __("Dark Mode Ready") }}
                            </h3>
                        </div>
                        <p class="text-sm text-gray-300 mb-4">
                            {{
                                __(
                                    "This template supports dark mode. All components automatically adapt to the user's theme preference.",
                                )
                            }}
                        </p>
                        <div
                            class="flex items-center gap-2 text-xs text-gray-400"
                        >
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>{{ __("Fully responsive design") }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div
                class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center md:text-left">
                        <h4
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            {{ __("Module Version") }}
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            v1.0.0 • {{ __("Elymod Framework") }}
                        </p>
                    </div>
                    <div class="text-center">
                        <h4
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            {{ __("Last Updated") }}
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ new Date().toLocaleDateString() }}
                        </p>
                    </div>
                    <div class="text-center md:text-right">
                        <h4
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            {{ __("Status") }}
                        </h4>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"
                            ></span>
                            {{ __("Operational") }}
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </v-layout>
</template>

<script setup>
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import VProfile from "@/components/VProfile.vue";
import VTheme from "@/components/VTheme.vue";
import VError from "@/components/VError.vue";
import VFileUploader from "@/components/VFileUploader.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VSelect from "@/components/VSelect.vue";
import VModal from "@/components/VModal.vue";
import VPaginate from "@/components/VPaginate.vue";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const dialog = ref(false);
const page = usePage();

const form = ref({
    name: "",
});
const files = ref([]);
const textarea = ref("");

const errors = ref(["Last error", "Another error"]);

const pages = ref({
    pages: 10,
    total_pages: 20,
    per_page: 15,
    current_page: 1,
});

const select = ref([
    {
        name: "Option 1",
        id: "1",
    },
    {
        name: "Option 2",
        id: "2",
    },
    {
        name: "Option 3",
        id: "3",
    },
    {
        name: "Option 4",
        id: "4",
    },
    {
        name: "Option 5",
        id: "5",
    },
]);
</script>

<style scoped>
/* Custom transitions for better UX */
.hover-scale {
    transition: transform 0.2s ease-in-out;
}

.hover-scale:hover {
    transform: translateY(-2px);
}

/* Smooth fade transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Custom scrollbar for dark mode */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.dark ::-webkit-scrollbar-thumb {
    background: #4b5563;
}

::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>
