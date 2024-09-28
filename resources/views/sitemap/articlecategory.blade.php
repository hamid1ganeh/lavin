<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($categories as $cat)
        <url>
            <loc>{{ urldecode(route('articlecategory',$cat->slug)) }}</loc>
            <lastmod>{{ $cat->updated_at }}</lastmod>
            <changefreq>always</changefreq>
            <priority>1</priority>
        </url>
    @endforeach
</urlset>