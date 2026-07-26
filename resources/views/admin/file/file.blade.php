<x-admin-layout :routes="$routes">
    @push('head')
        @include('layouts.parts.title', ['title' => __('File Management')])
        @module_vite(['resources/css/pages.css', 'resources/js/pages.js'])
        <style nonce="{{ $nonce }}">
            .drop-zone {
                border: 2px dashed #e2e8f0;
                border-radius: 1rem;
                padding: 2rem;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
                background: #f8fafc;
            }

            .drop-zone:hover {
                border-color: #0ea5e9;
                background: #f0f9ff;
            }

            .drop-zone.dragover {
                border-color: #0ea5e9;
                background: #e0f2fe;
                transform: scale(1.02);
            }

            .dark .drop-zone {
                background: #1e293b;
                border-color: #334155;
            }

            .dark .drop-zone:hover {
                border-color: #0ea5e9;
                background: #1e293b;
            }

            .dark .drop-zone.dragover {
                border-color: #0ea5e9;
                background: #0f172a;
            }

            .upload-preview {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 0.75rem;
                margin-top: 1rem;
            }

            .upload-preview-item {
                position: relative;
                border-radius: 0.75rem;
                overflow: hidden;
                aspect-ratio: 1;
                background: #f1f5f9;
                border: 1px solid #e2e8f0;
            }

            .dark .upload-preview-item {
                background: #1e293b;
                border-color: #334155;
            }

            .upload-preview-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .upload-preview-item .remove-btn {
                position: absolute;
                top: 0.25rem;
                right: 0.25rem;
                background: #ef4444;
                color: white;
                border: none;
                border-radius: 9999px;
                width: 1.5rem;
                height: 1.5rem;
                font-size: 0.75rem;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
            }

            .upload-preview-item .remove-btn:hover {
                transform: scale(1.1);
                background: #dc2626;
            }

            .upload-preview-item .file-name {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 0.25rem 0.5rem;
                font-size: 0.65rem;
                text-align: center;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .image-card {
                display: flex;
                flex-direction: column;
                background: white;
                border-radius: 1rem;
                border: 1px solid #e2e8f0;
                overflow: hidden;
                transition: all 0.2s ease;
            }

            .image-card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                border-color: #94a3b8;
            }

            .dark .image-card {
                background: #1e293b;
                border-color: #334155;
            }

            .dark .image-card:hover {
                border-color: #475569;
            }

            .image-card .image-wrapper {
                position: relative;
                padding-bottom: 75%;
                background: #f1f5f9;
                overflow: hidden;
            }

            .dark .image-card .image-wrapper {
                background: #0f172a;
            }

            .image-card .image-wrapper img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .image-card .image-wrapper:hover img {
                transform: scale(1.05);
            }

            .image-card .image-info {
                padding: 0.75rem;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .image-card .image-info .original-name {
                font-size: 0.75rem;
                color: #64748b;
                word-break: break-all;
                line-height: 1.3;
            }

            .dark .image-card .image-info .original-name {
                color: #94a3b8;
            }

            .image-card .image-info .disk-selector {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .image-card .image-info .disk-selector select {
                width: 100%;
                padding: 0.4rem 0.75rem;
                border-radius: 0.5rem;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
                font-size: 0.75rem;
                cursor: pointer;
                transition: all 0.2s;
                color: #0f172a;
            }

            .dark .image-card .image-info .disk-selector select {
                background: #0f172a;
                border-color: #334155;
                color: #e2e8f0;
            }

            .image-card .image-info .disk-selector select:focus {
                outline: none;
                border-color: #0ea5e9;
                ring: 2px solid #0ea5e9;
            }

            .image-card .image-info .disk-selector .save-disk-btn {
                width: 100%;
                padding: 0.4rem 0.75rem;
                border-radius: 0.5rem;
                background: #0ea5e9;
                color: white;
                font-size: 0.7rem;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                min-height: 2rem;
            }

            .image-card .image-info .disk-selector .save-disk-btn:hover {
                background: #0284c7;
            }

            .image-card .image-info .disk-selector .save-disk-btn.hidden {
                display: none !important;
            }

            .image-card .image-info .url-section {
                display: flex;
                gap: 0.4rem;
                align-items: center;
            }

            .image-card .image-info .url-section input {
                flex: 1;
                padding: 0.3rem 0.5rem;
                border-radius: 0.5rem;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
                font-size: 0.7rem;
                color: #475569;
                min-width: 0;
            }

            .dark .image-card .image-info .url-section input {
                background: #0f172a;
                border-color: #334155;
                color: #cbd5e1;
            }

            .image-card .image-info .url-section .copy-btn {
                padding: 0.3rem 0.6rem;
                border-radius: 0.5rem;
                background: #0ea5e9;
                color: white;
                font-size: 0.65rem;
                font-weight: 600;
                border: none;
                cursor: pointer;
                white-space: nowrap;
                transition: all 0.2s;
                min-height: 1.8rem;
            }

            .image-card .image-info .url-section .copy-btn:hover {
                background: #0284c7;
            }

            .image-card .image-info .url-section .copy-btn.copied {
                background: #059669;
            }

            .image-card .image-info .actions {
                display: flex;
                flex-direction: column;
                gap: 0.4rem;
                margin-top: 0.25rem;
            }

            .image-card .image-info .actions .delete-btn {
                width: 100%;
                padding: 0.5rem;
                border-radius: 0.5rem;
                background: #ef4444;
                color: white;
                font-size: 0.75rem;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                min-height: 2.2rem;
            }

            .image-card .image-info .actions .delete-btn:hover {
                background: #dc2626;
            }

            .custom-select {
                cursor: pointer;
            }

            /* Confirm Dialog */
            .confirm-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .confirm-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .confirm-dialog {
                background: white;
                border-radius: 1.5rem;
                padding: 2rem;
                max-width: 420px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                transform: scale(0.9);
                transition: transform 0.3s ease;
            }

            .confirm-overlay.active .confirm-dialog {
                transform: scale(1);
            }

            .dark .confirm-dialog {
                background: #1e293b;
                border: 1px solid #334155;
            }

            .confirm-dialog .icon {
                width: 4rem;
                height: 4rem;
                border-radius: 9999px;
                background: #fee2e2;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
            }

            .dark .confirm-dialog .icon {
                background: #7f1d1d;
            }

            .confirm-dialog .icon svg {
                width: 2rem;
                height: 2rem;
                color: #dc2626;
            }

            .dark .confirm-dialog .icon svg {
                color: #f87171;
            }

            .confirm-dialog h3 {
                font-size: 1.25rem;
                font-weight: 700;
                text-align: center;
                color: #0f172a;
                margin-bottom: 0.5rem;
            }

            .dark .confirm-dialog h3 {
                color: #f1f5f9;
            }

            .confirm-dialog p {
                font-size: 0.95rem;
                text-align: center;
                color: #64748b;
                margin-bottom: 1.5rem;
            }

            .dark .confirm-dialog p {
                color: #94a3b8;
            }

            .confirm-dialog .buttons {
                display: flex;
                gap: 0.75rem;
            }

            .confirm-dialog .buttons button {
                flex: 1;
                padding: 0.75rem 1.5rem;
                border-radius: 0.75rem;
                font-weight: 600;
                font-size: 0.95rem;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
            }

            .confirm-dialog .btn-cancel {
                background: #f1f5f9;
                color: #475569;
                cursor: pointer;
            }

            .confirm-dialog .btn-cancel:hover {
                background: #e2e8f0;
            }

            .dark .confirm-dialog .btn-cancel {
                background: #334155;
                color: #cbd5e1;
            }

            .dark .confirm-dialog .btn-cancel:hover {
                background: #475569;
            }

            .confirm-dialog .btn-danger {
                background: #dc2626;
                color: white;
                cursor: pointer;
            }

            .confirm-dialog .btn-danger:hover {
                background: #b91c1c;
                transform: scale(1.02);
            }

            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1rem;
            }

            @media (min-width: 768px) {
                .gallery-grid {
                    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                }
            }
        </style>
    @endpush

    <v-slot:main>
        <div class="space-y-6">
            <!-- Header -->
            <div
                class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-xl bg-sky-100 p-2.5 dark:bg-sky-900/30">
                                <svg class="h-6 w-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Image Gallery') }}
                                </h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Upload and manage your public images') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500 dark:text-slate-400">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $files->total() }}</span>
                            {{ __('images') }}
                        </span>
                        <form method="get" class="flex gap-2">
                            <input type="search" name="name" value="{{ request('name') }}"
                                placeholder="{{ __('Search...') }}"
                                class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:ring-sky-900/40" />
                            <button type="submit"
                                class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-700 dark:bg-sky-500 dark:hover:bg-sky-600 cursor-pointer">
                                {{ __('Search') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
                <!-- Upload Form -->
                <div
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Upload Images') }}</h2>
                        <span
                            class="rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">{{ __('5 max') }}</span>
                    </div>

                    <form action="{{ route('module.content.admin.files.store') }}" method="post"
                        enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('Storage Disk') }}
                            </label>
                            <select name="disk"
                                class="custom-select w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                <option value="content_local">{{ __('Local') }}</option>
                                <option value="content_s3">{{ __('AWS S3') }}</option>
                            </select>
                        </div>

                        <div class="drop-zone" id="dropZone">
                            <input type="file" name="files[]" id="fileInput" accept="image/*" multiple
                                class="hidden" />
                            <div>
                                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ __('Drop images here or click to browse') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Maximum 5 images') }}</p>
                            </div>
                        </div>

                        <div class="upload-preview" id="uploadPreview"></div>
                        <div id="nameInputs"></div>

                        <button type="submit" id="submitBtn"
                            class="mt-4 w-full rounded-xl bg-slate-900 py-3 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-slate-700 dark:hover:bg-slate-600 cursor-pointer">
                            {{ __('Upload Images') }}
                        </button>
                    </form>
                </div>

                <!-- Gallery Grid View -->
                <div>
                    @if ($files->count())
                        <div class="gallery-grid">
                            @foreach ($files as $file)
                                <div class="image-card">
                                    <div class="image-wrapper">
                                        <img src="{{ $file->links['show'] }}" alt="{{ $file->name }}"
                                            loading="lazy" />
                                    </div>
                                    <div class="image-info">
                                        <div class="original-name">{{ $file->original_name }}</div>

                                        <div class="disk-selector">
                                            <form action="{{ route('module.content.admin.files.update', $file->id) }}"
                                                method="post" class="disk-form" data-file-id="{{ $file->id }}"
                                                data-current-disk="{{ $file->disk }}">
                                                @csrf
                                                @method('put')
                                                <select name="disk" class="disk-select custom-select">
                                                    <option value="content_local"
                                                        {{ $file->disk === 'content_local' ? 'selected' : '' }}>
                                                        {{ __('Local') }}
                                                    </option>
                                                    <option value="content_s3"
                                                        {{ $file->disk === 'content_s3' ? 'selected' : '' }}>
                                                        {{ __('S3') }}
                                                    </option>
                                                </select>
                                                <button type="submit"
                                                    class="save-disk-btn hidden">{{ __('Change disk') }}</button>
                                            </form>
                                        </div>

                                        <div class="url-section">
                                            <input type="text" readonly value="{{ $file->url }}" />
                                            <button type="button" class="copy-btn"
                                                data-url="{{ $file->url }}">{{ __('Copy') }}</button>
                                        </div>

                                        <div class="actions">
                                            <button type="button" class="delete-btn"
                                                data-action="{{ route('module.content.admin.files.destroy', $file->id) }}"
                                                data-name="{{ $file->name }}">
                                                {{ __('Delete') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $files->links() }}
                        </div>
                    @else
                        <div
                            class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-white py-16 dark:border-slate-700 dark:bg-slate-900">
                            <svg class="mb-4 h-16 w-16 text-slate-400 dark:text-slate-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-lg font-medium text-slate-900 dark:text-white">{{ __('No images found') }}
                            </p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Upload your first images or adjust your search') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </v-slot:main>

    <!-- Custom Confirm Dialog -->
    <div id="confirmDialog" class="confirm-overlay">
        <div class="confirm-dialog">
            <div class="icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 id="confirmTitle">{{ __('Delete Image') }}</h3>
            <p id="confirmMessage">{{ __('Are you sure you want to delete this image?') }}</p>
            <div class="buttons">
                <button class="btn-cancel" id="confirmCancel">{{ __('Cancel') }}</button>
                <button class="btn-danger" id="confirmDelete">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>

    @push('js')
        <script nonce="{{ $nonce }}">
            document.addEventListener('DOMContentLoaded', function() {
                // Drag and Drop
                const dropZone = document.getElementById('dropZone');
                const fileInput = document.getElementById('fileInput');
                const previewContainer = document.getElementById('uploadPreview');
                const nameInputsContainer = document.getElementById('nameInputs');
                const submitBtn = document.getElementById('submitBtn');
                let selectedFiles = [];

                if (dropZone) {
                    dropZone.addEventListener('click', () => fileInput.click());
                    dropZone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        dropZone.classList.add('dragover');
                    });
                    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
                    dropZone.addEventListener('drop', (e) => {
                        e.preventDefault();
                        dropZone.classList.remove('dragover');
                        handleFiles(Array.from(e.dataTransfer.files));
                    });
                }

                if (fileInput) {
                    fileInput.addEventListener('change', (e) => handleFiles(Array.from(e.target.files)));
                }

                function handleFiles(files) {
                    const imageFiles = files.filter(file => file.type.startsWith('image/')).slice(0, 5);
                    if (!imageFiles.length) {
                        alert('{{ __('Please select valid image files') }}');
                        return;
                    }
                    selectedFiles = imageFiles;
                    renderPreviews(selectedFiles);
                    createNameInputs(selectedFiles);
                    updateSubmitButton();
                }

                function renderPreviews(files) {
                    if (!previewContainer) return;
                    previewContainer.innerHTML = '';
                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const div = document.createElement('div');
                            div.className = 'upload-preview-item';
                            div.innerHTML = `
                                <img src="${e.target.result}" alt="${file.name}" />
                                <button type="button" class="remove-btn" data-index="${index}">×</button>
                                <div class="file-name">${file.name}</div>
                            `;
                            previewContainer.appendChild(div);
                            div.querySelector('.remove-btn').addEventListener('click', () => removeFile(
                                index));
                        };
                        reader.readAsDataURL(file);
                    });
                }

                function createNameInputs(files) {
                    if (!nameInputsContainer) return;
                    nameInputsContainer.innerHTML = '';
                    files.forEach((file, index) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'names[]';
                        input.value = file.name.replace(/\.[^/.]+$/, '');
                        input.id = 'name_' + index;
                        nameInputsContainer.appendChild(input);
                    });
                }

                function removeFile(index) {
                    selectedFiles.splice(index, 1);
                    renderPreviews(selectedFiles);
                    createNameInputs(selectedFiles);
                    updateSubmitButton();
                    fileInput.value = '';
                }

                function updateSubmitButton() {
                    if (!submitBtn) return;
                    if (!selectedFiles.length) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = '{{ __('No images selected') }}';
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.textContent = '{{ __('Upload') }} ' + selectedFiles.length +
                            ' {{ __('images') }}';
                    }
                }

                updateSubmitButton();

                // Show/Hide Save button on disk change
                document.querySelectorAll('.disk-select').forEach(select => {
                    const form = select.closest('.disk-form');
                    const saveBtn = form.querySelector('.save-disk-btn');
                    const currentDisk = form.dataset.currentDisk;

                    select.addEventListener('change', function() {
                        if (this.value === currentDisk) {
                            saveBtn.classList.add('hidden');
                        } else {
                            saveBtn.classList.remove('hidden');
                        }
                    });
                });

                // Copy URL
                document.addEventListener('click', (e) => {
                    const copyBtn = e.target.closest('.copy-btn');
                    if (copyBtn) {
                        e.preventDefault();
                        const url = copyBtn.dataset.url;
                        if (url) copyImageUrl(url, copyBtn);
                    }
                });

                function copyImageUrl(url, btn) {
                    if (!url) return;
                    const doCopy = () => {
                        const original = btn.textContent;
                        btn.textContent = '✓ {{ __('Copied') }}';
                        btn.classList.add('copied');
                        setTimeout(() => {
                            btn.textContent = original;
                            btn.classList.remove('copied');
                        }, 2000);
                    };

                    if (navigator.clipboard?.writeText) {
                        navigator.clipboard.writeText(url).then(doCopy).catch(() => fallbackCopy(url, doCopy));
                    } else {
                        fallbackCopy(url, doCopy);
                    }
                }

                function fallbackCopy(url, callback) {
                    const input = document.createElement('input');
                    input.value = url;
                    document.body.appendChild(input);
                    input.select();
                    try {
                        document.execCommand('copy');
                        callback();
                    } catch (err) {
                        alert('{{ __('Unable to copy URL') }}');
                    }
                    document.body.removeChild(input);
                }

                // Delete with confirm
                const confirmDialog = document.getElementById('confirmDialog');
                const confirmTitle = document.getElementById('confirmTitle');
                const confirmMessage = document.getElementById('confirmMessage');
                const confirmCancel = document.getElementById('confirmCancel');
                const confirmDelete = document.getElementById('confirmDelete');
                let pendingDeleteUrl = null;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                document.addEventListener('click', (e) => {
                    const deleteBtn = e.target.closest('.delete-btn');
                    if (deleteBtn) {
                        e.preventDefault();
                        const action = deleteBtn.dataset.action;
                        const name = deleteBtn.dataset.name || '{{ __('this image') }}';
                        if (action) {
                            pendingDeleteUrl = action;
                            confirmTitle.textContent = '{{ __('Delete Image') }}';
                            confirmMessage.textContent = '{{ __('Are you sure you want to delete') }} "' +
                                name + '"? {{ __('This action cannot be undone.') }}';
                            confirmDialog.classList.add('active');
                        }
                    }
                });

                const closeConfirm = () => {
                    confirmDialog.classList.remove('active');
                    pendingDeleteUrl = null;
                };

                confirmCancel.addEventListener('click', closeConfirm);
                confirmDialog.addEventListener('click', (e) => {
                    if (e.target === confirmDialog) closeConfirm();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && confirmDialog.classList.contains('active')) closeConfirm();
                });

                confirmDelete.addEventListener('click', () => {
                    if (!pendingDeleteUrl) return;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = pendingDeleteUrl;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}" />
                        <input type="hidden" name="_method" value="DELETE" />
                    `;
                    document.body.appendChild(form);
                    form.submit();
                    closeConfirm();
                });
            });
        </script>
    @endpush
</x-admin-layout>
