    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>{{ $subject }}</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                line-height: 1.6; 
                color: #333;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                background: #004085;
                color: white;
                padding: 20px;
                border-radius: 5px;
                margin-bottom: 20px;
            }
            .candidate-card {
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 15px;
                margin-bottom: 15px;
                background: #f9f9f9;
            }
            .candidate-name {
                font-size: 18px;
                font-weight: bold;
                color: #004085;
                margin-bottom: 10px;
            }
            .candidate-detail {
                margin-bottom: 5px;
            }
            .resume-status {
                font-weight: bold;
                padding: 2px 8px;
                border-radius: 3px;
            }
            .resume-available {
                background: #d4edda;
                color: #155724;
            }
            .resume-not-available {
                background: #f8d7da;
                color: #721c24;
            }
            .footer {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Candidate Profiles for Review</h1>
            <p>Total Candidates: {{ $total_candidates }} | Resumes Attached: {{ $resumes_count }}</p>
        </div>
        
        <div class="content">
            {!! $message_content !!}
        </div>


        <!--<div class="footer">
            <p><strong>Best regards,</strong><br>
            {{ auth()->user()->name }}<br>
            Recruitment Team</p>
            
            <hr>
            <small>This email was sent automatically from the Recruitment System.</small>
        </div>-->
    </body>
    </html>