<div>
    {{-- Header Halaman --}}
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Projects') }}
                </h2>
                <div class="flex items-center gap-4">
                    <x-secondary-button wire:click="export">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </x-secondary-button>

                    <x-primary-button wire:click="openModal()">
                        {{ __('+ New Project') }}
                    </x-primary-button>
                </div>

            </div>
        </div>
    </header>

    {{-- konten utama --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- loop untuk setiap project --}}
                @forelse ($projects as $project)
                    <div wire:click="viewProject('{{ $project->id }}')"
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 transition hover:shadow-lg cursor-pointer">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $project->name }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ Str::limit($project->description, 45) }}
                        </p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-300">
                                Deadline: {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                            </span>
                            <span
                                class="px-2 py-1 text-xs font-semibold uppercase leading-5 text-green-800 dark:text-green-100 bg-green-100 dark:bg-green-800/50 rounded-full">
                                {{ $project->status }}
                            </span>
                        </div>
                        <div class="mt-4 border-t dark:border-gray-700 pt-4 flex justify-between items-center">
                            <a href="{{ route('projects.show', $project) }}" wire:navigate wire:click.stop
                                class="inline-flex items-center px-3 py-1 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Manage') }}
                            </a>
                            <button wire:click.stop="confirmDelete('{{ $project->id }}')"
                                class="font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                Delete
                            </button>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika tidak ada project --}}
                    <div
                        class="col-span-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">
                            You don't have any projects yet. Let's create one!
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- modal edit project dan create --}}
    @if ($isProjectModalOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" wire:click="closeModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl z-10">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $editingProjectId ? 'Edit Project' : 'Create New Project' }}
                </h3>

                <form wire:submit="store">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input wire:model.blur="name" id="name" class="block mt-1 w-full" type="text"
                                name="name" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea wire:model.blur="description" id="description" rows="4"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="deadline" :value="__('Deadline')" />
                            <x-text-input wire:model.blur="deadline" id="deadline" class="block mt-1 w-full"
                                type="date" name="deadline" required />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select wire:model.blur="status" id="status"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">Select Status</option>
                                <option value="ON-GOING">On-Going</option>
                                <option value="PENDING">Pending</option>
                                <option value="COMPLETED">Completed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <x-secondary-button wire:click="closeModal()">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-primary-button type="submit">
                            {{ $editingProjectId ? 'Save Changes' : 'Create Project' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- modal konfirm delete --}}
    @if ($isConfirmingDelete)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" wire:click="$set('isConfirmingDelete', false)"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md z-10">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Are you sure?</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    This action cannot be undone.
                </p>

                <div class="flex justify-end gap-4">
                    <x-secondary-button wire:click="$set('isConfirmingDelete', false)">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button wire:click="delete()">
                        {{ __('Yes, Delete It') }}
                    </x-danger-button>
                </div>
            </div>
        </div>
    @endif
</div>
