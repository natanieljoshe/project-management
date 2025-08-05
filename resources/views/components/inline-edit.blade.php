@props(['field', 'label', 'value'])

<div class="py-2">
    <strong class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $label }}:</strong>

    {{-- tampilan saat tidak diedit --}}
    <div wire:click="startEditing('{{ $field }}')" x-show="$wire.editingProjectDetail !== '{{ $field }}'"
        class="mt-1 text-gray-600 dark:text-gray-400 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700/50 p-2 rounded-md min-h-[40px] flex items-center justify-between">

        <span>{!! $value !!}</span>

        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536l12.232-12.232z" />
        </svg>
    </div>


    {{-- tampilan saat diedit --}}
    <div x-show="$wire.editingProjectDetail === '{{ $field }}'" class="mt-1">
        {{-- tempat yang diisi --}}
        {{ $slot }}

        <div class="flex items-center gap-2 mt-2">
            <button wire:click="updateProjectDetail('{{ $field }}')"
                class="text-xs font-bold text-green-600 dark:text-green-400 hover:underline">SAVE</button>
            <button wire:click="cancelEditing()"
                class="text-xs text-red-600 dark:text-red-400 hover:underline">CANCEL</button>
        </div>
    </div>
</div>
