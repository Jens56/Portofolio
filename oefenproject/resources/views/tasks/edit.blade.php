<x-layout>
    <x-slot:title>
        Plan veranderen
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Plan veranderen</h1>
        <div class="ml-10 card bg-base-100 shadow mt-8 w-360">
            <div class="card-body">
                <form method="POST" action="/tasks/{{ $task->id }}">
                    @csrf
                    @method('PUT')
                    <div class="form-control w-full">
                        <textarea name="title" placeholder="Titel van de taak"
                            class="flex rounded w-full h-8 font-sans p-1 resize-none mb-3" rows="1" maxlength="30"
                            required>{{ old('message', $task->title) }}</textarea>

                        <textarea name="message" placeholder="Geef een beschrijving?"
                            class="flex rounded w-full h-50 p-1 resize-none" rows="4" maxlength="1000"
                            required>{{ old('message', $task->message) }}</textarea>

                        @error('message')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="mt-4 flex items-center justify-end">
                        <a href="/day" class="btn ml-2 bg-white hover:bg-blue-500 hover:text-white btn-md shadow-sm">
                            Cancel
                        </a>
                        <button type="submit"
                            class="btn ml-2 bg-white hover:bg-blue-500 hover:text-white btn-md shadow-sm">
                            Verander
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>