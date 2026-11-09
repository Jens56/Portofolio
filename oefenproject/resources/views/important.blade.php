<x-layout>
    <x-slot:title>
        Belangrijk
    </x-slot:title>
    <div class="max-w-2xl mx-auto">
        <div class="">
            <div class="card-body">
                <div>
                    <h1 class="text-4xl font-bold">Belangrijk</h1>
                    <p class="mt-4 text-base-content/60">Hier zijn al je belangrijke taken</p>
                </div>
            </div>
            <div class="space-y-4 mt-8 ml-10 w-360">
                @forelse ($tasks as $task)
                    <x-task :task="$task" />
                @empty
                    <div class="hero py-12">
                        <div class="hero-content text-center">
                            <div>
                                <p class="mt-4 text-base-content/60">Nog geen belangrijke taken!</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>