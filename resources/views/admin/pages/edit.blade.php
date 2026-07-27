<x-admin-layout :routes="$routes">

    @push('head')
        @include('layouts.parts.title', ['title' => __('Edit page')])
        @module_vite(['resources/css/app.css', 'resources/css/tailwind.css', 'resources/js/pages.js'])
    @endpush

    <v-slot:main>

        @php
            $publishedAtValue = old('published_at');

            if (!$publishedAtValue && !empty($page->published_at)) {
                try {
                    $publishedAtValue = \Illuminate\Support\Carbon::parse($page->published_at)->format('Y-m-d\TH:i');
                } catch (\Throwable $e) {
                    $publishedAtValue = null;
                }
            }

            // Obtener el idioma actual
            $currentLang = request()->input('lang', 'en');
            $locales = ['es', 'en', 'fr'];

            // Valores para name y slug
            $name = old('name', $page->name ?? '');
            $nameEs = old('name_es', $page->name_es);
            $nameFr = old('name_fr', $page->name_fr);

            $slug = old('slug', $page->slug ?? '');
            $slugEs = old('slug_es', $page->slug_es);
            $slugFr = old('slug_fr', $page->slug_fr);
        @endphp

        <div class="container mx-auto px-4 py-6">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                        {{ __('Page manager') }}
                    </p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __('Edit page') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Update the main configuration and content of this page.') }}
                    </p>
                </div>

                <div class="flex justify-around gap-2">
                    <button type="button" id="resetButton"
                        class="inline-flex items-center justify-center rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm font-medium text-red-600 shadow-sm transition hover:bg-red-50 hover:border-red-500 dark:border-red-700 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:border-red-500">
                        <i class="mdi mdi-refresh mr-2 text-lg"></i>
                        {{ __('Reset') }}
                    </button>

                    <form id="resetForm" action="{{ route('module.content.admin.pages.reset', ['page' => $page->id]) }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>

                    <a href="{{ route('module.content.admin.pages.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:border-blue-400 dark:hover:text-blue-400">
                        <i class="mdi mdi-arrow-left mr-2 text-lg"></i>
                        {{ __('Back to pages') }}
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('module.content.admin.pages.update', $page->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex justify-between gap-4 border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ __('Page settings') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Edit the basic attributes used to organize and publish the page.') }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('module.content.admin.pages.show', $page->id) . "?lang=$currentLang" }}"
                                target="_blank"
                                class="inline-flex bg-blue-700 items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:border-blue-400 dark:hover:text-blue-400">
                                <i class="mdi mdi-arrow-right mr-2 text-lg"></i>
                                {{ __('Preview') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-6 px-6 py-6 md:grid-cols-2">
                        {{-- Name (inglés por defecto) --}}
                        <div>
                            <label for="name"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                🇬🇧 {{ __('Name (English)') }}
                            </label>
                            <input id="name" name="name" type="text" value="{{ old('name', $page->name) }}"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug (inglés por defecto) --}}
                        <div>
                            <label for="slug"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                🇬🇧 {{ __('Slug (English)') }}
                            </label>
                            <input id="slug" name="slug" type="text" value="{{ old('slug', $page->slug) }}"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            @error('slug')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Selector de idioma --}}
                        <div>
                            <label for="lang"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Select language') }}
                            </label>
                            <select id="lang" name="lang"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                @foreach (config('app.langs') as $key => $value)
                                    <option value="{{ $key }}" {{ $currentLang === $key ? 'selected' : '' }}>
                                        {{ __($value['title']) }} - {{ $key }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Estado Publicado --}}
                        <div>
                            <label for="is_published"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Is published') }}
                            </label>
                            <select id="is_published" name="is_published"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="0"
                                    {{ (string) old('is_published', (int) $page->is_published) === '0' ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="1"
                                    {{ (string) old('is_published', (int) $page->is_published) === '1' ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        </div>

                        {{-- Estado Borrador --}}
                        <div>
                            <label for="is_draft"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Is draft') }}
                            </label>
                            <select id="is_draft" name="is_draft"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="0"
                                    {{ (string) old('is_draft', (int) $page->is_draft) === '0' ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="1"
                                    {{ (string) old('is_draft', (int) $page->is_draft) === '1' ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        </div>

                        {{-- Página índice --}}
                        <div>
                            <label for="index"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Index page') }}
                            </label>
                            <select id="index" name="index"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="0"
                                    {{ (string) old('index', (int) $page->index) === '0' ? 'selected' : '' }}>
                                    {{ __('No') }}
                                </option>
                                <option value="1"
                                    {{ (string) old('index', (int) $page->index) === '1' ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </section>

                {{-- Sección de traducciones (oculta por defecto) --}}
                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <button type="button" id="toggleTranslations"
                        class="w-full flex justify-between items-center border-b border-gray-200 px-6 py-5 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="text-left">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                🌍 {{ __('Translations') }}
                                <span class="ml-2 text-xs font-normal text-gray-500 dark:text-gray-400">
                                    ({{ __('Spanish & French') }})
                                </span>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Click to expand/collapse translations for name and slug') }}
                            </p>
                        </div>
                        <i id="toggleIcon"
                            class="mdi mdi-chevron-down text-2xl text-gray-400 transition-transform duration-200"></i>
                    </button>

                    <div id="translationsContent" style="display: none;" class="px-6 py-6 space-y-4">
                        {{-- Español --}}
                        <div
                            class="grid grid-cols-2 gap-6 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-200 dark:border-blue-800/30">
                            <div>
                                <label for="name_es"
                                    class="mb-2 block text-sm font-medium text-blue-700 dark:text-blue-300">
                                    🇪🇸 {{ __('Name (Spanish)') }}
                                </label>
                                <input id="name_es" name="name_es" type="text" value="{{ $nameEs }}"
                                    class="w-full rounded-xl border border-blue-300 bg-white px-4 py-3 text-sm text-gray-900">
                                @error('name_es')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="slug_es"
                                    class="mb-2 block text-sm font-medium text-blue-700 dark:text-blue-300">
                                    🇪🇸 {{ __('Slug (Spanish)') }}
                                </label>
                                <input id="slug_es" name="slug_es" type="text" value="{{ $slugEs }}"
                                    class="w-full rounded-xl border border-blue-300 bg-white px-4 py-3 text-sm text-gray-900">
                                @error('slug_es')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Francés --}}
                        <div
                            class="grid grid-cols-2 gap-6 p-4 bg-purple-50 dark:bg-purple-900/10 rounded-xl border border-purple-200 dark:border-purple-800/30">
                            <div>
                                <label for="name_fr"
                                    class="mb-2 block text-sm font-medium text-purple-700 dark:text-purple-300">
                                    🇫🇷 {{ __('Name (French)') }}
                                </label>
                                <input id="name_fr" name="name_fr" type="text" value="{{ $nameFr }}"
                                    class="w-full rounded-xl border border-purple-300 bg-white px-4 py-3 text-sm text-gray-900 ">
                                @error('name_fr')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="slug_fr"
                                    class="mb-2 block text-sm font-medium text-purple-700 dark:text-purple-300">
                                    🇫🇷 {{ __('Slug (French)') }}
                                </label>
                                <input id="slug_fr" name="slug_fr" type="text" value="{{ $slugFr }}"
                                    class="w-full rounded-xl border border-purple-300 bg-white px-4 py-3 text-sm text-gray-900 ">
                                @error('slug_fr')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Editor de contenido --}}
                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Page content') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Edit the main content of the page') }}
                        </p>
                    </div>

                    <div class="px-6 py-6">
                        <x-content-editor label="{{ __('Edit content') }}" content="{{ $page->content }}"
                            preview="{{ false }}" jodit="{{ false }}" name="content"
                            lang="php" />
                    </div>
                </section>

                {{-- Botones de acción --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('module.content.admin.pages.index') }}"
                        class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/50">
                        <i class="mdi mdi-content-save-outline mr-2 text-lg"></i>
                        {{ __('Save changes') }}
                    </button>
                </div>
            </form>
        </div>
    </v-slot:main>
</x-admin-layout>

<script nonce="{{ $nonce }}">
    document.addEventListener("DOMContentLoaded", function() {

        // Cambio de idioma
        $("#lang").on("change", function(event) {
            window.location = "{{ $edit }}?lang=" + event.target.value;
        });

        // Reset
        $('#resetButton').on('click', function(e) {
            e.preventDefault();

            var confirmMessage =
                'WARNING: This will reset the template to the production version. All current changes will be lost. Are you sure?';

            if (confirm(confirmMessage)) {
                $('#resetForm').submit();
            }
        });

        // Toggle traducciones con animación
        $('#toggleTranslations').on('click', function() {
            $('#translationsContent').slideToggle(200);
            $('#toggleIcon').toggleClass('rotate-180');
        });

        // Abrir traducciones si hay errores en campos traducidos
        @if ($errors->has('name_es') || $errors->has('slug_es') || $errors->has('name_fr') || $errors->has('slug_fr'))
            $('#translationsContent').show();
            $('#toggleIcon').addClass('rotate-180');
        @endif
    });
</script>

<style nonce="{{ $nonce }}">
    /* Animación para el ícono */
    .rotate-180 {
        transform: rotate(180deg);
    }
</style>
