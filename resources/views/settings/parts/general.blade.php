{{-- 
    EXAMPLE TEMPLATE FOR CONFIGURATION SECTION
    Copy this base structure to create new configuration sections
--}}

@extends('Content::settings.main')

@section('form')
    <div class="space-y-6">
        {{-- ============================================ --}}
        {{-- HEADER SECTION (Always include this section) --}}
        {{-- ============================================ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-2">
                {{-- TITLE: Change according to your section --}}
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('SECTION_TITLE_HERE') }}
                </h2>
                <div class="flex items-center space-x-2">
                    {{-- ICON: Change based on context (fa-cog, fa-sliders-h, etc.) --}}
                    <i class="fas fa-cog text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Settings') }}</span>
                </div>
            </div>
            {{-- DESCRIPTION: Explain what this configuration does --}}
            <p class="text-gray-600 dark:text-gray-300">
                {{ __('SECTION_DESCRIPTION_HERE') }}
            </p>
        </div>

        {{-- ============================================ --}}
        {{-- CONFIGURATION BLOCK (Example for one item/group) --}}
        {{-- ============================================ --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- BLOCK HEADER --}}
            <div
                class="bg-linear-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            {{-- BLOCK ICON --}}
                            <i class="fas fa-layer-group text-gray-600 dark:text-gray-400"></i>
                        </div>
                        <div>
                            {{-- BLOCK TITLE --}}
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('BLOCK_TITLE_HERE') }}
                            </h3>
                            {{-- BLOCK SUBTITLE --}}
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('BLOCK_SUBTITLE_HERE') }}</p>
                        </div>
                    </div>
                    {{-- BADGE (optional - remove if not needed) --}}
                    <span
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm font-medium">
                        {{ __('BADGE_TEXT_HERE') }}
                    </span>
                </div>
            </div>

            {{-- BLOCK CONTENT --}}
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- EXAMPLE INPUT 1 --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-key text-indigo-500"></i>
                                <span>{{ __('FIELD_LABEL_1') }}</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('FIELD_HELP_TEXT_1') }}</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="example[field_1]"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('example.field_1', 'default value') }}">
                        </div>
                    </div>

                    {{-- EXAMPLE INPUT 2 (Number) --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-hashtag text-indigo-500"></i>
                                <span>{{ __('FIELD_LABEL_2') }}</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('FIELD_HELP_TEXT_2') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="example[field_2]" min="0"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('example.field_2', 0) }}">
                        </div>
                    </div>

                    {{-- EXAMPLE INPUT 3 (Toggle/Switch) --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-toggle-on text-indigo-500"></i>
                                <span>{{ __('FIELD_LABEL_3') }}</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('FIELD_HELP_TEXT_3') }}</span>
                        </label>
                        <div class="relative">
                            <select name="example[field_3]"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200">
                                <option value="1" {{ config_module('example.field_3', 1) == 1 ? 'selected' : '' }}>
                                    {{ __('Enabled') }}</option>
                                <option value="0" {{ config_module('example.field_3', 1) == 0 ? 'selected' : '' }}>
                                    {{ __('Disabled') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- EXAMPLE INPUT 4 (Textarea) --}}
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-align-left text-indigo-500"></i>
                                <span>{{ __('FIELD_LABEL_4') }}</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('FIELD_HELP_TEXT_4') }}</span>
                        </label>
                        <textarea name="example[field_4]" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200">{{ config_module('example.field_4', '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- REPEAT THE BLOCK ABOVE FOR MORE ITEMS --}}
        {{-- ============================================ --}}

        {{-- You can copy the entire block and paste it here for additional configuration groups --}}

        {{-- ============================================ --}}
        {{-- INFORMATION BOX (Optional - for tips/notes) --}}
        {{-- ============================================ --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <div class="shrink-0">
                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-xl mt-1"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-2">
                        {{ __('Information / Notes') }}
                    </h4>
                    <ul class="space-y-2 text-blue-700 dark:text-blue-400 text-sm">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check mt-1"></i>
                            <span>{{ __('Note 1: Add your first tip here') }}</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check mt-1"></i>
                            <span>{{ __('Note 2: Add your second tip here') }}</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check mt-1"></i>
                            <span>{{ __('Note 3: Add your third tip here') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
