<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            @if (isset($menu))
                <h3>{{ $menu }}</h3>
            @endif
            @if (isset($menu_desc))
                <p class="text-subtitle text-muted">{{ $menu_desc }}</p>
            @endif
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    @if (isset($url_sub1) || isset($sub1))
                        <li class="breadcrumb-item">
                            <a href="{{ $url_sub1 }}">{{ $sub1 }}</a>
                        </li>
                    @endif
                    @if (isset($url_sub2) || isset($sub2))
                        <li class="breadcrumb-item">
                            <a href="{{ $url_sub2 }}">{{ $sub2 }}</a>
                        </li>
                    @endif
                    @if (isset($menu))
                        <li class="breadcrumb-item active">
                            {{ isset($menu_active) ? $menu_active : $menu }}
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
