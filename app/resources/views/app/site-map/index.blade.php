<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($siteMap->getItems() as $item)
        <url>
            @if ($item->hasLocation())
                <loc>{{ $item->getLocation() }}</loc>
            @endif
            @if ($item->hasLastModified())
                <lastmod>{{ $item->getLastModified() }}</lastmod>
            @endif
            @if ($item->hasChangeFrequency())
                <changefreq>{{ $item->getChangeFrequency() }}</changefreq>
            @endif
            @if ($item->hasPriority())
                <priority>{{ $item->getPriority() }}</priority>
            @endif
        </url>
    @endforeach
</urlset>
