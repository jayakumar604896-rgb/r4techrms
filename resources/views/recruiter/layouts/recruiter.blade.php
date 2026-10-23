{{-- resources/views/recruiters/layouts/recruiter.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Paswi Manpower Consultant</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Common Styles -->
    <style>
        body { 
            background: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding-top: 10px;
        }
        .excel-table { 
            border: 2px solid #000; 
            font-size: 13px; 
            width: 100%;
        }
        .excel-table th, .excel-table td { 
            border: 1px solid #ccc; 
            padding: 4px 6px; 
            vertical-align: middle; 
        }
        .excel-table thead th { 
            background-color: #e9ecef; 
            font-weight: bold; 
            text-align: center; 
        }
        .formula-cell { 
            background-color: #fff3cd; 
            font-style: italic; 
            color: #856404; 
        }
        .summary-row { 
            background-color: #d4edda; 
            font-weight: bold; 
        }
        .total-row { 
            background-color: #fff3cd; 
            font-weight: bold; 
        }
        .header-section { 
            background: white; 
            border: 2px solid #0d6efd; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 5px;
        }
        .input-sm { 
            height: 28px; 
            font-size: 12px; 
            padding: 2px 5px; 
            width: 100%; 
        }
        .percent-cell { 
            min-width: 60px; 
        }
        .calc-btn { 
            position: fixed; 
            bottom: 20px; 
            right: 20px; 
            z-index: 1000; 
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
            background-color: #0d6efd;
            color: white;
        }
        .card-header {
            font-weight: 600;
        }
        @stack('custom-styles')
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-person-badge me-2"></i>Paswi Manpower
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('recruiter.daily')) active @endif" 
                           href="{{ route('recruiter.daily') }}">
                            <i class="bi bi-calendar-day"></i> Daily
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('recruiter.monthly')) active @endif" 
                           href="{{ route('recruiter.monthly') }}">
                            <i class="bi bi-calendar-month"></i> Monthly
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('recruiter.monthwise')) active @endif" 
                           href="{{ route('recruiter.monthwise') }}">
                            <i class="bi bi-calendar-range"></i> Monthwise
                        </a>
                    </li>
                </ul>
                <div class="navbar-text">
                    <span class="text-light me-3">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name ?? '' }}
                    </span>
                    <span class="badge bg-info" id="currentDateTime">
                        {{ date('d-M-Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-3 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">
                &copy; {{ date('Y') }} Paswi Manpower Consultant. All rights reserved.
                <span class="text-muted ms-3">v1.0.0</span>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Common JavaScript -->
    <script>
        // Update current date time
        function updateDateTime() {
            const now = new Date();
            const options = { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('currentDateTime').textContent = 
                now.toLocaleDateString('en-IN', options) + ' ' + 
                now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
        }
        
        // Update every minute
        updateDateTime();
        setInterval(updateDateTime, 60000);
        
        // Common utility functions
        function formatNumber(num) {
            return num.toLocaleString('en-IN', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            });
        }
        
        function calculatePercentage(part, total) {
            if (total === 0) return 0;
            return (part / total) * 100;
        }
        
        // Print function
        function printReport() {
            window.print();
        }
        
        // Export to Excel (basic)
        function exportToExcel(tableId, filename) {
            const table = document.getElementById(tableId);
            const html = table.outerHTML;
            
            // Create download link
            const blob = new Blob([html], {type: 'application/vnd.ms-excel'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${filename}_${new Date().toISOString().split('T')[0]}.xls`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>
    
    @stack('scripts')
</body>
</html>