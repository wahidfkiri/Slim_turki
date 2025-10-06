<?php
// app/Services/EmailManagerService.php

namespace App\Services;

use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Mail;
use App\Mail\PeakMindMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailManagerService
{
    protected $client;
    protected $connected = false;
    
    public function __construct()
    {
        $this->initializeClient();
    }
    
    protected function initializeClient()
    {
        try {
            $clientManager = new ClientManager($this->getConfig());
            $this->client = $clientManager->account('default');
            $this->client->connect();
            $this->connected = true;
            
            
        } catch (\Exception $e) {
            Log::error('Erreur connexion IMAP PeakMind: ' . $e->getMessage());
            $this->connected = false;
        }
    }

   protected function getConfig()
    {
        return [
            'default' => 'default',
            'accounts' => [
                'default' => [
                    'host' => env('IMAP_HOST', 'mail.peakmind-solutions.com'),
                    'port' => env('IMAP_PORT', 993),
                    'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
                    'validate_cert' => true,
                    'username' => env('IMAP_USERNAME', 'wahid.fkiri@peakmind-solutions.com'),
                    'password' => env('IMAP_PASSWORD'),
                    'protocol' => 'imap',
                    'timeout' => 30,
                ],
            ],
            'options' => [
                'delimiter' => '/',
                'fetch' => \Webklex\IMAP\Support\MessageCollection::class,
                'fetch_order' => 'desc',
                'fetch_body' => true,
                'fetch_attachment' => false,
                'fetch_flags' => true,
                'message_key' => 'list',
                'uid_cache' => false,
                'debug' => env('IMAP_DEBUG', false),
            ],
        ];
    }
 
    
    // protected function getConfig()
    // {
    //     $emailSettings = auth()->user()->emailSetting ?? null;
    //     return [
    //         'default' => 'default',
    //         'accounts' => [
    //             'default' => [
    //                 'host' => env('IMAP_HOST', 'mail.peakmind-solutions.com'),
    //                 'port' => env('IMAP_PORT', 993),
    //                 'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
    //                 'validate_cert' => true,
    //                 'username' => env('IMAP_USERNAME', 'wahid.fkiri@peakmind-solutions.com'),
    //                 'password' => env('IMAP_PASSWORD'),
    //                 'protocol' => 'imap',
    //                 'timeout' => 30,
    //             ],
    //         ],
    //         'options' => [
    //             'delimiter' => '/',
    //             'fetch' => \Webklex\IMAP\Support\MessageCollection::class,
    //             'fetch_order' => 'desc',
    //             'fetch_body' => true,
    //             'fetch_attachment' => false,
    //             'fetch_flags' => true,
    //             'message_key' => 'list',
    //             'uid_cache' => false,
    //             'debug' => env('IMAP_DEBUG', false),
    //         ],
    //     ];
    // }
    
    public function testConnection()
    {
        if (!$this->connected) {
            return [
                'connected' => false,
                'error' => 'Client IMAP non connecté',
                'account' => 'wahid.fkiri@peakmind-solutions.com'
            ];
        }
        
        try {
            $folders = $this->client->getFolders();
            
            return [
                'connected' => true,
                'account' => 'wahid.fkiri@peakmind-solutions.com',
                'folders_count' => count($folders),
                'status' => 'OK - PeakMind Solutions'
            ];
            
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage(),
                'account' => 'wahid.fkiri@peakmind-solutions.com'
            ];
        }
    }
    
    public function getFolders()
    {
        if (!$this->connected) {
            throw new \Exception('Client IMAP PeakMind non connecté');
        }
        
        $folderList = [];
        
        try {
            $folders = $this->client->getFolders();
            
            foreach ($folders as $folder) {
                try {
                    $folderInfo = [
                        'name' => $folder->name,
                        'path' => $folder->path,
                        'full_name' => $folder->full_name,
                    ];
                    
                    $folderList[] = $folderInfo;
                    
                } catch (\Exception $e) {
                    $folderList[] = [
                        'name' => $folder->name,
                        'path' => 'error',
                        'full_name' => 'error',
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            return $folderList;
            
        } catch (\Exception $e) {
            Log::error('Erreur récupération dossiers PeakMind: ' . $e->getMessage());
            throw new \Exception('Impossible de récupérer les dossiers PeakMind: ' . $e->getMessage());
        }
    }
    
    public function getEmailsRobust($folderName = 'Test', $limit = 20)
    {
        if (!$this->connected) {
            return ['success' => false, 'error' => 'Client IMAP PeakMind non connecté'];
        }
        
        try {
            $folder = $this->client->getFolder($folderName);
            
            if (!$folder) {
                return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
            }
            
            $messages = $folder->messages()
                ->limit($limit)
                ->all()
                ->get();
            
            $emails = [];
            $errorCount = 0;
            
            foreach ($messages as $message) {
                try {
                    $parsedEmail = $this->parseEmailWithPreview($message);
                    $emails[] = $parsedEmail;
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::warning("Erreur parsing message {$errorCount}: " . $e->getMessage());
                    
                    $emails[] = [
                        'uid' => 'error_' . $errorCount,
                        'subject' => 'Email non lisible',
                        'from' => 'inconnu',
                        'date' => now()->format('Y-m-d H:i:s'),
                        'preview' => 'Erreur lors du chargement de cet email',
                        'seen' => true,
                        'attachments_count' => 0,
                        'error' => $e->getMessage()
                    ];
                    
                    if ($errorCount >= 5) {
                        $emails[] = [
                            'uid' => 'error_limit',
                            'subject' => 'Limite d\'erreurs atteinte',
                            'from' => 'système',
                            'date' => now()->format('Y-m-d H:i:s'),
                            'preview' => 'Trop d\'emails n\'ont pas pu être chargés',
                            'seen' => true,
                            'attachments_count' => 0
                        ];
                        break;
                    }
                }
            }
            
            $result = [
                'success' => true,
                'emails' => $emails,
                'folder' => $folderName,
                'count' => count($emails),
                'error_count' => $errorCount,
                'account' => 'wahid.fkiri@peakmind-solutions.com'
            ];
            
            if ($errorCount > 0) {
                $result['warning'] = "{$errorCount} email(s) n'ont pas pu être chargés correctement";
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error("Erreur récupération emails robuste: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getEmailSimple($folderName, $uid)
    {
        if (!$this->connected) {
            return ['success' => false, 'error' => 'Client IMAP PeakMind non connecté'];
        }
        
        try {
            $folder = $this->client->getFolder($folderName);
            
            if (!$folder) {
                return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
            }
            
            $messages = $folder->messages()
                ->limit(50)
                ->all()
                ->get();
            
            $targetMessage = null;
            foreach ($messages as $message) {
                if ($message->getUid() == $uid) {
                    $targetMessage = $message;
                    break;
                }
            }
            
            if (!$targetMessage) {
                $messages = $folder->messages()
                    ->limit(200)
                    ->all()
                    ->get();
                    
                foreach ($messages as $message) {
                    if ($message->getUid() == $uid) {
                        $targetMessage = $message;
                        break;
                    }
                }
            }
            
            if (!$targetMessage) {
                return ['success' => false, 'error' => 'Email UID ' . $uid . ' non trouvé dans les premiers 200 emails'];
            }
            
            return [
                'success' => true,
                'email' => $this->parseEmailWithBody($targetMessage)
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur récupération email simple {$uid}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function findEmailByUidSequential($folderName, $uid, $batchSize = 50)
    {
        if (!$this->connected) {
            return ['success' => false, 'error' => 'Client IMAP PeakMind non connecté'];
        }
        
        try {
            $folder = $this->client->getFolder($folderName);
            
            if (!$folder) {
                return ['success' => false, 'error' => "Dossier {$folderName} non trouvé"];
            }
            
            $offset = 0;
            $found = false;
            $targetMessage = null;
            
            while (!$found) {
                $messages = $folder->messages()
                    ->limit($batchSize)
                    ->offset($offset)
                    ->all()
                    ->get();
                
                if ($messages->count() === 0) {
                    break;
                }
                
                foreach ($messages as $message) {
                    if ($message->getUid() == $uid) {
                        $targetMessage = $message;
                        $found = true;
                        break;
                    }
                }
                
                if ($found) {
                    break;
                }
                
                $offset += $batchSize;
                
                if ($offset >= 500) {
                    break;
                }
            }
            
            if (!$found) {
                return ['success' => false, 'error' => 'Email UID ' . $uid . ' non trouvé après recherche de ' . $offset . ' emails'];
            }
            
            return [
                'success' => true,
                'email' => $this->parseEmailWithBody($targetMessage)
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur recherche séquentielle email {$uid}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    protected function parseEmailWithBody($message)
    {
        try {
            $from = $message->getFrom();
            $subject = $message->getSubject();
            $date = $message->getDate();
            $to = $message->getTo();
            
            $formattedDate = now()->format('Y-m-d H:i:s');
            if ($date) {
                try {
                    if (method_exists($date, 'format')) {
                        $formattedDate = $date->format('Y-m-d H:i:s');
                    } elseif (is_string($date)) {
                        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
                    }
                } catch (\Exception $dateError) {
                    Log::warning("Erreur formatage date: " . $dateError->getMessage());
                }
            }
            
            $textBody = '';
            $htmlBody = '';
            
            try {
                $textBody = $message->getTextBody() ?? '';
            } catch (\Exception $e) {
                Log::warning("Erreur récupération texte: " . $e->getMessage());
            }
            
            try {
                $htmlBody = $message->getHTMLBody() ?? '';
            } catch (\Exception $e) {
                Log::warning("Erreur récupération HTML: " . $e->getMessage());
            }
            
            if (empty(trim($htmlBody)) && !empty(trim($textBody))) {
                $htmlBody = $this->formatTextToHtml($textBody);
            }
            
            if (empty(trim($htmlBody)) && empty(trim($textBody))) {
                $htmlBody = '<p class="text-muted"><em>Aucun contenu texte dans cet email</em></p>';
            }
            
            return [
                'uid' => $message->getUid(),
                'message_id' => $message->getMessageId() ?? 'N/A',
                'subject' => !empty($subject) ? $subject : 'Sans objet',
                'from' => !empty($from) ? ($from[0]->mail ?? 'inconnu') : 'inconnu',
                'from_name' => !empty($from) ? ($from[0]->personal ?? '') : '',
                'to' => $this->parseToAddressesSafe($to),
                'date' => $formattedDate,
                'seen' => $message->getFlags()->contains('seen'),
                'body' => [
                    'text' => $textBody,
                    'html' => $htmlBody
                ],
                'attachments' => $this->parseAttachmentsSafe($message->getAttachments()),
                'attachments_count' => $message->getAttachments()->count(),
                'cc' => $this->parseAddressesSafe($message->getCc()),
                'bcc' => $this->parseAddressesSafe($message->getBcc()),
                'reply_to' => $this->parseAddressesSafe($message->getReplyTo()),
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur parsing email avec body: " . $e->getMessage());
            return [
                'uid' => 'error',
                'subject' => 'Erreur de parsing',
                'from' => 'inconnu',
                'to' => [],
                'date' => now()->format('Y-m-d H:i:s'),
                'body' => [
                    'text' => 'Erreur: ' . $e->getMessage(),
                    'html' => '<p class="text-danger">Erreur lors du chargement du contenu: ' . e($e->getMessage()) . '</p>'
                ],
                'attachments' => [],
                'attachments_count' => 0,
                'cc' => [],
                'bcc' => [],
                'reply_to' => [],
                'error' => $e->getMessage()
            ];
        }
    }
    
    protected function parseEmailWithPreview($message)
    {
        try {
            $from = $message->getFrom();
            $subject = $message->getSubject();
            $date = $message->getDate();
            
            $formattedDate = now()->format('Y-m-d H:i:s');
            if ($date && method_exists($date, 'format')) {
                $formattedDate = $date->format('Y-m-d H:i:s');
            }
            
            $textBody = '';
            $preview = '';
            
            try {
                $textBody = $message->getTextBody();
            } catch (\Exception $e) {
                Log::warning("Erreur récupération texte preview: " . $e->getMessage());
            }
            
            if (!empty($textBody)) {
                $preview = strip_tags($textBody);
                $preview = Str::limit($preview, 100);
            } else {
                try {
                    $htmlBody = $message->getHTMLBody();
                    if (!empty($htmlBody)) {
                        $preview = strip_tags($htmlBody);
                        $preview = Str::limit($preview, 100);
                    }
                } catch (\Exception $e) {
                    Log::warning("Erreur récupération HTML preview: " . $e->getMessage());
                }
            }
            
            return [
                'uid' => $message->getUid(),
                'message_id' => $message->getMessageId() ?? 'N/A',
                'subject' => !empty($subject) ? $subject : 'Sans objet',
                'from' => !empty($from) ? ($from[0]->mail ?? 'inconnu') : 'inconnu',
                'from_name' => !empty($from) ? ($from[0]->personal ?? '') : '',
                'date' => $formattedDate,
                'seen' => $message->getFlags()->contains('seen'),
                'preview' => $preview,
                'attachments_count' => $message->getAttachments()->count(),
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur parsing email avec preview: " . $e->getMessage());
            return [
                'uid' => 'error',
                'subject' => 'Erreur parsing',
                'from' => 'inconnu',
                'date' => now()->format('Y-m-d H:i:s'),
                'preview' => 'Erreur lors du chargement',
                'seen' => true,
                'attachments_count' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    protected function parseAttachmentsSafe($attachments)
    {
        $result = [];
        
        try {
            foreach ($attachments as $attachment) {
                try {
                    $result[] = [
                        'id' => $attachment->id ?? uniqid(),
                        'name' => $attachment->getName() ?? 'piece_jointe',
                        'size' => $attachment->getSize() ?? 0,
                        'content_type' => $attachment->getContentType() ?? 'application/octet-stream',
                        'disposition' => $attachment->getDisposition() ?? 'attachment'
                    ];
                } catch (\Exception $e) {
                    Log::warning("Erreur parsing pièce jointe: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur globale parsing pièces jointes: " . $e->getMessage());
        }
        
        return $result;
    }
    
    protected function parseAddressesSafe($addresses)
    {
        $result = [];
        
        try {
            foreach ($addresses as $address) {
                try {
                    $result[] = [
                        'email' => $address->mail ?? 'inconnu',
                        'name' => $address->personal ?? $address->mail ?? 'inconnu'
                    ];
                } catch (\Exception $e) {
                    Log::warning("Erreur parsing adresse: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur globale parsing adresses: " . $e->getMessage());
        }
        
        return $result;
    }
    
    protected function parseToAddressesSafe($addresses)
    {
        $result = [];
        
        try {
            foreach ($addresses as $address) {
                try {
                    $result[] = [
                        'email' => $address->mail ?? 'inconnu',
                        'name' => $address->personal ?? $address->mail ?? 'inconnu'
                    ];
                } catch (\Exception $e) {
                    Log::warning("Erreur parsing destinataire: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur globale parsing destinataires: " . $e->getMessage());
        }
        
        return $result;
    }
    
    protected function formatTextToHtml($text)
    {
        if (empty($text)) {
            return '<p class="text-muted"><em>Aucun contenu</em></p>';
        }
        
        $escapedText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        
        $escapedText = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" style="color: #007bff; text-decoration: underline;">$1</a>',
            $escapedText
        );
        
        $escapedText = nl2br($escapedText);
        
        $html = '<div style="white-space: pre-wrap; font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
        $html .= $escapedText;
        $html .= '</div>';
        
        return $html;
    }
    
    public function sendEmail($to, $subject, $content, $options = [])
    {
        try {
            $mail = new PeakMindMail($subject, $content, $options);
            
            $mailer = Mail::to($to);
            
            if (!empty($options['cc'])) {
                $mailer->cc($options['cc']);
            }
            
            if (!empty($options['bcc'])) {
                $mailer->bcc($options['bcc']);
            }
            
            $mailer->send($mail);
            
            Log::info("Email PeakMind envoyé de: wahid.fkiri@peakmind-solutions.com vers: {$to} - Sujet: {$subject}");
            return [
                'success' => true, 
                'message' => 'Email envoyé avec succès via PeakMind',
                'from' => 'wahid.fkiri@peakmind-solutions.com',
                'to' => $to
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur envoi email PeakMind: " . $e->getMessage());
            return [
                'success' => false, 
                'error' => $e->getMessage(),
                'from' => 'wahid.fkiri@peakmind-solutions.com'
            ];
        }
    }
    
    public function reconnect()
    {
        try {
            if ($this->client) {
                $this->client->disconnect();
            }
            $this->connected = false;
            sleep(1);
            $this->initializeClient();
            
            return ['success' => true, 'message' => 'Reconnexion PeakMind effectuée'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
/**
 * Récupérer les dossiers avec le nombre d'emails non lus
 */
public function getFoldersWithCounts()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP PeakMind non connecté');
    }
    
    $folderList = [];
    
    try {
        $folders = $this->client->getFolders();
        
        foreach ($folders as $folder) {
            try {
                // Compter le total des messages
                $totalCount = $folder->query()->count();
                
                // Compter les messages non lus
                $unreadCount = $folder->search()->unseen()->count();
                
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'total_count' => $totalCount,
                    'unread_count' => $unreadCount,
                    'children' => $folder->children->count(),
                ];
                
                $folderList[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur comptage dossier {$folder->name}: " . $e->getMessage());
                
                $folderList[] = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'total_count' => 0,
                    'unread_count' => 0,
                    'children' => 0,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $folderList;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers avec counts: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers: ' . $e->getMessage());
    }
}


/**
 * Récupérer tous les dossiers disponibles
 */
public function getFoldersSimples()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP PeakMind non connecté');
    }
    
    $folderList = [];
    
    try {
        $folders = $this->client->getFolders(false); // false pour avoir tous les dossiers récursivement
        
        foreach ($folders as $folder) {
            try {
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'has_children' => $folder->children->count() > 0,
                    'children_count' => $folder->children->count(),
                ];
                
                $folderList[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur dossier {$folder->name}: " . $e->getMessage());
                
                $folderList[] = [
                    'name' => $folder->name,
                    'path' => 'error',
                    'full_name' => 'error',
                    'has_children' => false,
                    'children_count' => 0,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        // Trier les dossiers par nom pour une meilleure organisation
        usort($folderList, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
       // return $folderList;
        dd($folderList);die;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers simple: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers: ' . $e->getMessage());
    }
}
public function getFoldersSimple(array $excludedFolders = null)
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP PeakMind non connecté');
    }
    
    $folderList = [];
    
    try {
        $folders = $this->client->getFolders(false);
        
        // Dossiers exclus par défaut
        $defaultExcluded = ['INBOX', 'SENT', 'Sent', 'Sent Items', 'Boîte d\'envoi','Archive','spam','Trash','Junk','Drafts'];
        $excluded = $excludedFolders ?? $defaultExcluded;
        
        foreach ($folders as $folder) {
            try {
                $folderName = $folder->name;
                
                // Vérifier l'exclusion
                if (in_array($folderName, $excluded) || 
                    stripos($folderName, 'SENT') !== false) {
                    continue;
                }
                
                $folderInfo = [
                    'name' => $folderName,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'has_children' => $folder->children->count() > 0,
                    'children_count' => $folder->children->count(),
                ];
                
                $folderList[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur dossier {$folder->name}: " . $e->getMessage());
                continue;
            }
        }
        
        usort($folderList, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        return $folderList;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers simple: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers: ' . $e->getMessage());
    }
}
/**
 * Récupérer tous les dossiers avec leurs sous-dossiers
 */
public function getAllFoldersWithHierarchy()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP PeakMind non connecté');
    }
    
    $allFolders = [];
    
    try {
        // Récupérer tous les dossiers de manière récursive
        $folders = $this->client->getFolders(true);
        
        // Fonction récursive pour parcourir l'arborescence
        $processFolder = function($folder) use (&$processFolder, &$allFolders) {
            try {
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'level' => substr_count($folder->path, $folder->delimiter ?? '.'),
                    'has_children' => $folder->children->count() > 0,
                ];
                
                $allFolders[] = $folderInfo;
                
                // Traiter les sous-dossiers
                foreach ($folder->children as $child) {
                    $processFolder($child);
                }
                
            } catch (\Exception $e) {
                Log::warning("Erreur traitement dossier {$folder->name}: " . $e->getMessage());
            }
        };
        
        foreach ($folders as $folder) {
            $processFolder($folder);
        }
        
        return $allFolders;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération hiérarchie dossiers: ' . $e->getMessage());
        // Retourner les dossiers de base en cas d'erreur
        return $this->getFoldersSimple();
    }
}

/**
 * Récupérer les dossiers principaux (non récursif)
 */
public function getMainFolders()
{
    if (!$this->connected) {
        throw new \Exception('Client IMAP PeakMind non connecté');
    }
    
    $mainFolders = [];
    $commonFolders = ['INBOX', 'Sent', 'Drafts', 'Trash', 'Spam', 'Junk', 'Archive'];
    
    try {
        $folders = $this->client->getFolders();
        
        foreach ($folders as $folder) {
            try {
                $folderInfo = [
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'full_name' => $folder->full_name,
                    'is_common' => in_array($folder->name, $commonFolders),
                    'has_children' => $folder->children->count() > 0,
                ];
                
                $mainFolders[] = $folderInfo;
                
            } catch (\Exception $e) {
                Log::warning("Erreur dossier principal {$folder->name}: " . $e->getMessage());
            }
        }
        
        // Trier : dossiers communs d'abord, puis les autres par ordre alphabétique
        usort($mainFolders, function($a, $b) use ($commonFolders) {
            $aIndex = array_search($a['name'], $commonFolders);
            $bIndex = array_search($b['name'], $commonFolders);
            
            if ($aIndex !== false && $bIndex !== false) {
                return $aIndex - $bIndex;
            } elseif ($aIndex !== false) {
                return -1;
            } elseif ($bIndex !== false) {
                return 1;
            } else {
                return strcmp($a['name'], $b['name']);
            }
        });
        
        return $mainFolders;
        
    } catch (\Exception $e) {
        Log::error('Erreur récupération dossiers principaux: ' . $e->getMessage());
        throw new \Exception('Impossible de récupérer les dossiers principaux: ' . $e->getMessage());
    }
}
}