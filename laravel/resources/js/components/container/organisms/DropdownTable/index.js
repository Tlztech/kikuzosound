import React from "react";
import { connect } from "react-redux";

// bootstrap
import { Row, Col } from "react-bootstrap";

// components
import Div from "../../../presentational/atoms/Div";
import BR from "../../../presentational/atoms/Br";
import Label from "../../../presentational/atoms/Label";
import Select from "../../../presentational/atoms/Select";
import ExamTable from "../../../presentational/molecules/ExamTable/index";
import Table from "../../../presentational/atoms/Table";

//redux
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";

// style
import "./style.css";

let filtered_data = [];
let exam_group_filtered_data = [];
let dropdown_fields = ["exams", "users"];
let select_all_option = { id: 0, value: "all" };
//===================================================
// Component
//===================================================
class DropdownTable extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      showData: this.props.data,
      initialShowData: this.props.data,
      exam_groups: [],
      exams: [],
      users: [],
      user: "0",
      exam: "0",
      exam_group: "0",
    };
  }

  async componentDidMount() {
    await this.props.getExamGroup(this.props.userToken);
  }

  UNSAFE_componentWillReceiveProps(nextProps) {
    let all_exam_ids;

    const { users, exams } = this.state;
    let exam_groups = null;
    //only when no dropdown data is present
    //filling dropdown data

    if (users == "" && exams == "" && nextProps.data.table_data.length != 0) {
      exam_group_filtered_data = nextProps.data.table_data;
      dropdown_fields.forEach((item) => {
        let property_name = item.substring(0, item.length - 1);
        this.setState(
          {
            [item]: [select_all_option].concat(
              nextProps.data.table_data
                .filter(
                  (data, index, self) =>
                    self.findIndex(
                      (t) => t[property_name] === data[property_name]
                    ) === index
                )
                .map((data) => {
                  let data_id =
                    item == "exams" ? data.exam_data.id : data.user_data.id;
                  return {
                    id: data_id,
                    value: data[property_name],
                  };
                })
                .filter((data) => data.value != "")
            ),
          },
          () => {
            all_exam_ids = this.state.exams.map((exam) => exam.id);
          }
        );
      });

      //for exam group
      if (!nextProps.examGroup.isLoading) {
        exam_groups = [
          ...nextProps.examGroup.examGroupList.map((exam_group) => ({
            id: exam_group.id,
            value: exam_group.name,
          })),
        ];
        this.setState(
          {
            exam_groups: [{ id: 0, value: "all" }].concat(exam_groups),
          },

          //if clicked from exam page
          () => {
            if (this.props.exam_id != "") {
              this.setState(
                {
                  exam: all_exam_ids.includes(this.props.exam_id[0])
                    ? this.props.exam_id
                    : "no_data",
                  exam_group: this.props.currentUniversity.user.university_id,
                },
                () => {
                  if (this.state.exam == "no_data") {
                    concatNoDataExam(this);
                  }
                  setExamFromProp(this);
                }
              );
            }
          }
        );
      }
    }
  }

  componentDidUpdate(prevProps) {
    const { searchText } = this.props;
    if (searchText !== undefined && prevProps.searchText !== searchText) {
      handleSearchList(this, searchText);
    }
  }

  render() {
    const { exam_groups, exams, users, user, exam, exam_group } = this.state;
    const { t } = this.props;
    return (
      <Div className="organisms-DropdownTable-wrapper">
        <Row className="fixed-dropdown-field">
          <Col>
            <Label>{t("exam_group")}</Label>
            <Select
              value={exam_group}
              dropdownType="analytics"
              items={exam_groups}
              onChange={(event) => onChange(this, event, "exam_group")}
              className="dropdown-box"
            />
          </Col>

          <Col>
            <Label>{t("exam")}</Label>
            <Select
              value={exam}
              dropdownType="analytics"
              items={exams}
              onChange={(event) => onChange(this, event, "exam")}
              className="dropdown-box"
            />
          </Col>

          <Col>
            <Label>{t("user")}</Label>
            <Select
              value={user}
              dropdownType="analytics"
              items={users}
              onChange={(event) => onChange(this, event, "user")}
              className="dropdown-box"
            />
          </Col>
        </Row>
        <BR />
        <Table size="lg">
          <thead>
            <tr>
              {this.props.table_header.map((header, index) => {
                return <th key={index}>{t(header)}</th>;
              })}
            </tr>
          </thead>
          <ExamTable
            header={this.props.table_header}
            data={this.state.showData}
          />
        </Table>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 * search items from list
 * @param {*} context
 * @param {*} searchText
 */
const handleSearchList = (context, searchText) => {
  const { initialShowData } = context.state;
  if (searchText) {
    const table_data = initialShowData.table_data.filter(
      (item) =>
        item.id.toString().includes(searchText) ||
        item.exam_group.toLowerCase().includes(searchText) ||
        item.exam.toLowerCase().includes(searchText) ||
        item.user.toLowerCase().includes(searchText) ||
        item.miss_ok.toLowerCase().includes(searchText)
    );
    context.props.onUpdatedData(table_data);
    context.setState({ showData: { table_data: table_data } });
  } else {
    context.props.onUpdatedData(initialShowData.table_data);
    context.setState({ showData: { table_data: initialShowData.table_data } });
  }
};

/**
 * when getting specific exam from prop
 * @param {} context
 */
const setExamFromProp = (context) => {
  filterData(context, "setFromExamPage");
};

/**
 * concat no data to exam dropdown
 * @param {} context
 */
const concatNoDataExam = (context) => {
  context.setState({
    exams: context.state.exams.concat({ id: "no_data", value: "not_found" }),
  });
};

/**
 * filter dropdown data
 * @param {*} context
 * @param {*} event
 */
const filterData = (context, type) => {
  const { exam_group, user, exam } = context.state;
  let initial_data = context.props.data.table_data;
  switch (type) {
    case "exam_group":
      filtered_data = initial_data.filter(
        (data) =>
          (data.exam_data && data.exam_data.university_id == exam_group) ||
          exam_group == "0"
      );
      exam_group_filtered_data = filtered_data;
      setFilteredDropdowns(context);
      context.setState({
        user: "0",
        exam: "0",
      });
      break;
    case "user":
    case "exam":
      filtered_data = exam_group_filtered_data.filter((item) => {
        return (
          (item.exam_data.id == exam || exam == "0") &&
          (item.user_data.id == user || user == "0")
        );
      });
      break;

    case "setFromExamPage":
      filtered_data = initial_data.filter((item) => {
        return (
          (item.exam_data.id == exam || exam == "0") &&
          (item.university_id == exam_group || exam == "0")
        );
      });

      break;

    default:
      return initial_data;
  }
  context.setState(
    {
      showData: {
        table_data: filtered_data,
      },
      initialShowData: JSON.parse(
        JSON.stringify({
          table_data: filtered_data,
        })
      ),
    },
    () => {
      handleSearchList(context, context.props.searchText);
      context.props.onUpdatedData(filtered_data); //to update data for download csv or excel
      // context.props.updateChart(filtered_data);
    }
  );
};
//===================================================
// Actions
//===================================================
/**
 * on change dropdown
 * @param {} context
 * @param {} event
 * @param {} type
 */
const onChange = async (context, event, type) => {
  await context.setState({ [type]: event.target.value });
  filterData(context, type);
};

/**
 * on change exam_group dropdown
 * @param {} context
 */
const setFilteredDropdowns = (context) => {
  //users
  let filtered_users = [select_all_option].concat(
    filtered_data
      .map((data) => {
        return {
          id: data.user_data.id,
          value: data.user,
        };
      })
      .filter(
        (data, index, self) => self.findIndex((t) => t.id === data.id) === index
      )
      .filter((data) => data.value != "")
  );

  //exams
  let filtered_exams = [select_all_option].concat(
    filtered_data
      .map((data) => {
        return {
          id: data.exam_data.id,
          value: data.exam,
        };
      })
      .filter(
        (data, index, self) => self.findIndex((t) => t.id === data.id) === index
      )
      .filter((data) => data.value != "")
  );
  context.setState({
    exams: filtered_exams,
    users: filtered_users,
  });
};

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    currentUniversity: state.auth.userInfo,
    examGroup: state.examGroup,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, { getExamGroup })(DropdownTable);
