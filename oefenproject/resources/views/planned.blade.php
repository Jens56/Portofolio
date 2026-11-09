<x-layout>
    <x-slot:title>
        Gepland
    </x-slot:title>

    <div class="max-w-2xl mx-auto ">
        <h1 class="text-3xl font-bold mt-8">Gepland</h1>
        <div class="w-360 ml-10">
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