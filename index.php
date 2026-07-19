<?php
$files = glob(__DIR__ . '/data/*.json');
rsort($files);
$requested = preg_replace('/[^0-9-]/', '', $_GET['date'] ?? '');
$path = $requested ? __DIR__ . "/data/$requested.json" : ($files[0] ?? null);
if (!$path || !is_file($path)) { http_response_code(404); exit('No Gospel reading is available for this date.'); }
$d = json_decode(file_get_contents($path), true);
$dates = array_map(fn($f) => basename($f, '.json'), $files);
$pos = array_search($d['date'], $dates, true);
$newer = ($pos !== false && $pos > 0) ? $dates[$pos - 1] : null;
$older = ($pos !== false && $pos < count($dates) - 1) ? $dates[$pos + 1] : null;
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#07142c">
  <title><?= h($d['reference']) ?> · Lumen Verbi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=DM+Mono:wght@300;400;500&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css?v=2">
</head>
<body>
  <div class="grain"></div><div class="cursor-glow" aria-hidden="true"></div>
  <header class="masthead">
    <a class="brand" href="./"><i></i><span>Lumen <b>Verbi</b></span></a>
    <div class="edition"><span>Daily Gospel / Patristic intelligence</span><strong><?= h($d['date_display']) ?></strong></div>
    <nav class="date-nav" aria-label="Reading dates">
      <?php if ($older): ?><a href="?date=<?= h($older) ?>" aria-label="Older reading">←</a><?php else: ?><span>←</span><?php endif; ?>
      <?php if ($newer): ?><a href="?date=<?= h($newer) ?>" aria-label="Newer reading">→</a><?php else: ?><span>→</span><?php endif; ?>
    </nav>
  </header>

  <main>
    <section class="hero">
      <img src="<?= h($d['hero']) ?>" alt="<?= h($d['hero_alt']) ?>">
      <div class="hero-shade"></div>
      <div class="hero-copy">
        <div class="kicker"><span>✦</span> <?= h($d['liturgical_day']) ?></div>
        <h1><?= h($d['title']) ?></h1>
        <p><?= h($d['reference']) ?></p>
      </div>
      <div class="hero-orbit"><span>CATENA</span><b>AUREA</b><small>c. 1263</small></div>
    </section>

    <section class="orientation">
      <p class="eyebrow">Today’s interpretive key</p>
      <blockquote><?= h($d['theme']) ?></blockquote>
      <p><?= h($d['introduction']) ?></p>
      <div class="legend"><span><i class="gold"></i> Sacred text</span><span><i class="cyan"></i> Visible insight</span><span><i class="coral"></i> Tap for the full chain</span></div>
    </section>

    <section class="reading" aria-label="Gospel reading with commentary">
      <div class="reading-head"><span>THE HOLY GOSPEL</span><span><?= h($d['reference']) ?></span><span><?= count($d['verses']) ?> VERSES</span></div>
      <?php if (!empty($d['scripture_edition'])): ?>
      <p class="scripture-edition">
        Douay-AI · modernized from the public-domain
        <?php if (!empty($d['scripture_source_url'])): ?><a href="<?= h($d['scripture_source_url']) ?>" target="_blank" rel="noopener">Douay-Rheims Bible</a><?php else: ?>Douay-Rheims Bible<?php endif; ?>
      </p>
      <?php endif; ?>
      <?php foreach ($d['verses'] as $i => $v): ?>
      <article class="verse" style="--delay: <?= $i * 70 ?>ms">
        <div class="verse-number"><span><?= h($v['number']) ?></span><i></i></div>
        <p class="scripture"><?= h($v['text']) ?></p>
        <button class="insight" type="button" aria-expanded="false" aria-controls="note-<?= $i ?>">
          <span class="father"><?= h($v['author']) ?><small><?= h($v['author_date']) ?></small></span>
          <strong><?= h($v['insight']) ?></strong>
          <span class="reveal">Open the chain <b>+</b></span>
        </button>
        <div class="commentary" id="note-<?= $i ?>">
          <div><span class="source-mark">CA</span><p><?= h($v['commentary']) ?></p></div>
          <footer>Modernized from <?= h($v['source_label']) ?></footer>
        </div>
      </article>
      <?php endforeach; ?>
    </section>

    <section class="conclusion">
      <span>THE GOLDEN THREAD</span>
      <h2><?= h($d['closing_title']) ?></h2>
      <p><?= h($d['closing']) ?></p>
      <a href="<?= h($d['catena_url']) ?>" target="_blank" rel="noopener">Read the source Catena Aurea ↗</a>
    </section>
  </main>
  <footer class="site-footer"><span>Lumen Verbi / a living interface for the ancient Church</span><a href="../">← Image lab</a></footer>
  <script src="app.js?v=1"></script>
</body>
</html>
