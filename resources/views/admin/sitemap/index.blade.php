<x-admin-layout :routes="$routes">

    @push('head')
        <title>{{ __('Sitemap generator') }}</title>
        @module_vite(['resources/css/app.css', 'resources/js/app.js'])
    @endpush

    <v-slot:main>
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Sitemap Management') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Manage and optimize your website sitemap for SEO') }}</p>
            </div>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('module.content.admin.sitemaps.reset') }}"
                    onsubmit="return confirm('{{ __('Are you sure you want to reset the sitemap? This action cannot be undone and all URLs will be permanently deleted.') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('Reset Sitemap') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Manual URL Form --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Add URL Manually') }}
            </h3>

            <form method="POST" action="{{ route('module.content.admin.sitemaps.store') }}" id="addUrlForm">
                @csrf
                <x-content-editor label="{{ __('Edit custom sitemap') }}" content="{{ $content }}"
                    preview="{{ false }}" jodit="{{ false }}" name="content" lang="html" />

                <div class="block p-4 m-4">
                    <button type="submit" class="bg-blue-700 text-white dark:text-gray-800 px-4 p-2">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>

    </v-slot:main>
</x-admin-layout>
