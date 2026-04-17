use App\Models\AuditLog;

function audit_log($action, $model = null, $modelId = null, $details = null)
{
    AuditLog::create([
        'user_id' => auth()->id(),
        'facility_id' => session('facility_id'),
        'action' => $action,
        'model' => $model,
        'model_id' => $modelId,
        'details' => $details
    ]);
}
