import React from "react";
import RankingModal from "./index";

export default {
  title: "organism-rankingModal",
};

const popularQuizData = [
  {
    id: 1,
    filteredId: 1,
    filteredName: "Quiz 5",
    univId: 1,
    univName: "Quiz 5",
    systemId: 1,
    systemName: "Quiz 5",
  },
  {
    id: 2,
    filteredId: 2,
    filteredName: "Quiz 2",
    univId: 2,
    univName: "Quiz 2",
    systemId: 2,
    systemName: "Quiz 2",
  },
  {
    id: 3,
    filteredId: 3,
    filteredName: "Quiz 1",
    univId: 3,
    univName: "Quiz 1",
    systemId: 3,
    systemName: "Quiz 1",
  },
  {
    id: 4,
    filteredId: 4,
    filteredName: "Quiz 4",
    univId: 4,
    univName: "Quiz 4",
    systemId: 4,
    systemName: "Quiz 4",
  },
];

const learningTimeRankData = [
  {
    filtered_id: 1,
    filtered_user_name: "User A",
    univ_id: 1,
    univ_user_name: "User A",
  },
  {
    filtered_id: 2,
    filtered_user_name: "User B",
    univ_id: 2,
    univ_user_name: "User B",
  },

  {
    filtered_id: 3,
    filtered_user_name: "User C",
    univ_id: 3,
    univ_user_name: "User C",
  },
  {
    filtered_id: 4,
    filtered_user_name: "User D",
    univ_id: 4,
    univ_user_name: "User D",
  },
  {
    filtered_id: 5,
    filtered_user_name: "User E",
    univ_id: 5,
    univ_user_name: "User E",
  },
];

const popularLibraryRankData = [
  {
    filtered_id: 1,
    filtered_user_name: "Library A",
    univ_id: 1,
    univ_user_name: "Library A",
    system_id: 1,
    system_name: "Library A",
  },
  {
    filtered_id: 2,
    filtered_user_name: "Library B",
    univ_id: 2,
    univ_user_name: "Library B",
    system_id: 2,
    system_name: "Library B",
  },

  {
    filtered_id: 3,
    filtered_user_name: "Library C",
    univ_id: 3,
    univ_user_name: "Library C",
    system_id: 3,
    system_name: "Library C",
  },
  {
    filtered_id: 4,
    filtered_user_name: "Library D",
    univ_id: 4,
    univ_user_name: "Library D",
    system_id: 4,
    system_name: "Library D",
  },
];

const examResultUserData = [
  {
    filtered_id: 1,
    filtered_user_name: "User A",
    univ_id: 1,
    univ_user_name: "User A",
  },
  {
    filtered_id: 2,
    filtered_user_name: "User B",
    univ_id: 2,
    univ_user_name: "User B",
  },

  {
    filtered_id: 3,
    filtered_user_name: "User C",
    univ_id: 3,
    univ_user_name: "User C",
  },
  {
    filtered_id: 4,
    filtered_user_name: "User D",
    univ_id: 4,
    univ_user_name: "User D",
  },
];

const examResultRankCorrectRateData = [
  {
    filtered_id: "1",
    filtered_correctRate: "Exam B 80%",
    univ_id: "1",
    univ_correctRate: "Exam B 80%",
  },
  {
    filtered_id: "2",
    filtered_correctRate: "Exam C 80%",
    univ_id: "2",
    univ_correctRate: "Exam C 80%",
  },
  {
    filtered_id: "3",
    filtered_correctRate: "Exam D 80%",
    univ_id: "3",
    univ_correctRate: "Exam D 80%",
  },
  {
    filtered_id: "4",
    filtered_correctRate: "Exam E 80%",
    univ_id: "4",
    univ_correctRate: "Exam E 80%",
  },
  {
    filtered_id: "5",
    filtered_correctRate: "Exam F 80%",
    univ_id: "5",
    univ_correctRate: "Exam F 80%",
  },
];

const quizResultData = [
  {
    filtered_id: "1",
    filtered_correctRate: "Exam B 80%",
    univ_id: "1",
    univ_correctRate: "Exam B 80%",
  },
  {
    filtered_id: "2",
    filtered_correctRate: "Exam C 80%",
    univ_id: "2",
    univ_correctRate: "Exam C 80%",
  },
  {
    filtered_id: "3",
    filtered_correctRate: "Exam D 80%",
    univ_id: "3",
    univ_correctRate: "Exam D 80%",
  },
  {
    filtered_id: "4",
    filtered_correctRate: "Exam E 80%",
    univ_id: "4",
    univ_correctRate: "Exam E 80%",
  },
  {
    filtered_id: "5",
    filtered_correctRate: "Exam F 80%",
    univ_id: "5",
    univ_correctRate: "Exam F 80%",
  },
];

export const ranking_modal = () => (
  <RankingModal
    isVisible={true}
    popularQuizData={popularQuizData}
    learningTimeRankData={learningTimeRankData}
    popularLibraryRankData={popularLibraryRankData}
    examResultUserData={examResultUserData}
    examResultRankCorrectRateData={examResultRankCorrectRateData}
    quizResultData={quizResultData}
  />
);
