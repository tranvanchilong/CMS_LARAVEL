<?php

namespace App\Models\LMS\Api\Traits;

use App\Http\Resources\UserResource;
use App\Models\LMS\Api\Quiz;
use App\Models\LMS\Api\QuizzesResult;
use App\Models\LMS\Api\User;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;
use App\Models\LMS\WebinarAssignmentHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait WebinarChartTrait
{

    public function getStudentsIdsAttribute()
    {
        return Sale::where('webinar_id', $this->id)
            ->whereNull('refund_at')
            ->pluck('buyer_id')
            ->toArray();
    }

    public function getStudentsRolesAttribute()
    {

        $labels = [
            trans('public.students'),
            trans('public.instructors'),
            trans('home.organizations'),
        ];
        $studentsIds = Sale::where('webinar_id', $this->id)
            ->whereNull('refund_at')
            ->pluck('buyer_id')
            ->toArray();

        $users = User::whereIn('id', $studentsIds)
            ->select('id', 'role_name', DB::raw('count(id) as count'))
            ->groupBy('role_name')
            ->get();


        $data['students'] = 0;
        $data['instructors'] = 0;
        $data['organizations'] = 0;

        foreach ($users as $user) {
            if ($user->role_name == Role::$user) {
                $data['students'] = $user->count;
            } else if ($user->role_name == Role::$teacher) {
                $data['instructors'] = $user->count;
            } else if ($user->role_name == Role::$organization) {
                $data['organizations'] = $user->count;
            }
        }
        return $data;


        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function getQuizStatusAttribute()
    {
        $labels = [
            trans('quiz.passed'),
            trans('public.pending'),
            trans('quiz.failed'),
        ];

        $data[0] = 0; // passed
        $data[1] = 0; // pending
        $data[2] = 0; // failed

        $quizzes = $this->quizzes;

        foreach ($quizzes as $quiz) {
            $passed = $quiz->quizResults()->where('status', QuizzesResult::$passed)->count();
            $pending = $quiz->quizResults()->where('status', QuizzesResult::$waiting)->count();
            $failed = $quiz->quizResults()->where('status', QuizzesResult::$failed)->count();

            $data[0] += $passed;
            $data[1] += $pending;
            $data[2] += $failed;
        }
        return array_combine($labels, $data);

    }

    public function getAssignmentsStatusAttribute()
    {
        $labels = [
            trans('quiz.passed'),
            trans('public.pending'),
            trans('quiz.failed'),
        ];

        $data[0] = 0; // passed
        $data[1] = 0; // pending
        $data[2] = 0; // failed

        $assignments = $this->assignments;

        foreach ($assignments as $quiz) {
            $passed = $quiz->assignmentHistory()->where('status', WebinarAssignmentHistory::$passed)->count();
            $pending = $quiz->assignmentHistory()->where('status', WebinarAssignmentHistory::$pending)->count();
            $failed = $quiz->assignmentHistory()->where('status', WebinarAssignmentHistory::$notPassed)->count();

            $data[0] += $passed;
            $data[1] += $pending;
            $data[2] += $failed;
        }

        return array_combine($labels, $data);

    }


    public function getMonthlySalesAttribute()
    {
        $labels = [];
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(date('Y'), $month);

            $start_date = $date->timestamp;
            $end_date = $date->copy()->endOfMonth()->timestamp;

            $labels[] = trans('panel.month_' . $month);

            $amount = Sale::whereNull('refund_at')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('webinar_id', $this->id)
                ->sum('total_amount');

            $data[] = round($amount, 2);
        }

        return array_combine($labels, $data);

    }

    public function getCourseProgressAttribute()
    {
        $labels = [
            trans('update.completed'),
            trans('webinars.in_progress'),
            trans('update.not_started'),
        ];

        $data[0] = 0; // completed
        $data[1] = 0; // in_progress
        $data[2] = 0; // not_started

        foreach ($this->StudentsIds as $userId) {

            $progress = $this->getCourseProgressForStudent($this, $userId);

            if ($progress > 0 and $progress < 100) {
                $data[1] += 1;
            } elseif ($progress == 100) {
                $data[0] += 1;
            } else {
                $data[2] += 1;
            }
        }

        return array_combine($labels, $data);

    }

    public function getCourseProgressLineAttribute()
    {
        $labels = [];
        $data = [];

        $progress = [];

        foreach ($this->StudentsIds as $userId) {
            $progress[] = $this->getCourseProgressForStudent($this, $userId);
        }

        for ($percent = 0; $percent < 100; $percent += 10) {
            $endPercent = $percent + 10;
            $labels[] = $percent . '-' . $endPercent;

            $count = 0;

            foreach ($progress as $value) {
                if ($value >= $percent and $value < $endPercent) {
                    $count += 1;
                }
            }

            $data[] = $count;
        }

        return array_combine($labels, $data);

    }

    public function getCourseProgressForStudent($webinar, $userId)
    {
        $progress = 0;


        $filesStat = $webinar->getFilesLearningProgressStat($userId);
        $sessionsStat = $webinar->getSessionsLearningProgressStat($userId);
        $textLessonsStat = $webinar->getTextLessonsLearningProgressStat($userId);
        $assignmentsStat = $webinar->getAssignmentsLearningProgressStat($userId);
        $quizzesStat = $webinar->getQuizzesLearningProgressStat($userId);

        $passed = $filesStat['passed'] + $sessionsStat['passed'] + $textLessonsStat['passed'] + $assignmentsStat['passed'] + $quizzesStat['passed'];
        $count = $filesStat['count'] + $sessionsStat['count'] + $textLessonsStat['count'] + $assignmentsStat['count'] + $quizzesStat['count'];

        if ($passed > 0 and $count > 0) {
            $progress = ($passed * 100) / $count;
        }

        return round($progress, 2);
    }

    public function getStudents()
    {
        $webinar = $this;
        $users = User::whereIn('id', $this->studentsIds)
            ->paginate(10);

        $quizzesIds = $webinar->quizzes->pluck('id')->toArray();
        $assignmentsIds = $webinar->assignments->pluck('id')->toArray();

        foreach ($users as $user) {
            $user->course_progress = $this->getCourseProgressForStudent($webinar, $user->id);

            $user->passed_quizzes = Quiz::whereIn('lms_quizzes.id', $quizzesIds)
                ->join('lms_quizzes_results', 'lms_quizzes_results.quiz_id', 'lms_quizzes.id')
                ->select(DB::raw('count(lms_quizzes_results.id) as count'))
                ->where('lms_quizzes_results.user_id', $user->id)
                ->where('lms_quizzes_results.status', QuizzesResult::$passed)
                ->first()->count;

            $assignmentsHistoriesCount = WebinarAssignmentHistory::whereIn('assignment_id', $assignmentsIds)
                ->where('student_id', $user->id)
                ->count();

            $user->unsent_assignments = count($assignmentsIds) - $assignmentsHistoriesCount;

            $user->pending_assignments = WebinarAssignmentHistory::whereIn('assignment_id', $assignmentsIds)
                ->where('student_id', $user->id)
                ->where('status', WebinarAssignmentHistory::$pending)
                ->count();
        }
        return UserResource::collection($users);
    }


}




