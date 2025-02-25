<?php

namespace SmirlTech\LivewireModals\Livewire;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
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
    #[NoReturn]
    public function mount(string $model_type, string $model_id): void
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
        $this->label = (method_exists($this->model, 'label') ? $this->model->label() : null)
            ?? $this->model->name
            ?? $this->model->nom
            ?? $this->model->title
            ?? $this->model->label
            ?? $this->model->code
            ?? $this->model->id;
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('modals::livewire.delete-model');
    }

    // Delete the model
    public function delete(): void
    {
        $this->emit('hideModal');
        try {
            $this->model->delete();
            $this->emit('modelDeleted');
            $this->flash('success', 'L\'élément a bien été supprimé', [], URL::previous());

        } catch (QueryException $e) {
            Log::error("Delete failed for model ID {$this->model->id}: " . $e->getMessage());

            $message = $this->getHumanizedErrorMessage($e->getMessage(), $this->model->id);

            // dialog
            $this->alert(type: 'error', message: $message, options: ['toast' => false, 'timer' => 5000]);
        }
    }

    private function getHumanizedErrorMessage(string $errorMessage, string|int $modelId): string
    {
        if (str_contains($errorMessage, 'foreign key constraint fails')) {
            // Extract the foreign key constraint name
            if (preg_match("/CONSTRAINT `(.+?)`/", $errorMessage, $matches)) {
                $constraintName = $matches[1] ?? 'unknown_constraint';
                return "Impossible de supprimer l'élément (ID: {$modelId}) car il est référencé par la contrainte '{$constraintName}'.";
            }

            return "Impossible de supprimer l'élément (ID: {$modelId}) à cause d'une contrainte de clé étrangère.";
        }

        return "Une erreur est survenue lors de la suppression de l'élément (ID: {$modelId}).";
    }


}
