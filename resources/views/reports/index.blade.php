@extends("admin_layouts.app")
@section('title', __('english.student_details'))

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
    <style>
        .btn-group .btn {
            border-radius: 0;
        }
        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }
    </style>

        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Custom Reports</h2>
                    <a href="{{ route('reports.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Report
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-file-alt me-2"></i>Report Name</th>
                                        <th><i class="fas fa-info-circle me-2"></i>Description</th>
                                        <th><i class="fas fa-columns me-2"></i>Columns</th>
                                        <th><i class="fas fa-user me-2"></i>Created By</th>
                                        <th><i class="fas fa-calendar me-2"></i>Created At</th>
                                        <th><i class="fas fa-cogs me-2"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $report)
                                        <tr>
                                            <td><strong>{{ $report->report_name }}</strong></td>
                                            <td>{{ $report->description ?? 'No description' }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-list me-1"></i>{{ count($report->column_order) }} columns
                                                </span>
                                            </td>
                                            <td>{{ $report->creator->name ?? 'Unknown' }}</td>
                                            <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-info" title="View Report">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if($report->created_by == auth()->id())
                                                        <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-warning" title="Edit Report">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <button type="button" class="btn btn-sm btn-danger" title="Delete Report"
                                                                onclick="confirmDelete({{ $report->id }}, '{{ $report->report_name }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                                @if($report->created_by == auth()->id())
                                                    <form id="delete-form-{{ $report->id }}"
                                                          action="{{ route('reports.destroy', $report) }}"
                                                          method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <h5>No reports found</h5>
                                                <p>Create your first custom report to get started</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the report <strong id="reportNameToDelete"></strong>?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let reportIdToDelete = null;

    function confirmDelete(reportId, reportName) {
        reportIdToDelete = reportId;
        document.getElementById('reportNameToDelete').textContent = reportName;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (reportIdToDelete) {
            document.getElementById('delete-form-' + reportIdToDelete).submit();
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection
