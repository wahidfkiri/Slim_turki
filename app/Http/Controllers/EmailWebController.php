<?php
// app/Http/Controllers/EmailWebController.php

namespace App\Http\Controllers;

use App\Services\EmailManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailWebController extends Controller
{
    protected $emailService;
    
    public function __construct(EmailManagerService $emailService)
    {
        $this->emailService = $emailService;
    }

public function index()
{
    try {
        // Essayer d'abord les dossiers principaux
      return $folders = $this->emailService->getFoldersSimple();
       // $inboxEmails = $this->emailService->getEmailsRobust('Test', 20);
        
        return view('emails.folders', [
            'folders' => $folders,
           // 'emails' => $inboxEmails['success'] ? $inboxEmails['emails'] : [],
            'currentFolder' => 'Test',
            'account' => 'wahid.fkiri@peakmind-solutions.com',
          //  'warning' => $inboxEmails['warning'] ?? null,
            'totalFolders' => count($folders)
        ]);
        
    } catch (\Exception $e) {
        // Fallback : utiliser la méthode simple
        try {
            $folders = $this->emailService->getFoldersSimple();
            
            return view('emails.folders', [
                'folders' => $folders,
                'emails' => [],
                'error' => $e->getMessage(),
                'account' => 'wahid.fkiri@peakmind-solutions.com',
                'currentFolder' => 'Test',
                'totalFolders' => count($folders)
            ]);
            
        } catch (\Exception $e2) {
            return view('emails.index', [
                'folders' => [],
                'emails' => [],
                'error' => $e2->getMessage(),
                'account' => 'wahid.fkiri@peakmind-solutions.com',
                'currentFolder' => 'Test',
                'totalFolders' => 0
            ]);
        }
    }
}

// Mettez à jour showFolder :

public function showFolder($folder)
{
    try {
        $folders = $this->emailService->getFoldersSimple();
        $emailsResult = $this->emailService->getEmailsRobust($folder, 30);
        
        if (!$emailsResult['success']) {
            return redirect()->route('email.index')
                ->with('error', $emailsResult['error']);
        }
        
        return view('emails.index', [
            'folders' => $folders,
            'emails' => $emailsResult['emails'],
            'currentFolder' => $folder,
            'account' => 'wahid.fkiri@peakmind-solutions.com',
            'warning' => $emailsResult['warning'] ?? null,
            'totalFolders' => count($folders)
        ]);
        
    } catch (\Exception $e) {
        return redirect()->route('email.index')
            ->with('error', 'Erreur lors du chargement du dossier: ' . $e->getMessage());
    }
}

public function showEmail($folder, $uid)
{
    try {
        $folders = $this->emailService->getFoldersSimple();
        
        $emailResult = $this->emailService->getEmailSimple($folder, $uid);
        
        if (!$emailResult['success']) {
            $emailResult = $this->emailService->findEmailByUidSequential($folder, $uid, 30);
        }
        
        if (!$emailResult['success']) {
            return redirect()->route('email.folder', $folder)
                ->with('error', $emailResult['error']);
        }
        
        return view('emails.show', [
            'folders' => $folders,
            'email' => $emailResult['email'],
            'currentFolder' => $folder,
            'account' => 'wahid.fkiri@peakmind-solutions.com'
        ]);
        
    } catch (\Exception $e) {
        return redirect()->route('email.folder', $folder)
            ->with('error', 'Erreur lors du chargement de l\'email: ' . $e->getMessage());
    }
}
    
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $result = $this->emailService->sendEmail(
            $request->to,
            $request->subject,
            $request->content,
            $request->only(['cc', 'bcc'])
        );
        
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['error']);
        }
    }
    
    public function markAsRead(Request $request)
    {
        // À implémenter ultérieurement
        return response()->json(['success' => true, 'message' => 'Fonction à implémenter']);
    }
    
    public function moveEmail(Request $request)
    {
        // À implémenter ultérieurement
        return response()->json(['success' => true, 'message' => 'Fonction à implémenter']);
    }
    
    public function deleteEmail(Request $request)
    {
        // À implémenter ultérieurement
        return response()->json(['success' => true, 'message' => 'Fonction à implémenter']);
    }
    
    public function testConnection()
    {
        $status = $this->emailService->testConnection();
        return response()->json($status);
    }
    
    public function reconnect()
    {
        $result = $this->emailService->reconnect();
        
        if ($result['success']) {
            return redirect()->route('email.index')->with('success', $result['message']);
        } else {
            return redirect()->route('email.index')->with('error', $result['error']);
        }
    }
}