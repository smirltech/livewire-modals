@props(['title' => null, 'footer' => null,'theme'=>'primary'])
<!-- Modal -->
<div class="modal-dialog">
    <div class="modal-content">
        @if($title)
            <div class="modal-header bg-{{$theme}}">
                <h5 class="modal-title">{{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        @endif
        <div class="modal-body">
            {{$slot}}
            @isset($footer)
                <div class="modal-footer">
                    {{$footer}}
                </div>
            @endisset
        </div>
    </div>
</div>


