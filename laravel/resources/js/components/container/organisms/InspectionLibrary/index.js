import React from "react";

// libs
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
import CircularProgress from "@material-ui/core/CircularProgress";

// components
import EditModal from "../InspectionLibraryEdit";
import DeleteModal from "../DeleteModal";
import EditDeletePreviewButton from "../../../presentational/molecules/EditDeletePreviewButton";

// images
import { DragNdropIcon } from "../../../../assets";
import Image from "../../../presentational/atoms/Image";

// style
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class InspectionLIbrary extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      deleteItem: "",
      editItem: {},
      isEditModalVisible: false,
      isDeleteModalVisible: false,
    };
  }

  render() {
    const { isEditModalVisible, isDeleteModalVisible, editItem } = this.state;
    const { data } = this.props;
    const table_data = data && data.table_data ? data.table_data : [];
    return (
      <>
        {table_data.length != 0 && !data.isLoading
          ? getTableData(this, table_data)
          : getEmptyResult(this)}
        {isEditModalVisible && (
        <EditModal
          isVisible={isEditModalVisible}
          onHideEditModal={(test) =>
            handleEditModalVisible(this, false, editItem)
          }
          editItem={editItem}
        />
        )}
        <DeleteModal
          isVisible={isDeleteModalVisible}
          onHideDeleteModal={() => handleDeleteModalVisible(this, false)}
          onDeletePressed={() => handleDeleteItem(this, this.state.deleteItem)}
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
  context.props.updateInspectionOrder(items, context.props.userInfo.id);
};

/**
 * Return table row data
 * @param {*} context
 * @param {*} table_data
 */
const getTableData = (context, table_data) => {
  const { userInfo, t } = context.props;
  return (
    <DragDropContext
      onDragEnd={(result) => onDragEnd(result, table_data, context)}
    >
      <Droppable droppableId={"inspection-drag"}>
        {(provided) => (
          <tbody ref={provided.innerRef} {...provided.droppableProps}>
            {table_data.map((value, index) => {
              return (
                <Draggable
                  key={value.id}
                  draggableId={value.id.toString()}
                  index={index}
                  isDragDisabled={false}
                >
                  {(provided) => (
                    <tr
                      {...provided.draggableProps}
                      ref={provided.innerRef}
                      key={value.id}
                    >
                      <td
                        {...provided.dragHandleProps}
                        style={{ width: "30px" }}
                        className="text-center"
                      >
                        <Image mode="drag" url={DragNdropIcon} />
                      </td>
                      <td style={{ width: "60px" }}>{value.id}</td>
                      <td>
                        {i18next.language == "ja"
                          ? value.title
                          : value.title_en}
                      </td>
                      <td>
                        {t(`translation:${getItemType(value.soundType)}`)}
                      </td>
                      <td>{value.site || "-"}</td>
                      <td>
                        {value.isNormal
                          ? t("translation:normal")
                          : t("translation:abnormal")}
                      </td>
                      <td>{t(`translation:${getStatus(value.status)}`)}</td>
                      <td>{value.updatedDate}</td>
                      <td>
                        {value.role == 101 && userInfo.role != 101
                          ? t("admin")
                          : value.user || "-"}
                      </td>
                      <td colSpan="2">
                        {value.user_id == userInfo.id ? (
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
 * get supervision status
 * @param {*} item_type
 */
const getStatus = (item_type) => {
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
const getItemType = (item_no) => {
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

/**
 * Display Loading or empty list message
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
  context.setState({ isEditModalVisible: isVisible, editItem: value });
};

/**
 * show/hide delete modal
 * @param {*} context
 * @param {*} isVisible
 * @param {*} index
 */
const handleDeleteModalVisible = (context, isVisible, index) => {
  context.setState({ isDeleteModalVisible: isVisible, deleteItem: index });
};

/**
 * send delete item props
 * @param {*} context
 */
const handleDeleteItem = (context, index) => {
  context.setState({ isDeleteModalVisible: false });
  context.props.deleteItem(index);
};

/**
 * open preview modal
 * @param {*} context
 * @param {*} value
 */
const handlePreviewModalVisible = (context, value) => {
  context.props.setPreviewModalVisible(value);
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
export default withTranslation("translation")(InspectionLIbrary);
