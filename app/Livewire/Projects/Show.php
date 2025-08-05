<?php

namespace App\Livewire\Projects;

use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Show extends Component
{
    //menampung data project
    public Project $project;

    public string $title = '';
    public string $description = '';
    public string $deadline = '';
    public string $status = '';
    
    public bool $isTaskModalOpen = false;
    public ?string $editingTaskId = null;
    public ?string $editingTaskDateId = null;
    public ?Task $viewingTask = null;
    public bool $isViewTaskModalOpen = false;

    public string $newDeadline = '';
    public ?string $editingTaskTitleId = null;
    public string $newTitle = '';

    public bool $isConfirmingTaskDelete = false;
    public ?string $deletingTaskId = null;

    public ?string $editingTaskDescriptionId = null;
    public string $newDescription = '';

    //validasi untuk task baru
    protected function rules()
    {
        return [
            'title' => 'required|string|min:3',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'status' => 'required|in:TODO,IN-PROGRESS,DONE',
        ];
    }
    
    public function mount(Project $project)
    {
        // pastikan user hanya bisa akses proyeknya sendiri
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $this->project = $project;
    }
    
    public function openTaskModal(): void
    {
        $this->reset('title', 'description', 'deadline', 'status', 'editingTaskId');
        $this->isTaskModalOpen = true;
    }

    public function closeTaskModal(): void
    {
        $this->isTaskModalOpen = false;
    }
    
    //menyimpan tugas baru
    public function storeTask(): void
    {
        if ($this->editingTaskId) {
            $this->updateTask();
            return;
        }

        // status defaultnya 'TODO'
        $validated = $this->validate([
            'title' => 'required|string|min:3',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
        ]);
        
        $this->project->tasks()->create($validated);
        session()->flash('task_message', 'Task successfully created.');
        $this->project = $this->project->fresh(); 
        $this->closeTaskModal();

        if ($this->viewingTask) {
        $this->viewingTask->refresh();
    }
    }

    public function editTask(Task $task): void
    {
        $this->editingTaskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->deadline = $task->deadline->format('Y-m-d');
        $this->status = $task->status;

        $this->isTaskModalOpen = true;
    }

    public function updateTask(): void
    {
        $validated = $this->validate();
        
        $task = Task::findOrFail($this->editingTaskId);
        $task->update($validated);

        session()->flash('task_message', 'Task successfully updated.');
        $this->project = $this->project->fresh(); 

        if ($this->viewingTask) {
        $this->viewingTask->refresh();
    }

        $this->closeTaskModal();
    }
    
    
    public function confirmTaskDelete(string $taskId): void
    {
        $this->deletingTaskId = $taskId;
        $this->isConfirmingTaskDelete = true;
    }

    public function deleteTask(): void
    {
        Task::findOrFail($this->deletingTaskId)->delete();
        
        $this->isConfirmingTaskDelete = false;
        $this->project = $this->project->fresh(); 
        if ($this->viewingTask) {
        $this->viewingTask->refresh();
    }
        session()->flash('task_message', 'Task successfully deleted.');
    }

    public function updateTaskStatus(string $taskId, string $newStatus)
    {
        // dd($taskId, $newStatus); 
        $task = Task::findOrFail($taskId);
        
        // Validasi untuk memastikan status yang dikirim valid
        if (!in_array($newStatus, ['TODO', 'IN-PROGRESS', 'DONE'])) {
            return;
        }
        
        $task->update(['status' => $newStatus]);

        $this->project = $this->project->fresh(); 

        if ($this->viewingTask) {
            $this->viewingTask->refresh();
        }
    }    

    public function startEditingDate(Task $task): void
{
    $this->editingTaskDateId = $task->id;
    $this->newDeadline = $task->deadline->format('Y-m-d');
}

    public function cancelEditingDate(): void
    {
        $this->reset('editingTaskDateId', 'newDeadline');
    }

    public function updateTaskDate(): void
    {
        $this->validate(['newDeadline' => 'required|date']);
        
        $task = Task::findOrFail($this->editingTaskDateId);
        $task->update(['deadline' => $this->newDeadline]);
        
        $this->project = $this->project->fresh(); 
        $this->cancelEditingDate();
        
        if ($this->viewingTask) {
        $this->viewingTask->refresh();
    }
    }

    public function startEditingTitle(Task $task): void
{
    $this->editingTaskTitleId = $task->id;
    $this->newTitle = $task->title;
}

    public function cancelEditingTitle(): void
    {
        $this->reset('editingTaskTitleId', 'newTitle');
    }

    public function updateTaskTitle(): void
    {
        $this->validate(['newTitle' => 'required|string|min:3']);
        
        $task = Task::findOrFail($this->editingTaskTitleId);
        $task->update(['title' => $this->newTitle]);
        
        $this->project = $this->project->fresh(); 
        $this->cancelEditingTitle();

        if ($this->viewingTask) {
        $this->viewingTask->refresh();
    }
    }

    public function viewTask(string $taskId): void
    {
        $this->viewingTask = Task::findOrFail($taskId);
        $this->isViewTaskModalOpen = true;
    }

    public function closeViewTaskModal(): void
    {
        $this->isViewTaskModalOpen = false;
        $this->viewingTask = null;
    }

    public function startEditingDescription(Task $task): void
{
    $this->editingTaskDescriptionId = $task->id;
    $this->newDescription = $task->description;
}

    public function cancelEditingDescription(): void
    {
        $this->reset('editingTaskDescriptionId', 'newDescription');
    }

    public function updateTaskDescription(): void
    {
        $task = Task::findOrFail($this->editingTaskDescriptionId);
        $task->update(['description' => $this->newDescription]);
        
        $this->viewingTask->refresh();
        $this->cancelEditingDescription();

        if ($this->viewingTask) {
        $this->viewingTask->refresh();
    }
    }

    public function saveAndCloseModal(): void
    {
        // cek apa ada proses edit yang aktif
        if ($this->editingTaskTitleId) {
            $this->updateTaskTitle();
        }
        if ($this->editingTaskDateId) {
            $this->updateTaskDate();
        }
        if ($this->editingTaskDescriptionId) {
            $this->updateTaskDescription();
        }

        $this->closeViewTaskModal();
    }

    public function render()
    {
        return view('livewire.projects.show');
    }
}
