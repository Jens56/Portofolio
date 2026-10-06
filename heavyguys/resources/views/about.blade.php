<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Over ons</h1>
                <p class="text-gray-300 text-sm md:text-base">Leer meer over ons team en onze missie</p>
            </div>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 md:p-8 rounded-xl border border-gray-700 max-w-2xl mx-auto">
                <img src="{{ asset('images/logoZonderwit.png') }}" alt="Logo" class="w-32 h-auto mx-auto mb-6">
                <p class="text-gray-300 text-sm md:text-base mb-4">
                    Wij zijn een bedrijf. Met 4 personen die ieder een eigen taak hebben gehad.
                    3 Gamedevelopers en een webdeveloper. Wij proberen goede en leuke spellen te maken en uit te brengen. 
                    Naast games bestaat er ook een community waar je in contact kan komen met andere gamers via de blog. 
                </p>
            </div>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 md:p-8 rounded-xl border border-gray-700 max-w-2xl mx-auto mt-8">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Ons team</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/Jarno.jpeg') }}" alt="Team Member 1" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h3 class="text-white font-semibold text-lg">Jarno Huizenga</h3>
                            <p class="text-gray-400 text-sm">Projectleider & Game ontwikkelaar</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/JensUlrich.png') }}" alt="Team Member 1" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h3 class="text-white font-semibold text-lg">Jens Ulrich</h3>
                            <p class="text-gray-400 text-sm">Webdeveloper</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/Patricia.png') }}" alt="Team Member 1" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h3 class="text-white font-semibold text-lg">Patricia Faragó</h3>
                            <p class="text-gray-400 text-sm">Game ontwikkelaar</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/AlexDeGroot.jpeg') }}" alt="Team Member 1" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h3 class="text-white font-semibold text-lg">Alex de Groot</h3>
                            <p class="text-gray-400 text-sm">Game ontwikkelaar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>