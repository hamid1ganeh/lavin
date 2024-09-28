<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('sitemap-static.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap-articlecategory.xml') }}</loc>
    </sitemap>
     <sitemap>
        <loc>{{ url('sitemap-servicecategory.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap-service.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap-productcategory.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ url('sitemap-product.xml') }}</loc>
    </sitemap>
</sitemapindex>
