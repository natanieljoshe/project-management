<div>
    {{-- Header --}}
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Project: {{ $project->name }}
            </h2>
        </div>
    </header>

    {{-- konten utama --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- kolom kiri (detail proyek) --}}
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Project Details</h3>
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <strong class="text-gray-900 dark:text-gray-100">Description:</strong><br>
                        {{ $project->description }}
                    </p>
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <strong class="text-gray-900 dark:text-gray-100">Deadline:</strong>
                        {{ $project->deadline->format('d F Y') }}
                    </p>
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <strong class="text-gray-900 dark:text-gray-100">Status:</strong>
                        <span
                            class="px-2 py-1 text-xs font-semibold uppercase leading-5 text-green-800 dark:text-green-100 bg-green-100 dark:bg-green-800/50 rounded-full">
                            {{ $project->status }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- kolom kanan: daftar tugas --}}
            <div class="md:col-span-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Tasks</h3>
                        <x-primary-button wire:click="openTaskModal()">+ New Task</x-primary-button>
                    </div>

                    @if (session()->has('task_message'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('task_message') }}</p>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @forelse ($project->tasks as $task)
                            <div wire:key="task-{{ $task->id }}" wire:click="viewTask('{{ $task->id }}')"
                                class="border dark:border-gray-700 rounded-lg p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 dark:text-gray-200">{{ $task->title }}</h4>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Deadline:
                                        {{ $task->deadline->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <select wire:change="updateTaskStatus('{{ $task->id }}', $event.target.value)"
                                        wire:click.stop
                                        class="border-none bg-transparent text-xs font-semibold uppercase leading-5 rounded-full appearance-none focus:outline-none focus:ring-0
    {{ $task->status === 'TODO' ? 'text-yellow-800 dark:text-yellow-100 bg-yellow-100 dark:bg-yellow-800/50' : '' }}
    {{ $task->status === 'IN-PROGRESS' ? 'text-blue-800 dark:text-blue-100 bg-blue-100 dark:bg-blue-800/50' : '' }}
    {{ $task->status === 'DONE' ? 'text-green-800 dark:text-green-100 bg-green-100 dark:bg-green-800/50' : '' }}
">
                                        <option value="TODO" @if ($task->status === 'TODO') selected @endif>TO DO
                                        </option>
                                        <option value="IN-PROGRESS" @if ($task->status === 'IN-PROGRESS') selected @endif>IN
                                            PROGRESS</option>
                                        <option value="DONE" @if ($task->status === 'DONE') selected @endif>DONE
                                        </option>
                                    </select>
                                    <div class="flex gap-2">
                                        <button wire:click.stop="confirmTaskDelete('{{ $task->id }}')"
                                            class="text-sm font-medium text-red-600 hover:text-red-900">Delete</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">No tasks yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- modal add new task --}}
    @if ($isTaskModalOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" wire:click="closeTaskModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl z-10">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    Create New Task
                </h3>

                <form wire:submit="storeTask">
                    <div class="grid grid-cols-1 gap-6">

                        <div>
                            <x-input-label for="title" :value="__('Task Title')" />
                            <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text"
                                name="title" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea wire:model="description" id="description" rows="4"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="deadline" :value="__('Deadline')" />
                            <x-text-input wire:model="deadline" id="deadline" class="block mt-1 w-full" type="date"
                                name="deadline" required />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <x-secondary-button wire:click="closeTaskModal()">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            Create Task
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- modal konfirmasi hapus --}}
    @if ($isConfirmingTaskDelete)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" wire:click="$set('isConfirmingTaskDelete', false)"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md z-10">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Are you sure?</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    This action cannot be undone.
                </p>

                <div class="flex justify-end gap-4">
                    <x-secondary-button wire:click="$set('isConfirmingTaskDelete', false)">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button wire:click="deleteTask()">
                        {{ __('Yes, Delete Task') }}
                    </x-danger-button>
                </div>
            </div>
        </div>
    @endif

    @if ($isViewTaskModalOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" wire:click="saveAndCloseModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-2xl z-10">

                @if ($viewingTask)
                    <div class="mb-2">
                        @if ($editingTaskTitleId === $viewingTask->id)
                            <div class="flex items-center gap-2">
                                <x-text-input wire:model="newTitle" wire:keydown.enter="updateTaskTitle"
                                    wire:keydown.escape="cancelEditingTitle" class="py-1 text-2xl font-bold w-full" />
                            </div>
                            <x-input-error :messages="$errors->get('newTitle')" class="mt-1" />
                        @else
                            <h3 wire:click="startEditingTitle('{{ $viewingTask->id }}')"
                                class="text-2xl font-bold text-gray-900 dark:text-gray-100 cursor-pointer flex items-center gap-2">
                                {{ $viewingTask->title }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536l12.232-12.232z" />
                                </svg>
                            </h3>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <span
                            class="px-2 py-1 text-xs font-semibold uppercase leading-5 rounded-full 
    {{ $viewingTask->status === 'TODO' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/50 dark:text-yellow-100' : '' }}
    {{ $viewingTask->status === 'IN-PROGRESS' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800/50 dark:text-blue-100' : '' }}
    {{ $viewingTask->status === 'DONE' ? 'bg-green-100 text-green-800 dark:bg-green-800/50 dark:text-green-100' : '' }}
">
                            {{ str_replace('-', ' ', $viewingTask->status) }}
                        </span>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            @if ($editingTaskDateId === $task->id)
                                <div class="flex items-center gap-2">
                                    <input type="date" wire:model="newDeadline"
                                        class="text-xs border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md p-1">
                                    <button wire:click="updateTaskDate()" class="text-green-500">Save</button>
                                    <button wire:click="cancelEditingDate()" class="text-red-500">Cancel</button>
                                </div>
                                <x-input-error :messages="$errors->get('newDeadline')" class="mt-1" />
                            @else
                                <span>Deadline: {{ $task->deadline->format('d M Y') }}</span>
                                <button wire:click="startEditingDate('{{ $task->id }}')"
                                    class="ml-2 text-indigo-500 text-xs">(edit)</button>
                            @endif
                        </div>
                    </div>

                    <div wire:click="startEditingDescription('{{ $viewingTask->id }}')"
                        class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 mt-4 cursor-pointer p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        @if ($editingTaskDescriptionId === $viewingTask->id)
                            <textarea wire:model="newDescription" wire:keydown.escape="cancelEditingDescription"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"
                                rows="5"></textarea>
                            <div class="flex justify-end gap-2 mt-2">
                                <button wire:click.stop="updateTaskDescription()"
                                    class="text-xs font-bold text-green-500">SAVE</button>
                                <button wire:click.stop="cancelEditingDescription()"
                                    class="text-xs font-bold text-red-500">CANCEL</button>
                            </div>
                        @else
                            @if (!empty($viewingTask->description))
                                <p>{!! nl2br(e($viewingTask->description)) !!}</p>
                            @else
                                <p class="text-gray-400">No description. Click to add.</p>
                            @endif
                        @endif
                    </div>
                @endif

                <div class="mt-6 flex justify-end">
                    <x-secondary-button wire:click="closeViewTaskModal()">
                        {{ __('Close') }}
                    </x-secondary-button>
                </div>
            </div>
        </div>
    @endif

</div>
