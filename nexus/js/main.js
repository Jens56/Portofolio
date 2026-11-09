// =====================================================================
// WEERWEBSITE
// Deze pagina laat het weer van vandaag en de komende dagen zien voor
// een gekozen stad. De gegevens komen uit n8n (via webhooks).
// Je kunt zoeken, filteren en dagen als favoriet markeren.
// =====================================================================

// ---------- De lijst met steden (alleen hier aanpassen) ----------

// Elke stad heeft een naam, een provincie en een positie (latitude = breedtegraad,
// longitude = lengtegraad). De positie sturen we later naar n8n om het weer op te halen.
const cities = [
    { name: 'Groningen',  province: 'Groningen',     latitude: 53.22, longitude: 6.57 },
    { name: 'Leeuwarden', province: 'Friesland',     latitude: 53.20, longitude: 5.80 },
    { name: 'Assen',      province: 'Drenthe',       latitude: 52.99, longitude: 6.56 },
    { name: 'Zwolle',     province: 'Overijssel',    latitude: 52.51, longitude: 6.09 },
    { name: 'Lelystad',   province: 'Flevoland',     latitude: 52.52, longitude: 5.47 },
    { name: 'Arnhem',     province: 'Gelderland',    latitude: 51.98, longitude: 5.91 },
    { name: 'Utrecht',    province: 'Utrecht',       latitude: 52.09, longitude: 5.12 },
    { name: 'Haarlem',    province: 'Noord-Holland', latitude: 52.38, longitude: 4.64 },
    { name: 'Den Haag',   province: 'Zuid-Holland',  latitude: 52.08, longitude: 4.31 },
    { name: 'Middelburg', province: 'Zeeland',       latitude: 51.50, longitude: 3.61 },
    { name: 'Den Bosch',  province: 'Noord-Brabant', latitude: 51.70, longitude: 5.30 },
    { name: 'Maastricht', province: 'Limburg',       latitude: 50.85, longitude: 5.69 }
];

// De n8n webhooks (test URL's). Zet hier later de production URL's neer (/webhook/ in plaats van /webhook-test/).
// Webhook 1: het weer van vandaag en de komende dagen
const WEBHOOK_URL = 'http://localhost:5678/webhook/weather-above-10';
// Webhook 2: de status per dag (Koud / Warm)
const WEBHOOK_URL_2 = 'http://localhost:5678/webhook/weather-above-10-warm-or-cold';

// ---------- Gegevens die de pagina onthoudt ----------

// Dit zijn "globale variabelen": alle functies hieronder kunnen ze lezen en aanpassen.
let allDays = [];    // alle dagen van de gekozen stad (uit n8n)
let favorites = [];  // de ID's van de favoriete dagen (verdwijnt als je de pagina herlaadt)

// ---------- Hulpfuncties ----------

// De gekozen stad uit het keuzemenu ophalen
function getSelectedCity() {
    // In het keuzemenu staat de waarde als "breedtegraad,lengtegraad"
    const value = $('#city-select').val(); // bijvoorbeeld "53.22,6.57"
    // split(',') knipt de tekst bij de komma in twee stukken
    const parts = value.split(',');

    // We geven een object terug met de naam en de twee coördinaten
    return {
        name: $('#city-select option:selected').text(), // de zichtbare tekst van de gekozen optie
        latitude: parts[0],
        longitude: parts[1]
    };
}

// Een icoon kiezen bij de weercode
// (de codes komen van Open-Meteo, bijvoorbeeld 0 = onbewolkt, 95 = onweer)
function getIcon(code) {
    if (code === 0) return '☀️';
    if (code === 1) return '🌤️';
    if (code === 2) return '⛅';
    if (code === 3) return '☁️';
    if (code === 45 || code === 48) return '🌫️';
    if (code >= 51 && code <= 57) return '🌦️';
    if (code >= 61 && code <= 67) return '🌧️';
    if (code >= 71 && code <= 77) return '❄️';
    if (code >= 80 && code <= 82) return '🌧️';
    if (code >= 95 && code <= 99) return '⛈️';
    return '🌡️'; // onbekende code: een standaardicoon
}

// Een tekst kiezen bij de weercode (zelfde codes als hierboven)
function getText(code) {
    if (code === 0) return 'Onbewolkt';
    if (code === 1) return 'Overwegend zonnig';
    if (code === 2) return 'Half bewolkt';
    if (code === 3) return 'Bewolkt';
    if (code === 45 || code === 48) return 'Mist';
    if (code >= 51 && code <= 57) return 'Motregen';
    if (code >= 61 && code <= 67) return 'Regen';
    if (code >= 71 && code <= 77) return 'Sneeuw';
    if (code >= 80 && code <= 82) return 'Buien';
    if (code >= 95 && code <= 99) return 'Onweer';
    return 'Onbekend';
}

// De naam van een dag, bijvoorbeeld "woensdag 11 nov"
function getDayName(day) {
    // De datum uit n8n is tekst; new Date() maakt er een echte datum van
    const date = new Date(day.time);

    // toLocaleDateString zet de datum om naar een Nederlandse tekst
    return date.toLocaleDateString('nl-NL', {
        weekday: 'long',
        day: 'numeric',
        month: 'short'
    });
}

// Het advies voor een dag: kleding + eventueel paraplu en wind
function getAdvice(day) {
    // Eerst proberen we het kledingadvies uit n8n (veld "Kledingadvies").
    // Is dat er niet, dan rekenen we het zelf uit met dezelfde grenzen als in n8n.
    // Webhook 1 (de dag zelf) heeft voorrang, daarna webhook 2
    let clothing = day.Kledingadvies || day.clothing;

    if (!clothing || clothing === '-') {
        const max = Number(day.temperature_2m_max);

        if (max < 10) {
            clothing = 'Jas aan';
        } else if (max < 18) {
            clothing = 'Trui';
        } else {
            clothing = 'T-shirt';
        }
    }

    const tips = [clothing];

    // Regen: een paraplu meenemen
    if (Number(day.precipitation_sum) > 0) {
        tips.push('neem een paraplu mee');
    }

    // Harde wind (30 km/u of meer)
    if (Number(day.wind_speed_10m_max) >= 30) {
        tips.push('het waait hard');
    }

    return tips.join(', ');
}

// Het keuzemenu en de suggesties van de zoekbalk vullen met de steden uit de lijst
function fillCitySelect() {
    for (let i = 0; i < cities.length; i++) {
        const city = cities[i];

        // Een <option> voor het keuzemenu: waarde = coördinaten, tekst = naam
        const option = $('<option>')
            .val(city.latitude + ',' + city.longitude)
            .text(city.name);
        $('#city-select').append(option);
    }
}

// ---------- Zoeken op stad ----------

// Een stad kiezen: het keuzemenu aanpassen en het weer laden
function selectCity(city) {
    // Het keuzemenu springt naar de gevonden stad
    $('#city-select').val(city.latitude + ',' + city.longitude);
    // De melding "stad niet gevonden" verbergen
    $('#city-search-message').addClass('hidden');
    loadWeather();
}

// Zoeken met de knop of Enter: stad of provincie mag een deel van de tekst zijn
function searchCity() {
    // trim() haalt spaties weg aan het begin en einde, toLowerCase() maakt alles klein
    // (zo maakt hoofdletter of kleine letter niet uit)
    const searchText = $('#city-search').val().trim().toLowerCase();

    // Bij een leeg zoekveld doen we niets
    if (searchText === '') {
        return;
    }

    // null betekent: nog niets gevonden
    let found = null;

    for (let i = 0; i < cities.length; i++) {
        // includes() kijkt of de zoektekst ergens in de naam of provincie voorkomt
        const inName = cities[i].name.toLowerCase().includes(searchText);
        const inProvince = cities[i].province.toLowerCase().includes(searchText);

        if (inName || inProvince) {
            found = cities[i];
            break; // de eerste stad die past is genoeg
        }
    }

    // Niets gevonden: de melding tonen. Wel gevonden: die stad kiezen.
    if (found === null) {
        $('#city-search-message').removeClass('hidden');
    } else {
        selectCity(found);
    }
}

// Als de gebruiker een stad uit de suggesties kiest, staat de volledige naam in het veld
// Hier zoeken we dus op een exacte naam (niet op een deel van de tekst)
function searchExactCity() {
    const searchText = $('#city-search').val().trim().toLowerCase();

    for (let i = 0; i < cities.length; i++) {
        if (cities[i].name.toLowerCase() === searchText) {
            selectCity(cities[i]);
            break;
        }
    }
}

// ---------- Data ophalen via n8n (webhook 1: het weer) ----------

function loadWeather() {
    const city = getSelectedCity();

    // De naam van de stad bovenaan de pagina zetten
    $('#city-name').text(city.name);

    // Bij een nieuwe stad beginnen de filters weer leeg (de favorieten blijven)
    resetControls();

    // "Laden..." tonen en de fout en de inhoud verbergen, voor "Vandaag"
    $('#today-loading').removeClass('hidden');
    $('#today-error').addClass('hidden');
    $('#today-content').addClass('hidden');

    // Hetzelfde voor "Komende dagen"
    $('#forecast-loading').removeClass('hidden');
    $('#forecast-error').addClass('hidden');
    $('#forecast-content').addClass('hidden');

    // Een verzoek (GET) sturen naar n8n, met de coördinaten van de stad
    $.ajax({
        url: WEBHOOK_URL,
        method: 'GET',
        data: {
            latitude: city.latitude,
            longitude: city.longitude
        },
        dataType: 'json',
        // success: wordt uitgevoerd als n8n antwoord geeft
        success: function (data) {
            console.log('Antwoord van n8n:', data); // kijk hier of de velden kloppen

            // n8n kan een lijst of één object terugsturen
            // We maken er altijd een lijst van, zodat de rest van de code hetzelfde werkt
            const items = Array.isArray(data) ? data : [data];

            // try/catch: gaat er iets mis met de gegevens, dan tonen we een foutmelding
            try {
                showToday(items);

                // Alle dagen onthouden, met een ID (stad + datum) en een status
                allDays = getDays(items);

                for (let i = 0; i < allDays.length; i++) {
                    // Het ID wordt gebruikt om favorieten te onthouden, bijvoorbeeld "Utrecht|2026-11-09"
                    allDays[i].id = city.name + '|' + String(allDays[i].time).slice(0, 10);
                    // Tijdelijke status, tot webhook 2 antwoord geeft
                    allDays[i].status = 'laden...';
                }

                // "Laden..." verbergen en de dagen tonen
                $('#forecast-loading').addClass('hidden');
                $('#forecast-content').removeClass('hidden');
                updateResults();

                loadStatus(city); // daarna de status (Koud / Warm) ophalen
            } catch (error) {
                console.error(error);
                showErrors();
            }
        },
        // error: wordt uitgevoerd als n8n niet bereikbaar is of een fout geeft
        error: function (error) {
            console.error(error);
            showErrors();
        }
    });
}

// De foutmeldingen tonen (en "laden..." verbergen)
function showErrors() {
    $('#today-loading').addClass('hidden');
    $('#today-error').removeClass('hidden');
    $('#forecast-loading').addClass('hidden');
    $('#forecast-error').removeClass('hidden');
}

// Maakt van de n8n-items een lijst met dagen
// (werkt als "daily" per dag één waarde heeft, of lijsten met waarden)
function getDays(items) {
    const days = [];

    for (let i = 0; i < items.length; i++) {
        // Staat alles in "daily", dan gebruiken we dat. Anders het item zelf.
        const daily = items[i].daily || items[i];

        if (Array.isArray(daily.time)) {
            // Geval 1: lijsten met waarden (bijvoorbeeld time = [dag1, dag2, ...]).
            // De waarde op plek j in elke lijst hoort bij dezelfde dag.
            for (let j = 0; j < daily.time.length; j++) {
                days.push({
                    time: daily.time[j],
                    // Sommige velden zijn niet altijd aanwezig: dan wordt het undefined
                    weather_code: daily.weather_code ? daily.weather_code[j] : undefined,
                    temperature_2m_max: daily.temperature_2m_max[j],
                    temperature_2m_min: daily.temperature_2m_min[j],
                    precipitation_sum: daily.precipitation_sum[j],
                    wind_speed_10m_max: daily.wind_speed_10m_max ? daily.wind_speed_10m_max[j] : undefined
                });
            }
        } else {
            // Geval 2: één item is al één dag
            // Het kledingadvies uit n8n staat op het item zelf: dat nemen we mee
            if (items[i].Kledingadvies !== undefined) {
                daily.Kledingadvies = items[i].Kledingadvies;
            }
            days.push(daily);
        }
    }

    return days;
}

// ---------- Vandaag ----------

// De gegevens van vandaag op het scherm zetten
function showToday(items) {
    const current = items[0].current; // het weer op dit moment
    const days = getDays(items);
    const today = days[0];            // de eerste dag is vandaag

    // Het huidige weer
    $('#today-icon').text(getIcon(current.weather_code));
    $('#today-text').text(getText(current.weather_code));
    $('#today-temp').text(current.temperature_2m + ' °C');
    $('#today-rain').text(current.precipitation + ' mm');
    $('#today-wind').text(current.wind_speed_10m + ' km/h');
    // De verwachting voor de hele dag
    $('#today-max').text(today.temperature_2m_max + ' °C');
    $('#today-min').text(today.temperature_2m_min + ' °C');
    $('#today-rain-total').text(today.precipitation_sum + ' mm');

    // De status (Koud / Warm) wordt later ingevuld, zodra webhook 2 antwoord geeft
    // We bewaren de datum in een data-attribuut, zodat we later weten bij welke dag de status hoort
    $('#today-status').attr('data-date', String(today.time).slice(0, 10)).text('laden...');

    // Het advies voor vandaag (wordt na webhook 2 nog bijgewerkt met het kledingadvies uit n8n)
    $('#today-advice').text(getAdvice(today));

    // "Laden..." verbergen en de inhoud tonen
    $('#today-loading').addClass('hidden');
    $('#today-content').removeClass('hidden');
}

// ---------- Komende dagen: zoeken, filters en favorieten ----------

// Eén functie die alles samenvoegt: zoeken + 3 filters + favorieten
// Elke keer dat iets verandert, begint deze functie opnieuw met alle dagen
// en haalt er stap voor stap dagen uit die niet passen.
function updateResults() {
    // Eerst alle ingevulde waarden uit het scherm ophalen
    const searchText = $('#filter-search').val().trim().toLowerCase();
    const rain = $('#filter-rain').val();
    const temp = $('#filter-temp').val();
    const status = $('#filter-status').val();
    const onlyFavorites = $('#favorites-only').is(':checked');

    let results = allDays.slice(); // een kopie, zodat allDays zelf heel blijft

    // Zoeken op dagnaam, weertype en status
    if (searchText !== '') {
        // filter() houdt alleen de dagen over waarvoor de functie "true" teruggeeft
        results = results.filter(function (day) {
            const text = getDayName(day) + ' ' + getText(day.weather_code) + ' ' + day.status + ' ' + getAdvice(day);
            return text.toLowerCase().includes(searchText);
        });
    }

    // Filter 1: neerslag
    if (rain === 'dry') {
        // droog = precies 0 mm
        results = results.filter(function (day) {
            return Number(day.precipitation_sum) === 0;
        });
    } else if (rain === 'wet') {
        // nat = meer dan 0 mm
        results = results.filter(function (day) {
            return Number(day.precipitation_sum) > 0;
        });
    }

    // Filter 2: maximale temperatuur
    // Number() zet tekst om naar een getal, zodat we kunnen vergelijken
    if (temp === 'low') {
        // laag: onder de 5 graden
        results = results.filter(function (day) {
            return Number(day.temperature_2m_max) < 5;
        });
    } else if (temp === 'mid') {
        // gemiddeld: van 5 tot en met 10 graden
        results = results.filter(function (day) {
            return Number(day.temperature_2m_max) >= 5 && Number(day.temperature_2m_max) <= 10;
        });
    } else if (temp === 'high') {
        // hoog: boven de 10 graden
        results = results.filter(function (day) {
            return Number(day.temperature_2m_max) > 10;
        });
    }

    // Filter 3: status (Koud / Warm uit webhook 2)
    if (status !== '') {
        results = results.filter(function (day) {
            return String(day.status).toLowerCase() === status;
        });
    }

    // Alleen favorieten
    if (onlyFavorites) {
        results = results.filter(function (day) {
            return favorites.includes(day.id);
        });
    }

    // Wat overblijft, zetten we op het scherm
    showDays(results);
}

// De dagen op het scherm zetten
function showDays(days) {
    // Eerst de oude kaarten weghalen
    $('#forecast-days').empty();

    // Het sjabloon (template) uit de HTML: een lege dagkaart die we voor elke dag kopiëren
    const templateHtml = $('#day-template').html().trim();

    // Geen dagen over na het zoeken/filteren: een melding tonen
    if (days.length === 0) {
        $('#forecast-days').text('Geen dagen gevonden met deze zoekopdracht en filters.');
    }

    for (let i = 0; i < days.length; i++) {
        const item = days[i];
        const isFavorite = favorites.includes(item.id);

        // Een nieuwe kaart maken van het sjabloon en alle gegevens invullen
        const day = $(templateHtml);
        day.attr('data-id', item.id); // het ID onthouden, voor de favoriet-knop
        day.find('.day-name').text(getDayName(item));
        day.find('.day-icon').text(getIcon(item.weather_code));
        day.find('.day-text').text(getText(item.weather_code));
        day.find('.day-temp').text(item.temperature_2m_max + '° / ' + item.temperature_2m_min + '°');
        day.find('.day-rain').text('Neerslag: ' + item.precipitation_sum + ' mm');
        day.find('.day-wind').text('Wind: ' + item.wind_speed_10m_max + ' km/h');
        day.find('.day-status').text('Status: ' + item.status);
        day.find('.day-advice').text('Advies: ' + getAdvice(item));

        // Een gevulde ster (★) als de dag favoriet is, anders een lege ster (☆)
        day.find('.favorite-button')
            .text(isFavorite ? '★' : '☆')
            .attr('aria-label', isFavorite ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten');

        // De kaart toevoegen aan de pagina
        $('#forecast-days').append(day);
    }
}

// Een dag toevoegen aan of verwijderen uit de favorieten
function toggleFavorite(id) {
    if (favorites.includes(id)) {
        // Staat de dag er al in? Dan halen we hem eruit
        favorites = favorites.filter(function (favoriteId) {
            return favoriteId !== id;
        });
    } else {
        // Staat de dag er nog niet in? Dan voegen we hem toe
        favorites.push(id);
    }

    // De lijst opnieuw tonen, zodat de ster verandert
    updateResults();
}

// Alle zoek- en filtervelden terugzetten naar de beginwaarde (zonder iets op te halen)
function resetControls() {
    $('#filter-search').val('');
    $('#filter-rain').val('');
    $('#filter-temp').val('');
    $('#filter-status').val('');
    $('#favorites-only').prop('checked', false);
}

// De knop "Reset filters": velden leeg en alle dagen weer tonen, zonder opnieuw te laden
function resetFilters() {
    resetControls();
    updateResults();
}

// ---------- Status Koud / Warm (webhook 2) ----------

// Een waarde uit een item halen. n8n kan de naam met een punt bewaren
// ("daily.time"), als geneste data (daily.time) of gewoon als "time".
// Deze functie probeert alle drie de manieren, zodat het altijd werkt.
function pick(item, name) {
    if (item['daily.' + name] !== undefined) return item['daily.' + name];
    if (item.daily && item.daily[name] !== undefined) return item.daily[name];
    return item[name];
}

// De status (Koud / Warm) ophalen bij de tweede webhook
function loadStatus(city) {
    $.ajax({
        url: WEBHOOK_URL_2,
        method: 'GET',
        data: {
            latitude: city.latitude,
            longitude: city.longitude
        },
        dataType: 'json',
        success: function (data) {
            console.log('Antwoord van tweede webhook:', data);

            // Weer: altijd een lijst van maken
            const items = Array.isArray(data) ? data : [data];

            // Een lijstje maken: datum -> status
            // Bijvoorbeeld { "2026-11-09": "Koud", "2026-11-10": "Warm" }
            const statusByDate = {};
            // Nog een lijstje: datum -> kledingadvies uit n8n
            const clothingByDate = {};

            for (let i = 0; i < items.length; i++) {
                const time = pick(items[i], 'time');

                // Het nieuwe veld uit n8n (Koud / Warm).
                // Heet het veld in jouw Edit Fields-nodes anders? Pas dan hier de naam aan.
                const status = items[i].temperatureCategory;

                // Het kledingadvies uit n8n (veld "Kledingadvies" uit de Edit Fields-node)
                if (time !== undefined && items[i].Kledingadvies !== undefined) {
                    clothingByDate[String(time).slice(0, 10)] = items[i].Kledingadvies;
                }

                // Alleen opslaan als zowel de datum als de status bestaat
                if (time !== undefined && status !== undefined) {
                    // slice(0, 10) pakt alleen de datum (jjjj-mm-dd), zonder de tijd
                    statusByDate[String(time).slice(0, 10)] = status;
                }
            }

            showStatus(statusByDate, clothingByDate);
        },
        error: function (error) {
            console.error(error);
            showStatus({}, {}); // zonder antwoord tonen we een streepje
        }
    });
}

// De status bij elke dag zetten en daarna de resultaten opnieuw tonen
function showStatus(statusByDate, clothingByDate) {
    for (let i = 0; i < allDays.length; i++) {
        const date = String(allDays[i].time).slice(0, 10);
        // Geen status gevonden voor deze dag? Dan een streepje
        allDays[i].status = statusByDate[date] || '-';
        // Het kledingadvies uit n8n (ontbreekt het? Dan rekent getAdvice() het zelf uit)
        allDays[i].clothing = clothingByDate[date];
    }

    // Opnieuw tonen, zodat de kaarten de echte status laten zien
    updateResults();

    // En de status bij "Vandaag" (zelfde dag als de max. en min. temperatuur daar)
    const todayDate = $('#today-status').attr('data-date');
    $('#today-status').text(statusByDate[todayDate] || '-');

    // En het advies bij "Vandaag" bijwerken
    const todayDay = allDays.find(function (day) {
        return String(day.time).slice(0, 10) === todayDate;
    });

    if (todayDay) {
        $('#today-advice').text(getAdvice(todayDay));
    }
}

// ================= STARTEN =================

// $(document).ready: deze code start pas als de pagina helemaal geladen is
$(document).ready(function () {
    fillCitySelect(); // eerst het keuzemenu vullen
    loadWeather();    // daarna het weer van de eerste stad laden

    // Kiezen uit het menu: de zoekbalk en de melding worden leeggemaakt
    $('#city-select').on('change', function () {
        $('#city-search').val('');
        $('#city-search-message').addClass('hidden');
        loadWeather();
    });

    // Zoeken met de knop, met Enter, of door een stad uit de suggesties te kiezen
    $('#city-search-button').on('click', searchCity);
    $('#city-search').on('keyup', function (event) {
        if (event.key === 'Enter') {
            searchCity();
        }
    });
    $('#city-search').on('input', searchExactCity);

    // "Opnieuw proberen"-knoppen bij een foutmelding
    $('#today-retry').on('click', loadWeather);
    $('#forecast-retry').on('click', loadWeather);

    // Zoeken, filters en "alleen favorieten": allemaal via updateResults()
    $('#filter-search').on('input', updateResults);
    $('#filter-rain').on('change', updateResults);
    $('#filter-temp').on('change', updateResults);
    $('#filter-status').on('change', updateResults);
    $('#favorites-only').on('change', updateResults);

    // Reset filters
    $('#reset-filters').on('click', resetFilters);

    // Favoriet-knop (☆ / ★) op elke dagkaart
    // We luisteren op #forecast-days (en niet op de knop zelf), omdat de kaarten
    // pas later worden aangemaakt. Zo werkt de knop ook bij nieuwe kaarten.
    $('#forecast-days').on('click', '.favorite-button', function () {
        // Zoek de kaart waar de knop in zit en lees het ID van die kaart
        const id = $(this).closest('[data-id]').attr('data-id');
        toggleFavorite(id);
    });
});