@if($item->hide_title==0)
    <div class="section-title {{$item->feature_position==1 ? 'text-center' : ($item->feature_position==0 ? 'text-left' : 'text-right')}}">
        <h1>{{($item->feature_title)}}</h1>
        <span>{{($item->feature_subtitle)}}</span>
    </div>
@endif