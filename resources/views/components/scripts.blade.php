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

    function showModal(alias, param1 = null, param2 = null, param3 = null) {
        Livewire.emit('showModal', alias, param1, param2, param3);
    }

    function hideModal(alias) {
        Livewire.emit('hideModal', alias);
    }
</script>
