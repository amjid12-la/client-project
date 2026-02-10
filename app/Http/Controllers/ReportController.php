<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Notifications\NewReportSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::approved()
            ->latest()
            ->get();

        return view('frontend.pages.reports', compact('reports'));
    }

    public function create()
    {
        return view('frontend.pages.submit-report');
    }

    public function store(Request $request)
    {
        // Check if request is AJAX
        $isAjax = $request->ajax() || $request->wantsJson();

        try {
            $validator = Validator::make($request->all(), [
                'individual_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'location' => 'nullable|string|max:255',
                'narrative' => 'nullable|string|min:10|max:1000',
                'g-recaptcha-response' => 'required',
            ], [
                'individual_name.required' => 'Name is required',
                'individual_name.regex' => 'Name must contain only letters and spaces',
                'photo.image' => 'File must be an image',
                'photo.max' => 'Photo size must not exceed 5MB',
                'narrative.min' => 'Description must be at least 10 characters',
                'narrative.max' => 'Description must not exceed 1000 characters',
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification',
            ]);

            // Custom validation: Check space count in name (max 10 spaces)
            if (!$validator->fails()) {
                $name = $request->input('individual_name');
                $spaceCount = substr_count($name, ' ');
                if ($spaceCount > 4) {
                    $validator->errors()->add('individual_name', 'Name can contain maximum 4 spaces');
                }
            }

            // Validate reCAPTCHA
            if (!$validator->fails()) {
                $recaptchaResponse = $request->input('g-recaptcha-response');
                $secretKey = env('RECAPTCHA_SECRET_KEY');
                
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $recaptchaResponse);
                $responseData = json_decode($verifyResponse);
                
                if (!$responseData->success) {
                    $validator->errors()->add('g-recaptcha-response', 'reCAPTCHA verification failed. Please try again.');
                }
            }

            // If validation fails and it's AJAX, return JSON
            if ($validator->fails()) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            // Start database transaction
            \DB::beginTransaction();

            try {
                // Handle photo upload
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('frontend/reports'), $filename);
                    $photoPath = 'frontend/reports/' . $filename;
                }

                // Create report
                $report = Report::create([
                    'individual_name' => $validated['individual_name'],
                    'photo_path' => $photoPath,
                    'incident_date' => null,
                    'location' => $validated['location'] ?? null,
                    'narrative' => $validated['narrative'] ?? null,
                    'status' => 'pending',
                ]);

                // Check for potential duplicates (basic duplicate detection)
                $this->checkForDuplicates($report);

                // Commit transaction
                \DB::commit();

                // Send notification to admin email via queue (outside transaction)
                try {
                    $adminEmail = env('MAIL_FROM_ADDRESS', 'omarahmad2326@gmail.com');
                     $reportUrl = route('admin.reports.show', $report->id);
                    \Mail::to($adminEmail)->queue(new \App\Mail\NewReportSubmittedMail($report, $reportUrl));
                    \Log::info('Report notification queued for admin: ' . $adminEmail);
                } catch (\Exception $e) {
                    // Log notification failure but don't stop the process
                    \Log::warning('Failed to queue notification email: ' . $e->getMessage());
                }

                // Return JSON for AJAX requests
                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Report submitted successfully! It will be reviewed by our admin team.'
                    ]);
                }

                // Regular redirect for non-AJAX requests
                return redirect()->route('home')
                    ->with('success', 'Report submitted successfully! It will be reviewed by our admin team.');

            } catch (\Exception $e) {
                // Rollback transaction on error
                \DB::rollBack();

                // Log the error
                \Log::error('Failed to create report: ' . $e->getMessage());

                // Delete uploaded photo if exists
                if (isset($photoPath) && file_exists(public_path($photoPath))) {
                    unlink(public_path($photoPath));
                }

                // Return error response
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to submit report. Please try again.'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Failed to submit report. Please try again.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Unexpected error in report submission: ' . $e->getMessage());

            // Return error response
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected error occurred. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'An unexpected error occurred. Please try again.')
                ->withInput();
        }
    }

    /**
     * Check for potential duplicate reports
     * Basic duplicate detection based on name, location, and description
     */
    private function checkForDuplicates(Report $newReport)
    {
        try {
            // Get all existing reports (pending and approved)
            $existingReports = Report::whereIn('status', ['pending', 'approved'])
                ->where('id', '!=', $newReport->id)
                ->get();

            $potentialDuplicates = [];

            foreach ($existingReports as $existingReport) {
                $similarityScore = 0;
                $matchedFields = [];

                // 1. Check name similarity (case-insensitive, exact match)
                if (!empty($newReport->individual_name) && !empty($existingReport->individual_name)) {
                    if (strtolower(trim($newReport->individual_name)) === strtolower(trim($existingReport->individual_name))) {
                        $similarityScore += 40; // Name match is 40 points
                        $matchedFields[] = 'name';
                    }
                }

                // 2. Check location similarity (case-insensitive, contains)
                if (!empty($newReport->location) && !empty($existingReport->location)) {
                    $newLocation = strtolower(trim($newReport->location));
                    $existingLocation = strtolower(trim($existingReport->location));
                    
                    if ($newLocation === $existingLocation) {
                        $similarityScore += 30; // Exact location match is 30 points
                        $matchedFields[] = 'location';
                    } elseif (strpos($newLocation, $existingLocation) !== false || strpos($existingLocation, $newLocation) !== false) {
                        $similarityScore += 15; // Partial location match is 15 points
                        $matchedFields[] = 'location (partial)';
                    }
                }

                // 3. Check narrative/description similarity (basic text comparison)
                if (!empty($newReport->narrative) && !empty($existingReport->narrative)) {
                    $newNarrative = strtolower(trim($newReport->narrative));
                    $existingNarrative = strtolower(trim($existingReport->narrative));
                    
                    // Calculate similarity percentage
                    similar_text($newNarrative, $existingNarrative, $percent);
                    
                    if ($percent >= 80) {
                        $similarityScore += 30; // High similarity is 30 points
                        $matchedFields[] = 'description (high)';
                    } elseif ($percent >= 50) {
                        $similarityScore += 15; // Medium similarity is 15 points
                        $matchedFields[] = 'description (medium)';
                    }
                }

                // If similarity score is 50 or higher, mark as potential duplicate
                if ($similarityScore >= 50) {
                    $potentialDuplicates[] = [
                        'report_id' => $existingReport->id,
                        'similarity_score' => $similarityScore,
                        'matched_fields' => implode(', ', $matchedFields),
                    ];
                }
            }

            // Store potential duplicates in the report's metadata
            if (!empty($potentialDuplicates)) {
                // Sort by similarity score (highest first)
                usort($potentialDuplicates, function($a, $b) {
                    return $b['similarity_score'] - $a['similarity_score'];
                });

                // Store as JSON in admin_notes or create a new field
                $duplicateInfo = json_encode([
                    'has_duplicates' => true,
                    'duplicate_count' => count($potentialDuplicates),
                    'duplicates' => $potentialDuplicates,
                    'checked_at' => now()->toDateTimeString(),
                ]);

                $newReport->update([
                    'duplicate_check' => $duplicateInfo,
                ]);

                \Log::info('Potential duplicates found for report #' . $newReport->id, [
                    'duplicate_count' => count($potentialDuplicates),
                    'duplicates' => $potentialDuplicates,
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the report submission
            \Log::error('Duplicate check failed: ' . $e->getMessage());
        }
    }
}
