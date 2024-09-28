<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach($articles as $article)
        <url>
            <loc>
                {{ urldecode(route('articlesingle',$article->slug)) }}
            </loc>
            <lastmod>{{ $article->updated_at }}</lastmod>
            <changefreq>hourly</changefreq>
            <priority>0.8</priority>
            <image:image>
                <image:loc>
                 {{ env('APP_URL').$article->thumbnail }}
                </image:loc>
                <image:caption>لاوین</image:caption>
                <image:title>{{ $article->title }}</image:title>
            </image:image>
        </url>
    @endforeach
</urlset>
