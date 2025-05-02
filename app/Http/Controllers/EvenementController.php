<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class EvenementController extends Controller
{
    /**
     * Affiche la liste des événements.
     */
    public function index()
    {
        $evenements = Evenement::paginate(10);
        return view('pages.evenements.index', compact('evenements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'description' => 'required|string',
            'date_evenement' => 'required|date',
            'lieu' => 'nullable|string',
            'limite_scan_heure' => 'required|integer',
            'nombre_scan_max' => 'required|integer',
        ]);

        Log::info('Début création événement');

        // Générer un UUID temporaire pour le nom de fichier du QR Code
        $tempUuid = Str::uuid();
        $filename = 'qrcodes/' . $tempUuid . '.png';

        // URL dynamique à utiliser pour la redirection après scan
        $url = url('participation/' . Str::slug($data['nom']));

        try {
            // Utiliser le Builder pour créer le QR code sans logo
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($url)  // L'URL à inclure dans le QR code
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                ->build();

            // Sauvegarder l'image générée dans le dossier public/storage
            Storage::disk('public')->put($filename, $result->getString());
            Log::info("QR code temporaire enregistré : $filename");

            $data['qr_code_path'] = $filename;

            // Création de l'événement avec le chemin du QR code
            $evenement = Evenement::create($data);
            Log::info("Événement créé avec ID : {$evenement->id}");

            return redirect()->back()->with('success', 'Événement créé avec QR Code généré.');
        } catch (\Exception $e) {
            Log::error('Erreur QR Code : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la génération du QR Code.');
        }
    }


    public function update(Request $request, Evenement $evenement)
{
    // Valider les données de l'événement
    $data = $request->validate([
        'nom' => 'required|string',
        'description' => 'required|string',
        'date_evenement' => 'required|date',
        'lieu' => 'nullable|string',
        'limite_scan_heure' => 'required|integer',
        'nombre_scan_max' => 'required|integer',
    ]);

    // Vérifier si le nom de l'événement a changé
    if ($evenement->nom !== $data['nom']) {
        // Générer un nouveau QR code avec le nouveau nom de l'événement
        $tempUuid = Str::uuid();
        $filename = 'qrcodes/' . $tempUuid . '.png';

        // URL dynamique à utiliser pour la redirection après scan
        $url = url('participation/' . Str::slug($data['nom']));  // URL avec le nouveau nom

        try {
            // Utiliser le Builder pour créer un nouveau QR code
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($url)  // L'URL à inclure dans le QR code
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                ->build();

            // Sauvegarder le nouveau QR code
            Storage::disk('public')->put($filename, $result->getString());

            // Supprimer l'ancien QR code du stockage
            if ($evenement->qr_code_path) {
                Storage::delete('public/' . $evenement->qr_code_path);
            }

            // Mettre à jour le chemin du QR code dans la base de données
            $data['qr_code_path'] = $filename;
        } catch (\Exception $e) {
            Log::error('Erreur QR Code lors de la mise à jour : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la génération du QR Code.');
        }
    }

    // Mettre à jour l'événement dans la base de données
    $evenement->update($data);

    return redirect()->back()->with('success', 'Événement mis à jour.');
}


    public function destroy(Evenement $evenement)
    {
        if ($evenement->qr_code_path) {
            Storage::delete('public/' . $evenement->qr_code_path);
        }

        $evenement->delete();

        return redirect()->back()->with('success', 'Événement supprimé.');
    }

    public function downloadQrCode($id)
    {
        // Trouver l'événement par ID
        $evenement = Evenement::findOrFail($id);

        // Vérifier si le QR code existe
        if (!$evenement->qr_code_path) {
            return redirect()->back()->with('error', 'QR Code non disponible.');
        }

        // Télécharger le fichier QR Code depuis le stockage
        $path = storage_path('app/public/' . $evenement->qr_code_path);  // chemin complet du fichier dans public

        // Retourner la réponse de téléchargement du fichier
        return response()->download($path);
    }
}
