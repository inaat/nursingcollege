<!-- resources/views/reports/results.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $report->report_name }} - Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        
        .report-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 0 auto;
            max-width: 100%;
        }
        
        /* Print-only elements (hidden on screen, shown during print) */
        .print-only {
            display: none !important;
        }
        
        /* Action buttons */
        .action-bar {
            background: #e9ecef;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-print { background: #dc3545; color: white; }
        .btn-export { background: #198754; color: white; }
        .btn-back { background: #0d6efd; color: white; }
        
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            color: white;
        }
        
        /* Report title section */
        .report-title-section {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 2px solid #2d5a4a;
            text-align: center;
        }
        
        .report-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2d5a4a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Table styling similar to the image */
        .table-container {
            padding: 0;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 12px;
        }
        
        .report-table th {
            background: #2d5a4a;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #fff;
            font-size: 11px;
            line-height: 1.2;
        }
        
        .report-table td {
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: middle;
            font-size: 11px;
        }
        
        .report-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .report-table tbody tr:hover {
            background-color: #e3f2fd;
        }
        
        /* Serial number column */
        .serial-col { width: 40px; }
        .roll-col { width: 60px; }
        .name-col { width: 120px; }
        .father-col { width: 100px; }
        .date-col { width: 80px; }
        .mobile-col { width: 100px; }
        .cnic-col { width: 100px; }
        .address-col { width: 120px; }
        .amount-col { width: 80px; }
        
        /* Footer summary */
        .report-footer {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #2d5a4a;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2d5a4a;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-data i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #ccc;
        }
        
        /* Print styles */
        @media print {
            body { 
                background: white !important; 
                padding: 0 !important;
            }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .report-container { 
                box-shadow: none !important; 
                border-radius: 0 !important;
            }
            .report-table { font-size: 10px; }
            .report-table th, .report-table td { 
                padding: 6px 4px; 
                font-size: 10px;
            }
            .institutional-header {
                background: #2d5a4a !important;
                -webkit-print-color-adjust: exact;
            }
            .report-title-section {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 10px; }
            .header-content { flex-direction: column; text-align: center; }
            .institution-logo { margin-top: 15px; }
            .institution-name { font-size: 2rem; }
            .action-buttons { justify-content: center; }
            .report-table { font-size: 10px; }
            .report-table th, .report-table td { padding: 6px 4px; }
        }
        
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="report-container">
        
        <!-- Action Bar -->
        <div class="action-bar no-print">
            <div class="action-buttons">
                <button onclick="printReport()" class="btn-action btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <button onclick="exportToExcel()" class="btn-action btn-export">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <a href="{{ route('reports.index') }}" class="btn-action btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Reports
                </a>
            </div>
        </div>

        <!-- Header Logo (only shows during print) -->
        <div class="print-only">
            <img src="{{  url('/uploads/business_logos/'.session()->get("system_details.page_header_logo")) }}" style="text-align:center; width:100% ; height:120px"/>
        </div>

        <!-- Report Title -->
        <div class="report-title-section">
            <h2 class="report-title">{{ $report->report_name }}</h2>
        </div>

        <!-- Data Table -->
        <div class="table-container">
            @if($results->count() > 0)
            <table class="report-table" id="reportTable">
                <thead>
                    <tr>
                        <th class="serial-col">#</th>
                        @foreach($report->column_order as $column)
                            <th class="{{ 
                                $column == 'roll_number' ? 'roll-col' : 
                                (in_array($column, ['student_name', 'name']) ? 'name-col' : 
                                (in_array($column, ['father_name', 'fathers_name']) ? 'father-col' : 
                                (in_array($column, ['birth_date', 'date_of_birth', 'admission_date']) ? 'date-col' : 
                                (in_array($column, ['mobile_no', 'phone', 'contact']) ? 'mobile-col' : 
                                (in_array($column, ['father_cnic', 'cnic', 'fathers_cnic']) ? 'cnic-col' : 
                                (in_array($column, ['address']) ? 'address-col' : 'amount-col'))))))
                            }}">
                                {{ ucwords(str_replace(['_', 'fathers'], [' ', "Father's"], $column)) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $row)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            @foreach($report->column_order as $column)
                                <td>
                                    @if($column == 'birth_date' || $column == 'admission_date' || $column == 'date_of_birth')
                                        {{ $row->$column ? \Carbon\Carbon::parse($row->$column)->format('d-m-Y') : 'N/A' }}
                                    @elseif(in_array($column, ['student_tuition_fee', 'class_tuition_fee', 'student_transport_fee', 'total_due', 'total_due_transport_fee', 'total_admission_fee', 'tuition', 'transport', 'paid', 'fee_due', 'transport_fee_due', 'balance']))
                                        {{ number_format($row->$column ?? 0, 2) }}
                                    @else
                                        {{ $row->$column ?? 'N/A' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-data">
                <i class="fas fa-search"></i>
                <h4>No Data Found</h4>
                <p>No records match your selected filters. Please adjust your search criteria.</p>
            </div>
            @endif
        </div>

        <!-- Report Footer -->
        <div class="report-footer">
            <div class="summary-stats">
                <div class="stat-box">
                    <div class="stat-label">Total Records</div>
                    <div class="stat-value">{{ $results->count() }}</div>
                </div>
                @if($results->count() > 0 && in_array('total_due', $report->column_order))
                <div class="stat-box">
                    <div class="stat-label">Total Due Amount</div>
                    <div class="stat-value">{{ number_format($results->sum('total_due'), 2) }}</div>
                </div>
                @endif
                @if($results->count() > 0 && in_array('total_due_transport_fee', $report->column_order))
                <div class="stat-box">
                    <div class="stat-label">Total Transport Due</div>
                    <div class="stat-value">{{ number_format($results->sum('total_due_transport_fee'), 2) }}</div>
                </div>
                @endif
                <div class="stat-box">
                    <div class="stat-label">Generated On</div>
                    <div class="stat-value">{{ now()->format('d-M-Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>Generating Excel File...</h5>
            <p>Please wait while we prepare your report.</p>
        </div>
    </div>

    <script>
        function printReport() {
            window.print();
        }

        function exportToExcel() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';
            
            setTimeout(() => {
                try {
                    const table = document.getElementById('reportTable');
                    
                    if (!table) {
                        alert('No data available to export');
                        return;
                    }
                    
                    // Create workbook
                    const wb = XLSX.utils.book_new();
                    
                    // Convert table to worksheet
                    const ws = XLSX.utils.table_to_sheet(table);
                    
                    // Style the worksheet
                    const range = XLSX.utils.decode_range(ws['!ref']);
                    
                    // Auto-fit columns
                    const colWidths = [];
                    for (let col = range.s.c; col <= range.e.c; col++) {
                        let maxWidth = 10;
                        for (let row = range.s.r; row <= range.e.r; row++) {
                            const cellRef = XLSX.utils.encode_cell({r: row, c: col});
                            if (ws[cellRef] && ws[cellRef].v) {
                                const cellValue = ws[cellRef].v.toString();
                                maxWidth = Math.max(maxWidth, cellValue.length);
                            }
                        }
                        colWidths.push({ wch: Math.min(maxWidth + 2, 30) });
                    }
                    ws['!cols'] = colWidths;
                    
                    // Add worksheet to workbook
                    XLSX.utils.book_append_sheet(wb, ws, "{{ str_replace(' ', '_', $report->report_name) }}");
                    
                    // Create summary sheet
                    const summaryData = [
                        ['REPORT SUMMARY', ''],
                        ['', ''],
                        ['Report Name', '{{ $report->report_name }}'],
                        ['Generated On', '{{ now()->format("d-M-Y H:i:s") }}'],
                        ['Total Records', {{ $results->count() }}],
                        @if($results->count() > 0 && in_array('total_due', $report->column_order))
                        ['Total Due Amount', {{ $results->sum('total_due') }}],
                        @endif
                        @if($results->count() > 0 && in_array('total_due_transport_fee', $report->column_order))
                        ['Total Transport Due', {{ $results->sum('total_due_transport_fee') }}],
                        @endif
                        ['', ''],
                        ['Generated by: Your Institution Management System', '']
                    ];
                    
                    const summaryWs = XLSX.utils.aoa_to_sheet(summaryData);
                    summaryWs['!cols'] = [{ wch: 25 }, { wch: 20 }];
                    
                    XLSX.utils.book_append_sheet(wb, summaryWs, "Summary");
                    
                    // Generate filename
                    const now = new Date();
                    const filename = `{{ str_replace(' ', '_', $report->report_name) }}_${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}.xlsx`;
                    
                    // Save file
                    XLSX.writeFile(wb, filename);
                    
                    // Show success notification
                    showNotification('Excel file exported successfully!', 'success');
                    
                } catch (error) {
                    console.error('Export error:', error);
                    showNotification('Error exporting file. Please try again.', 'error');
                } finally {
                    loadingOverlay.style.display = 'none';
                }
            }, 1000);
        }
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
            notification.style.cssText = `
                top: 20px; right: 20px; z-index: 10000; min-width: 300px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 6px;
            `;
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 5000);
        }
    </script>
</body>
</html>