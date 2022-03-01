import React from "react";

// libs
import { Modal } from "react-bootstrap";

// Components
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";
import Div from "../../../presentational/atoms/Div";
import PopularQuizRankingTable from "../../../presentational/molecules/PopularQuizRankingTable";
import PopularLibraryRankTable from "../../../presentational/molecules/PopularLibraryRankTable";
import ExamResultUserRankTable from "../../../presentational/molecules/ExamResultUserRankTable";
import ExamResultRankCorrectRateTable from "../../../presentational/molecules/ExamResultRankCorrectRateTable";
import QuizResultTable from "../../../presentational/molecules/QuizResultTable";
import LearningTimeRankingTable from "../../../presentational/molecules/LearningTimeRankingTable";
import TitleWithIcon from "../../../presentational/molecules/TitleWithIcon";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class RankingModal extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isDownloadingCsv: {
        popular_quiz_rank: false,
        popular_library_rank: false,
        exam_result_user_rank: false,
        exam_correct_rate_rank: false,
        quiz_result: false,
        learning_time: false,
      },
    };
  }

  componentDidMount() {
    handleFetchRankingModalData(this);
  }

  render() {
    const { isDownloadingCsv } = this.state;
    const { isVisible, onHideRankingModal, rankingList, t } = this.props;
    const rankingTitles = (rankingList && Object.keys(rankingList)) || [];
    return (
      <Modal
        className="organism-rankingModal-wrapper"
        show={isVisible}
        onHide={() => onHideRankingModal()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("ranking")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHideRankingModal()}
          />
        </Modal.Header>
        {
          <Modal.Body>
            {!rankingList ? (
              <Div className="loading-indicator">Loading...</Div>
            ) : (
              <>
                {rankingTitles.includes("popular_quiz_rank") && (
                  <>
                    <TitleWithIcon
                      label={t("popular_quiz_ranking")}
                      csvData={{
                        isLoading: false,
                        table_data: rankingList["popular_quiz_rank"],
                      }}
                      csvMode="popularQuizRank"
                      handleFetchCsvData={() =>
                        setDownloadCsv(this, "popular_quiz_rank", true)
                      }
                      isDownloading={isDownloadingCsv["popular_quiz_rank"]}
                      setDownloadCsv={(isDownload) =>
                        setDownloadCsv(this, "popular_quiz_rank", isDownload)
                      }
                    />
                    <PopularQuizRankingTable
                      tableData={rankingList["popular_quiz_rank"]}
                    />
                  </>
                )}
                {rankingTitles.includes("popular_library_rank") && (
                  <>
                    <TitleWithIcon
                      label={t("popular_library_ranking")}
                      csvData={{
                        isLoading: false,
                        table_data: rankingList["popular_library_rank"],
                      }}
                      csvMode="popular_library_rank"
                      handleFetchCsvData={() =>
                        setDownloadCsv(this, "popular_library_rank", true)
                      }
                      isDownloading={isDownloadingCsv["popular_library_rank"]}
                      setDownloadCsv={(isDownload) =>
                        setDownloadCsv(this, "popular_library_rank", isDownload)
                      }
                    />
                    <PopularLibraryRankTable
                      tableData={rankingList["popular_library_rank"]}
                    />
                  </>
                )}
                {rankingTitles.includes("exam_result_user_rank") && (
                  <>
                    <TitleWithIcon
                      label={t("exam_result_user_ranking")}
                      csvData={{
                        isLoading: false,
                        table_data: rankingList["exam_result_user_rank"],
                      }}
                      csvMode="exam_result_user_rank"
                      handleFetchCsvData={() =>
                        setDownloadCsv(this, "exam_result_user_rank", true)
                      }
                      isDownloading={isDownloadingCsv["exam_result_user_rank"]}
                      setDownloadCsv={(isDownload) =>
                        setDownloadCsv(
                          this,
                          "exam_result_user_rank",
                          isDownload
                        )
                      }
                    />
                    <ExamResultUserRankTable
                      tableData={rankingList["exam_result_user_rank"]}
                    />
                  </>
                )}
                {rankingTitles.includes("exam_correct_rate_rank") && (
                  <>
                    <TitleWithIcon
                      label={t("exam_result_ranking_correct_rate")}
                      csvData={{
                        isLoading: false,
                        table_data: rankingList["exam_correct_rate_rank"],
                      }}
                      csvMode="examResultCorrectRate"
                      handleFetchCsvData={() =>
                        setDownloadCsv(this, "exam_correct_rate_rank", true)
                      }
                      isDownloading={isDownloadingCsv["exam_correct_rate_rank"]}
                      setDownloadCsv={(isDownload) =>
                        setDownloadCsv(
                          this,
                          "exam_correct_rate_rank",
                          isDownload
                        )
                      }
                    />
                    <ExamResultRankCorrectRateTable
                      tableData={rankingList["exam_correct_rate_rank"]}
                    />
                  </>
                )}
                {rankingTitles.includes("quiz_result") && (
                  <>
                    <TitleWithIcon
                      label={t("quiz_result")}
                      csvData={{
                        isLoading: false,
                        table_data: rankingList["quiz_result"],
                      }}
                      csvMode="quizResultRanking"
                      handleFetchCsvData={() =>
                        setDownloadCsv(this, "quiz_result", true)
                      }
                      isDownloading={isDownloadingCsv["quiz_result"]}
                      setDownloadCsv={(isDownload) =>
                        setDownloadCsv(this, "quiz_result", isDownload)
                      }
                    />
                    <QuizResultTable tableData={rankingList["quiz_result"]} />
                  </>
                )}
                {rankingTitles.includes("learning_time") && (
                  <>
                    <TitleWithIcon
                      label={t("learning_time_ranking")}
                      csvData={{
                        isLoading: false,
                        table_data: rankingList["learning_time"],
                      }}
                      csvMode="learningTimeRanking"
                      handleFetchCsvData={() =>
                        setDownloadCsv(this, "learning_time", true)
                      }
                      isDownloading={isDownloadingCsv["learning_time"]}
                      setDownloadCsv={(isDownload) =>
                        setDownloadCsv(this, "learning_time", isDownload)
                      }
                    />
                    <LearningTimeRankingTable
                      tableData={rankingList["learning_time"]}
                    />
                  </>
                )}
              </>
            )}
          </Modal.Body>
        }
      </Modal>
    );
  }
}

//===================================================
// functions
//===================================================
/**
 * fetch ranking data
 * @param {*} context
 */
const handleFetchRankingModalData = (context) => {
  context.props.handleGetRankingData();
};

/**
 * set download csv false
 * @param {*} context
 * @param {*} isDownload
 */
const setDownloadCsv = (context, rankingType, isDownload) => {
  context.setState((prevState) => ({
    isDownloadingCsv: {
      ...prevState.isDownloadingCsv,
      [rankingType]: isDownload,
    },
  }));
};

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default withTranslation("translation")(RankingModal);
