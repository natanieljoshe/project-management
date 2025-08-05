<div>
    {{-- header --}}
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Welcome, {{ Auth::user()->name }}!
            </h2>
        </div>
    </header>

    {{-- konten utama --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- bagian atas --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                {{-- kolom kiri --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Set Priority Project</h3>
                        <span x-data="{ show: false }" x-show="show" x-transition.opacity.out.duration.1500ms
                            x-init="$wire.on('priority-updated', () => {
                                show = true;
                                setTimeout(() => show = false, 2000)
                            })" class="text-sm text-gray-500">Saved!</span>
                    </div>
                    <select wire:model.live="selectedPriorityProject"
                        class="mt-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">Select a project</option>
                        @foreach ($allProjects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- kolom kanan--}}
                <div class="space-y-4">
                    {{-- card on going--}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">On-Going Projects</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">
                            {{ $ongoingProjectsCount }}</p>
                    </div>
                    {{-- card pending --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Projects</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-700 dark:text-gray-300">
                            {{ $pendingProjectsCount }}</p>
                    </div>
                </div>

            </div>

            {{-- bagian bawah: priority dan upcoming --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Priority Tasks</h3>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                        @if ($priorityProject)
                            <h4 class="font-bold text-indigo-600 dark:text-indigo-400">{{ $priorityProject->name }}</h4>
                            @forelse ($priorityProject->tasks->where('status', '!=', 'DONE')->sortBy('deadline') as $task)
                                <div
                                    class="border-l-4 {{ $task->status === 'TODO' ? 'border-yellow-400' : 'border-blue-400' }} pl-3">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $task->title }}
                                        </p>
                                        <span
                                            class="text-xs font-bold {{ $task->status === 'TODO' ? 'text-yellow-500' : 'text-blue-500' }}">
                                            {{ str_replace('-', ' ', $task->status) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Due:
                                        {{ $task->deadline->format('d M Y') }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No pending tasks in this project.
                                    Great job!</p>
                            @endforelse
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No projects available.</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Upcoming To-Dos</h3>
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4 max-h-[400px] overflow-y-auto">
                        @forelse ($upcomingTasks as $task)
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="font-bold">{{ $task->project->name }}</span> | Due:
                                    {{ $task->deadline->format('d M Y') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No upcoming tasks. Time to relax!</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
