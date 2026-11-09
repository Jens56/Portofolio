@props(['task'])
<div class="space-y-4 mt-8">
    <div class="card bg-base-100 shadow">
        <a href="/tasks/{{ $task->id }}/edit"
            class="card-content body p-5 block hover:shadow-sm hover:bg-white hover:border-2 hover:border-blue-500">
            <h3 class="font-semibold text-lg">{{ $task->title }}</h3>
            <p class="mt-1 text-base-content/80 break-words">{{ $task->message }}</p>
            @can('update', $task)
                <form method="POST" class="flex justify-end" action="{{ route('tasks.destroy', $task) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this task?')"
                        class="btn rounded shadow-sm bg-white hover:bg-blue-500 hover:text-white">
                        Verwijderen
                    </button>
                </form>
            @endcan
            <form action="{{ route('important.toggleImportant', $task) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="checkbox" class="checkbox shadow-sm text-blue-500" name="important" onchange="this.form.submit()" {{ $task->important ? 'checked' : '' }}> Belangrijk
            </form>
        </a>
    </div>
</div>