import React from "react";
import { connect } from "react-redux";

// components
import BR from "../../atoms/Br/index";
import Div from "../../atoms/Div/index";
import Span from "../../atoms/Span/index";
import Label from "../../atoms/Label/index";
import Select from "../../../presentational/atoms/Select";

// bootstrap
import { Col, Row } from "react-bootstrap";

//redux
import { getAllUsers } from "../../../../redux/modules/actions/UserAction";
import { getExamGroup } from "../../../../redux/modules/actions/ExamGroupAction";

// style
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";

const type = [
  { id: 0, value: "Select type" },
  { id: 1, value: "Ranking" },
  { id: 2, value: "Exam" },
  { id: 3, value: "Spend Time" },
];

const ability = [
  { id: 1, value: "9-0 point" },
  { id: 2, value: "29-10 point" },
  { id: 3, value: "49-30 point" },
  { id: 4, value: "69-50 point" },
  { id: 5, value: "79-70 point" },
  { id: 6, value: "89-80 point" },
  { id: 7, value: "100-90 point" },
];

const scope = [
  { id: 1, value: "Contents" },
  { id: 2, value: "Score of Exam" },
  { id: 3, value: "Time of Studying" },
  { id: 4, value: "Rate of correct answers of exam" },
  { id: 5, value: "Rate of correct answers of Quizzes" },
];

let exam_groups = [];
let selectedExam = "";
let defaultExamGroup = "";
let users = [];

//===================================================
// Component
//===================================================
class LogAnalyticsDropDown extends React.Component {
  async componentDidMount() {
    await this.props.getAllUsers(this.props.userToken);
    await this.props.getExamGroup(this.props.userToken);
  }

  UNSAFE_componentWillReceiveProps(nextProps) {
    //for user
    if (!nextProps.users.isLoading && nextProps.users.usersList) {
      users = nextProps.users.usersList.map((user) => ({
        id: user.id,
        value: user.user,
      }));
    }
    //for exam group
    if (!nextProps.examGroup.isLoading && nextProps.examGroup.examGroupList) {
      exam_groups = [
        ...nextProps.examGroup.examGroupList.map((exam_group) => ({
          id: exam_group.id,
          value: exam_group.name,
        })),
      ];
    }
    defaultExamGroup = nextProps.userExamGroup;
  }

  render() {
    const {
      onChangeExamGroup,
      onChangeType,
      isScopeDropdownVisible,
      onChangeScope,
      onChangeUser,
      selectedUser,
      onChangeAbility,
      t,
    } = this.props;

    return (
      <Div className="molecules-logAnalyticsDropdown-Wrapper">
        <Div className="grid-box molecules-exam-dropdown">
          <Label>{t("exam_group")}</Label>
          <Select
            value={selectedExam || defaultExamGroup}
            items={exam_groups}
            onChange={(event) => handleChangeExamGroupEvent(event, this)}
            className="dropdown-box"
          />
        </Div>
        <Div>
          <Div className="grid-box molecules-type-dropdown">
            <Label>{t("ability")}</Label>
            <Select
              items={ability}
              onChange={(event) => onChangeAbility(event)}
              className="dropdown-box"
            />
          </Div>
          <Div className="grid-box molecules-type-dropdown">
            <Label>{t("type")}</Label>
            <Select
              items={scope}
              onChange={(event) => onChangeScope(event)}
              className="dropdown-box"
            />
          </Div>
        </Div>
        <Div className="grid-box molecules-user-dropdown">
          <Label className="labelStyle">{t("user")}</Label>
          <Select
            items={users}
            onChange={(event) => onChangeUser(event)}
            value={selectedUser}
            className="dropdown-box"
          />
        </Div>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

const handleChangeExamGroupEvent = (event, context) => {
  selectedExam = event.target.value;
  return context.props.onChangeExamGroup(event);
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    users: state.userManagement,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    examGroup: state.examGroup,
    userExamGroup:
      state.auth.userInfo && state.auth.userInfo.user.university_id,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, { getAllUsers, getExamGroup })(
  withTranslation("translation")(LogAnalyticsDropDown)
);
