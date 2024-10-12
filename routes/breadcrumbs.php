<?php

// ادمین ها

//صفحه اصلی
Breadcrumbs::for('home', function ($trail) {
    $trail->push('صفحه اصلی', route('admin.home'));
});

//آمار
Breadcrumbs::for('static', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('آمار', route('admin.static'));
});

// داشبورد > گزارش مواد مصرفی
Breadcrumbs::for('reports.consumptions', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('گزارش مواد مصرفی', route('admin.reports.consumptions'));
});

// داشبورد > پرونده پرسنلی
Breadcrumbs::for('staff.documents', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('پرونده پرسنلی', route('admin.staff.documents.index'));
});

// داشبورد > پرونده پرسنلی > مشخصات شخصی
Breadcrumbs::for('staff.documents.personal', function ($trail) {
    $trail->parent('staff.documents', $trail);
    $trail->push('مشخصات شخصی', route('admin.staff.documents.personal'));
});

// داشبورد > پرونده پرسنلی > مدارک تحصیلی
Breadcrumbs::for('staff.documents.educations', function ($trail) {
    $trail->parent('staff.documents', $trail);
    $trail->push('مدارک تحصیلی', route('admin.staff.documents.educations.index'));
});


// داشبورد > پرونده پرسنلی > شبکه های اجتماعی
Breadcrumbs::for('staff.documents.socialmedias', function ($trail) {
    $trail->parent('staff.documents', $trail);
    $trail->push('شبکه های اجتماعی', route('admin.staff.documents.socialmedias.index'));
});

// داشبورد > پرونده پرسنلی > مشخصات بانکی
Breadcrumbs::for('staff.documents.banks', function ($trail) {
    $trail->parent('staff.documents', $trail);
    $trail->push('مشخصات بانکی', route('admin.staff.documents.banks.index'));
});

// داشبورد > پرونده پرسنلی > مشخصات بازآموزی
Breadcrumbs::for('staff.documents.retrainings', function ($trail) {
    $trail->parent('staff.documents', $trail);
    $trail->push('مشخصات بازآموزی', route('admin.staff.documents.retrainings.index'));
});


// داشبورد > مقالات
Breadcrumbs::for('articles', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('مقالات', route('admin.article.index'));
});

// داشبورد > مقالات > ایجاد مقاله جدید
Breadcrumbs::for('article.create', function ($trail) {
    $trail->parent('articles', $trail);
    $trail->push('ایجاد مقاله جدید', route('admin.article.create'));
});

// داشبورد > مقالات > ویرایش مقاله
Breadcrumbs::for('article.edit', function ($trail,$article) {
    $trail->parent('articles', $trail);
    $trail->push('ویرایش مقاله ', route('admin.article.edit',$article));
});


//داشبورد > مقالات > دسته بندی مقالات
Breadcrumbs::for('article.categorys.index', function ($trail) {
    $trail->parent('articles', $trail);
    $trail->push('دسته بندی مقالات', route('admin.article.categorys.index'));
});


//داشبورد > مقالات > دسته بندی مقالات > ایجاد دسته بندی جدید
Breadcrumbs::for('article.categorys.create', function ($trail) {
    $trail->parent('article.categorys.index', $trail);
    $trail->push('ایجاد دسته بندی جدید', route('admin.article.categorys.create'));
});

//داشبورد > مقالات > دسته بندی مقالات > ویرایش دسته بندی
Breadcrumbs::for('article.categorys.edit', function ($trail,$category) {
    $trail->parent('article.categorys.index', $trail);
    $trail->push('ویرایش دسته بندی', route('admin.article.categorys.edit', $category));
});


// داشبورد >  سرگروه خدمات
Breadcrumbs::for('services', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('سرگروه خدمات', route('admin.services.index'));
});

// داشبورد > سرگروه خدمت >افزودن سرگروه خدمات
Breadcrumbs::for('services.create', function ($trail) {
    $trail->parent('services', $trail);
    $trail->push('افزودن سرگروه  جدید', route('admin.services.create'));
});

// داشبورد > سرگروه خدمت > ویرایش سرگروه خدمت
Breadcrumbs::for('services.edit', function ($trail,$service) {
    $trail->parent('services', $trail);
    $trail->push('ویرایش سرگروه خدمت', route('admin.services.edit',$service));
});

// داشبورد > سرگروه خدمت >  خدمات
Breadcrumbs::for('services.detiles', function ($trail) {
    $trail->parent('services', $trail);
    $trail->push('خدمات', route('admin.details.index'));
});


// داشبورد > سرگروه خدمت >  خدمات > ایجاد خدمت جدید
Breadcrumbs::for('services.detiles.create', function ($trail) {
    $trail->parent('services.detiles');
    $trail->push('ایجاد خدمت ', route('admin.details.create'));
});

// داشبورد > سرگروه خدمت >  خدمات > ویرایش خدمت
Breadcrumbs::for('services.detiles.edit', function ($trail,$detail) {
    $trail->parent('services.detiles');
    $trail->push('ویرایش خدمت ', route('admin.details.edit',$detail));
});

// داشبورد > سرگروه خدمت >  خدمات >  ویدئو خدمت
Breadcrumbs::for('services.detiles.videos', function ($trail,$detail) {
    $trail->parent('services.detiles');
    $trail->push('ویدئوهای خدمت', route('admin.details.videos.show',$detail));
});


//  داشبورد > سرگروه خدمت >  خدمات >  ویدئو خدمت > ایجاد ویدئو جدید
Breadcrumbs::for('services.detiles.videos.create', function ($trail,$detail) {
    $trail->parent('services.detiles.videos',$detail);
    $trail->push('افزوده ویدئو', route('admin.details.videos.create',$detail));
});


 // داشبورد > سرگروه خدمت >  خدمات >  تصاویر خدمت
Breadcrumbs::for('services.detiles.images', function ($trail,$detail) {
    $trail->parent('services.detiles');
    $trail->push('تصاویر خدمت', route('admin.details.images.show',$detail));
});


// داشبورد > سرگروه خدمت >  خدمات >  ویدئو خدمت
Breadcrumbs::for('services.detiles.luck', function ($trail,$detail) {
    $trail->parent('services.detiles');
    $trail->push('افزودن به گرونه شانس', route('admin.details.luck.create',$detail));
});



// داشبورد >   دسته بندی خدمات
Breadcrumbs::for('services.cat', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('دسته بندی خدمات', route('admin.services.categories.index'));
});

// داشبورد >   دسته بندی خدمات  > ایجاد دسته بندی جدید
Breadcrumbs::for('services.cat.create', function ($trail) {
    $trail->parent('services.cat', $trail);
    $trail->push('ایجاد دسته بندی جدید ', route('admin.services.categories.create'));
});

// داشبورد >   دسته بندی خدمات  > ویرایش دسته بندی
Breadcrumbs::for('services.cat.edit', function ($trail,$category) {
    $trail->parent('services.cat', $trail);
    $trail->push('ویرایش دسته بندی', route('admin.services.categories.edit',$category));
});

// داشبورد >   دسته بندی خدمات  > زیر دسته ها
Breadcrumbs::for('services.cat.sub', function ($trail,$parent) {
    $trail->parent('services.cat', $parent);
    $trail->push('زیردسته ها', route('admin.services.categories.sub.index',$parent));
});


// داشبورد >   دسته بندی خدمات  > زیر دسته ها > ایجاد  زیردسته
Breadcrumbs::for('services.cat.sub.create', function ($trail,$parent) {
    $trail->parent('services.cat.sub', $parent);
    $trail->push('ایجاد زیردسته', route('admin.services.categories.sub.create',$parent));
});


//  داشبورد >   دسته بندی خدمات  > زیر دسته ها > ویرایش  زیردسته
Breadcrumbs::for('services.cat.sub.edit', function ($trail,$parent,$sub) {
    $trail->parent('services.cat.sub', $parent);
    $trail->push('ویرایش زیردسته', route('admin.services.categories.sub.edit',[$parent,$sub]));
});


// داشبورد > رزرو
Breadcrumbs::for('reserves', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('رزرو', route('admin.reserves.index'));
});

Breadcrumbs::for('receptions', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('پذیرش', route('admin.receptions.index'));
});


// داشبورد > رزرو > گزارش ارتقاء
Breadcrumbs::for('reserves.upgrades', function ($trail) {
    $trail->parent('reserves', $trail);
    $trail->push('گزارش ارتقاء', route('admin.reserves.upgrades'));
});


// داشبورد > رزرو > ایجاد
Breadcrumbs::for('reserves.create', function ($trail) {
    $trail->parent('reserves', $trail);
    $trail->push('ایجاد رزرو', route('admin.reserves.create'));
});

// داشبورد > رزرو > عوارض
Breadcrumbs::for('reserves.complications', function ($trail,$reserve) {
    $trail->parent('reserves', $trail);
    $trail->push('عوارض', route('admin.reserves.complications.show',$reserve));
});


// داشبورد > رزرو > تعیین وضعیت رزرو
Breadcrumbs::for('reserves.determining', function ($trail,$reserve) {
    $trail->parent('reserves', $trail);
    $trail->push('تعیین وضعیت رزرو', route('admin.reserves.determining',$reserve));
});


// داشبورد > رزرو > مواد مصرفی
Breadcrumbs::for('reserves.consumptions', function ($trail,$reserve) {
    $trail->parent('reserves', $trail);
    $trail->push('مواد مصرفی', route('admin.reserves.consumptions.index',$reserve));
});


// داشبورد > رزرو > ارتقاء
Breadcrumbs::for('reserves.upgrade', function ($trail,$reserve) {
    $trail->parent('reserves', $trail);
    $trail->push('ارتقاء', route('admin.reserves.upgrade.index',$reserve));
});


// داشبورد > رزرو > ارتقاء > جدید
Breadcrumbs::for('reserves.upgrade.create', function ($trail,$reserve) {
    $trail->parent('reserves.upgrade',$reserve);
    $trail->push('ارتقاء جدید', route('admin.reserves.upgrade.create',$reserve));
});

// داشبورد > رزرو > ارتقاء > ویرایش ارتقاء
Breadcrumbs::for('reserves.upgrade.edit', function ($trail,$reserve,$upgrade) {
    $trail->parent('reserves.upgrade',$reserve);
    $trail->push('ویرایش ارتقاء', route('admin.reserves.upgrade.edit',[$reserve,$upgrade]));
});




// داشبورد > شعبه ها
Breadcrumbs::for('branchs.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('شعبه ها', route('admin.branchs.index'));
});

// داشبورد > شعبه ها > ایجاد شعبه جدید
Breadcrumbs::for('branchs.create', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('ایجاد شعبه جدید', route('admin.branchs.create'));
});

// داشبورد > شعبه ها > ویرایش  شعبه
Breadcrumbs::for('branchs.edit', function ($trail,$branch) {
    $trail->parent('branchs.index');
    $trail->push('ویرایش شعبه', route('admin.branchs.edit',$branch));
});

 // داشبورد > دکتر
Breadcrumbs::for('doctors', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('پزشکان', route('admin.doctors.index'));
});

 //    داشبورد > دکتر > اطلاعات
 Breadcrumbs::for('doctors.info', function ($trail,$doctor) {
    $trail->parent('doctors', $trail);
    $trail->push('اطلاعات پزشک', route('admin.doctors.info',$doctor));
});

 //  داشبورد > دکتر > اطلاعات > ویدئو پزشک
 Breadcrumbs::for('doctors.info.video', function ($trail,$doctor) {
    $trail->parent('doctors.info', $doctor);
    $trail->push('ویدئو پزشک', route('admin.doctors.video',$doctor));
});

 // داشبورد > تیکت ها
 Breadcrumbs::for('tickets', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('تیکت ها', route('admin.tickets.index'));
});

 // داشبورد > تیکت ها > نمایش تیکت
 Breadcrumbs::for('ticket.show', function ($trail,$ticket) {
    $trail->parent('tickets', $trail);
    $trail->push('نمایش تیکت ', route('admin.tickets.show',$ticket));
});


 // داشبورد > تیکت ها >  واحدهای پشتیبانی
 Breadcrumbs::for('ticket.departments', function ($trail) {
    $trail->parent('tickets', $trail);
    $trail->push('واحدهای پشتیبانی ', route('admin.departments.index'));
});


 //  داشبورد > تیکت ها >  واحدهای پشتیبانی >ایجاد واحد جدید
 Breadcrumbs::for('ticket.departments.create', function ($trail) {
    $trail->parent('ticket.departments', $trail);
    $trail->push('ایجاد واحد جدید', route('admin.departments.create'));
});

//  داشبورد > تیکت ها >  واحدهای پشتیبانی >ویرایش واحد
Breadcrumbs::for('ticket.departments.edit', function ($trail,$department) {
    $trail->parent('ticket.departments', $trail);
    $trail->push('ویرایش واحد', route('admin.departments.edit',$department));
});

// داشبورد > اعلانات
Breadcrumbs::for('notifications.users', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('اعلانات کاربران', route('admin.notifications.users.index'));
});


// داشبورد > اعلانات
Breadcrumbs::for('notifications.admins', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('اعلانات ادمین ها', route('admin.notifications.admins.index'));
});

// داشبورد > اعلانات > اعلانات من
Breadcrumbs::for('admin.notifications.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('اعلانات من', route('admin.notifications.index'));
});

// داشبورد > اعلانات >نمایش اعلانات من
Breadcrumbs::for('admin.notifications.show', function ($trail,$notification) {
    $trail->parent('home', $trail);
    $trail->push('نمایش اعلانات من', route('admin.notifications.show',$notification));
});

Breadcrumbs::for('admin.notifications', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('اعلانات من', route('admin.notifications.index'));
});

//  داشبورد > اعلانات >  اعلان جدید
Breadcrumbs::for('notifications.create', function ($trail) {
    $trail->parent('notifications', $trail);
    $trail->push('اعلان جدید', route('admin.notifications.users.create'));
});


// داشبورد > گروه های بازخورد
Breadcrumbs::for('rewiewgroups', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('گروه بازخوردها', route('admin.rewiewGroups.index'));
});

//داشبورد > گروه های بازخورد> جدید
Breadcrumbs::for('rewiewgroups.create', function ($trail) {
    $trail->parent('rewiewgroups', $trail);
    $trail->push('گروه بازخورد جدید', route('admin.rewiewGroups.create'));
});

//داشبورد > گروه های بازخورد> ویرایش
Breadcrumbs::for('rewiewgroups.edit', function ($trail,$reviewgroup) {
    $trail->parent('rewiewgroups', $trail);
    $trail->push('ویرایش گروه بازخورد', route('admin.rewiewGroups.edit',$reviewgroup));
});

// داشبورد > بازخوردها
Breadcrumbs::for('reviews', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('بازخوردها', route('admin.rewiewGroups.index'));
});

// داشبورد > بازخوردها> نظرسنجی اختصاصی رزروها
Breadcrumbs::for('polls', function ($trail) {
    $trail->parent('reviews', $trail);
    $trail->push('نظرسنجی اختصاصی رزروها', route('admin.reviews.polls'));
});


// داشبورد > گالری ها
Breadcrumbs::for('galleries', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('گالری ها', route('admin.gallery.index'));
});

// داشبورد > گالری ها
Breadcrumbs::for('gallery.image', function ($trail, $gallery) {
    $trail->parent('galleries', $trail);
    $trail->push('تصاویر گالری', route('admin.gallery.images.index', $gallery));
});


// داشبورد >  محصولات
Breadcrumbs::for('products', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('محصولات', route('admin.shop.products.index'));
});


//  داشبورد >  محصولات > ایجاد محصول
Breadcrumbs::for('products.create', function ($trail) {
    $trail->parent('products', $trail);
    $trail->push('ایجاد محصول', route('admin.shop.products.create'));
});

//  داشبورد >  محصولات > ویرایش محصول
Breadcrumbs::for('products.edit', function ($trail,$product) {
    $trail->parent('products', $trail);
    $trail->push('ویرایش محصول', route('admin.shop.products.edit',$product));
});


//  داشبورد >  محصولات >  ویژگی های محصول
Breadcrumbs::for('products.attributes', function ($trail,$product) {
    $trail->parent('products', $trail);
    $trail->push('ویژگی های محصول', route('admin.shop.products.attributes.show',$product));
});

//  داشبورد >  محصولات > تصاویر محصول
Breadcrumbs::for('products.images', function ($trail,$product) {
    $trail->parent('products', $trail);
    $trail->push('تصاویر محصول', route('admin.shop.products.images.show',$product));
});


//  داشبورد >  محصولات >  افزون به قرعه کشی
Breadcrumbs::for('products.luck', function ($trail,$product) {
    $trail->parent('products', $trail);
    $trail->push('افزودن به قرعه کشی', route('admin.shop.products.luck.create',$product));
});


//  داشبورد >  محصولات >  دسته بندی محصولات
Breadcrumbs::for('products.categories', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('دسته بندی محصولات', route('admin.shop.products.categories.index'));
});

 //    داشبورد >  محصولات >  دسته بندی محصولات > جدید
Breadcrumbs::for('products.categories.create', function ($trail) {
    $trail->parent('products.categories', $trail);
    $trail->push('ایجاد دسته بندی جدید', route('admin.shop.products.categories.create'));
});


 //    داشبورد >  محصولات >  دسته بندی محصولات > ویرایش
 Breadcrumbs::for('products.categories.edit', function ($trail,$category) {
    $trail->parent('products.categories', $trail,$category);
    $trail->push('ویرایش دسته بندی', route('admin.shop.products.categories.edit',$category));
});


 //    داشبورد >  محصولات >  دسته بندی محصولات > زیردسته ها
 Breadcrumbs::for('products.categories.sub', function ($trail,$category) {
    $trail->parent('products.categories', $trail);
    $trail->push('زیردسته ها', route('admin.shop.products.categories.sub.index',$category));
});

 //    داشبورد >  محصولات >  دسته بندی محصولات > زیردسته ها > ایجاد زیردسته جدید
 Breadcrumbs::for('products.categories.sub.create', function ($trail,$category) {
    $trail->parent('products.categories.sub', $category);
    $trail->push('زیردسته جدید', route('admin.shop.products.categories.sub.create',$category));
});



 //    داشبورد >  محصولات >  دسته بندی محصولات > زیردسته ها >  ویرایش زیردسته
 Breadcrumbs::for('products.categories.sub.edit', function ($trail,$category,$sub) {
    $trail->parent('products.categories.sub', $category);
    $trail->push('ویرایش زیردسته', route('admin.shop.products.categories.sub.edit',[$category,$sub]));
});



//  داشبورد  > لیست فروش
Breadcrumbs::for('orders', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('لیست فروش', route('admin.shop.sells.index'));
});


//  داشبورد  >  تخفیف ها
Breadcrumbs::for('discounts', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('تخفیف ها', route('admin.discounts.index'));
});


// داشبورد >  تخفیف ها> ایجاد تخفیف جدید
Breadcrumbs::for('discounts.create', function ($trail) {
    $trail->parent('discounts', $trail);
    $trail->push('تخفیف ها', route('admin.discounts.create'));
});

// داشبورد >  تخفیف ها> ویرایش تخفیف
Breadcrumbs::for('discounts.edit', function ($trail,$discount) {
    $trail->parent('discounts', $trail);
    $trail->push('ویرایش تخفیف', route('admin.discounts.edit',$discount));
});

// داشبورد >  تخفیف ها>  کاربر
Breadcrumbs::for('discounts.users', function ($trail,$discount) {
    $trail->parent('discounts', $trail);
    $trail->push('کاربران', route('admin.discounts.users.show',$discount));
});


// داشبورد >  تخفیف ها>  سرویس ها
Breadcrumbs::for('discounts.services', function ($trail,$services) {
    $trail->parent('discounts', $trail);
    $trail->push('سرویس ها', route('admin.discounts.services.show',$services));
});


// داشبورد >   گردونه شانس
Breadcrumbs::for('luck', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('گردونه شانس', route('admin.luck.index'));
});


// داشبورد >  نمونه کار
Breadcrumbs::for('portfolios', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('نمونه کار', route('admin.portfolios.index'));
});



//  داشبورد >  نمونه کار > ایجاد
Breadcrumbs::for('portfolios.create', function ($trail) {
    $trail->parent('portfolios', $trail);
    $trail->push('ایجاد نمونه کار', route('admin.portfolios.index'));
});



//  داشبورد >  نمونه کار > ویرایش
Breadcrumbs::for('portfolios.edit', function ($trail,$portfolio) {
    $trail->parent('portfolios', $trail);
    $trail->push('ویرایش نمونه کار', route('admin.portfolios.edit',$portfolio));
});



// داشبورد > نظرات
Breadcrumbs::for('commnets', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('نظرات', route('admin.comments.index'));
});


//استان > شهرها
Breadcrumbs::for('provinces', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('استان ها', route('admin.provinces.index'));
});


//  داشبورد > استان ها> ویرایش استان
Breadcrumbs::for('provinces.edit', function ($trail,$province) {
    $trail->parent('provinces', $trail);
    $trail->push('ویرایش استان', route('admin.provinces.edit',$province));
});

//  داشبورد > استان ها> شهرها
Breadcrumbs::for('provinces.cities.index', function ($trail,$province) {
    $trail->parent('provinces', $trail);
    $trail->push('شهرها', route('admin.provinces.cities.index',$province));
});

//  داشبورد > استان ها> شهرها > ایجاد شهر جدید
 Breadcrumbs::for('provinces.cities.create', function ($trail,$province) {
    $trail->parent('provinces.cities.index',$province);
    $trail->push('ایجاد شهر جدید', route('admin.provinces.cities.create',$province));
});

 // داشبورد > استان ها> شهرها > ویرایش شهر
Breadcrumbs::for('provinces.cities.edit', function ($trail,$province,$city) {
    $trail->parent('provinces.cities.index', $province);
    $trail->push('ویرایش شهر', route('admin.provinces.cities.edit',[$province,$city]));
});


 // داشبورد > استان ها> شهرها >  مناطق
Breadcrumbs::for('provinces.cities.parts.index', function ($trail,$province,$city) {
    $trail->parent('provinces.cities.index', $province,$city);
    $trail->push('مناطق', route('admin.provinces.cities.parts.index',[$province,$city]));
});


 // داشبورد > استان ها> شهرها >   ایجاد منطقه جدید
Breadcrumbs::for('provinces.cities.parts.create', function ($trail,$province,$city) {
    $trail->parent('provinces.cities.parts.index', $province,$city);
    $trail->push('ایجاد منطقه جدید', route('admin.provinces.cities.parts.create',[$province,$city]));
});


 // داشبورد > استان ها> شهرها > ویرایش منطقه
Breadcrumbs::for('provinces.cities.parts.edit', function ($trail,$province,$city,$part) {
    $trail->parent('provinces.cities.parts.index',  $province,$city);
    $trail->push('ویرایش منطقه', route('admin.provinces.cities.parts.edit',[$province,$city,$part]));
});


// داشبورد > استان ها> شهرها >  مناطق > محلات
Breadcrumbs::for('provinces.cities.parts.areas.index', function ($trail,$province,$city,$part) {
    $trail->parent('provinces.cities.parts.index', $province,$city);
    $trail->push('محلات', route('admin.provinces.cities.parts.areas.index',[$province,$city,$part]));
});

// داشبورد > استان ها> شهرها >  مناطق > محلات > ایجاد محله جدید
Breadcrumbs::for('provinces.cities.parts.areas.create', function ($trail,$province,$city,$part) {
    $trail->parent('provinces.cities.parts.areas.index', $province,$city,$part);
    $trail->push('ایجاد محله جدید', route('admin.provinces.cities.parts.areas.create',[$province,$city,$part]));
});

// داشبورد > استان ها> شهرها >  مناطق > محلات > ویرایش محله
Breadcrumbs::for('provinces.cities.parts.areas.edit', function ($trail,$province,$city,$part,$area) {
    $trail->parent('provinces.cities.parts.areas.index', $province,$city,$part);
    $trail->push('ویرایش محله', route('admin.provinces.cities.parts.areas.edit',[$province,$city,$part,$area]));
});

 // داشبورد > مشاغل
Breadcrumbs::for('jobs', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('مشاغل', route('admin.jobs.index'));
});



 // داشبورد > مشاغل > ایجاد شغل جدید
 Breadcrumbs::for('jobs.create', function ($trail) {
    $trail->parent('jobs', $trail);
    $trail->push('ایجاد شغل جدید', route('admin.jobs.create'));
});


 // داشبورد > مشاغل > ویرایش شغل
 Breadcrumbs::for('jobs.edit', function ($trail,$job) {
    $trail->parent('jobs', $trail);
    $trail->push('ویرایش شغل', route('admin.jobs.edit',$job));
});


 // داشبورد > رسانه های اجتماعی
 Breadcrumbs::for('socialmedia', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('رسانه های اجتماعی', route('admin.socialmedia.index'));
});


 // داشبورد > رسانه های اجتماعی > ایجاد  رسانه اجتماعی جدید
 Breadcrumbs::for('socialmedia.create', function ($trail) {
    $trail->parent('socialmedia', $trail);
    $trail->push('ایجاد رسانه اجتماعی جدید', route('admin.socialmedia.index'));
});


 // داشبورد > رسانه های اجتماعی > ویرایش  رسانه اجتماعی
 Breadcrumbs::for('socialmedia.edit', function ($trail,$socialmedia) {
    $trail->parent('socialmedia', $trail);
    $trail->push('ویرایش رسانه اجتماعی ', route('admin.socialmedia.edit',$socialmedia));
});


 // داشبورد > تلفن های تماس
 Breadcrumbs::for('phones', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('تلفن های تماس', route('admin.phones.index'));
});



 //  داشبورد > تلفن های تماس  > ایجاد تلفن جدید
 Breadcrumbs::for('phones.create', function ($trail) {
    $trail->parent('phones', $trail);
    $trail->push('تلفن جدید', route('admin.phones.create'));
});


 //  داشبورد > تلفن های تماس  > ویرایش تلفن
 Breadcrumbs::for('phones.edit', function ($trail,$phone) {
    $trail->parent('phones', $trail);
    $trail->push('ویرایش تلفن', route('admin.phones.edit',$phone));
});


 // داشبورد >  پیام ها
 Breadcrumbs::for('messages', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('پیام ها', route('admin.messages.index'));
});



 //   داشبورد >  پیام ها > نمایش پیام
 Breadcrumbs::for('messages.show', function ($trail,$message) {
    $trail->parent('messages', $trail);
    $trail->push('نمایش پیام', route('admin.messages.show',$message));
});


 // داشبورد >  ادمین ها
 Breadcrumbs::for('admins', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('ادمین ها', route('admin.admins.index'));
});


 //  داشبورد >  ادمین ها > ایجاد ادمین جدید
 Breadcrumbs::for('admins.create', function ($trail) {
    $trail->parent('admins', $trail);
    $trail->push('ادمین جدید', route('admin.admins.create'));
});

 //  داشبورد >  ادمین ها > ویرایش  ادمین
 Breadcrumbs::for('admins.edit', function ($trail,$admin) {
    $trail->parent('admins', $trail);
    $trail->push('ویرایش ادمین', route('admin.admins.edit',$admin));
});

 //  داشبورد >  ادمین ها > آدرس  ادمین
 Breadcrumbs::for('admins.address', function ($trail,$admin) {
    $trail->parent('admins', $trail);
    $trail->push('آدرس ادمین', route('admin.admins.address.show',$admin));
});


 //  داشبورد >  ادمین ها > رشته های تحصیلی ادمین
 Breadcrumbs::for('admins.feilds', function ($trail,$admin) {
    $trail->parent('admins', $trail);
    $trail->push('رشته های تحصیلی ادمین', route('admin.admins.feilds.index',$admin));
});


 //   داشبورد >  ادمین ها > رشته های تحصیلی ادمین  > افزودن رشته جدید
 Breadcrumbs::for('admins.feilds.create', function ($trail,$admin) {
    $trail->parent('admins.feilds', $admin);
    $trail->push('افزودن رشته جدید', route('admin.admins.feilds.create',$admin));
});

 //   داشبورد >  ادمین ها > رشته های تحصیلی ادمین  > ویرایش رشته
 Breadcrumbs::for('admins.feilds.edit', function ($trail,$admin,$feild) {
    $trail->parent('admins.feilds', $admin);
    $trail->push('ویرایش رشته', route('admin.admins.feilds.edit',[$admin,$feild]));
});


 //  داشبورد >  ادمین ها > شبکه های اجتماعی ادمین
 Breadcrumbs::for('admins.medias', function ($trail,$admin) {
    $trail->parent('admins', $trail);
    $trail->push('شبکه های اجتماعی ادمین', route('admin.admins.medias.index',$admin));
});


 //  داشبورد >  ادمین ها > شبکه های اجتماعی ادمین  > جدید
 Breadcrumbs::for('admins.medias.create', function ($trail,$admin) {
    $trail->parent('admins.medias', $admin);
    $trail->push('ایجاد شبکه اجتماعی جدید', route('admin.admins.medias.create',$admin));
});

 //  داشبورد >  ادمین ها > شبکه های اجتماعی ادمین  > ویرایش شبکه
 Breadcrumbs::for('admins.medias.edit', function ($trail,$admin,$media) {
    $trail->parent('admins.medias', $admin);
    $trail->push('ویرایش شبکه اجتماعی', route('admin.admins.medias.edit',[$admin,$media]));
});


//  داشبورد >  ادمین ها >  پرونده پرسنلی
Breadcrumbs::for('admins.staff.documents', function ($trail,$admin) {
    $trail->parent('admins', $trail);
    $trail->push('پرونده پرسنلی', route('admin.admins.staff.documents',$admin));
});

//  داشبورد >  ادمین ها >  پرونده پرسنلی > مشخصات شخصی
Breadcrumbs::for('admins.staff.documents.personal', function ($trail,$admin) {
    $trail->parent('admins.staff.documents',$admin, $trail);
    $trail->push('مشخصات شخصی', route('admin.admins.staff.personal.index',$admin));
});

//  داشبورد >  ادمین ها >  پرونده پرسنلی > مدارک تحصیلی
Breadcrumbs::for('admins.staff.documents.educations', function ($trail,$admin) {
    $trail->parent('admins.staff.documents',$admin, $trail);
    $trail->push('مدارک تحصیلی', route('admin.admins.staff.educations.index',$admin));
});

//  داشبورد >  ادمین ها >  پرونده پرسنلی > شبکه های اجتماعی
Breadcrumbs::for('admins.staff.documents.socialmedias', function ($trail,$admin) {
    $trail->parent('admins.staff.documents',$admin, $trail);
    $trail->push('شبکه های اجتماعی', route('admin.admins.staff.socialmedias.index',$admin));
});

//  داشبورد >  ادمین ها >  پرونده پرسنلی > مشخصات بانکی
Breadcrumbs::for('admins.staff.documents.banks', function ($trail,$admin) {
    $trail->parent('admins.staff.documents',$admin, $trail);
    $trail->push('مشخصات بانکی', route('admin.admins.staff.banks.index',$admin));
});

//  داشبورد >  ادمین ها >  پرونده پرسنلی > بازآموزی ها
Breadcrumbs::for('admins.staff.documents.retrainings', function ($trail,$admin) {
    $trail->parent('admins.staff.documents',$admin, $trail);
    $trail->push('بازآموزی ها', route('admin.admins.staff.retrainings.index',$admin));
});



// داشبورد >  کاربران
 Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('کاربران', route('admin.users.index'));
});

 // داشبورد >  کاربران
 Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users', $trail);
    $trail->push('کاربر جدید', route('admin.users.create'));
});


//   < داشبورد >  کاربران ویرایش کاربر
 Breadcrumbs::for('users.edit', function ($trail,$user) {
    $trail->parent('users', $trail);
    $trail->push('ویرایش کاربر', route('admin.users.edit',$user));
});


  // داشبورد >  سطوح
  Breadcrumbs::for('leveles', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('سطوح', route('admin.levels.index'));
});

  // داشبورد >  سطوح >  ایجاد سطح جدید
  Breadcrumbs::for('leveles.create', function ($trail) {
    $trail->parent('leveles', $trail);
    $trail->push('ایجاد سطح جدید', route('admin.levels.create'));
});

  // داشبورد >  سطوح >  ایجاد سطح جدید
  Breadcrumbs::for('leveles.edit', function ($trail,$level) {
    $trail->parent('leveles', $trail);
    $trail->push('ویرایش سطح', route('admin.levels.edit',$level));
});


// داشبورد >  پرسش و پاسخ
Breadcrumbs::for('faq.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('پرسش و پاسخ', route('admin.faq.index'));
});


//  داشبورد >  پرسش و پاسخ > ایجاد پرسش و پاسخ جدید
Breadcrumbs::for('faq.create', function ($trail) {
    $trail->parent('faq.index', $trail);
    $trail->push('ایجاد پرسش و پاسخ', route('admin.faq.create'));
});

//  داشبورد >  پرسش و پاسخ > ایجاد ویرایش و پاسخ
Breadcrumbs::for('faq.edit', function ($trail,$faq) {
    $trail->parent('faq.index', $trail);
    $trail->push('ویرایش پرسش و پاسخ', route('admin.faq.edit',$faq));
});

  // داشبورد >  نقش ها
  Breadcrumbs::for('roles', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('نقش ها', route('admin.roles.index'));
});

  //  داشبورد >  نقش ها > ایجاد نقش جدید
  Breadcrumbs::for('roles.create', function ($trail) {
    $trail->parent('roles', $trail);
    $trail->push('ایجاد نقش جدید', route('admin.roles.create'));
});

 // ایجاد داشبورد >  نقش ها > ویرایش نقش
 Breadcrumbs::for('roles.edit', function ($trail,$role) {
    $trail->parent('roles', $trail);
    $trail->push('ویرایش نقش', route('admin.roles.edit',$role));
});

 // داشبورد >  مشاوره تلفنی
 Breadcrumbs::for('numbers', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('مشاوره تلفنی', route('admin.numbers.index'));
});

//  داشبورد >  مشاوره تلفنی >  درون ریزی
Breadcrumbs::for('numbers.csv', function ($trail) {
    $trail->parent('numbers', $trail);
    $trail->push('درون ریزی', route('admin.numbers.csv'));
});

//  داشبورد >  مشاوره تلفنی > ایجاد  شماره جدید
Breadcrumbs::for('numbers.create', function ($trail) {
    $trail->parent('numbers', $trail);
    $trail->push('ایجاد شماره جدید', route('admin.numbers.create'));
});

//  داشبورد >  مشاوره تلفنی > تاریخجه اپراتورهای تلفنی
Breadcrumbs::for('numbers.history.operators', function ($trail) {
    $trail->parent('numbers', $trail);
    $trail->push('تاریخچه  اپراتورها', route('admin.numbers.history.operators'));
});

//  داشبورد >  مشاوره تلفنی > تاریخجه مشاورهای تلفنی
Breadcrumbs::for('numbers.history.advisers', function ($trail) {
    $trail->parent('numbers', $trail);
    $trail->push('تاریخچه مشاورها', route('admin.numbers.history.advisers'));
});

//  داشبورد >  مشاوره تلفنی > ویرایش  شماره
Breadcrumbs::for('numbers.edit', function ($trail,$number) {
    $trail->parent('numbers', $trail);
    $trail->push('ویرایش شماره', route('admin.numbers.edit',$number));
});

//  داشبورد >  مشاوره تلفنی >  مشاوره
Breadcrumbs::for('numbers.advisers', function ($trail,$number) {
    $trail->parent('numbers', $trail);
    $trail->push('مشاوره', route('admin.numbers.advisers.index',$number));
});


//  داشبورد >  لیست درخواست های آنالیز
Breadcrumbs::for('ask.analysis', function ($trail) {
    $trail->parent('home');
    $trail->push('درخواست های آنالیز', route('admin.ask.analysis.index'));
});

//  داشبورد >  تاریخچه پیامک
Breadcrumbs::for('sms.history', function ($trail) {
    $trail->parent('home');
    $trail->push(' تاریخچه پیامک ها', route('admin.sms.history'));
});

//  داشبورد >  لیست درخواست های آنالیز> نمایش درخواست آنالیز
Breadcrumbs::for('ask.analysis.show', function ($trail,$ask) {
    $trail->parent('ask.analysis');
    $trail->push(' نمایش درخواست آنالیز', route('admin.ask.analysis.show',$ask));
});

//  داشبورد >  لیست هایلایت ها >  لیست استوری های هایلایت
Breadcrumbs::for('stories.index', function ($trail) {
    $trail->parent('home');
    $trail->push('لیست استوری ها', route('admin.stories.index'));
});

//  داشبورد  >  لیست استوری ها >  ایجاد استوری جدید
Breadcrumbs::for('highlights.stories.create', function ($trail) {
    $trail->parent('stories.index');
    $trail->push('ایجاد استوری جدید', route('admin.stories.create'));
});

//  داشبورد  > لیست استوری ها > ویرایش استوری
Breadcrumbs::for('stories.edit', function ($trail,$story) {
    $trail->parent('stories.index');
    $trail->push(' ویرایش استوری', route('admin.stories.edit',$story));
});

//  داشبورد >  لیست استورها ها >  لیست هایلایتها
Breadcrumbs::for('highlights.index', function ($trail) {
    $trail->parent('stories.index');
    $trail->push('لیست هایلایتها', route('admin.highlights.index'));
});


//  داشبورد  >  لیست استوری ها> لیست هایلایتها >  ایجاد هایلایت جدید
Breadcrumbs::for('highlights.create', function ($trail) {
    $trail->parent('highlights.index');
    $trail->push('ایجاد هایلایت جدید', route('admin.highlights.create'));
});

//  داشبورد  >  لیست استوری ها> لیست هایلایتها >  ویرایش هایلایت
Breadcrumbs::for('highlights.edit', function ($trail,$highlight) {
    $trail->parent('highlights.index');
    $trail->push('ویرایش هایلایت', route('admin.highlights.edit',$highlight));
});

//  داشبورد >  لیست عوارض
Breadcrumbs::for('complications.index', function ($trail) {
    $trail->parent('home');
    $trail->push('لیست عوارض', route('admin.complications.index'));
});

//  داشبورد >  لیست عوارض > ایجاد عارضه جدید
Breadcrumbs::for('complications.create', function ($trail) {
    $trail->parent('complications.index');
    $trail->push('ایجاد عارضه جدید', route('admin.complications.create'));
});

//  داشبورد >  لیست عوارض > ویرایش عارضه
Breadcrumbs::for('complications.edit', function ($trail,$complication) {
    $trail->parent('complications.index');
    $trail->push('ویرایش عارضه', route('admin.complications.edit',$complication));
});



// داشبورد >  دسته بندی ها اصلی استخدام
Breadcrumbs::for('employments.categories.main.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('دسته بندی ها اصلی استخدام', route('admin.employments.categories.main.index'));
});

// داشبورد >  دسته بندی ها اصلی استخدام > ایجاد دسته بندی جدید
Breadcrumbs::for('employments.categories.main.create', function ($trail) {
    $trail->parent('employments.categories.main.index', $trail);
    $trail->push('ایجاد دسته بندی جدید', route('admin.employments.categories.main.create'));
});


// داشبورد >  دسته بندی ها اصلی استخدام > ویرایش دسته بندی بندی
Breadcrumbs::for('employments.categories.main.edit', function ($trail,$cat) {
    $trail->parent('employments.categories.main.index', $trail);
    $trail->push('ویرایش دسته بندی', route('admin.employments.categories.main.edit',$cat));
});

// داشبورد >  دسته بندی ها اصلی استخدام >  زیردسته ها
Breadcrumbs::for('employments.categories.sub.index', function ($trail,$main) {
    $trail->parent('employments.categories.main.index', $trail);
    $trail->push('زیردسته ها', route('admin.employments.categories.sub.index',$main));
});

// داشبورد >  دسته بندی ها اصلی استخدام >  زیردسته ها > ایجاد زیردسته جدید
Breadcrumbs::for('employments.categories.sub.create', function ($trail,$main) {
    $trail->parent('employments.categories.sub.index',$main);
    $trail->push('ایجاد زیردسته جدید', route('admin.employments.categories.sub.create',$main));
});

// داشبورد >  دسته بندی ها اصلی استخدام >  زیردسته ها > ویرایش زیردسته
Breadcrumbs::for('employments.categories.sub.edit', function ($trail,$main,$sub) {
    $trail->parent('employments.categories.sub.index',$main);
    $trail->push('ویرایش زیردسته', route('admin.employments.categories.sub.edit',[$main,$sub]));
});

// داشبورد >  مشاغل استخدام
Breadcrumbs::for('employments.jobs.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('مشاغل استخدام', route('admin.employments.jobs.index'));
});


// داشبورد >  درخواست های استخدام
Breadcrumbs::for('employments.employments.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('درخواست های استخدام', route('admin.employments.index'));
});


// داشبورد >  مشاغل استخدام > ایجاد شغل استخدام
Breadcrumbs::for('employments.jobs.create', function ($trail) {
    $trail->parent('employments.jobs.index', $trail);
    $trail->push('ایجاد شغل استخدام', route('admin.employments.jobs.create'));
});

// داشبورد >  مشاغل استخدام > ویرایش شغل استخدام
Breadcrumbs::for('employments.jobs.edit', function ($trail,$job) {
    $trail->parent('employments.jobs.index', $trail);
    $trail->push('ویرایش شغل استخدام', route('admin.employments.jobs.edit',$job));
});


// داشبورد >  دسته بندی ها اصلی کالاها
Breadcrumbs::for('warehousing.categories.main.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('دسته بندی ها اصلی کالاها', route('admin.warehousing.categories.main.index'));
});

// داشبورد >  دسته بندی ها اصلی کالاها> ایجاد دسته جدید
Breadcrumbs::for('warehousing.categories.main.crate', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('دسته بندی ها اصلی کالاها', route('admin.warehousing.categories.main.create'));
});

// داشبورد >  دسته بندی ها اصلی کالاها > ویرایش دسته بندی بندی
Breadcrumbs::for('warehousing.categories.main.edit', function ($trail,$cat) {
    $trail->parent('warehousing.categories.main.index', $trail);
    $trail->push('ویرایش دسته بندی', route('admin.warehousing.categories.main.edit',$cat));
});


// داشبورد >  دسته بندی ها اصلی کالاها >  زیردسته ها
Breadcrumbs::for('warehousing.categories.sub.index', function ($trail,$main) {
    $trail->parent('warehousing.categories.main.index', $trail);
    $trail->push('زیردسته ها', route('admin.warehousing.categories.sub.index',$main));
});

// داشبورد >  دسته بندی ها اصلی کالاها > ایجاد زیردسته جدید
Breadcrumbs::for('warehousing.categories.sub.create', function ($trail,$main) {
    $trail->parent('warehousing.categories.sub.index', $main);
    $trail->push('ایجاد زیردسته جدید', route('admin.warehousing.categories.sub.create',$main));
});

// داشبورد >  دسته بندی ها اصلی کالاها >  زیردسته ها > ویرایش زیردسته
Breadcrumbs::for('warehousing.categories.sub.edit', function ($trail,$main,$sub) {
    $trail->parent('warehousing.categories.sub.index',$main);
    $trail->push('ویرایش زیردسته', route('admin.warehousing.categories.sub.edit',[$main,$sub]));
});


// داشبورد > لیست کالاهای انبار اصلی
Breadcrumbs::for('warehousing.goods.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('لیست کالاهای انبار اصلی', route('admin.warehousing.goods.index'));
});

// داشبورد >  لیست کالاهای انبار اصلی >ایجاد کالا جدید
Breadcrumbs::for('warehousing.goods.create', function ($trail) {
    $trail->parent('warehousing.goods.index', $trail);
    $trail->push('ایجاد کالا جدید', route('admin.warehousing.goods.create'));
});

// داشبورد >  لیست کالاهای انبار اصلی >ویرایش کالا
Breadcrumbs::for('warehousing.goods.edit', function ($trail,$good) {
    $trail->parent('warehousing.goods.index', $trail);
    $trail->push('ویرایش کالا', route('admin.warehousing.goods.edit',$good));
});

// داشبورد > لیست انبارها
Breadcrumbs::for('warehousing.warehouses.index', function ($trail) {
    $trail->parent('home', $trail);
    $trail->push('لیست انبارها', route('admin.warehousing.warehouses.index'));
});

// داشبورد > لیست انبارها > ایجاد انبار جدید
Breadcrumbs::for('warehousing.warehouses.create', function ($trail) {
    $trail->parent('warehousing.warehouses.index', $trail);
    $trail->push('ایجاد انبار جدید', route('admin.warehousing.warehouses.create'));
});

// داشبورد > لیست انبارها > ویرایش انبار
Breadcrumbs::for('warehousing.warehouses.edit', function ($trail,$warehouse) {
    $trail->parent('warehousing.warehouses.index', $trail);
    $trail->push('ویرایش انبار', route('admin.warehousing.warehouses.edit',$warehouse));
});


// داشبورد > لیست انبارها > موجودی انبار
Breadcrumbs::for('warehousing.warehouses.stocks', function ($trail,$warehouse) {
    $trail->parent('warehousing.warehouses.index', $trail);
    $trail->push('موجودی انبار', route('admin.warehousing.warehouses.stocks',$warehouse));
});


// داشبورد > لیست انبارها > موجودی انبار> حوالات
Breadcrumbs::for('warehousing.warehouses.orders.index', function ($trail,$warehouse) {
    $trail->parent('warehousing.warehouses.stocks', $warehouse);
    $trail->push('حوالات', route('admin.warehousing.warehouses.orders.index',$warehouse));
});


