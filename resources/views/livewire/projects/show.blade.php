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
                        <strong class="text-gray-900 dark:text-gray-100">Deadline:</strong> {{ $project->deadline->format('d F Y') }}
                    </p>
                     <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <strong class="text-gray-900 dark:text-gray-100">Status:</strong>
                        <span class="px-2 py-1 text-xs font-semibold uppercase leading-5 text-green-800 dark:text-green-100 bg-green-100 dark:bg-green-800/50 rounded-full">
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
                        <x-primary-button>+ New Task</x-primary-button>
                    </div>
                    
                    {{-- tampilan daftar tugas --}}
                    <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                        No tasks yet.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>