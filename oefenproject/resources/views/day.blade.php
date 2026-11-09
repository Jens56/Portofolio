<x-layout>
    <x-slot:title>
        Mijn dag
    </x-slot:title>

    <div class="max-w-2xl mx-auto ">
        <h1 class="text-3xl font-bold mt-8">Mijn dag</h1>
        <div class="ml-10 card bg-base-100 shadow mt-8 w-360">
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    <div class="form-control w-full">
                        <textarea name="title" placeholder="Titel van de taak"
                            class="flex rounded w-full h-8 font-sans p-1 resize-none mb-3" rows="1" maxlength="30"
                            required></textarea>

                        <textarea name="message" placeholder="Geef een beschrijving"
                            class="flex rounded w-full h-50 p-1 resize-none" rows="4" maxlength="1000"
                            required></textarea>

                        @error('message')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="mt-4 flex items-center justify-end">
                        <button type="submit" class="btn rounded shadow-sm bg-white hover:bg-blue-500 hover:text-white">
                            Plaats
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="space-y-4 mt-8 ml-10 w-360">
            @forelse ($tasks as $task)
                <x-task :task="$task" />
            @empty
                <div class="hero py-12">
                    <div class="hero-content text-center">
                        <div>
                            <p class="mt-4 text-base-content/60">Nog geen taken!</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>