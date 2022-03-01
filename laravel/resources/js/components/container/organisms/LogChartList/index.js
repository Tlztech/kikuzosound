import React from "react";
import { connect } from "react-redux";

// bootstrap
import { Row, Col } from "react-bootstrap";

//Components
import Div from "../../../presentational/atoms/Div";
import PieChart from "../../../presentational/molecules/PieChart";
import ExamRankingList from "../../../presentational/molecules/ExamRankingList";
import SpendTimeRankingList from "../../../presentational/molecules/SpendTimeRanking";
import RankingList from "../../../presentational/molecules/RankingList";
import LogAnalyticsDropDown from "../../../presentational/molecules/LogAnalyticsDropDown";

//redux
import { getLogAnalytics } from "../../../../redux/modules/actions/LogAnalyticsAction";
import { getAllUsersByExamGroup } from "../../../../redux/modules/actions/UserAction";

// Style
import "./style.css";

//===================================================
// Component
//===================================================

let user_token,
  ranking_scope_param = 1,
  exam_group_param = null,
  abilty = 1,
  user_param = null,
  fixedBackgroundColor = [
    "#037bfc",
    "#fc1c03",
    "#fca903",
    "#2e9c16",
    "#de6926",
    "#26dec9",
    "#5e8bad",
  ],
  remaining_color_length;

class LogChartList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      title: "Ranking",
      isScopeDropdownVisible: false,
      selectedUser: "",

      ranking_data_set: {
        //exam title
        labels: [],
        rank_scope: "",
        datasets: [
          {
            data: [], //exam point
            backgroundColor: fixedBackgroundColor,
            list_data: [],
          },
        ],
      },
      pie_data: {},
    };
  }

  async componentDidMount() {
    const { userToken } = this.props;
    user_token = userToken;
    onDefaultLoad(this);
  }

  render() {
    const {
      title,
      isScopeDropdownVisible,
      ranking_data_set,
      pie_data,
      selectedUser,
    } = this.state;

    const get_type_list = () => {
      switch (title) {
        case "Use ratio":
          return <ExamRankingList />;

        case "User Ratio":
          return <SpendTimeRankingList />;

        case "Ranking":
          return <RankingList data={ranking_data_set} />;
      }
    };

    return (
      <Div className="organisms-log-chart-wrapper">
        <LogAnalyticsDropDown
          onChangeExamGroup={(e) => onChangeExamGroup(this, e)}
          onChangeAbility={(e) => onChangeAbility(this, e)}
          onChangeUser={(e) => onChangeUser(this, e)}
          selectedUser={selectedUser}
          onChangeScope={(e) => onChangeScope(this, e)}
          isScopeDropdownVisible={isScopeDropdownVisible}
        />
        <Row className="chart-style">
          <Col md={7} className="col-md-7">
            <PieChart title={title} data={pie_data} />
          </Col>
          <Col md={5} className="organisms-rank-list mt-3">
            <Div className="list-wrapper">{get_type_list()}</Div>
          </Col>
        </Row>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 *
 * @param {*} c
 * @param {event} e
 */
const onChangeExamGroup = async (c, e) => {
  exam_group_param = e.target.value;

  await c.props.getAllUsersByExamGroup(user_token, exam_group_param);
  user_param = c.props.defaultUser;

  if (user_param !== null) {
    await c.props.getLogAnalytics(
      user_param,
      exam_group_param,
      ranking_scope_param,
      abilty,
      user_token
    );
    handleOnChange(c, e);
  } else {
    let ranking_data_set = { ...c.state.ranking_data_set };
    ranking_data_set.datasets[0].list_data = ranking_data_set.datasets[0].data = ranking_data_set.labels = [];
    c.setState({ ranking_data_set, pie_data: ranking_data_set });
  }
};

const onDefaultLoad = async (context) => {
  await context.props.getAllUsersByExamGroup(
    user_token,
    context.props.userExamGroup
  );
  if (context.props.userExamGroup && context.props.defaultUser) {
    user_param = context.props.defaultUser;
    exam_group_param = context.props.userExamGroup;
    ranking_scope_param = "1";
    await context.props.getLogAnalytics(
      user_param,
      exam_group_param,
      1,
      abilty,
      user_token
    );

    handleOnChange(context);
  }
};

/**
 *
 * @param {*} c
 * @param {event} e
 *
 */
const onChangeUser = async (c, e) => {
  user_param = e.target.value;

  await c.props.getLogAnalytics(
    user_param,
    exam_group_param,
    ranking_scope_param,
    abilty,
    user_token
  );

  handleOnChange(c, e);
  c.setState({ selectedUser: user_param });
};

/**
 * on changing dropdown value in scope drowdon
 * @param {*} c
 * @param {event} e
 *
 */
const onChangeScope = async (c, e) => {
  e.persist();

  ranking_scope_param = e.target.value;
  //call api
  await c.props.getLogAnalytics(
    user_param,
    exam_group_param,
    ranking_scope_param,
    abilty,
    user_token
  );
  handleOnChange(c, e);
};

const handleOnChange = async (c, e) => {
  let ranking_data_set = { ...c.state.ranking_data_set };
  ranking_data_set.rank_scope = ranking_scope_param;
  if (!c.props.logAnalytics.logAnalyticList) return;

  //conditional for scopes
  if (ranking_scope_param == 1 || ranking_scope_param == 2) {
    ranking_data_set.datasets[0].data = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.is_correct;
      }
    );
    ranking_data_set.labels = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.exam.name;
      }
    );

    ranking_data_set.datasets[0].list_data = c.props.logAnalytics.logAnalyticList.map(
      (item) => `${item.exam.name}:${item.is_correct}`
    );
  }

  if (ranking_scope_param == 3) {
    ranking_data_set.datasets[0].data = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.used_time;
      }
    );

    ranking_data_set.labels = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.exam_title;
      }
    );

    ranking_data_set.datasets[0].list_data = c.props.logAnalytics.logAnalyticList.map(
      (item) => `${item.exam_title}:${item.used_time}`
    );
  }

  if (ranking_scope_param == 4) {
    //
    ranking_data_set.datasets[0].data = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.exam_percentage.slice(0, -1);
      }
    );

    ranking_data_set.labels = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.exam_label;
      }
    );

    ranking_data_set.datasets[0].list_data = c.props.logAnalytics.logAnalyticList.map(
      (item) => `${item.exam_label}:${item.exam_percentage}`
    );
  }

  if (ranking_scope_param == 5) {
    //
    ranking_data_set.datasets[0].data = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.quiz_percentage.slice(0, -1);
      }
    );

    ranking_data_set.labels = c.props.logAnalytics.logAnalyticList.map(
      (item) => {
        return item.quiz_title;
      }
    );

    ranking_data_set.datasets[0].list_data = c.props.logAnalytics.logAnalyticList.map(
      (item) => `${item.quiz_title}:${item.quiz_percentage}`
    );
  }

  c.setState({ ranking_data_set, pie_data: ranking_data_set });

  getRandomColor(c, ranking_data_set);
};

/**
 * get Random color
 * @param {*} c
 *
 */
const getRandomColor = (c, ranking_data_set) => {
  remaining_color_length = c.state.ranking_data_set.datasets[0].data.length - 7;
  let colors = [];

  for (let i = 0; i < remaining_color_length; i++) {
    let letters = "0123456789ABCDEF";
    let color = "#";
    for (let j = 0; j < 6; j++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    colors.push(color);
  }

  ranking_data_set.datasets[0].backgroundColor = [
    ...fixedBackgroundColor,
    ...colors,
  ];
  c.setState({ ranking_data_set });
};

/**
 * on change ability
 * @param {*} c
 * @param {event} e
 *
 */
const onChangeAbility = async (c, e) => {
  abilty = e.target.value;
  await c.props.getLogAnalytics(
    user_param,
    exam_group_param,
    ranking_scope_param,
    abilty,
    user_token
  );

  handleOnChange(c, e);
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    logAnalytics: state.logAnalytics,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userExamGroup:
      state.auth.userInfo && state.auth.userInfo.user.university_id,
    defaultUser:
      state.userManagement.isLoading === false &&
      state.userManagement.usersList &&
      state.userManagement.usersList.length !== 0
        ? state.userManagement.usersList[0].id
        : null,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {
  getLogAnalytics,
  getAllUsersByExamGroup,
})(LogChartList);
