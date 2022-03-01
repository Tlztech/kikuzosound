import React from "react";

// libs
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
import { CircularProgress } from "@material-ui/core";

// components
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import EditModal from "../ExamManageEdit";
import DeleteModal from "../DeleteModal";
import EditDeletePreviewButton from "../../../presentational/molecules/EditDeletePreviewButton";

//images
import { DragNdropIcon } from "../../../../assets";
import Image from "../../../presentational/atoms/Image";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class ExamTable extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      deleteItem: "",
      examEditItem: [],
      row_data: {},
      dragging: {},
      dragRow: false,
      deletedItem: {},
      isEditModalVisible: false,
      isDeleteModalVisible: false,
    };
  }

  render() {
    const { data } = this.props;
    const table_data = data && data.table_data ? data.table_data : [];
    const {
      isEditModalVisible,
      isDeleteModalVisible,
      examEditItem,
    } = this.state;
    return (
      <>
        {table_data.length != 0 && !this.props.data.isLoading
          ? getTableData(this, table_data)
          : getEmptyResult(this)}
        {isEditModalVisible && (
          <EditModal
            isVisible={isEditModalVisible}
            onHideEditModal={() =>
              handleEditModalVisible(this, false, examEditItem)
            }
            examEditItem={examEditItem}
            editExamData={(updatedExam, updatedQuiz) =>
              this.props.updateExamData(updatedExam, updatedQuiz)
            }
          />
        )}
        <DeleteModal
          isVisible={isDeleteModalVisible}
          onHideDeleteModal={() => handleDeleteModalVisible(this, false)}
          onDeletePressed={() =>
            handleDeleteItem(
              this,
              this.state.deleteItem,
              this.state.deleteItemIndex
            )
          }
        />
      </>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * Get sorted list after drag ends
 * @param {*} result
 * @param {*} table_data
 * @param {*} context
 */
const onDragEnd = (result, table_data, context) => {
  const { destination, source } = result;
  if (!destination) {
    return;
  }
  if (
    destination.droppableId === source.droppableId &&
    destination.index === source.index
  ) {
    return;
  }
  const items = Array.from(table_data);
  const [reorderedItem] = items.splice(source.index, 1);
  items.splice(destination.index, 0, reorderedItem);
  context.props.updateOrder(items, context.props.userInfo.id);
};

/**
 * show table data
 * @param {*} context
 * @param {*} table_data
 */
const getTableData = (context, table_data) => {
  const { userInfo, t } = context.props;
  return (
    <DragDropContext onDragEnd={(e) => onDragEnd(e, table_data, context)}>
      <Droppable droppableId="exam-drag">
        {(provided) => (
          <tbody {...provided.droppableProps} ref={provided.innerRef}>
            {table_data.map((value, index) => {
              const caseSteps = value.Step_of_Exam;
              return (
                <Draggable
                  key={index}
                  draggableId={value.quizPackId && value.quizPackId.toString()}
                  index={index}
                  isDragDisabled={false}
                >
                  {(provided) => (
                    <tr
                      {...provided.draggableProps}
                      ref={provided.innerRef}
                      key={index}
                    >
                      <td
                        {...provided.dragHandleProps}
                        style={{ width: "30px" }}
                        className="text-center"
                      >
                        <Image mode="drag" url={DragNdropIcon} />
                      </td>
                      <td style={{ width: "60px" }}>
                        {value.examId}
                      </td>
                      <td>
                        {value.exams && value.exams.length != 0
                          ? i18next.language === "ja"
                            ? value.exams[0].name_jp
                            : value.exams[0].name
                          : i18next.language == "ja"
                          ? value.title_jp
                          : value.title_en}
                      </td>
                      <td>
                        {caseSteps.map((item, index) => {
                          return (
                            <P mode="table" key={index}>
                              {i18next.language === "ja"
                                ? item.title
                                : item.title_en}
                            </P>
                          );
                        })}
                      </td>
                      <td>{value.Created}</td>
                      <td>{value.Updated}</td>
                      <td>{typeName(value, context)}</td>
                      <td>{value.exam_release ? t("public") : t("private")}</td>
                      <td>
                        <Button
                          className="analytics-button"
                          mode="active"
                          onClick={() =>
                            context.props.history.push({
                              pathname: "/analytics",
                              state: {
                                value:
                                  value.examId,
                                  created: new Date(value.Created),
                              },
                            })
                          }
                        >
                          {t("analytics")}
                        </Button>
                      </td>
                      <td>
                        {!value.author
                          ? "-"
                          : value.author.role == userInfo.role ||
                            userInfo.role == 101
                          ? value.author.name
                          : t("admin")}
                      </td>
                      <td colSpan={2}>
                        {!value.author ||
                        (value.author && value.author.role == userInfo.role) ||
                        userInfo.role == 101 ? (
                          value.user_id == userInfo.id ? (
                            <EditDeletePreviewButton
                              onEditClicked={() =>
                                handleEditModalVisible(context, true, value)
                              }
                              onDeleteClicked={() =>
                                handleDeleteModalVisible(
                                  context,
                                  true,
                                  value,
                                  index
                                )
                              }
                              onPreviewClicked={() =>
                                handlePreviewModalVisible(context, value)
                              }
                            />
                          ) : (
                            <EditDeletePreviewButton
                              onPreviewClicked={() =>
                                handlePreviewModalVisible(context, value)
                              }
                              disableEditDelete={true}
                            />
                          )
                        ) : (
                          ""
                        )}
                      </td>
                    </tr>
                  )}
                </Draggable>
              );
            })}
            {provided.placeholder}
          </tbody>
        )}
      </Droppable>
    </DragDropContext>
  );
};

/**
 * show empty table
 * @param {*} context
 */
const getEmptyResult = (context) => {
  const { t } = context.props;
  return (
    <tbody>
      <tr className="no-data">
        <td>
          {context.props.data && context.props.data.isLoading ? (
            <CircularProgress />
          ) : (
            t("empty_data")
          )}
        </td>
      </tr>
    </tbody>
  );
};

/**
 * show/hide edit modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleEditModalVisible = (context, isVisible, value) => {
  context.setState({ isEditModalVisible: isVisible, examEditItem: value });
};

/**
 * show/hide delete modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleDeleteModalVisible = (context, isVisible, item, index) => {
  context.setState({
    isDeleteModalVisible: isVisible,
    deleteItem: item,
    deleteItemIndex: index,
  });
};

/**
 * show/hide preview modal
 * @param {*} context
 * @param {*} isVisible
 */
const handlePreviewModalVisible = (context, value) => {
  console.log("vlaw",value)
  context.props.setQuizPreviewModalVisible(value.quizPackId);
};

/**
 * delete item
 * @param {*} context
 */
const handleDeleteItem = (context, item, index) => {
  context.setState({ isDeleteModalVisible: false });
  context.props.deleteItem(item, index);
  context.setState({ deletedItem: { item: item, index: index } });
};

/**
 * get types
 * @param {*} item
 */
const typeName = (item, context) => {
  const { t } = context.props;
  let type = 0;
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

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(ExamTable);
