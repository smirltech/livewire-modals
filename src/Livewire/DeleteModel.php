<?php

namespace SmirlTech\LivewireModals\Livewire;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\URL;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

class DeleteModel extends Component
{

    use AuthorizesRequests, LivewireAlert;

    public Model $model;
    public string $label;

    /**
     * @throws Exception
     */
    #[NoReturn] public function mount(string $model_type, string $model_id): void
    {

        $model_type = 'App\\Models\\' . $model_type;

        if ($model_id && class_exists($model_type)) {
            $this->model = $model_type::find($model_id);
            if (!$this->model->exists) {
                throw new Exception("No model found with id '{$model_id}'");
            }
        } else {
            throw new Exception("The model '{$model_type}' does not exist");
        }

        $this->authorize('delete', $this->model);
        $this->label = $this->model->name ?? $this->model->nom ?? $this->model->title ?? $this->model->label ?? $this->model->code ?? $this->model->id;
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modals::livewire.delete-model');
    }

    // delete the model
    public function delete(): void
    {
        $this->emit('hideModal');
        try {
            $this->model->delete();
            $this->dispatch('modelDeleted');
            $this->flash('success', 'L\'élément a bien été supprimé', [], URL::previous());
        } catch (QueryException $e) {
            $this->alert('error', $e->errorInfo[2], $this->getHumanizedErrorMessage($e->errorInfo));
        }
    }

    private function getHumanizedErrorMessage(?array $errorInfo)
    {
        if (isset($errorInfo[2])) {
            $message = $errorInfo[2];
            if (str_contains($message, 'foreign key constraint fails')) {
                $message = 'Impossible de supprimer cet élément car il est utilisé par d\'autres éléments';
            }
            return $message;
        }
        return 'Une erreur est survenue';
    }
}
