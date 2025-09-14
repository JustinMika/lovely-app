<?php

namespace App\Livewire;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ExampleAlert extends Component
{
    public function showSuccess()
    {
        LivewireAlert::title('Succès!')
            ->text('L\'opération a été effectuée avec succès.')
            ->success()
            ->show();
    }

    public function showError()
    {
        LivewireAlert::title('Erreur!')
            ->text('Une erreur s\'est produite lors de l\'opération.')
            ->error()
            ->show();
    }

    public function showWarning()
    {
        LivewireAlert::title('Attention!')
            ->text('Veuillez vérifier les informations saisies.')
            ->warning()
            ->show();
    }

    public function showConfirm()
    {
        LivewireAlert::title('Êtes-vous sûr?')
            ->text('Cette action ne peut pas être annulée.')
            ->warning()
            ->withConfirmButton('Oui, supprimer!')
            ->withCancelButton('Annuler')
            ->confirmButtonColor('#d33')
            ->onConfirm('deleteConfirmed')
            ->show();
    }

    public function showToast()
    {
        LivewireAlert::title('Notification!')
            ->text('Message de notification toast.')
            ->info()
            ->toast()
            ->position('top-end')
            ->timer(3000)
            ->show();
    }

    public function deleteConfirmed()
    {
        // Logique de suppression ici
        LivewireAlert::title('Supprimé!')
            ->text('L\'élément a été supprimé avec succès.')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.example-alert');
    }
}
