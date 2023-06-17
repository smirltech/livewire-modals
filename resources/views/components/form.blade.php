@props(['title' => null, 'footer' => null,'theme'=>'primary','submit'=>'submit'])
<div class="modal-dialog">
    <form wire:submit.prevent="{{$submit}}">
        <div class="modal-content">
            @if($title or $header)
                <div class="modal-header bg-{{$theme}}">
                    {{$header}}
                    <h5 class="modal-title">{{$title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif
            <div class="modal-body">
                {{$slot}}
                <div class="modal-footer">
                    @isset($footer)
                        {{$footer}}
                    @else
                        <x-form::button
                                theme="secondary"
                                icon="close"
                                type="button"
                                data-bs-dismiss="modal"
                                label="Fermer"/>
                        <x-form::button
                                icon="check"
                                type="submit"
                                label="Soumettre"/>
                    @endisset
                </div>
            </div>

        </div>
    </form>
</div>


