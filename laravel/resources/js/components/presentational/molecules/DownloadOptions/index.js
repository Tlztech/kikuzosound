import React from "react";

// libs
import { CSVLink } from "react-csv";

// components
import Div from "../../atoms/Div/index";
import Image from "../../atoms/Image/index";
import Toast from "../Toast/index";

// images
import csv from "../../../../../../public/csv.png";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class DownloadOptions extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      is_download_empty: false,
      csvData: null,
    };
  }

  async componentDidUpdate() {
    const { isDownloading, setDownloadCsv, t } = this.props;
    if (isDownloading) {
      setDownloadCsv(false);
      const checked = await checkDownloadCSV(this, t);
      if (checked) {
        this.csvLink.link.click();
      }
    }
  }

  render() {
    const { csvData, is_download_empty } = this.state;
    return (
      <Div className="molecules-DownloadOptions-wrapper">
        <Image
          url={csv}
          className="download-image"
          onClick={() => this.props.handleFetchCsvData()}
        />
        <CSVLink
          data={csvData ? csvData.table_data : []}
          filename={`${this.props.filename}.csv`}
          ref={(r) => (this.csvLink = r)}
        ></CSVLink>
        {is_download_empty && (
          <Div className="toast-wrapper">
            <Toast
              message={{
                mode: "failed",
                content: "Empty data.",
              }}
            />
          </Div>
        )}
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================

/**
 * check if data is available for csv
 * @param {*} context
 * @param {*} t
 * @returns
 */
const checkDownloadCSV = (context, t) => {
  if (context.state.is_download_empty) {
    context.setState({
      is_download_empty: false,
    });
  }
  return context.props.data
    ? context.props.data.table_data && context.props.data.table_data.length != 0
      ? downloadCSV(context, t)
      : setAlert(context)
    : setAlert(context);
};

/**
 * Display empty data alert
 * @param {*} context
 * @returns
 */
const setAlert = (context) => {
  context.setState({
    is_download_empty: true,
  });
  return false;
};

/**
 * download table_data in csv
 * @param {*} context
 */
const downloadCSV = (context, t) => {
  let data = context.props.data.table_data;
  const { csv_mode, chartType } = context.props;
  let csv_data = {};
  const selectedLanguage = i18next.language;

  switch (csv_mode) {
    case "exam_csv":
      let exam_data =
        data &&
        data.map((value) => {
          const caseSteps =
            value.Step_of_Exam &&
            value.Step_of_Exam.map((item) => {
              return selectedLanguage.lang === "en"
                ? item.title_en
                : item.title;
            });
          return {
            Id: value.examId.length != 0 ? value.examId[0] : value.quizPackId,
            [t("Title")]:
              selectedLanguage.lang === "en" ? value.title_en : value.title_jp,
            [t("stepofexam")]: caseSteps && caseSteps.join("\n"),
            [t("created_data_time")]: value.Created,
            [t("updated_data_time")]: value.Updated,
            [t("type")]: typeName(value, context),
            [t("public/private")]: value.exam_release
              ? t("public")
              : t("private"),
            [t("Author")]:
              value.author && value.author.role == 101
                ? t("admin")
                : value.user,
          };
        });
      csv_data = {
        table_data: exam_data,
      };
      // return csv_data;
      break;

    //for all library pages csv
    case "library_mode":
      csv_data = {};
      if (data) {
        let lib_data = data.map((data) => {
          return {
            ID: data.ID,
            [t("Title")]: data.title,
            [t("Auscultation sound type")]: data.soundtype,
            [t("Auscultation site")]: data.area,
            [t("Normal abnormal")]: data.normal_abnormal,
            [t("status")]: data.status,
            [t("Update Date")]: data.updated_at,
            [t("Author")]: data.user,
          };
        });
        csv_data = {
          table_data: lib_data,
        };
      }
      // return csv_data;
      break;

    case "test-analytics":
      let data_test_analytics = data.map((data) => {
        return {
          Id: data.id,
          exam_group: data.exam_group,
          exam: data.exam,
          user: data.user,
          start_datetime: data.start_datetime,
          end_datetime: data.end_datetime,
          used_time: data.used_time,
          answer: data.answer,
          miss_ok: data.miss_ok,
        };
      });
      csv_data = {
        table_data: data_test_analytics,
      };
      // return csv_data;
      break;

    case "quiz_list_csv_data":
      let data_quiz_packs = data.map((value) => {
        return {
          ID: value.id,
          [t("title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("an_illustration")]: `${
            value.illustration ? `${value.illustration}` : "/img/no_image.png"
          }`,
          [t("library")]: t(value.library),
          [t("content")]: t(value.content),
          [t("answer_options")]: t(value.answerOptions),
          [t("time_limit")]:
            value.timeLimit == 0 ? t("unlimited") : value.timeLimit,
        };
      });
      csv_data = {
        table_data: data_quiz_packs,
      };
      // return csv_data;
      break;

    case "exam_analytics_csv":
      let exam_analytics_data =
        chartType == "user"
          ? data.table_data.map((value) => {
              return {
                [t("group")]: value.group_name,
                [t("user")]: value.user_name,
                [t("title")]: value.exam_title,
                [t("qstn_number")]: value.number_of_question,
                [t("correct")]: value.correct_answers,
                [t("rate")]: value.rate,
                [t("average_study_time_day")]: value.average_time,
              };
            })
          : data.table_data.map((value) => {
              return {
                [t("group")]: value.group_name,
                [t("title")]: value.exam_title,
                [t("qstn_number")]: value.number_of_question,
                [t("correct")]: value.correct_answers,
                [t("rate")]: value.rate,
                [t("average_study_time_day")]: value.average_time,
              };
            });
      csv_data = {
        table_data: exam_analytics_data,
      };
      // return csv_data;
      break;

    case "quizResultRanking":
      let quizRankingCsv = data.own_data.all_data.map((item, index) => {
        const rowIndex = index;
        const filteredItem = data.filtered.all_data[`${rowIndex}`];
        const ownItem = data.own_data.all_data[`${rowIndex}`];

        return [
          rowIndex + 1,
          filteredItem && filteredItem.title_en,
          filteredItem && filteredItem.res,
          ownItem && ownItem.title_en,
          ownItem && ownItem.res,
        ];
      });
      csv_data = {
        table_data: [
          ["", t("filtered"), "", t("group_data"), ""],
          [t("ranking"), t("title"), t("average"), t("title"), t("average")],
          ...quizRankingCsv,
        ],
      };
      // return csv_data;
      break;

    case "examResultCorrectRate":
      let examCorrectRate = data.own_data.all_data.map((item, index) => {
        const rowIndex = index;
        const filteredItem = data.filtered.all_data[`${rowIndex}`];
        const ownItem = data.own_data.all_data[`${rowIndex}`];

        return [
          rowIndex + 1,
          filteredItem && filteredItem.name,
          filteredItem && filteredItem.res,
          ownItem && ownItem.name,
          ownItem && ownItem.res,
        ];
      });
      csv_data = {
        table_data: [
          ["", t("filtered"), "", t("group_data"), ""],
          [t("ranking"), t("title"), t("average"), t("title"), t("average")],
          ...examCorrectRate,
        ],
      };
      // return csv_data;
      break;

    case "popularQuizRank":
      let popularQuiz = data.own_data.all_data.map((item, index) => {
        const rowIndex = index;
        const filteredItem = data.filtered.all_data[`${rowIndex}`];
        const ownItem = data.own_data.all_data[`${rowIndex}`];
        return [
          rowIndex + 1,
          filteredItem && filteredItem.title_en,
          filteredItem && filteredItem.total,
          ownItem && ownItem.title_en,
          ownItem && ownItem.total
        ];
      });
      csv_data = {
        table_data: [
          [
            "",
            t("filtered"),
            "",
            t("all_own_group_ranking")
          ],
          [
            t("ranking"),
            t("title"),
            t("no_of_answer"),
            t("title"),
            t("no_of_answer")
          ],
          ...popularQuiz,
        ],
      };
      // return csv_data;
      break;

    case "learningTimeRanking":
      let learningTime = data.own.all_data.map((item, index) => {
        const rowIndex = index;
        const filteredItem = data.filtered.all_data[`${rowIndex}`];
        const ownItem = data.own.all_data[`${rowIndex}`];
        return [
          rowIndex + 1,
          filteredItem && filteredItem.name,
          filteredItem && convetSeconds(filteredItem.used_time),
          ownItem && ownItem.name,
          ownItem && convetSeconds(ownItem.used_time),
        ];
      });
      csv_data = {
        table_data: [
          ["", t("filtered"), "", t("group_data"), ""],
          [
            t("ranking"),
            t("user_name"),
            t("Total"),
            t("user_name"),
            t("Study_time_total"),
          ],
          ...learningTime,
        ],
      };
      // return csv_data;
      break;

    case "exam_result_user_rank":
      let examResultUser = data.own_data.all_data.map((item, index) => {
        const rowIndex = index;
        const filteredItem = data.filtered.all_data[`${rowIndex}`];
        const ownItem = data.own_data.all_data[`${rowIndex}`];
        return [
          rowIndex + 1,
          filteredItem && filteredItem.name,
          filteredItem && filteredItem.res + "%",
          ownItem && ownItem.name,
          ownItem && ownItem.res + "%",
        ];
      });
      csv_data = {
        table_data: [
          ["", t("filtered"), "", t("all_own_group"), ""],
          [
            t("ranking"),
            t("User_name"),
            t("Average"),
            t("User_name"),
            t("Average"),
          ],
          ...examResultUser,
        ],
      };
      // return csv_data;
      break;

    case "popular_library_rank":
      const popularLibrary =
        data &&
        data.own_data.all_data.map((item, index) => {
          const rowIndex = index;
          const filteredItem = data.filtered.all_data[`${rowIndex}`];
          const ownItem = data.own_data.all_data[`${rowIndex}`];
          return [
            index + 1,
            (filteredItem && filteredItem.title_en) || "-",
            (filteredItem && convetSeconds(filteredItem.used_time)) || "-",
            (ownItem && ownItem.title_en) || "-",
            (ownItem && convetSeconds(ownItem.used_time)) || "-",

          ];
        });
      csv_data = {
        table_data: [
          [
            "",
            t("filtered"),
            "",
            t("all_own_group_ranking"),
          ],
          [
            t("ranking"),
            t("title"),
            t("time"),
            t("title"),
            t("time"),
          ],
          ...popularLibrary,
        ],
      };
      // return csv_data;
      break;

    case "target_totalize":
      let log_analytics_data = data.map((value) => {
        return {
          [t("group")]: value.univ,
          [t("user")]: value.user,
          [t("exam_type")]: value.exam_type,
          [t("exam")]: value.exam,
          [t("quiz")]: value.quiz,
          [t("library")]: value.library,
          [t("library_type")]: value.library_type,
          [t("correct")]: value.correct,
          [t("usage_time")]: value.used_time,
        };
      });
      csv_data = {
        table_data: log_analytics_data,
      };
      break;

    case "ecg_csv":
      let ecg_data = data.map((value) => {
        return {
          ID: value.ID,
          [t("Title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("Auscultation sound type")]: value.soundtype,
          [t("Auscultation site")]: value.area || "-",
          [t("Normal abnormal")]: t(value.normal_abnormal),
          [t("public/private")]: t(getPublicPrivate(value.public_private)),
          [t("Update Date")]: value.updated_at,
          [t("Author")]:
            value.role == 101 && value.userInfo.role != 101
              ? t("admin")
              : value.user,
        };
      });
      csv_data = {
        table_data: ecg_data,
      };
      // return csv_data;
      break;

    case "inspection_csv":
      let inspection_data = data.map((value) => {
        return {
          ID: value.id,
          [t("title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("Auscultation sound type")]: t(
            getAusculationType(value.soundType)
          ),
          [t("Auscultation site")]: value.site || "-",
          [t("Normal abnormal")]: value.isNormal ? t("normal") : t("abnormal"),
          [t("public/private")]: t(getPublicPrivate(value.status)),
          [t("Update Date")]: value.updatedDate,
          [t("Author")]:
            value.role == 101 && value.userInfo.role != 101
              ? t("admin")
              : value.user,
        };
      });
      csv_data = {
        table_data: inspection_data,
      };
      // return csv_data;
      break;

    case "xray_csv":
      let xray_data = data.map((value) => {
        return {
          ID: value.id,
          [t("Title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("Auscultation sound type")]: t(
            getAusculationType(value.soundType)
          ),
          [t("Auscultation site")]: value.site || "-",
          [t("Normal abnormal")]: value.isNormal ? t("normal") : t("abnormal"),
          [t("public/private")]: value.isPublic ? t("public") : t("private"),
          [t("Update Date")]: value.updatedDate,
          [t("Author")]:
            value.role == 101 && value.userInfo.role != 101
              ? t("admin")
              : value.user,
        };
      });
      csv_data = {
        table_data: xray_data,
      };
      // return csv_data;
      break;

    case "ucg_csv":
      let ucg_data = data.map((value) => {
        return {
          ID: value.ID,
          [t("Title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("Auscultation sound type")]: t(
            getAusculationType(value.soundtype)
          ),
          [t("Auscultation site")]: value.site || "-",
          [t("Normal abnormal")]: t(value.normal_abnormal),
          [t("public/private")]: t(value.public_private),
          [t("Update Date")]: value.updated_at,
          [t("Author")]:
            value.role == 101 && value.userInfo.role != 101
              ? t("admin")
              : value.user,
        };
      });
      csv_data = {
        table_data: ucg_data,
      };
      // return csv_data;
      break;

    case "ausculaide_csv":
      let ausculaide_data = data.map((value) => {
        return {
          ID: value.ID,
          [t("Title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("Auscultation sound type")]: t(`${value.soundtype}`),
          [t("Auscultation site")]: value.area || "-",
          [t("Normal abnormal")]: t(value.normal_abnormal),
          [t("public/private")]: t(value.public_private),
          [t("Update Date")]: value.updated_at,
          [t("Author")]:
            value.role == 101 && value.userInfo.role != 101
              ? t("admin")
              : value.user,
        };
      });
      csv_data = {
        table_data: ausculaide_data,
      };
      // return csv_data;
      break;

    case "stetho_csv":
      let stetho_data = data.map((value) => {
        return {
          ID: value.ID,
          [t("Title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("Auscultation sound type")]: t(`${value.soundtype}`),
          [t("Auscultation site")]: value.area || "-",
          [t("Normal abnormal")]: t(value.normal_abnormal),
          [t("public/private")]: t(value.public_private),
          [t("Update Date")]: value.updated_at,
          [t("Author")]:
            value.role == 101 && value.userInfo.role != 101
              ? t("admin")
              : value.user,
        };
      });
      csv_data = {
        table_data: stetho_data,
      };
      // return csv_data;
      break;

    case "palpation_csv":
      let palpation_data = data.map((value) => {
        return {
          ID: value.ID,
          [t("Title")]:
            selectedLanguage === "en" ? value.title_en : value.title,
          [t("Auscultation sound type")]: t(
            getAusculationType(value.soundtype)
          ),
          [t("Auscultation site")]: value.area || "-",
          [t("Normal abnormal")]: t(value.normal_abnormal),
          [t("public/private")]: t(value.public_private),
          [t("Update Date")]: value.updated_at,
          [t("Author")]:
            value.role == 101 && value.userInfo.role != 101
              ? t("admin")
              : value.user,
        };
      });
      csv_data = {
        table_data: palpation_data,
      };
      // return csv_data;
      break;

    case "user_data":
      let user_data = data.map((value) => {
        return {
          ID: value.ID,
          [t("user_name")]: value.UserName,
          [t("user_id")]: value.UserID,
          [t("mail_address")]: value.MailAddress,
          [t("created_data_time")]: value.CreatedDate,
          [t("enable/disable")]:
            value.Enabled === "Enabled" ? t("enabled") : t("disabled"),
          [t("disabled_date")]: value.DisabledDate || "-",
        };
      });
      csv_data = {
        table_data: user_data,
      };
      // return csv_data;
      break;

    default:
      let data_default =
        data &&
        data.map((item) => {
          let examKey = Object.keys(item);
          let examVal = Object.values(item);
          let result = {};
          examVal.forEach((exam, key) => {
            result[examKey[key]] =
              examKey[key] === "Step_of_Exam"
                ? exam.map((quiz) => quiz.title).join(", ")
                : exam;
          });
          return result;
        });
      csv_data = {
        table_data: data_default,
      };
      // return csv_data;
      break;
  }
  context.setState({ csvData: csv_data });
  return true;
};

/**
 * get types
 * @param {*} item
 */
const typeName = (item, context) => {
  let type = 0;
  const { t } = context.props;
  const name = ["None", t("Both"), t("Exam"), t("Quizzes")];
  if (item.type_exam && item.type_quizzes) {
    type = 1;
  } else if (item.type_exam && !item.type_quizzes) {
    type = 2;
  } else {
    type = 3;
  }
  return name[type];
};

const convetSeconds = (d) => {
  return new Date(d * 1000).toISOString().substr(11, 8);
};

/**
 * get public private status
 * @param {*} item_type
 */
const getPublicPrivate = (item_type) => {
  switch (item_type) {
    case 0:
    case 1:
      return "private";
    case 2:
    case 3:
      return "public";
    default:
      return "private";
  }
};

/**
 * get ausculation type
 * @param {*} item_no
 */
const getAusculationType = (item_no) => {
  switch (item_no) {
    case 1:
      return "lung_sound";
    case 2:
      return "heart_sound";
    case 3:
      return "intestinal_sound";
    case 9:
      return "other";
    default:
      return "-";
  }
};
//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(DownloadOptions);
