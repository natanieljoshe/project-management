<?php

namespace App\Livewire\Projects;

use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads; 
use Illuminate\Support\Facades\Storage; 

#[Layout('layouts.app')]
class Show extends Component
{   
    use WithFileUploads;

    //untuk upload file
    public $file;
    //menampung data project
    public Project $project;

    //untuk edit project
    public ?string $editingProjectDetail = null;
    public string $newProjectName = '', $newProjectDescription = '', $newProjectDeadline = '', $newProjectStatus = '';


    //untuk task
    public string $title = '';
    public string $description = '';
    public string $deadline = '';
    public string $status = '';
    
    //modal task
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

    //filter dan search
    public string $searchQuery = '';
    public string $filterStatus = '';
    public string $filterDeadline = '';

    //view mode
    public string $viewMode = 'list'; 


    //view mode
    public function setViewMode(string $mode)
    {
        // Pastikan mode yang dipilih valid
        if (in_array($mode, ['list', 'kanban'])) {
            $this->viewMode = $mode;
        }
    }
    
    //project
    public function startEditing(string $field): void
    {
        $this->editingProjectDetail = $field;
        $this->newProjectName = $this->project->name;
        $this->newProjectDescription = $this->project->description;
        $this->newProjectDeadline = $this->project->deadline->format('Y-m-d');
        $this->newProjectStatus = $this->project->status;
    }

    public function cancelEditing(): void
    {
        $this->editingProjectDetail = null;
    }

    public function updateProjectDetail(string $field): void
    {
        $validatedData = [];
        $updatePayload = [];

        // validasi data dan prepare data
        switch ($field) {
            case 'name':
                $validatedData = $this->validate(['newProjectName' => 'required|string|min:3']);
                $updatePayload['name'] = $validatedData['newProjectName'];
                break;
            case 'description':
                $validatedData = $this->validate(['newProjectDescription' => 'nullable|string']);
                $updatePayload['description'] = $validatedData['newProjectDescription'];
                break;
            case 'deadline':
                $validatedData = $this->validate(['newProjectDeadline' => 'required|date']);
                $updatePayload['deadline'] = $validatedData['newProjectDeadline'];
                break;
            case 'status':
                $validatedData = $this->validate(['newProjectStatus' => 'required|string']);
                $updatePayload['status'] = $validatedData['newProjectStatus'];
                break;
        }

        if (!empty($updatePayload)) {
            $this->project->update($updatePayload);
        }

        $this->project->refresh();
        $this->cancelEditing();
    }

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

    public function updatedFile()
{
    //validasi file, tipe file bebas, maks 10MB (10240 KB)
    $this->validate([
        'file' => 'required|file|max:10240', 
    ]);

    // ambil tugas yang sedang dilihat
    $task = $this->viewingTask;
    
    // hapus file lama jika ada
    if ($task->file_path) {
        Storage::disk('public')->delete($task->file_path);
    }
    
    $path = $this->file->store('task-files', 'public');
    
    $task->update(['file_path' => $path]);

    // Refresh data dan reset input file
    $this->viewingTask->refresh();
    $this->reset('file');

    session()->flash('file_message', 'File successfully uploaded.');
}

    public function deleteFile(string $taskId)
    {
        $task = Task::findOrFail($taskId);
        
        // Hapus file dari storage
        Storage::disk('public')->delete($task->file_path);
        
        $task->update(['file_path' => null]);
        
        $this->viewingTask->refresh();
        session()->flash('file_message', 'File successfully removed.');
    }


    public function render()
    {
        $tasksQuery = $this->project->tasks()->orderBy('created_at');

        // filter pencarian
        if ($this->searchQuery) {
            $tasksQuery->where('title', 'like', '%' . $this->searchQuery . '%');
        }

        // filter status
        if ($this->filterStatus) {
            $tasksQuery->where('status', $this->filterStatus);
        }
        
        //filter deadline
        if ($this->filterDeadline) {
            match ($this->filterDeadline) {
                'today' => $tasksQuery->whereDate('deadline', today()),
                'past' => $tasksQuery->whereDate('deadline', '<', today()),
                'future' => $tasksQuery->whereDate('deadline', '>', today()),
                default => null,
            };
        }
        
        $tasks = $tasksQuery->get();

        return view('livewire.projects.show', [
            'tasks' => $tasks,
        ]);
    }
}
