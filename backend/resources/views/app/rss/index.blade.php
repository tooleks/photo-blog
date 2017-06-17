<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
    <channel>
        <title>{{ $rss->getChannel()->getTitle() }}</title>
        <link>{{ $rss->getChannel()->getLink() }}</link>
        <description>{{ $rss->getChannel()->getDescription() }}</description>
        @foreach ($rss->getItems() as $item)
            <item>
                <title>{{ $item->getTitle() }}</title>
                <link>{{ $item->getLink() }}</link>
                <description>{{ $item->getDescription() }}</description>
                <guid>{{ $item->getGuid() }}</guid>
                <pubDate>{{ $item->getPubDate() }}</pubDate>
                @if ($item->getEnclosure())
                    <enclosure url="{{ $item->getEnclosure()->getUrl() }}"
                               type="{{ $item->getEnclosure()->getType() }}"
                               length="{{ $item->getEnclosure()->getLength() }}"/>
                @endif
                @if ($item->getCategories())
                    @foreach ($item->getCategories() as $category)
                        <category>{{ $category->getValue() }}</category>
                    @endforeach
                @endif
            </item>
        @endforeach
    </channel>
</rss>
