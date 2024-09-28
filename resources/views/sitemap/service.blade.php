<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach($serviceDetails as $service)
        <url>
            <loc>
                {{ urldecode(route('website.services.show',$service->slug)) }}
            </loc>
            <lastmod>{{ $service->updated_at }}</lastmod>
            <changefreq>hourly</changefreq>
            <priority>0.8</priority>
            
            <image:image>
                <image:loc>
                    @if($service->image)
                    {{ env('APP_URL').'/'.$service->get_thumbnail('medium') }}
                    @endif
                </image:loc>
                <image:caption>لاوین</image:caption>
                <image:title>{{ $service->title }}</image:title>
            </image:image>
        </url>
    @endforeach
</urlset>
