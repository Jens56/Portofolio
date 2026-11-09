<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
    <script src="js/main.js" defer></script>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">
    <main class="max-w-6xl mx-auto p-4 md:p-8">
        <h1 class="text-3xl font-bold mb-6">Weerbericht</h1>

        <section class="bg-white rounded-2xl shadow-md p-6 md:p-8">

            <h2 class="text-2xl font-bold mb-4">Weer in <span id="city-name"></span></h2>

            <!-- Zoeken op stad of kiezen uit het menu -->
            <div class="grid gap-4 sm:grid-cols-2 mb-2">
                <div>
                    <label for="city-search" class="block text-sm text-gray-600 mb-1">Zoek een hoofdstad/provincie:</label>
                    <div class="flex gap-2">
                        <input id="city-search" type="text" list="city-options" placeholder="Bijvoorbeeld Zwolle..."
                               class="w-full rounded-lg border border-gray-300 px-3 py-2">
                        <button id="city-search-button"
                                class="rounded-lg bg-teal-600 px-4 py-2 font-semibold text-white hover:bg-teal-700">
                            Zoeken
                        </button>
                    </div>
                    <!-- De suggesties worden door main.js ingevuld -->
                    <datalist id="city-options"></datalist>
                </div>

                <div>
                    <label for="city-select" class="block text-sm text-gray-600 mb-1">Of kies een hoofdstad:</label>
                    <!-- De opties worden door main.js ingevuld -->
                    <select id="city-select" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2"></select>
                </div>
            </div>

            <!-- Melding als de stad niet bestaat -->
            <p id="city-search-message" class="hidden rounded-lg bg-yellow-100 text-yellow-800 p-3 mb-4">
                Stad niet gevonden. Kies een provinciehoofdstad, bijvoorbeeld Zwolle.
            </p>

            <div class="mb-6"></div>

            <!-- VANDAAG -->
            <p id="today-loading" class="text-gray-600">Weerdata wordt opgehaald...</p>

            <div id="today-error" class="hidden rounded-lg bg-red-100 text-red-700 p-4" role="alert">
                <p>Ophalen van het weer is mislukt. Bekijk de console.</p>
                <button id="today-retry" class="mt-2 font-semibold text-teal-700 underline">
                    Opnieuw proberen
                </button>
            </div>

            <div id="today-content" class="hidden">
                <h3 class="text-lg font-semibold">Vandaag</h3>

                <p class="flex items-center gap-2 font-semibold my-3">
                    <span id="today-icon" class="text-4xl"></span>
                    <span id="today-text"></span>
                </p>

                <div class="flex flex-wrap gap-4 mt-3">
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Temperatuur</span>
                        <span id="today-temp" class="text-2xl font-bold text-teal-600"></span>
                    </div>
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Neerslag</span>
                        <span id="today-rain" class="text-2xl font-bold text-teal-600"></span>
                    </div>
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Windsnelheid</span>
                        <span id="today-wind" class="text-2xl font-bold text-teal-600"></span>
                    </div>
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Max. temperatuur</span>
                        <span id="today-max" class="text-2xl font-bold text-teal-600"></span>
                    </div>
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Min. temperatuur</span>
                        <span id="today-min" class="text-2xl font-bold text-teal-600"></span>
                    </div>
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Neerslag vandaag</span>
                        <span id="today-rain-total" class="text-2xl font-bold text-teal-600"></span>
                    </div>
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Status</span>
                        <span id="today-status" class="text-2xl font-bold text-teal-600"></span>
                    </div>
                    <!-- Advies voor vandaag (wordt door main.js ingevuld) -->
                    <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
                        <span class="text-sm text-gray-600">Advies</span>
                        <span id="today-advice" class="text-lg font-bold text-teal-600"></span>
                    </div>
                </div>
            </div>

            <!-- KOMENDE DAGEN -->
            <h2 class="text-2xl font-bold mt-10 mb-4">Weer komende dagen</h2>

            <p id="forecast-loading" class="text-gray-600">Voorspelling wordt opgehaald...</p>

            <div id="forecast-error" class="hidden rounded-lg bg-red-100 text-red-700 p-4" role="alert">
                <p>Ophalen van de voorspelling is mislukt.</p>
                <button id="forecast-retry" class="mt-2 font-semibold text-teal-700 underline">
                    Opnieuw proberen
                </button>
            </div>

            <div id="forecast-content" class="hidden">

                <!-- Zoeken en filters voor de dagen -->
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4 mb-4">
                    <div>
                        <label for="filter-search" class="block text-sm text-gray-600 mb-1">Zoek in de dagen:</label>
                        <input id="filter-search" type="text" placeholder="Bijvoorbeeld bewolkt of paraplu..."
                               class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label for="filter-rain" class="block text-sm text-gray-600 mb-1">Neerslag:</label>
                        <select id="filter-rain" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2">
                            <option value="">Alle</option>
                            <option value="dry">Droog (0 mm)</option>
                            <option value="wet">Nat (meer dan 0 mm)</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-temp" class="block text-sm text-gray-600 mb-1">Max. temperatuur:</label>
                        <select id="filter-temp" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2">
                            <option value="">Alle</option>
                            <option value="low">Onder 5 °C</option>
                            <option value="mid">5 t/m 10 °C</option>
                            <option value="high">Boven 10 °C</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-status" class="block text-sm text-gray-600 mb-1">Status:</label>
                        <select id="filter-status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2">
                            <option value="">Alle</option>
                            <option value="koud">Koud</option>
                            <option value="warm">Warm</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <label class="flex items-center gap-2 text-sm">
                        <input id="favorites-only" type="checkbox">
                        Alleen favorieten
                    </label>

                    <button id="reset-filters" type="button"
                            class="rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-800 hover:bg-gray-300">
                        Reset filters
                    </button>

                    <span id="result-count" class="text-sm text-gray-600"></span>
                </div>

                <div id="forecast-days" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5"></div>
            </div>

        </section>
    </main>

    <!-- Voorbeeld van één dag. De JavaScript kopieert dit voor elke dag. -->
    <template id="day-template">
        <div class="flex flex-col gap-1 rounded-xl border border-teal-100 bg-teal-50 px-5 py-4">
            <div class="flex items-start justify-between gap-2">
                <strong class="day-name"></strong>
                <button type="button" class="favorite-button text-2xl leading-none text-yellow-500"></button>
            </div>
            <span class="day-icon text-4xl my-1"></span>
            <span class="day-text text-sm text-gray-600"></span>
            <span class="day-temp text-2xl font-bold text-teal-600"></span>
            <span class="day-rain text-sm"></span>
            <span class="day-wind text-sm"></span>
            <span class="day-status text-sm font-semibold"></span>
            <!-- Nieuw: het advies (kleding, paraplu, wind) -->
            <span class="day-advice text-sm font-semibold text-teal-700"></span>
        </div>
    </template>
</body>
</html>