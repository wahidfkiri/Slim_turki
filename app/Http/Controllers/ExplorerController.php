<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ExplorerController extends Controller
{
    /**
     * Open folder in Windows Explorer
     */
    public function openFolder(Request $request)
{
    try {
        $validated = $request->validate([
            'path' => 'required|string|max:500'
        ]);
        
        $path = $validated['path'];
        
        // Execute the Artisan command
        $exitCode = Artisan::call('explorer:open', [
            'path' => $path
        ]);
        
        $output = Artisan::output();
        
        return response()->json([
            'success' => $exitCode === 0,
            'message' => $exitCode === 0 ? 'L’Explorateur Windows est en cours d’ouverture...' : 'Échec de l’ouverture de l’Explorateur Windows',
            'path' => $path,
            'output' => $output,
            'exit_code' => $exitCode
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error in openFolder controller', [
            'path' => $request->path,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage(),
            'path' => $request->path ?? ''
        ], 500);
    }
}

// app/Http/Controllers/ExplorerController.php
// public function openFolder(Request $request)
// {
//     try {
//         $validated = $request->validate([
//             'path' => 'required|string|max:500'
//         ]);
        
//         $path = $validated['path'];
        
//         // Convertir le chemin local en chemin réseau
//         $networkPath = $this->convertToNetworkPath($path);
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Use the network path below in Windows Explorer',
//             'local_path' => $path,
//             'network_path' => $networkPath,
//             'instructions' => 'Copy the network path and paste in Windows Explorer'
//         ]);
        
//     } catch (\Exception $e) {
//         Log::error('Error in openFolder controller', [
//             'path' => $request->path,
//             'error' => $e->getMessage()
//         ]);
        
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur: ' . $e->getMessage()
//         ], 500);
//     }
// }

/**
 * Convertir le chemin local en chemin réseau
 */
private function convertToNetworkPath(string $localPath): string
{
    $serverIP = '192.168.1.15'; // Votre IP serveur
    $localPath = str_replace('/', '\\', $localPath);
    
    // Mapping des chemins locaux vers les partages réseau
    $networkMappings = [
        'D:\\My Project\\Slim Turki\\Project\\storage\\app\\public\\dossiers' => 'dossiers',
        // Ajoutez d'autres mappings si nécessaire
    ];
    
    foreach ($networkMappings as $local => $shareName) {
        if (str_starts_with($localPath, $local)) {
            $relativePath = str_replace($local, '', $localPath);
            $relativePath = ltrim($relativePath, '\\');
            
            return "\\\\{$serverIP}\\{$shareName}" . ($relativePath ? "\\{$relativePath}" : '');
        }
    }
    
    // Fallback: utiliser le partage par défaut
    return "\\\\{$serverIP}\\dossiers";
}
    
    /**
     * Show the open folder form
     */
    public function showForm()
    {
        return view('open-folder');
    }
}