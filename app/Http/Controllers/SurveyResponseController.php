<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SurveyResponseController extends Controller
{
    public function show(Survey $survey, string $token)
    {
        try {
            $registrationId = Crypt::decrypt($token);

            // Check if already responded (for non-anonymous surveys)
            if (!$survey->is_anonymous) {
                $exists = SurveyResponse::where('survey_id', $survey->id)
                    ->where('registration_id', $registrationId)
                    ->exists();

                if ($exists) {
                    return view('surveys.already-submitted');
                }
            }

            return view('surveys.respond', compact('survey', 'token'));

        } catch (\Exception $e) {
            abort(403, 'Invalid survey link');
        }
    }

    public function store(Request $request, Survey $survey)
    {
        try {
            $registrationId = Crypt::decrypt($request->token);

            // Prevent duplicate submissions for non-anonymous surveys
            if (!$survey->is_anonymous) {
                $exists = SurveyResponse::where('survey_id', $survey->id)
                    ->where('registration_id', $registrationId)
                    ->exists();

                if ($exists) {
                    return view('surveys.already-submitted');
                }
            }

            $answers = [];
            foreach ($survey->questions as $index => $question) {
                $answers[] = $request->input("question_{$index}");
            }

            SurveyResponse::create([
                'survey_id'             => $survey->id,
                'registration_id'       => $registrationId,
                'event_registration_id' => null,
                'answers'               => $answers,
            ]);

            return view('surveys.thank-you');

        } catch (\Exception $e) {
            abort(403, 'Invalid submission');
        }
    }
}
