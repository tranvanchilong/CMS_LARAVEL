@if(!empty($item->btn_text) && !empty($item->btn_url))
<div class="btn-style-1 mt-30">
    <a class="p-3 px-4" href="{{$item->btn_url}}">{{$item->btn_text}}</a>
</div>
@endif