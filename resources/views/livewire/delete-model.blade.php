<x-modals::base theme="danger">
    <x-slot name="title">Suppression de {{$label}}</x-slot>
    <div class="row">
        <div class="col-md-12">
            <p>Êtes-vous sûr de vouloir supprimer le {{class_basename($model)}} <strong
                        class="badge badge-danger">{{$label}}</strong> ?<br>De
                plus, cette action est
                irréversible.</p>
        </div>
    </div>

    <x-slot name="footer">
        <button
                wire:click="delete"
                class="btn btn-danger">
            Supprimer
        </button>
        <button
                wire:click="$emit('hideModal')"
                class="btn btn-secondary">
            Annuler
        </button>
    </x-slot>
</x-modals::base>
