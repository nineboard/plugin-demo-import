@section('page_title')
    <h2>{{ xe_trans('demo_import::demoImport') }}</h2>
@endsection

@section('page_description')
    {{ xe_trans('demo_import::settingMenuDescription') }}
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body">
                    <div class="first_guide">
                        {!! xe_trans('demo_import::firstImportGuide') !!}
                    </div>

                    <div class="second_guide">
                        {{ xe_trans('demo_import::secondImportGuideTitle') }}
                    </div>
                    <div class="second_guide">
                        {{ xe_trans('demo_import::secondImportGuideFirstSub') }}
                    </div>
                    <div class="second_guide">
                        {{ xe_trans('demo_import::secondImportGuideSecondSub') }}
                    </div>
                    <div class="second_guide">
                        {{ xe_trans('demo_import::secondImportGuideThirdSub') }}
                    </div>
                    <div class="second_guide">
                        {{ xe_trans('demo_import::secondImportGuideFourth') }}
                    </div>
                </div>

                <div class="panel-body">
                    <form method="post" action="{{ route('demo_import.store_theme') }}">
                        {!! csrf_field() !!}
                        <ul class="list-group">
                            @foreach ($demoSupplyThemes as $theme)
                                <li class="list-group-item theme_item">
                                <div class="list-group-item-checkbox">
                                    <label class="xe-label">
                                        <input type="checkbox" class="import_checkbox __xe_checkbox">
                                        <span class="xe-input-helper"></span>
                                        <span class="xe-label-text xe-sr-only">체크박스</span>
                                        <input type="hidden" class="theme_id" name="themeIds[]" value="{{ $theme->getId() }}" disabled>
                                    </label>
                                </div>

                                <div class="left-group">
                                    <span class="plugin-title">{{ $theme->getTitle() }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>

                        <button type="button" class="import_btn xe-btn">{{ xe_trans('demo_import::demoDataImport') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="success_row row">
    <div class="col-sm-12">
        <div class="panel-group success_panel">
            <div class="panel">
                <div class="panel-body">
                    <div class="import_success_title">
                        {!! xe_trans('demo_import::successMessageTitle') !!}
                    </div>
                    <div class="import_success_sub">
                        {!! xe_trans('demo_import::successMessageFirstSub') !!}
                    </div>
                    <div class="import_success_sub">
                        {!! xe_trans('demo_import::successMessageSecondSub') !!}
                    </div>
                </div>

                <div class="panel-body">
                    <a href="{{ route('settings.menu.index') }}" class="xe-btn">{{ xe_trans('demo_import::checkToSitemap')  }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fail_row row">
    <div class="col-sm-12">
        <div class="panel-group fail_panel">
            <div class="panel">
                <div class="panel-body">
                    <div class="import_success_title">
                        {!! xe_trans('demo_import::failMessageTitle') !!}
                    </div>
                    <div class="import_success_sub">
                        {!! xe_trans('demo_import::failMessageSub') !!}
                    </div>
                </div>

                <div class="panel-body">
                    <a href="{{ route('settings.menu.index') }}" class="xe-btn">{{ xe_trans('demo_import::checkToSitemap')  }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
