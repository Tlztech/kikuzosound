import React from "react";

// components
import DeleteModal from "../DeleteModal";
import EditModal from "../StethoLibraryEdit";
import EditDeletePreviewButton from "../../../presentational/molecules/EditDeletePreviewButton";
import Image from "../../../presentational/atoms/Image";

// images
import { DragNdropIcon } from "../../../../assets";

// style
import "./style.css";

//libs
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
import { CircularProgress } from "@material-ui/core";

//i18n
import i18next from "i18next";
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class StethoSounds extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      deleteItem: null,
      editItem: null,
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
            onHideEditModal={() =>
              handleEditModalVisible(this, false, editItem)
            }
            editItem={JSON.parse(JSON.stringify(editItem))}
            updateStethoData={(data, index) =>
              this.props.updateStethoData(data, index)
            }
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
  context.props.updateStethoOrder(items, context.props.userInfo.id);
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
      <Droppable droppableId={"stetho-drag"}>
        {(provided) => (
          <tbody ref={provided.innerRef} {...provided.droppableProps}>
            {table_data.map((value, index) => {
              return (
                <Draggable
                  key={value.ID}
                  draggableId={value.ID.toString()}
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
                      <td style={{ width: "60px" }}>{value.ID}</td>
                      <td>
                        {i18next.language == "ja"
                          ? value.title
                          : value.title_en}
                      </td>
                      <td>{value.soundtype}</td>
                      <td>{value.area}</td>
                      <td>{value.normal_abnormal}</td>
                      <td>{t(`translation:${value.public_private}`)}</td>
                      <td>{value.updated_at}</td>
                      <td>
                        {value.role == 101 && userInfo.role != 101
                          ? t("admin")
                          : value.user}
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
 * get empty result
 * @param {*} context
 */
const getEmptyResult = (context) => {
  const { t } = context.props;
  return (
    <tbody>
      {context.props.data && context.props.data.isLoading ? (
        <tr className="no-data">
          <td>
            {" "}
            <CircularProgress />{" "}
          </td>
        </tr>
      ) : (
        <tr className="no-data">
          <td>{t("empty_data")}</td>
        </tr>
      )}
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
export default withTranslation("translation")(StethoSounds);
