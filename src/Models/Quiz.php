<?php

namespace App\Models;

use App\DB;
use PDO;

class Quiz
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        return $quiz ?: null;
    }

    public function findByLesson(int $lessonId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE lesson_id = ? LIMIT 1");
        $stmt->execute([$lessonId]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        return $quiz ?: null;
    }

    public function getQuestions(int $quizId, bool $includeCorrect = false, bool $shuffle = false): array
    {
        $qStmt = $this->db->prepare("SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY sort_order ASC, id ASC");
        $qStmt->execute([$quizId]);
        $questions = $qStmt->fetchAll(PDO::FETCH_ASSOC);

        $ansSql = $includeCorrect
            ? "SELECT id, question_id, answer_text, is_correct FROM quiz_answers WHERE question_id = ? ORDER BY id ASC"
            : "SELECT id, question_id, answer_text FROM quiz_answers WHERE question_id = ? ORDER BY id ASC";
        $ansStmt = $this->db->prepare($ansSql);

        foreach ($questions as &$question) {
            $ansStmt->execute([$question['id']]);
            $answers = $ansStmt->fetchAll(PDO::FETCH_ASSOC);
            if ($shuffle) {
                shuffle($answers);
            }
            $question['answers'] = $answers;
        }

        if ($shuffle) {
            shuffle($questions);
        }

        return $questions;
    }

    public function submitAttempt(int $userId, int $quizId, array $submittedAnswers): array
    {
        $quiz = $this->find($quizId);
        if (!$quiz) {
            throw new \RuntimeException("Không tìm thấy bài kiểm tra.");
        }

        $questions = $this->getQuestions($quizId, true);
        $totalQuestions = count($questions);
        $totalPoints = 0.00;
        $earnedPoints = 0.00;
        $detailResults = [];

        foreach ($questions as $q) {
            $qId = $q['id'];
            $points = (float) $q['points'];
            $totalPoints += $points;

            // Lấy id đáp án đúng
            $correctAnswerIds = [];
            foreach ($q['answers'] as $ans) {
                if (!empty($ans['is_correct'])) {
                    $correctAnswerIds[] = (int) $ans['id'];
                }
            }

            $userAnsId = isset($submittedAnswers[$qId]) ? (int) $submittedAnswers[$qId] : null;
            $isCorrect = ($userAnsId !== null && in_array($userAnsId, $correctAnswerIds, true));

            if ($isCorrect) {
                $earnedPoints += $points;
            }

            $detailResults[] = [
                'question_id' => $qId,
                'question_text' => $q['question_text'],
                'explanation' => $q['explanation'],
                'chosen_answer_id' => $userAnsId,
                'is_correct' => $isCorrect,
                'correct_answer_ids' => $correctAnswerIds,
            ];
        }

        $percentage = ($totalPoints > 0) ? round(($earnedPoints / $totalPoints) * 100, 2) : 0.00;
        $isPassed = ($percentage >= (float) $quiz['passing_score']);

        // Lưu vào quiz_attempts
        $stmt = $this->db->prepare("INSERT INTO quiz_attempts (user_id, quiz_id, score_achieved, total_score, score_percentage, is_passed, submitted_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $quizId,
            $earnedPoints,
            $totalPoints,
            $percentage,
            $isPassed ? 1 : 0,
            date('Y-m-d H:i:s'),
        ]);
        $attemptId = (int) $this->db->lastInsertId();

        // Lưu chi tiết từng câu vào quiz_attempt_answers
        $detStmt = $this->db->prepare("INSERT INTO quiz_attempt_answers (attempt_id, question_id, answer_id, is_correct) VALUES (?, ?, ?, ?)");
        foreach ($detailResults as $det) {
            if ($det['chosen_answer_id'] !== null) {
                $detStmt->execute([
                    $attemptId,
                    $det['question_id'],
                    $det['chosen_answer_id'],
                    $det['is_correct'] ? 1 : 0,
                ]);
            }
        }

        // Nếu pass và quiz gắn với bài học, tự động đánh dấu hoàn thành bài học
        if ($isPassed && !empty($quiz['lesson_id'])) {
            $progModel = new LessonProgress();
            $progModel->markCompleted($userId, (int) $quiz['lesson_id']);

            $enrollModel = new Enrollment();
            $enrollModel->updateProgress($userId, (int) $quiz['course_id']);
        }

        return [
            'attempt_id' => $attemptId,
            'earned_points' => $earnedPoints,
            'total_points' => $totalPoints,
            'score_percentage' => $percentage,
            'is_passed' => $isPassed,
            'passing_score' => (float) $quiz['passing_score'],
            'details' => $detailResults,
        ];
    }

    public function getLatestAttempt(int $quizId, int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM quiz_attempts WHERE quiz_id = ? AND user_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$quizId, $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
