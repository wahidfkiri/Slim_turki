{{-- resources/views/emails/peakmind-template.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'PeakMind Solutions' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PeakMind Solutions</h1>
            <p>Votre partenaire en solutions digitales</p>
        </div>
        
        <div class="content">
            {!! $content !!}
            
            <div class="signature">
                <p>Cordialement,<br>
                <strong>Wahid Fkiri</strong><br>
                PeakMind Solutions<br>
                <a href="mailto:wahid.fkiri@peakmind-solutions.com">wahid.fkiri@peakmind-solutions.com</a></p>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} PeakMind Solutions. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>