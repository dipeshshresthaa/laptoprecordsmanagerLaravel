<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // <--- Ensure DomPDF is imported!

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'active');
        $search = $request->input('search');
        $isActive = $status === 'active';

        // Filter for the operational tabs (Partners, Trainees, Principals)
        $applyFilters = function ($query) use ($isActive, $search) {
            $query->where('is_active', $isActive);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                });
            }
        };

        $partners = Employee::query()->where('role', 'Partner')->where($applyFilters)->orderBy('first_name', 'asc')->get();
        $trainees = Employee::with('principal')->where('role', 'ArticleTrainee')->where($applyFilters)->orderBy('first_name','asc')->get();

        $principalStats = Employee::query()->where('principal_id', '<>', null)
            ->where('role', 'ArticleTrainee')->where($applyFilters)
            ->select('principal_id', DB::raw('count(*) as trainee_count'))
            ->with('principal')->groupBy('principal_id')->get();

        // NEW: Total Staff List (Ignores active/left toggle, respects search)
        $totalStaffQuery = Employee::with('principal') ->orderBy('role', 'desc')
            ->orderBy('is_active', 'desc')       // 1. Active staff (1) first, then Left staff (0)
            ->orderBy('employment_date', 'desc') // 2. Date of employment descending (Newest first)
            ->orderBy('first_name', 'asc')       // 3. First name ascending (A-Z)
            ->orderBy('last_name', 'asc');;
        if ($search) {
            $totalStaffQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }
        $totalStaff = $totalStaffQuery->get();

        return view('reports.index', compact('partners', 'trainees', 'principalStats', 'totalStaff', 'status', 'search'));
    }

    // NEW: PDF Export Method
    public function downloadComprehensivePdf(Request $request)
    {
        $search = $request->input('search');

        // Base Query: Respects the search bar, but ignores active/left toggle so we get everyone
        $baseQuery = Employee::with('principal')
            ->orderBy('role', 'desc')
            ->orderBy('is_active', 'desc')       // 1. Active staff (1) first, then Left staff (0)
            ->orderBy('employment_date', 'desc') // 2. Date of employment descending (Newest first)
            ->orderBy('first_name', 'asc')       // 3. First name ascending (A-Z)
            ->orderBy('last_name', 'asc');       // 4. Last name ascending (A-Z)

        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // 1. Active Staff
        $activeStaff = (clone $baseQuery)->where('is_active', true)->get();

        // 2. Left / Inactive Staff
        $leftStaff = (clone $baseQuery)->where('is_active', false)->get();

        // 3. Principal-wise Trainees (Group only the active trainees by their principal)
        $activeTrainees = (clone $baseQuery)->where('role', 'ArticleTrainee')->where('is_active', true)->get();
        $principalGroups = $activeTrainees->groupBy('principal_id');

        // Fetch the actual Principal models so we can print their names
        $validPrincipalIds = $principalGroups->keys()->filter()->toArray();
        $allPrincipals = Employee::whereIn('id', $validPrincipalIds)->get()->keyBy('id');

        // 4. Overall Staff (Master Roster)
        $totalStaff = (clone $baseQuery)->get();

        $pdf = Pdf::loadView('pdfs.comprehensive_report', compact(
            'activeStaff',
            'leftStaff',
            'principalGroups',
            'allPrincipals',
            'totalStaff',
            'search'
        ), [], 'UTF-8');

        return $pdf->download('Comprehensive_Firm_Report_' . now()->format('Ymd') . '.pdf');
    }

    public function getTraineesByPrincipal(Request $request, $principalId)
    {
        // Ensure the popup respects whether we are viewing Active or Left staff
        $status = $request->query('status', 'active');
        $isActive = $status === 'active';

        $trainees = Employee::query()->where('principal_id', $principalId)
            ->where('role', 'ArticleTrainee')
            ->where('is_active', $isActive)
            ->select('id', 'first_name', 'last_name', 'department')
            ->orderBy('first_name', 'asc')
            ->get();

        return response()->json($trainees);
    }
}
