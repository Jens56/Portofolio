<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\Controllers\ExportController;
    use App\Controllers\UploadController;

    session_start();

    function formatSize(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / (1024 * 1024), 1, ',', '.') . ' MB';
        }

        return number_format($bytes / 1024, 1, ',', '.') . ' KB';
    }

    $controller = new UploadController();
    $exportController = new ExportController($controller);

    if (isset($_GET['thumbnail'])) {
        $controller->showThumbnail($_GET['thumbnail']);
    }

    if (isset($_GET['resized'])) {
        $controller->showResized($_GET['resized']);
    }

    if (isset($_GET['file'])) {
        $controller->showFile($_GET['file']);
    }

    if (isset($_GET['export'])) {
        $exportController->showExport($_GET['export'], isset($_GET['download']));
    }

    // Het exportformulier gaat naar ExportController, de rest naar UploadController.
    $result = ($_POST['action'] ?? '') === 'export'
        ? $exportController->handle()
        : $controller->handle();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['flash'] = $result;
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '#' . $result['section']);
        exit;
    }

    $flash = $_SESSION['flash'] ?? ['errors' => [], 'messages' => [], 'section' => 'upload'];
    unset($_SESSION['flash']);

    $flashSection = $flash['section'] ?? 'upload';
    $errors = $flash['errors'];
    $messages = $flash['messages'];
    $allFiles = $controller->getFiles();
    $exports = $exportController->getExports();

    $stats = $controller->getStats();
    $stats['exports'] = count($exports);

    $pagination = $controller->paginate($allFiles, (int) ($_GET['page'] ?? 1), 5);

    $files = $pagination['files'];
    $page = $pagination['page'];
    $totalPages = $pagination['totalPages'];
?>

<!doctype html>
<html lang="nl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>DocFlow</title>

    <!-- Tailwind via CDN: geen build nodig, geen eigen CSS-bestand nodig -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#f4f8fc] text-[#17324d] font-sans antialiased">

    <!-- ===== Navbar ===== -->
    <nav class="fixed inset-x-0 top-0 z-50 flex items-center gap-4 sm:gap-8 px-4 sm:px-6 py-3 bg-[#0d3b66] text-white shadow-lg shadow-[#0d3b66]/20 overflow-x-auto">
        <div class="flex items-center gap-3 shrink-0">
            <div class="grid place-items-center w-10 h-10 rounded-[10px] bg-[#2f7dd1]">▰</div>
            <strong class="text-xl sm:text-2xl">Doc<span class="text-[#7fb3e6]">Flow</span></strong>
        </div>

        <div class="flex items-center gap-1.5 shrink-0">
            <a href="#dashboard" class="px-3 py-2 rounded-lg font-bold text-[#dbe9f6] hover:bg-[#1479da] hover:text-white whitespace-nowrap">Dashboard</a>
            <a href="#files" class="px-3 py-2 rounded-lg font-bold text-[#dbe9f6] hover:bg-[#1479da] hover:text-white whitespace-nowrap">Bestanden</a>
            <a href="#upload" class="px-3 py-2 rounded-lg font-bold text-[#dbe9f6] hover:bg-[#1479da] hover:text-white whitespace-nowrap">Upload</a>
            <a href="#exports" class="px-3 py-2 rounded-lg font-bold text-[#dbe9f6] hover:bg-[#1479da] hover:text-white whitespace-nowrap">Exports</a>
        </div>

        <div class="ml-auto hidden md:flex items-center gap-3">
            <input type="search" placeholder="Zoeken..."
                   class="w-64 px-4 py-2 rounded-full text-sm text-[#17324d] bg-white border border-[#dce7f2] focus:outline-none focus:ring-2 focus:ring-[#2f7dd1]">
            <div class="grid place-items-center w-9 h-9 rounded-full bg-[#2f7dd1] font-bold text-sm">SD</div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-8 pt-24 pb-16 space-y-8">

        <!-- ===== Dashboard ===== -->
        <section id="dashboard" class="scroll-mt-24">
            <p class="text-xs font-extrabold tracking-[0.15em] text-[#2f7dd1]">DOCFLOW</p>
            <h1 class="mt-1 mb-2 text-3xl sm:text-4xl font-bold">Dashboard</h1>
            <p class="mb-6 text-[#6f8295]">Overzicht van je geüploade bestanden.</p>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <article class="p-5 bg-white border border-[#dce7f2] rounded-2xl shadow-lg shadow-[#0d3b66]/5">
                    <strong class="block text-3xl text-[#0d3b66]"><?= $stats['files'] ?></strong>
                    <span class="text-[#6f8295]">Bestanden</span>
                </article>
                <article class="p-5 bg-white border border-[#dce7f2] rounded-2xl shadow-lg shadow-[#0d3b66]/5">
                    <strong class="block text-3xl text-[#0d3b66]"><?= $stats['resized'] ?></strong>
                    <span class="text-[#6f8295]">Aangepaste afbeeldingen</span>
                </article>
                <article class="p-5 bg-white border border-[#dce7f2] rounded-2xl shadow-lg shadow-[#0d3b66]/5">
                    <strong class="block text-3xl text-[#0d3b66]"><?= formatSize($stats['bytes']) ?></strong>
                    <span class="text-[#6f8295]">Opslag</span>
                </article>
                <article class="p-5 bg-white border border-[#dce7f2] rounded-2xl shadow-lg shadow-[#0d3b66]/5">
                    <strong class="block text-3xl text-[#0d3b66]"><?= $stats['exports'] ?></strong>
                    <span class="text-[#6f8295]">Exports</span>
                </article>
            </div>
        </section>

        <!-- ===== Bestanden ===== -->
        <section id="files" class="scroll-mt-24 p-5 sm:p-6 bg-white border border-[#dce7f2] rounded-2xl shadow-lg shadow-[#0d3b66]/5">
            <p class="text-xs font-extrabold tracking-[0.15em] text-[#2f7dd1]">BESTANDEN</p>
            <h2 class="mt-1 mb-4 text-2xl font-bold">Recente bestanden</h2>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-left border-collapse">
                    <thead>
                        <tr class="text-xs uppercase text-[#6f8295]">
                            <th class="px-2.5 py-3 border-b border-[#edf2f7]">Preview</th>
                            <th class="px-2.5 py-3 border-b border-[#edf2f7]">Naam</th>
                            <th class="px-2.5 py-3 border-b border-[#edf2f7]">Type</th>
                            <th class="px-2.5 py-3 border-b border-[#edf2f7]">Grootte</th>
                            <th class="px-2.5 py-3 border-b border-[#edf2f7]">Gewijzigd</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($files)): ?>
                            <tr>
                                <td colspan="5" class="px-2.5 py-8 text-center text-[#6f8295]">Er zijn nog geen bestanden geüpload.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($files as $file): ?>
                            <?php
                                // Naam en preview openen de verkleinde afbeelding als die bestaat,
                                // anders het originele bestand.
                                $mainLink = $file['resized_name'] !== null
                                    ? '?resized=' . urlencode($file['resized_name'])
                                    : '?file=' . urlencode($file['name']);
                            ?>
                            <tr class="hover:bg-[#f9fcff]">
                                <td class="w-16 px-2.5 py-3 border-b border-[#edf2f7]">
                                    <?php if ($file['has_thumbnail']): ?>
                                        <a href="<?= $mainLink ?>" target="_blank" rel="noopener">
                                            <img src="?thumbnail=<?= urlencode($file['name']) ?>"
                                                 alt="Preview van <?= htmlspecialchars($file['display_name']) ?>" loading="lazy"
                                                 class="block w-12 h-12 object-cover rounded-lg border border-[#dce7f2] hover:border-[#2f7dd1]">
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xl text-[#9aabba]">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-2.5 py-3 border-b border-[#edf2f7] break-all">
                                    <a href="<?= $mainLink ?>" target="_blank" rel="noopener"
                                       class="font-bold text-[#2f7dd1] hover:underline"><?= htmlspecialchars($file['display_name']) ?></a>

                                    <?php if ($file['resized_name'] !== null && $file['resized_name'] !== $file['name']): ?>
                                        <p class="mt-0.5 text-xs text-[#6f8295]">
                                            Origineel:
                                            <a href="?file=<?= urlencode($file['name']) ?>" target="_blank" rel="noopener"
                                               class="hover:underline"><?= htmlspecialchars($file['name']) ?></a>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-2.5 py-3 border-b border-[#edf2f7]">
                                    <?php if (in_array($file['extension'], ['jpg', 'jpeg', 'png'], true)): ?>
                                        <span class="inline-grid place-items-center w-9 h-9 rounded-lg text-lg bg-[#e5f5ee] text-[#147a4b]" title="Afbeelding">▧</span>
                                    <?php elseif ($file['extension'] === 'pdf'): ?>
                                        <span class="inline-grid place-items-center w-9 h-9 rounded-lg text-[10px] font-extrabold bg-[#fde8e7] text-[#b42318]" title="PDF">PDF</span>
                                    <?php else: ?>
                                        <span class="inline-grid place-items-center w-9 h-9 rounded-lg text-lg bg-[#eef2f6] text-[#526579]" title="Bestand">▤</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-2.5 py-3 border-b border-[#edf2f7] whitespace-nowrap"><?= number_format($file['size'] / 1024, 1, ',', '.') ?> KB</td>
                                <td class="px-2.5 py-3 border-b border-[#edf2f7] whitespace-nowrap"><?= date('d-m-Y H:i', $file['modified']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="flex flex-wrap items-center justify-end gap-2 mt-5" aria-label="Pagina-navigatie">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" aria-label="Vorige pagina"
                           class="inline-flex items-center justify-center min-w-[38px] h-[38px] rounded-full border border-[#dce7f2] bg-white font-bold hover:border-[#2f7dd1] hover:text-[#2f7dd1]">&laquo;</a>
                    <?php else: ?>
                        <span aria-hidden="true"
                              class="inline-flex items-center justify-center min-w-[38px] h-[38px] rounded-full border border-[#dce7f2] bg-[#f3f7fb] text-[#8aa0b7] font-bold pointer-events-none">&laquo;</span>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $page): ?>
                            <span aria-current="page"
                                  class="inline-flex items-center justify-center min-w-[38px] h-[38px] rounded-full border border-[#2f7dd1] bg-[#2f7dd1] text-white font-bold"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>"
                               class="inline-flex items-center justify-center min-w-[38px] h-[38px] rounded-full border border-[#dce7f2] bg-white font-bold hover:border-[#2f7dd1] hover:text-[#2f7dd1]"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>" aria-label="Volgende pagina"
                           class="inline-flex items-center justify-center min-w-[38px] h-[38px] rounded-full border border-[#dce7f2] bg-white font-bold hover:border-[#2f7dd1] hover:text-[#2f7dd1]">&raquo;</a>
                    <?php else: ?>
                        <span aria-hidden="true"
                              class="inline-flex items-center justify-center min-w-[38px] h-[38px] rounded-full border border-[#dce7f2] bg-[#f3f7fb] text-[#8aa0b7] font-bold pointer-events-none">&raquo;</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- ===== Upload ===== -->
        <section id="upload" class="scroll-mt-24 p-5 sm:p-6 bg-white border border-[#dce7f2] rounded-2xl shadow-lg shadow-[#0d3b66]/5">
            <p class="text-xs font-extrabold tracking-[0.15em] text-[#2f7dd1]">UPLOAD</p>
            <h2 class="mt-1 mb-4 text-2xl font-bold">Bestand uploaden</h2>

            <div class="mb-5 p-4 rounded-xl bg-[#e8f4fd] border border-[#b6dcf6] text-[#0c4a6e]">
                <p>
                    ℹ️ Selecteer een bestand om te uploaden (JPG, PNG of PDF, maximaal 2 MB).<br>
                    Van een afbeelding wordt ook een verkleinde versie gemaakt. Het originele bestand blijft ongewijzigd.
                </p>
            </div>

            <!-- meldingen na het uploaden -->
            <?php if ($flashSection === 'upload'): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="mb-3 p-4 rounded-xl bg-[#fde8e7] border border-[#f5b5b0] text-[#b42318]"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>

                <?php foreach ($messages as $message): ?>
                    <div class="mb-3 p-4 rounded-xl bg-[#e7f5e9] border border-[#a7d9b0] text-[#14633a]"><?= htmlspecialchars($message) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" action="" class="space-y-4">
                <input type="hidden" name="action" value="upload">

                <label class="block">
                    <span class="block mb-1 font-bold">Bestand</span>
                    <input type="file" name="document" required
                           class="block w-full text-sm bg-white border border-[#dce7f2] rounded-lg cursor-pointer
                                  file:mr-4 file:py-2.5 file:px-4 file:border-0 file:bg-[#2f7dd1] file:text-white file:font-bold file:cursor-pointer hover:file:bg-[#1479da]">
                </label>

                <label class="block">
                    <span class="block mb-1 font-bold">Omschrijving</span>
                    <input type="text" name="description" maxlength="120"
                           class="block w-full px-3 py-2.5 bg-white border border-[#dce7f2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2f7dd1]">
                </label>

                <fieldset class="p-4 border border-[#dce7f2] rounded-xl space-y-4">
                    <legend class="px-2 font-bold">Verkleinde afbeelding (alleen bij JPG/PNG)</legend>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <label class="block">
                            <span class="block mb-1 font-bold">Max. breedte (px)</span>
                            <input type="number" name="max_width" min="50" max="5000" step="1" placeholder="1200"
                                   class="block w-full px-3 py-2.5 bg-white border border-[#dce7f2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2f7dd1]">
                        </label>

                        <label class="block">
                            <span class="block mb-1 font-bold">Max. hoogte (px)</span>
                            <input type="number" name="max_height" min="50" max="5000" step="1" placeholder="1200"
                                   class="block w-full px-3 py-2.5 bg-white border border-[#dce7f2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2f7dd1]">
                        </label>
                    </div>

                    <label class="block">
                        <span class="block mb-1 font-bold">Naam van de nieuwe afbeelding</span>
                        <input type="text" name="new_name" maxlength="60" placeholder="bijvoorbeeld: vakantie-klein"
                               class="block w-full px-3 py-2.5 bg-white border border-[#dce7f2] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2f7dd1]">
                        <span class="block mt-1 text-sm text-[#6f8295]">
                            Leeg laten = 1200 px en de originele naam + "-resized". Toegestaan: 50 tot 5000 px.
                            De extensie wordt automatisch toegevoegd.
                        </span>
                    </label>
                </fieldset>

                <button type="submit"
                        class="px-5 py-2.5 rounded-[10px] bg-[#2f7dd1] text-white font-bold hover:bg-[#1479da] transition-colors">
                    Uploaden
                </button>
            </form>
        </section>

        <!-- ===== Exports ===== -->
        <section id="exports" class="scroll-mt-24 p-5 sm:p-6 bg-white border border-[#dce7f2] rounded-2xl shadow-lg shadow-[#0d3b66]/5">
            <p class="text-xs font-extrabold tracking-[0.15em] text-[#2f7dd1]">EXPORTS</p>
            <h2 class="mt-1 mb-4 text-2xl font-bold">Exports</h2>

            <div class="mb-5 p-4 rounded-xl bg-[#e7f5e9] border border-[#a7d9b0] text-[#14633a]">
                <p>
                    ℹ️ Maak een PDF-rapport met een overzicht van alle geüploade bestanden (naam, type, grootte en datum/tijd).<br>
                    De rapporten worden in de exportmap opgeslagen en kunnen worden geopend of gedownload.
                </p>
            </div>

            <!-- meldingen na het exporteren -->
            <?php if ($flashSection === 'exports'): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="mb-3 p-4 rounded-xl bg-[#fde8e7] border border-[#f5b5b0] text-[#b42318]"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>

                <?php foreach ($messages as $message): ?>
                    <div class="mb-3 p-4 rounded-xl bg-[#e7f5e9] border border-[#a7d9b0] text-[#14633a]"><?= htmlspecialchars($message) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="post" action="" class="mb-6">
                <input type="hidden" name="action" value="export">
                <button type="submit"
                        class="px-5 py-2.5 rounded-[10px] bg-[#1e9d59] text-white font-bold hover:bg-[#178048] transition-colors">
                    PDF-rapport maken
                </button>
            </form>

            <?php if (empty($exports)): ?>
                <p class="text-[#6f8295]">Er zijn nog geen rapporten gemaakt.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px] text-left border-collapse">
                        <thead>
                            <tr class="text-xs uppercase text-[#6f8295]">
                                <th class="px-2.5 py-3 border-b border-[#edf2f7]">Rapport</th>
                                <th class="px-2.5 py-3 border-b border-[#edf2f7]">Grootte</th>
                                <th class="px-2.5 py-3 border-b border-[#edf2f7]">Gemaakt</th>
                                <th class="px-2.5 py-3 border-b border-[#edf2f7]">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exports as $export): ?>
                                <tr class="hover:bg-[#f9fcff]">
                                    <td class="px-2.5 py-3 border-b border-[#edf2f7] break-all"><?= htmlspecialchars($export['name']) ?></td>
                                    <td class="px-2.5 py-3 border-b border-[#edf2f7] whitespace-nowrap"><?= formatSize($export['size']) ?></td>
                                    <td class="px-2.5 py-3 border-b border-[#edf2f7] whitespace-nowrap"><?= date('d-m-Y H:i', $export['modified']) ?></td>
                                    <td class="px-2.5 py-3 border-b border-[#edf2f7] whitespace-nowrap">
                                        <a href="?export=<?= urlencode($export['name']) ?>" target="_blank" rel="noopener"
                                           class="font-bold text-[#2f7dd1] hover:underline">Openen</a>
                                        <span class="mx-1 text-[#9aabba]">|</span>
                                        <a href="?export=<?= urlencode($export['name']) ?>&download=1"
                                           class="font-bold text-[#2f7dd1] hover:underline">Downloaden</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>