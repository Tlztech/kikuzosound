import React from "react";
import { storiesOf } from "@storybook/react";

import ExamManagePreview from "./index";

storiesOf("organisms-ExamManagePreview", module).add("Preview", () => {
  const examEditItem = {
    //   {Analytics: "test", Created: "2020-10-16 09:22:22"},
    Analytics: "test",
    Created: "2020-10-16 09:22:22",
    Delete: "teset",
    Edit: "teste",
    Enable_Disable: "enabled",
    Step_of_Exam: [
      {
        id: 147,
        question:
          "【問題】この症例の頸静脈を見て答えなさい。この症例に「異常所…",
        quiz_choices: [
          { is_correct_answer: 1, title: "異常なし" },
          { is_correct_answer: 2, title: "異常あり" },
          { is_correct_answer: 3, title: "異常あり" },
        ],
        title: "1.視診問題（頸静脈）",
      },
      {
        id: 148,
        question:
          "【問題】この症例の心エコー図の説明としてふさわしいものはどれ",
        quiz_choices: [
          { is_correct_answer: 1, title: "異常なし" },
          { is_correct_answer: 2, title: "異常あり" },
          { is_correct_answer: 3, title: "異常あり" },
        ],
        title: "2.視診問題（頸静脈）",
      },
      {
        id: 145,
        question:
          "【問題】この症例の心音は次のどれか？正しいものを１つ選びなさ",
        quiz_choices: [
          { is_correct_answer: 1, title: "異常なし" },
          { is_correct_answer: 2, title: "異常あり" },
          { is_correct_answer: 3, title: "異常あり" },
        ],
        title: "3.視診問題（頸静脈）",
      },
    ],
    Updated: "2020-10-16 18:22:22",
    description: "ddddAll about ",
    description_en:
      "動画イメージ。dd問題には1問につき動画1点（80Mbまで）が掲載できます。動画はお客様でご用意ください",
    destination_mail: "yhhh@h.com",
    disp_order: 220,
    examId: [18],
    exam_release: 0,
    icon_path: "",
    is_public: 1,
    lang: "ja",
    max_quiz_count: 3,
    quizPackId: 18,
    quiz_order_type: 1,
    title_color: "#333333",
    title_en: "q心臓病診断の動画イメージ",
    title_jp: "Hello Textdd  data ",
    type_exam: false,
    type_quizzes: true,
  };

  return (
    <ExamManagePreview
      isVisible={true}
      onHidePreviewModal={() => console.log("hello")}
      examPreviewItem={examEditItem}
    />
  );
});
