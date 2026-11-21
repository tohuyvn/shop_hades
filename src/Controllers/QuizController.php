<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;

class QuizController
{
    private Quiz $quizModel;
    private Enrollment $enrollmentModel;

    public function __construct()
    {
        $this->quizModel = new Quiz();
        $this->enrollmentModel = new Enrollment();
    }

    public function show(int $quizId): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập để làm bài kiểm tra.');
            redirect('/login');
        }

        $userId = (int) $user['id'];
        $quiz = $this->quizModel->find($quizId);
        if (!$quiz) {
            http_response_code(404);
            flash('error', 'Bài kiểm tra không tồn tại.');
            redirect('/courses');
        }

        $courseId = (int) $quiz['course_id'];
        if (!isAdmin() && !$this->enrollmentModel->isEnrolled($userId, $courseId)) {
            flash('error', 'Bạn cần đăng ký khóa học này trước khi làm bài thi.');
            redirect('/courses/' . $courseId);
        }

        $questions = $this->quizModel->getQuestions($quizId, false, true);
        $latestAttempt = $this->quizModel->getLatestAttempt($quizId, $userId);

        view('client.quiz.show', [
            'quiz' => $quiz,
            'questions' => $questions,
            'latestAttempt' => $latestAttempt,
            'title' => 'Làm bài thi: ' . $quiz['title'],
        ]);
    }

    public function submit(int $quizId): void
    {
        $user = authUser();
        if (!$user) {
            flash('error', 'Vui lòng đăng nhập.');
            redirect('/login');
        }

        $userId = (int) $user['id'];
        $quiz = $this->quizModel->find($quizId);
        if (!$quiz) {
            http_response_code(404);
            flash('error', 'Bài kiểm tra không tồn tại.');
            redirect('/courses');
        }

        $submittedAnswers = $_POST['answers'] ?? [];
        $result = $this->quizModel->submitAttempt($userId, $quizId, $submittedAnswers);

        view('client.quiz.result', [
            'quiz' => $quiz,
            'result' => $result,
            'title' => 'Kết quả bài thi: ' . $quiz['title'],
        ]);
    }
}
