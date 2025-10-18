<?php
// app/Http/Controllers/EmailWebController.php

namespace App\Http\Controllers;

use App\Services\EmailManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            $folders = $this->emailService->getFoldersSimples();
            $inboxEmails = $this->emailService->getEmailsRobust('Inbox', 20);
            
            // Clean email previews
            if ($inboxEmails['success'] && isset($inboxEmails['emails'])) {
                $inboxEmails['emails'] = $this->cleanEmailPreviews($inboxEmails['emails']);
            }
            
            return view('emails.folders', [
                'folders' => $folders,
                'emails' => $inboxEmails['success'] ? $inboxEmails['emails'] : [],
                'currentFolder' => 'Inbox',
                'account' => 'wahid.fkiri@peakmind-solutions.com',
                'warning' => $inboxEmails['warning'] ?? null,
                'totalFolders' => count($folders)
            ]);
            
        } catch (\Exception $e) {
            // Fallback : utiliser la méthode simple
            try {
                $folders = $this->emailService->getFoldersSimples();
                
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

    public function showFolder($folder)
    {
        try {
            $folders = $this->emailService->getFoldersSimples();
            $emailsResult = $this->emailService->getEmailsRobust($folder, 30);
            
            if (!$emailsResult['success']) {
                return redirect()->route('email.index')
                    ->with('error', $emailsResult['error']);
            }
            
            // Clean email previews
            if (isset($emailsResult['emails'])) {
                $emailsResult['emails'] = $this->cleanEmailPreviews($emailsResult['emails']);
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
            
            // Clean email preview for single email view if needed
            if (isset($emailResult['email']['preview'])) {
                $emailResult['email']['clean_preview'] = $this->cleanSinglePreview($emailResult['email']['preview']);
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
    
    /**
     * Clean email previews for multiple emails
     */
    private function cleanEmailPreviews($emails)
    {
        return collect($emails)->map(function ($email) {
            if (isset($email['preview'])) {
                $email['clean_preview'] = $this->cleanSinglePreview($email['preview']);
            }
            return $email;
        })->toArray();
    }
    
    /**
     * Clean a single email preview text
     */
    private function cleanSinglePreview($preview)
    {
        if (empty($preview) || trim($preview) === '...') {
            return '';
        }
        
        // Step 1: Decode Unicode escape sequences (\u00e9 -> é)
        $cleaned = $this->decodeUnicodeEscapes($preview);
        
        // Step 2: Decode HTML entities (&nbsp; -> space)
        $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Step 3: Remove HTML tags
        $cleaned = strip_tags($cleaned);
        
        // Step 4: Normalize and clean whitespace
        $cleaned = $this->normalizeWhitespace($cleaned);
        
        // Step 5: Trim and limit
        $cleaned = trim($cleaned);
        
        return Str::limit($cleaned, 80);
    }
    
    /**
     * Decode Unicode escape sequences like \u00e9, \u00e0, etc.
     */
    private function decodeUnicodeEscapes($text)
    {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $text);
    }
    
    /**
     * Normalize various whitespace characters
     */
    private function normalizeWhitespace($text)
    {
        // Replace various line breaks and tabs with single space
        $text = preg_replace('/[\r\n\t]+/', ' ', $text);
        
        // Replace multiple spaces with single space
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove non-breaking spaces and other special spaces
        $text = str_replace(['&nbsp;', '\u00a0', ' '], ' ', $text);
        
        return $text;
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