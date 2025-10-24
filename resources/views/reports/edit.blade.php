<!DOCTYPE html>
<html>
<head>
    <title>Edit Custom Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .section-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .section-header {
            background: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
            font-size: 18px;
        }
        .section-body {
            padding: 20px;
            height: 400px;
            overflow-y: auto;
        }
        .column-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            margin: 3px 0;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .column-item.selected {
            background: #e3f2fd;
            border-color: #007bff;
        }
        .preview-header {
            background: #007bff;
            color: white;
            padding: 12px;
            margin: 5px;
            border-radius: 20px;
            display: inline-block;
            font-size: 14px;
            position: relative;
            cursor: move;
            user-select: none;
            transition: all 0.2s ease;
        }
        .preview-header:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,123,255,0.3);
        }
        .preview-header.dragging {
            opacity: 0.5;
            transform: rotate(3deg) scale(1.05);
            z-index: 1000;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        }
        .preview-container.drag-over {
            background: #e3f2fd;
            border-color: #007bff;
        }
        .preview-header .remove-btn {
            background: rgba(255,255,255,0.3);
            border: none;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            margin-left: 8px;
            cursor: pointer;
            font-size: 12px;
            pointer-events: auto;
            position: relative;
            z-index: 10;
        }
        .preview-header .remove-btn:hover {
            background: rgba(255,255,255,0.5);
        }
        .preview-header.dragging .remove-btn {
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }
        .preview-container {
            min-height: 100px;
            padding: 15px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .add-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        .add-btn:hover {
            background: #0056b3;
        }
        .add-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .save-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .save-btn:hover {
            background: #218838;
        }
        .three-column-row {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .three-column-row .col {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Edit Custom Report</h2>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Reports
                    </a>
                </div>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('reports.update', $report) }}" method="POST" id="reportForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Report Info -->
                    <div class="section-card mb-4">
                        <div class="section-header">Basic Information</div>
                        <div class="section-body" style="height: auto; overflow: visible;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="report_name" class="form-label">Report Name</label>
                                    <input type="text" class="form-control" id="report_name" name="report_name" 
                                           value="{{ old('report_name', $report->report_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="description" name="description"
                                           value="{{ old('description', $report->description) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Three Column Layout -->
                    <div class="three-column-row">
                        <!-- Basic Info Section -->
                        <div class="col">
                            <div class="section-card">
                                <div class="section-header">Basic Info</div>
                                <div class="section-body">
                                    @foreach($availableColumns['basic_info'] as $columnKey => $columnName)
                                        <div class="column-item" id="column-{{ $columnKey }}">
                                            <span>{{ $columnName }}</span>
                                            <button type="button" class="add-btn" id="btn-{{ $columnKey }}" 
                                                    onclick="addColumn('{{ $columnKey }}', '{{ $columnName }}')">Add</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Financial Info Section -->
                        <div class="col">
                            <div class="section-card">
                                <div class="section-header">Financial Info</div>
                                <div class="section-body">
                                    @foreach($availableColumns['financial_info'] as $columnKey => $columnName)
                                        <div class="column-item" id="column-{{ $columnKey }}">
                                            <span>{{ $columnName }}</span>
                                            <button type="button" class="add-btn" id="btn-{{ $columnKey }}" 
                                                    onclick="addColumn('{{ $columnKey }}', '{{ $columnName }}')">Add</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Academic Info Section -->
                        <div class="col">
                            <div class="section-card">
                                <div class="section-header">Academic Info</div>
                                <div class="section-body">
                                    @foreach($availableColumns['academic_info'] as $columnKey => $columnName)
                                        <div class="column-item" id="column-{{ $columnKey }}">
                                            <span>{{ $columnName }}</span>
                                            <button type="button" class="add-btn" id="btn-{{ $columnKey }}" 
                                                    onclick="addColumn('{{ $columnKey }}', '{{ $columnName }}')">Add</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                       <!-- Qualification Info Section -->
                        <div class="col">
                            <div class="section-card">
                                <div class="section-header">Qualification Info</div>
                                <div class="section-body">
                                    @foreach($availableColumns['qualification_info'] as $columnKey => $columnName)
                                        <div class="column-item">
                                            <span>{{ $columnName }}</span>
                                            <button type="button" class="add-btn" onclick="addColumn('{{ $columnKey }}', '{{ $columnName }}')">Add</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Preview Headers Section -->
                    <div class="section-card">
                        <div class="section-header">Preview Headers</div>
                        <div class="section-body" style="height: auto; overflow: visible;">
                            <div class="preview-container" id="previewContainer">
                                <div class="text-muted text-center">
                                    <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                    <p>Click "Add" buttons to add columns to your report</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <button type="submit" class="save-btn">
                                    <i class="fas fa-save me-2"></i>Update Report
                                </button>
                            </div>
                        </div>
                    </div>
                 
                    <!-- Table Preview Section -->
                    <div class="section-card">
                        <div class="section-header">Table Preview</div>
                        <div class="section-body" style="height: auto; overflow: visible;">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr id="tablePreviewHeader">
                                            <td class="text-center text-muted" style="padding: 40px;">
                                                Add columns to see table preview
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs -->
                    <input type="hidden" name="selected_columns" id="selectedColumnsInput">
                    <input type="hidden" name="column_order" id="columnOrderInput">
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedColumns = [];
        let columnOrder = [];
        
        // Load existing report data
        const existingColumns = @json($report->selected_columns ?? []);
        const existingOrder = @json($report->column_order ?? []);
        
        // Initialize with existing data
        document.addEventListener('DOMContentLoaded', function() {
            if (existingColumns && existingColumns.length > 0) {
                selectedColumns = [...existingColumns];
                columnOrder = [...existingOrder];
                
                // Mark selected columns and disable buttons
                selectedColumns.forEach(columnKey => {
                    const columnElement = document.getElementById('column-' + columnKey);
                    const buttonElement = document.getElementById('btn-' + columnKey);
                    if (columnElement) {
                        columnElement.classList.add('selected');
                    }
                    if (buttonElement) {
                        buttonElement.textContent = 'Added';
                        buttonElement.disabled = true;
                    }
                });
                
                // Update previews
                updateAllPreviews();
                updateHiddenInputs();
            }
        });
        
        function addColumn(columnKey, columnName) {
            // Check if column already added
            if (selectedColumns.includes(columnKey)) {
                alert('Column already added!');
                return;
            }
            
            // Add to arrays
            selectedColumns.push(columnKey);
            columnOrder.push(columnKey);
            
            // Update UI
            const columnElement = document.getElementById('column-' + columnKey);
            const buttonElement = document.getElementById('btn-' + columnKey);
            if (columnElement) {
                columnElement.classList.add('selected');
            }
            if (buttonElement) {
                buttonElement.textContent = 'Added';
                buttonElement.disabled = true;
            }
            
            // Update preview
            updatePreview(columnKey, columnName);
            updateTablePreview();
            updateHiddenInputs();
        }
        
        function removeColumn(columnKey) {
            // Remove from arrays
            const index = selectedColumns.indexOf(columnKey);
            if (index > -1) {
                selectedColumns.splice(index, 1);
                columnOrder.splice(index, 1);
            }
            
            // Update UI
            const columnElement = document.getElementById('column-' + columnKey);
            const buttonElement = document.getElementById('btn-' + columnKey);
            if (columnElement) {
                columnElement.classList.remove('selected');
            }
            if (buttonElement) {
                buttonElement.textContent = 'Add';
                buttonElement.disabled = false;
            }
            
            // Update previews
            updateAllPreviews();
            updateHiddenInputs();
        }
        
        function updatePreview(columnKey, columnName) {
            const previewContainer = document.getElementById('previewContainer');
            
            // Remove placeholder if exists
            const placeholder = previewContainer.querySelector('.text-muted');
            if (placeholder) {
                placeholder.remove();
            }
            
            // Create preview header with drag functionality
            const headerDiv = document.createElement('div');
            headerDiv.className = 'preview-header';
            headerDiv.setAttribute('data-column', columnKey);
            headerDiv.draggable = true;
            headerDiv.innerHTML = `
                <span class="drag-handle">${columnName}</span>
                <button type="button" class="remove-btn" onclick="removeColumn('${columnKey}'); event.stopPropagation();">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add drag functionality
            setupDragAndDrop(headerDiv);
            
            previewContainer.appendChild(headerDiv);
        }
        
        function setupDragAndDrop(element) {
            element.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', this.getAttribute('data-column'));
                this.classList.add('dragging');
                
                // Prevent remove button from being clickable during drag
                const removeBtn = this.querySelector('.remove-btn');
                if (removeBtn) {
                    removeBtn.style.pointerEvents = 'none';
                }
                
                // Add drag-over class to all other headers
                setTimeout(() => {
                    document.querySelectorAll('.preview-header:not(.dragging)').forEach(header => {
                        header.classList.add('drop-target');
                    });
                }, 0);
            });
            
            element.addEventListener('dragover', function(e) {
                e.preventDefault();
                if (!this.classList.contains('dragging')) {
                    this.classList.add('drag-over');
                }
            });
            
            element.addEventListener('dragleave', function(e) {
                this.classList.remove('drag-over');
            });
            
            element.addEventListener('drop', function(e) {
                e.preventDefault();
                
                const draggedColumnKey = e.dataTransfer.getData('text/plain');
                const targetColumnKey = this.getAttribute('data-column');
                
                if (draggedColumnKey && targetColumnKey && draggedColumnKey !== targetColumnKey) {
                    // Find indices
                    const draggedIndex = columnOrder.indexOf(draggedColumnKey);
                    const targetIndex = columnOrder.indexOf(targetColumnKey);
                    
                    if (draggedIndex !== -1 && targetIndex !== -1) {
                        // Remove from old position and insert at new position
                        columnOrder.splice(draggedIndex, 1);
                        columnOrder.splice(targetIndex, 0, draggedColumnKey);
                        
                        // Update displays
                        updateAllPreviews();
                        updateHiddenInputs();
                    }
                }
                
                // Clean up classes
                this.classList.remove('drag-over');
            });
            
            element.addEventListener('dragend', function(e) {
                // Clean up all drag states
                document.querySelectorAll('.preview-header').forEach(header => {
                    header.classList.remove('dragging', 'drag-over', 'drop-target');
                    // Re-enable remove button
                    const removeBtn = header.querySelector('.remove-btn');
                    if (removeBtn) {
                        removeBtn.style.pointerEvents = 'auto';
                    }
                });
            });
            
            // Prevent drag when clicking on remove button
            const removeBtn = element.querySelector('.remove-btn');
            if (removeBtn) {
                removeBtn.addEventListener('mousedown', function(e) {
                    e.stopPropagation();
                });
                
                removeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                });
            }
        }
        
        function updateAllPreviews() {
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = '';
            
            if (selectedColumns.length === 0) {
                previewContainer.innerHTML = `
                    <div class="text-muted text-center" style="width: 100%;">
                        <i class="fas fa-plus-circle fa-2x mb-2"></i>
                        <p>Click "Add" buttons to add columns to your report</p>
                    </div>
                `;
            } else {
                columnOrder.forEach(columnKey => {
                    const columnName = getColumnName(columnKey);
                    const headerDiv = document.createElement('div');
                    headerDiv.className = 'preview-header';
                    headerDiv.setAttribute('data-column', columnKey);
                    headerDiv.draggable = true;
                    headerDiv.innerHTML = `
                        <span class="drag-handle">${columnName}</span>
                        <button type="button" class="remove-btn" onclick="removeColumn('${columnKey}'); event.stopPropagation();">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    // Add drag functionality
                    setupDragAndDrop(headerDiv);
                    
                    previewContainer.appendChild(headerDiv);
                });
            }
            
            updateTablePreview();
        }
        
        function updateTablePreview() {
            const tableHeader = document.getElementById('tablePreviewHeader');
            
            if (selectedColumns.length === 0) {
                tableHeader.innerHTML = '<td class="text-center text-muted" style="padding: 40px;">Add columns to see table preview</td>';
            } else {
                let headerHtml = '';
                columnOrder.forEach(columnKey => {
                    const columnName = getColumnName(columnKey);
                    headerHtml += `<th>${columnName}</th>`;
                });
                tableHeader.innerHTML = headerHtml;
            }
        }
        
        function getColumnName(columnKey) {
            // This should match your available columns array
            const columnMap = {
                @foreach($availableColumns as $category => $columns)
                    @foreach($columns as $key => $name)
                        '{{ $key }}': '{{ $name }}',
                    @endforeach
                @endforeach
            };
            return columnMap[columnKey] || columnKey;
        }
        
        function updateHiddenInputs() {
            document.getElementById('selectedColumnsInput').value = JSON.stringify(selectedColumns);
            document.getElementById('columnOrderInput').value = JSON.stringify(columnOrder);
        }
        
        // Form validation
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            if (selectedColumns.length === 0) {
                e.preventDefault();
                alert('Please add at least one column to your report.');
                return false;
            }
        });
    </script>
</body>
</html>