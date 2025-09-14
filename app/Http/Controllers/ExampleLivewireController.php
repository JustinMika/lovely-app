<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ExampleLivewireController extends Controller
{
    /**
     * Example of how to use SweetAlert2 with Livewire
     */
    public function showAlert(): View
    {
        return view('pages.examples.sweetalert');
    }

    /**
     * Example method showing different alert types
     */
    public function triggerAlert(Request $request)
    {
        $type = $request->get('type', 'success');

        switch ($type) {
            case 'success':
                LivewireAlert::title('Succès!')
                    ->text('L\'opération a été effectuée avec succès.')
                    ->success()
                    ->show();
                break;

            case 'error':
                LivewireAlert::title('Erreur!')
                    ->text('Une erreur s\'est produite.')
                    ->error()
                    ->show();
                break;

            case 'warning':
                LivewireAlert::title('Attention!')
                    ->text('Veuillez vérifier les informations.')
                    ->warning()
                    ->show();
                break;

            case 'confirm':
                LivewireAlert::title('Êtes-vous sûr?')
                    ->text('Cette action ne peut pas être annulée.')
                    ->warning()
                    ->withConfirmButton('Oui, supprimer!')
                    ->withCancelButton('Annuler')
                    ->confirmButtonColor('#d33')
                    ->onConfirm('deleteConfirmed')
                    ->show();
                break;

            case 'toast':
                LivewireAlert::title('Notification!')
                    ->text('Message de notification.')
                    ->info()
                    ->toast()
                    ->position('top-end')
                    ->timer(3000)
                    ->show();
                break;
        }

        return back();
    }
}
