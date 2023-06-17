@props(['submit'=>'submit','footer'=>null,'header'=>null])
<x-modals::base {{$attributes }}>
    <x-slot name="header">
        {{$header}}
    </x-slot>
    <form wire:submit.prevent="{{$submit}}">
        {{$slot}}
        <div class="modal-footer">
            @if($footer)
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
            @endif
        </div>
    </form>
</x-modals::base>
