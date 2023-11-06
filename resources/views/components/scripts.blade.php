<script>
    let modalsElement = document.getElementById('livewire-modals');

    modalsElement.addEventListener('hidden.bs.modal', () => {
        Livewire.emit('resetModal');
    });

    Livewire.on('showBootstrapModal', () => {
        let modal = new Modal(modalsElement)

        modal.show();
    });

    Livewire.on('hideModal', () => {
        let modal = Modal.getInstance(modalsElement);

        modal.hide();
    });

    function showDeleteModal(model_type, id) {
        showModal('modals::delete-model', model_type, id);
    }

    function showModal(alias, param1 = null, param2 = null, param3 = null) {
        if (param1) {
            Livewire.dispatch('showModal', alias, {param1:param1, param2:param2, param3:param3});
        } else {
            Livewire.dispatch('showModal', alias);
        }
    }

    function hideModal(alias) {
        Livewire.emit('hideModal', alias);
    }
</script>
