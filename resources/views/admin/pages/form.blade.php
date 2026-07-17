<button type="button" id="openCreateModalBtn"
    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    {{ __('Create New Page') }}
</button>

<!-- Modal -->
<div id="createPageModal"
    class="fixed inset-0 z-50 {{ ($openCreateModal ?? false) || $errors->any() ? '' : 'hidden' }} overflow-y-auto"
    role="dialog" aria-modal="true">

    <div class="fixed inset-0 bg-black/50" id="modalBackdrop"></div>

    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl rounded-lg bg-white shadow-xl dark:bg-gray-800">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('Create New Page') }}
                </h3>
                <button type="button" id="closeModalBtn"
                    class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="createPageForm" method="POST" action="{{ route('module.content.admin.pages.store') }}"
                class="p-6">
                @csrf

                <!-- Errors -->
                @if ($errors->any())
                    <div
                        class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/50 dark:text-red-400">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tabs -->
                <div class="mb-4">
                    <!-- Tab Buttons -->
                    <div class="flex gap-8 border-b border-gray-200 dark:border-gray-700">
                        <button type="button" class="tab-btn active" data-tab="en">
                            🇬🇧 {{ __('English') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="es">
                            🇪🇸 {{ __('Spanish') }}
                        </button>
                        <button type="button" class="tab-btn" data-tab="fr">
                            🇫🇷 {{ __('French') }}
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="mt-4">
                        <!-- English -->
                        <div class="tab-content" data-tab="en">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Page Name') }}
                                    </label>
                                    <input type="text" name="name" id="page_name" value="{{ old('name') }}"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Page Slug') }}
                                    </label>
                                    <input type="text" name="slug" id="page_slug" value="{{ old('slug') }}"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @error('slug')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Spanish -->
                        <div class="tab-content hidden" data-tab="es">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Page Name') }} <span
                                            class="text-xs text-gray-500">({{ __('Spanish') }})</span>
                                    </label>
                                    <input type="text" name="name_es" id="page_name_es" value="{{ old('name_es') }}"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @error('name_es')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Page Slug') }} <span
                                            class="text-xs text-gray-500">({{ __('Spanish') }})</span>
                                    </label>
                                    <input type="text" name="slug_es" id="page_slug_es" value="{{ old('slug_es') }}"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @error('slug_es')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- French -->
                        <div class="tab-content hidden" data-tab="fr">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Page Name') }} <span
                                            class="text-xs text-gray-500">({{ __('French') }})</span>
                                    </label>
                                    <input type="text" name="name_fr" id="page_name_fr" value="{{ old('name_fr') }}"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @error('name_fr')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Page Slug') }} <span
                                            class="text-xs text-gray-500">({{ __('French') }})</span>
                                    </label>
                                    <input type="text" name="slug_fr" id="page_slug_fr"
                                        value="{{ old('slug_fr') }}"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @error('slug_fr')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 flex justify-end gap-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <button type="button" id="cancelModalBtn"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('Create Page') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {


            const modal = $('#createPageModal');
            const backdrop = $('#modalBackdrop');
            const openBtn = $('#openCreateModalBtn');
            const closeBtn = $('#closeModalBtn');
            const cancelBtn = $('#cancelModalBtn');
            const form = $('#createPageForm');

            // Open modal
            openBtn.on('click', function(e) {
                e.preventDefault();
                modal.removeClass('hidden');
            });

            // Close modal
            function closeModal() {
                modal.addClass('hidden');
            }

            closeBtn.on('click', closeModal);
            cancelBtn.on('click', closeModal);
            backdrop.on('click', closeModal);

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !modal.hasClass('hidden')) {
                    closeModal();
                }
            });

            // Tabs
            $('.tab-btn').on('click', function() {
                const tab = $(this).data('tab');

                // Update button styles
                $('.tab-btn').removeClass(
                        'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400')
                    .addClass(
                        'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    );

                $(this).removeClass(
                        'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    )
                    .addClass('border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400');

                // Show/hide content
                $('.tab-content').addClass('hidden');
                $('.tab-content[data-tab="' + tab + '"]').removeClass('hidden');
            });

            // Auto-generate slugs
            const mappings = [{
                    name: '#page_name',
                    slug: '#page_slug'
                },
                {
                    name: '#page_name_es',
                    slug: '#page_slug_es'
                },
                {
                    name: '#page_name_fr',
                    slug: '#page_slug_fr'
                },
            ];

            mappings.forEach(function(m) {
                const nameInput = $(m.name);
                const slugInput = $(m.slug);

                if (nameInput.length && slugInput.length) {
                    let auto = true;

                    slugInput.on('input', function() {
                        auto = false;
                    });

                    nameInput.on('input', function() {
                        if (auto && nameInput.val().trim()) {
                            const slug = nameInput.val().trim()
                                .toLowerCase()
                                .normalize('NFD')
                                .replace(/[\u0300-\u036f]/g, '')
                                .replace(/[^a-z0-9\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/-+/g, '-');
                            slugInput.val(slug);
                        }
                    });
                }
            });
        });
    </script>
@endpush
