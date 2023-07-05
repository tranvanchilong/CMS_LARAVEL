@if(!empty($menus))
    @php
        $mainMenus=$menus;

    @endphp
    @if(isset($mainMenus['name']))
        @php
            $name=$mainMenus['name'];
            $menus=$mainMenus['data'];
        @endphp

        <h3 class="footer-title">{{ $name ?? '' }}</h3>
        <div class="footer-info-list {{$class}}">
            <ul>
                @foreach ($menus ?? [] as $row)
                    <li>
                        <a href="{{ url($row->href) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
                    </li>
                    @if (isset($row->children))
                        @foreach($row->children as $childrens)
                            @include('frontend.norda.components.footer_menu.child', ['childrens' => $childrens])
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
@endif
