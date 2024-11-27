
<!--  menu sidebar -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li>
                    <a href="{{ route('admin.home') }}" class="waves-effect">
                        <i class="fa fa-home"></i>
                        <span class="IRANYekanRegular">صفحه اصلی</span>
                    </a>
                </li>

                @if(Auth::guard('admin')->user()->can('reception.index') ||
                    Auth::guard('admin')->user()->can('reserves.index') ||
                    Auth::guard('admin')->user()->can('article.index') ||
                    Auth::guard('admin')->user()->can('article.categorys.index') ||
                    Auth::guard('admin')->user()->can('analysis.ask.index') ||
                    Auth::guard('admin')->user()->can('tickets.index') ||
                    Auth::guard('admin')->user()->can('departments.index') ||
                    Auth::guard('admin')->user()->can('socialmedia.index') ||
                    Auth::guard('admin')->user()->can('phones.index') ||
                    Auth::guard('admin')->user()->can('messages.index') ||
                    Auth::guard('admin')->user()->can('admins.index') ||
                    Auth::guard('admin')->user()->can('users.index') ||
                    Auth::guard('admin')->user()->can('levels.index') ||
                    Auth::guard('admin')->user()->can('roles.index') ||
                    Auth::guard('admin')->user()->can('numbers.index') ||
                    Auth::guard('admin')->user()->can('numbers.advisers'))
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="ti-align-left"></i>
                            <span class="IRANYekanRegular">عملیات</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">

                            @if(Auth::guard('admin')->user()->can('reception.index'))
                                <li>
                                    <a href="{{ route('admin.receptions.index') }}" class="waves-effect">
                                        <i class="ti-pencil-alt"></i>
                                        @if($openReceptionCount>0)
                                            <span class="badge badge-info float-right">{{ $openReceptionCount }}</span>
                                        @endif
                                        <span class="IRANYekanRegular">پذیرش</span>
                                    </a>
                                </li>
                            @endif


                            @if(Auth::guard('admin')->user()->can('reserves.index'))
                                <li>
                                    <a href="{{ route('admin.reserves.index') }}" class="waves-effect">
                                        <i class="wi wi-time-10"></i>
                                        <span class="IRANYekanRegular">رزروها</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('employments.index'))
                                <li class="IRANYekanRegular">
                                    <a href="{{ route('admin.employments.index') }}">
                                        <i class="mdi mdi-briefcase"></i>
                                        <span>استخدام</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('article.index') || Auth::guard('admin')->user()->can('article.categorys.index'))
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i class="fas fa-newspaper"></i>
                                        <span class="IRANYekanRegular">مقالات</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="nav-second-level" aria-expanded="false">

                                        @if(Auth::guard('admin')->user()->can('article.index'))
                                            <li>
                                                <a href="{{ route('admin.article.index') }}" class="waves-effect">
                                                    <i class="fas fa-newspaper"></i>
                                                    <span class="IRANYekanRegular">مقالات</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::guard('admin')->user()->can('article.categorys.index'))
                                            <li>
                                                <a href="{{ route('admin.article.categorys.index') }}" class="waves-effect">
                                                    <i class="fas fa-layer-group"></i>
                                                    <span class="IRANYekanRegular">دسته بندی‌ها</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('analysis.ask.index'))
                                <li>
                                    <a href="{{ route('admin.ask.analysis.index') }}" class="waves-effect">
                                        <i class="fa fa-spinner"></i>
                                        @if($doctorAnalise>0)
                                            <span class="badge badge-info float-right">{{ $doctorAnalise }}</span>
                                        @endif
                                        @if($analiseCount>0)
                                            <span class="badge badge-info float-right">{{ $analiseCount }}</span>
                                        @endif
                                        <span class="IRANYekanRegular">درخواست های آنالیز</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('tickets.index') ||
                                Auth::guard('admin')->user()->can('departments.index'))
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i class="fa fa-headphones"></i>
                                        <span class="IRANYekanRegular">پشتیبانی</span>
                                        <span class="menu-arrow"></span>
                                    </a>

                                    @if(Auth::guard('admin')->user()->can('tickets.index'))
                                        <ul class="nav-second-level" aria-expanded="false">
                                            <li class="IRANYekanRegular">
                                                <a href="{{ route('admin.tickets.index') }}">
                                                    <i class="fas fa-ticket-alt"></i>
                                                    <span>تیکت ها</span>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif

                                    @if(Auth::guard('admin')->user()->can('departments.index'))
                                        <ul class="nav-second-level" aria-expanded="false">
                                            <li class="IRANYekanRegular">
                                                <a href="{{ route('admin.departments.index') }}">
                                                    <i class="fas fa-building"></i>
                                                    <span>واحدها</span>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('notifications.admins.index') || Auth::guard('admin')->user()->can('notifications.users.index'))
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i class="fas fa-bell"></i>
                                        <span class="IRANYekanRegular">اعلانات</span>
                                        <span class="menu-arrow"></span>
                                    </a>

                                    @if(Auth::guard('admin')->user()->can('notifications.admins.index'))
                                        <ul class="nav-second-level" aria-expanded="false">
                                            <li class="IRANYekanRegular">
                                                <a href="{{ route('admin.notifications.users.index') }}" class="waves-effect">
                                                    <i class="fas fa-user"></i>
                                                    <span class="IRANYekanRegular">کاربران</span>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif

                                    @if(Auth::guard('admin')->user()->can('notifications.users.index'))
                                        <ul class="nav-second-level" aria-expanded="false">
                                            <li class="IRANYekanRegular">
                                                <a href="{{ route('admin.notifications.admins.index') }}" class="waves-effect">
                                                    <i class="fas fa-user"></i>
                                                    <span class="IRANYekanRegular">ادمین ها</span>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif

                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('highlights.index'))
                                <li>
                                    <a href="{{ route('admin.stories.index') }}" class="waves-effect">
                                        <i class="fas fa-image"></i>
                                        <span class="IRANYekanRegular">استوری ها</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('rewiewGroups.index') || Auth::guard('admin')->user()->can('reviews.index'))
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i class="fas fa-poll"></i>
                                        <span class="IRANYekanRegular">نظرسنجی</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="nav-second-level" aria-expanded="false">

                                        @if(Auth::guard('admin')->user()->can('rewiewGroups.index'))
                                            <li>
                                                <a href="{{ route('admin.rewiewGroups.index') }}" class="waves-effect">
                                                    <i class="fas fa-layer-group"></i>
                                                    <span class="IRANYekanRegular">گروه بازخوردها</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::guard('admin')->user()->can('reviews.index'))
                                            <li>
                                                <a href="{{ route('admin.reviews.index') }}" class="waves-effect">
                                                    <i class="fa fa-comments"></i>
                                                    <span class="IRANYekanRegular">بازخوردها</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('gallery.index'))
                                <li>
                                    <a href="{{ route('admin.gallery.index') }}" class="waves-effect">
                                        <i class="fas fa-images"></i>
                                        <span class="IRANYekanRegular">گالری تصاویر</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('shop.products.index') ||
                                Auth::guard('admin')->user()->can('shop.products.categories.index') ||
                                Auth::guard('admin')->user()->can('shop.products.sells.index'))
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="IRANYekanRegular">فروشگاه</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="nav-second-level" aria-expanded="false">

                                        @if(Auth::guard('admin')->user()->can('shop.products.index'))
                                            <li>
                                                <a href="{{ route('admin.shop.products.index') }}" class="waves-effect">
                                                    <i class="fab fa-product-hunt"></i>
                                                    <span class="IRANYekanRegular">محصولات</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::guard('admin')->user()->can('shop.products.categories.index'))
                                            <li>
                                                <a href="{{ route('admin.shop.products.categories.index') }}" class="waves-effect">
                                                    <i class="fas fa-layer-group"></i>
                                                    <span class="IRANYekanRegular">دسته بندی‌ها</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::guard('admin')->user()->can('shop.products.sells.index'))
                                            <li>
                                                <a href="{{ route('admin.shop.sells.index') }}" class="waves-effect">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span class="IRANYekanRegular">فروش‌ها</span>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif


                            @if(Auth::guard('admin')->user()->can('portfolios.index'))
                                <li>
                                    <a href="{{ route('admin.portfolios.index') }}" class="waves-effect">
                                        <i class="fas fa-suitcase"></i>
                                        <span class="IRANYekanRegular">نمونه کارها</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('comments.index'))
                                <li>
                                    <a href="{{ route('admin.comments.index') }}" class="waves-effect">
                                        <i class="fa fa-comments"></i>
                                            <?php
                                            $unapproved = App\Models\Comment::where('approved',App\Enums\CommentStatus::unapproved)->count();
                                            ?>
                                        @if($unapproved>0)
                                            <span class="badge badge-info float-right">{{ $unapproved }}</span>
                                        @endif
                                        <span class="IRANYekanRegular">نظرات</span>
                                    </a>
                                </li>
                            @endif


                            @if(Auth::guard('admin')->user()->can('numbers.index'))
                                <li>
                                    <a href="{{ route('admin.numbers.index') }}" class="waves-effect">
                                        <i class="fa fa-phone"></i>
                                        @if($waitingAdviser>0)
                                            <span class="badge badge-info float-right">{{ $waitingAdviser }}</span>
                                        @endif
                                        @if($operatorCount>0)
                                            <span class="badge badge-info float-right">{{ $operatorCount }}</span>
                                        @endif
                                        <span class="IRANYekanRegular">مشاوره تلفنی</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('numbers.advisers'))
                                <li>
                                    <a href="{{ route('admin.advisers.index') }}" class="waves-effect">
                                        <i class="fa fa-phone"></i>
                                        @if($adviserCount>0)
                                            <span class="badge badge-info float-right">{{ $adviserCount }}</span>
                                        @endif
                                        @if($acceptCount>0)
                                            <span class="badge badge-info float-right">{{ $acceptCount }}</span>
                                        @endif
                                        <span class="IRANYekanRegular">مشاوره</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                 @endif

                @if(Auth::guard('admin')->user()->can('static') ||
                    Auth::guard('admin')->user()->can('sms.history') ||
                    Auth::guard('admin')->user()->can('reserves.consumptions.reports'))
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="ti-pie-chart"></i>
                            <span class="IRANYekanRegular">گزارشات</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">

                            @if(Auth::guard('admin')->user()->can('static'))
                                <li>
                                    <a href="{{ route('admin.static') }}" class="waves-effect">
                                        <i class="ti-pie-chart"></i>
                                        <span class="IRANYekanRegular">آمار</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->can('complications.report'))
                               <li>
                                    <a href="{{ route('admin.complications.registered') }}" class="waves-effect">
                                        <i class="fa fa-exclamation"></i>
                                        <span class="IRANYekanRegular">عوارض</span>
                                    </a>
                                </li>
                            @endif

                           @if(Auth::guard('admin')->user()->can('reserves.consumptions.reports'))
                            <li>
                                <a href="{{ route('admin.reports.consumptions') }}" class="waves-effect">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="IRANYekanRegular">مواد مصرفی</span>
                                </a>
                            </li>
                           @endif

                            @if(Auth::guard('admin')->user()->can('sms.history'))
                            <li>
                                <a href="{{ route('admin.sms.history') }}" class="waves-effect">
                                    <i class="fas fa-history"></i>
                                    <span class="IRANYekanRegular">تاریخچه پیامک ها</span>
                                </a>
                            </li>
                            @endif

                        </ul>
                    </li>
                 @endif

                @if(Auth::guard('admin')->user()->can('branchs.index') ||
                Auth::guard('admin')->user()->can('jobs.index') ||
                Auth::guard('admin')->user()->can('provinces.index') ||
                Auth::guard('admin')->user()->can('services.index') ||
                Auth::guard('admin')->user()->can('services.categories.index') ||
                Auth::guard('admin')->user()->can('doctors.index') ||
                Auth::guard('admin')->user()->can('employments.jobs.index')||
                Auth::guard('admin')->user()->can('employments.categories.main.index')||
                Auth::guard('admin')->user()->can('discounts.index') ||
                Auth::guard('admin')->user()->can('warehousing.categories.main.index')||
                Auth::guard('admin')->user()->can('warehousing.categories.sub.index') ||
                Auth::guard('admin')->user()->can('warehousing.goods.index') ||
                Auth::guard('admin')->user()->can('warehousing.warehouses.index') ||
                Auth::guard('admin')->user()->can('warehousing.lasers.index'))
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="ti-write"></i>
                        <span class="IRANYekanRegular">تعاریف</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">

                    @if(Auth::guard('admin')->user()->can('branchs.index'))
                        <li>
                            <a href="{{ route('admin.branchs.index') }}" class="waves-effect">
                                <i class="fas fa-code-branch"></i>
                                <span class="IRANYekanRegular">شعبه ها</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::guard('admin')->user()->can('festivals.index'))
                        <li>
                            <a href="{{ route('admin.festivals.index') }}" class="waves-effect">
                                <i class="ti-wand"></i>
                                <span class="IRANYekanRegular">جشنواره ها</span>
                            </a>
                        </li>
                    @endif


                   @if(Auth::guard('admin')->user()->can('admin.employments.jobs.index')||
                       Auth::guard('admin')->user()->can('admin.employments.categories.main.index'))
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            <i class="ti-money"></i>
                            <span class="IRANYekanRegular">استخدام</span>
                            <span class="menu-arrow"></span>
                        </a>
                        @if(Auth::guard('admin')->user()->can('employments.jobs.index'))
                        <ul class="nav-second-level" aria-expanded="false">
                            <li class="IRANYekanRegular">
                                <a href="{{ route('admin.employments.categories.main.index') }}">
                                    <i class="fas fa-layer-group"></i>
                                    <span>دسته بندی ها</span>
                                </a>
                            </li>
                        </ul>
                        @endif
                        @if(Auth::guard('admin')->user()->can('employments.jobs.index'))
                        <ul class="nav-second-level" aria-expanded="false">
                          <li class="IRANYekanRegular">
                              <a href="{{ route('employments.jobs.index') }}">
                                  <i class="fa fa-graduation-cap"></i>
                                  <span>مشاغل</span>
                              </a>
                          </li>
                        </ul>
                        @endif
                     </li>
                   @endif
                   @if(Auth::guard('admin')->user()->can('warehousing.categories.main.index')||
                       Auth::guard('admin')->user()->can('warehousing.categories.sub.index') ||
                       Auth::guard('admin')->user()->can('warehousing.goods.index') ||
                       Auth::guard('admin')->user()->can('warehousing.warehouses.index') ||
                       Auth::guard('admin')->user()->can('warehousing.lasers.index'))
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="mdi mdi-city"></i>
                                <span class="IRANYekanRegular">انبارداری</span>
                                <span class="menu-arrow"></span>
                            </a>
                            @if(Auth::guard('admin')->user()->can('warehousing.categories.main.index'))
                             <ul class="nav-second-level" aria-expanded="false">
                                <li class="IRANYekanRegular">
                                    <a href="{{ route('admin.warehousing.categories.main.index') }}">
                                        <i class="fas fa-layer-group"></i>
                                        <span>دسته بندی ها</span>
                                    </a>
                                </li>
                             </ul>
                            @endif

                            @if(Auth::guard('admin')->user()->can('warehousing.goods.index'))
                            <ul class="nav-second-level" aria-expanded="false">
                                <li class="IRANYekanRegular">
                                    <a href="{{ route('admin.warehousing.goods.index') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>کالا ها</span>
                                    </a>
                                </li>
                            </ul>
                            @endif

                            @if(Auth::guard('admin')->user()->can('warehousing.lasers.index'))
                            <ul class="nav-second-level" aria-expanded="false">
                                <li class="IRANYekanRegular">
                                    <a href="{{ route('admin.warehousing.lasers.index') }}">
                                        <i class="fas fa-deaf"></i>
                                        <span>دستگاهای لیزر</span>
                                    </a>
                                </li>
                            </ul>
                            @endif

                            @if(Auth::guard('admin')->user()->can('warehousing.warehouses.index'))
                            <ul class="nav-second-level" aria-expanded="false">
                                <li class="IRANYekanRegular">
                                    <a href="{{ route('admin.warehousing.warehouses.index') }}">
                                        <i class="mdi mdi-city"></i>
                                        <span>انبارها</span>
                                    </a>
                                </li>
                            </ul>
                            @endif

                        </li>

                   @endif

                    @if(Auth::guard('admin')->user()->can('complications.index'))
                    <li>
                      <a href="{{ route('admin.complications.index') }}" class="waves-effect">
                          <i class="fa fa-exclamation"></i>
                          <span class="IRANYekanRegular">لیست عوارض</span>
                      </a>
                    </li>
                   @endif

                    @if(Auth::guard('admin')->user()->can('jobs.index'))
                      <li>
                          <a href="{{ route('admin.jobs.index') }}" class="waves-effect">
                              <i class="fa fa-graduation-cap"></i>
                              <span class="IRANYekanRegular">مشاغل</span>
                          </a>
                      </li>
                    @endif

                    @if(Auth::guard('admin')->user()->can('provinces.index'))
                      <li>
                          <a href="{{ route('admin.provinces.index') }}" class="waves-effect">
                              <i class="fas fa-map-marker"></i>
                              <span class="IRANYekanRegular">استان‌ها</span>
                          </a>
                      </li>
                    @endif

                    @if(Auth::guard('admin')->user()->can('services.index') || Auth::guard('admin')->user()->can('services.categories.index'))
                      <li>
                          <a href="javascript: void(0);" class="waves-effect">
                              <i class="fab fa-servicestack"></i>
                              <span class="IRANYekanRegular">خدمات</span>
                              <span class="menu-arrow"></span>
                          </a>
                          <ul class="nav-second-level" aria-expanded="false">

                              @if(Auth::guard('admin')->user()->can('services.categories.index'))
                                  <li>
                                      <a href="{{ route('admin.services.categories.index') }}" class="waves-effect">
                                          <i class="fas fa-layer-group"></i>
                                          <span class="IRANYekanRegular">دسته بندی‌ها</span>
                                      </a>
                                  </li>
                              @endif

                              @if(Auth::guard('admin')->user()->can('services.index'))
                                  <li>
                                      <a href="{{ route('admin.services.index') }}" class="waves-effect">
                                          <i class="fab fa-servicestack"></i>
                                          <span class="IRANYekanRegular">سرگروه خدمات</span>
                                      </a>
                                  </li>
                              @endif

                              @if(Auth::guard('admin')->user()->can('services.details.index'))
                                  <li>
                                      <a href="{{ route('admin.details.index') }}" class="waves-effect">
                                          <i class="fa fa-info"></i>
                                          <span class="IRANYekanRegular">خدمات</span>
                                      </a>
                                  </li>
                              @endif

                              @if(Auth::guard('admin')->user()->can('services.lasers.index'))
                                  <li>
                                      <a href="{{ route('admin.services.lasers.index') }}" class="waves-effect">
                                          <i class="fas fa-deaf"></i>
                                          <span class="IRANYekanRegular">خدمات لیزر</span>
                                      </a>
                                  </li>
                              @endif


                              @if(Auth::guard('admin')->user()->can('analysis.index'))
                                  <li>
                                      <a href="{{ route('admin.analysis.index') }}" class="waves-effect">
                                          <i class="fa fa-spinner"></i>
                                          <span class="IRANYekanRegular">آنالیز</span>
                                      </a>
                                  </li>
                              @endif

                          </ul>
                      </li>
                    @endif

                    @if(Auth::guard('admin')->user()->can('doctors.index'))
                      <li>
                          <a href="{{ route('admin.doctors.index') }}" class="waves-effect">
                              <i class="fas fa-user-md"></i>
                              <span class="IRANYekanRegular">پزشکان</span>
                          </a>
                      </li>
                    @endif

                    @if(Auth::guard('admin')->user()->can('faq.index'))
                      <li>
                          <a href="{{ route('admin.faq.index') }}" class="waves-effect">
                              <i class="fas fa-question"></i>
                              <span class="IRANYekanRegular">پرسش و پاسخ</span>
                          </a>
                      </li>
                    @endif

                    @if(Auth::guard('admin')->user()->can('discounts.index'))
                      <li>
                          <a href="{{ route('admin.discounts.index') }}" class="waves-effect">
                              <i class="fas fa-percent"></i>
                              <span class="IRANYekanRegular">کد‌ تخفیف</span>
                          </a>
                      </li>
                    @endif

            </ul>
        </li>
        @endif

        @if(Auth::guard('admin')->user()->can('luck.index') ||
        Auth::guard('admin')->user()->can('socialmedia.index') ||
        Auth::guard('admin')->user()->can('phones.index') ||
        Auth::guard('admin')->user()->can('messages.index') ||
        Auth::guard('admin')->user()->can('departments.index') ||
        Auth::guard('admin')->user()->can('admins.index') ||
        Auth::guard('admin')->user()->can('users.index') ||
        Auth::guard('admin')->user()->can('levels.index') ||
        Auth::guard('admin')->user()->can('roles.index'))
        <li>
        <a href="javascript: void(0);" class="waves-effect">
        <i class="ti-settings"></i>
        <span class="IRANYekanRegular">تنظیمات</span>
        <span class="menu-arrow"></span>
        </a>
        <ul class="nav-second-level" aria-expanded="false">

        @if(Auth::guard('admin')->user()->can('luck.index'))
        <li>
        <a href="{{ route('admin.luck.index') }}" class="waves-effect">
          <i class="fas fa-hockey-puck"></i>
          <span class="IRANYekanRegular">گردونه شانس</span>
        </a>
        </li>
        @endif

        @if(Auth::guard('admin')->user()->can('socialmedia.index') || Auth::guard('admin')->user()->can('phones.index')
        || Auth::guard('admin')->user()->can('messages.index') || Auth::guard('admin')->user()->can('departments.index'))
        <li>
        <a href="javascript: void(0);" class="waves-effect">
          <i class="fas fa-address-book"></i>
          <span class="IRANYekanRegular">راه های ارتباطی</span>
          <span class="menu-arrow"></span>
        </a>

        @if(Auth::guard('admin')->user()->can('socialmedia.index'))
          <ul class="nav-second-level" aria-expanded="false">
              <li class="IRANYekanRegular">
                  <a href="{{ route('admin.socialmedia.index') }}">
                      <i class="fab fa-instagram"></i>
                      شبکه های اجتماعی
                  </a>
              </li>
          </ul>
        @endif

        @if(Auth::guard('admin')->user()->can('phones.index'))
          <ul class="nav-second-level" aria-expanded="false">
              <li class="IRANYekanRegular">
                  <a href="{{ route('admin.phones.index') }}">
                      <i class="fas fa-phone"></i>
                      تلفن های تماس
                  </a>
              </li>
          </ul>
        @endif

        @if(Auth::guard('admin')->user()->can('messages.index'))
          <ul class="nav-second-level" aria-expanded="false">
              <li class="IRANYekanRegular">
                  <a href="{{ route('admin.messages.index') }}">
                      <i class="fas fa-envelope-square"></i>
                      پیام ها
                  </a>
              </li>
          </ul>
        @endif

        </li>
        @endif

        @if(Auth::guard('admin')->user()->can('admins.index') ||
        Auth::guard('admin')->user()->can('users.index') ||
        Auth::guard('admin')->user()->can('levels.index') ||
        Auth::guard('admin')->user()->can('roles.index'))
        <li>
        <a href="javascript: void(0);" class="waves-effect">
          <i class="fa fa-users"></i>
          <span class="IRANYekanRegular">کاربران</span>
          <span class="menu-arrow"></span>
        </a>

        @if(Auth::guard('admin')->user()->can('admins.index'))
          <ul class="nav-second-level" aria-expanded="false">
              <li>
                  <a href="{{ route('admin.admins.index') }}" class="waves-effect">
                      <i class="fa fa-user"></i>
                      <span class="IRANYekanRegular">ادمین ها</span>
                  </a>
              </li>
          </ul>
        @endif

        @if(Auth::guard('admin')->user()->can('users.index'))
          <ul class="nav-second-level" aria-expanded="false">
              <li>
                  <a href="{{ route('admin.users.index') }}" class="waves-effect">
                      <i class="fa fa-user"></i>
                      <span class="IRANYekanRegular">کاربران عادی</span>
                  </a>
              </li>
          </ul>
        @endif

        @if(Auth::guard('admin')->user()->can('levels.index'))
          <ul class="nav-second-level" aria-expanded="false">
              <li>
                  <a href="{{ route('admin.levels.index') }}" class="waves-effect">
                      <i class="fas fa-layer-group"></i>
                      <span class="IRANYekanRegular">سطوح</span>
                  </a>
              </li>
          </ul>
        @endif

        @if(Auth::guard('admin')->user()->can('roles.index'))
          <ul class="nav-second-level" aria-expanded="false">
              <li>
                  <a href="{{ route('admin.roles.index') }}" class="waves-effect">
                      <i class="fas fa-universal-access"></i>
                      <span class="IRANYekanRegular">نقش‌ها</span>
                  </a>
              </li>
          </ul>
        @endif
        </li>
        @endif
        </ul>
    </li>
    @endif
</ul>

</div>
<!-- End Sidebar -->

<div class="clearfix"></div>

</div>
<!-- Sidebar -left -->

</div>
<!-- end menu sidbar -->
