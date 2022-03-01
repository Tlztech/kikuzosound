import React from "react";

// Components
import Div from "../../atoms/Div";
import Table from "../../atoms/Table";
import Label from "../../atoms/Label";
import Span from "../../atoms/Span";

// Styles
import "./style.css";

//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

let list_data = [];
const noResult = "No Results Found";
let isNotEmpty = false;
class RankingList extends React.Component {
  UNSAFE_componentWillReceiveProps(nextProps) {
    list_data = nextProps.data.datasets[0].list_data;
    list_data = list_data.map((list, index) => {
      return {
        id: index,
        exam_name: list.split(":")[0],
        points: list.split(":")[1],
      };
    });
    switch (nextProps.data.rank_scope) {
      case "1":
        this.setState({
          title: "Contents",
        });
        break;

      case "2":
        this.setState({
          title: "Score of Exam",
        });
        break;

      case "3":
        this.setState({
          title: "Time of Studying",
        });

        break;

      case "4":
        this.setState({
          title: "Rate of correct answers of exam",
        });

        break;

      case "5":
        this.setState({
          title: "Rate of correct answers of Quizzes",
        });

        break;

      default:
        return "";
    }
    isNotEmpty = !!list_data.length;

    this.setState({ table_data: list_data });
  }

  constructor(props) {
    super(props);
    this.state = {
      table_data: [],
      title: "",
    };
  }

  render() {
    const { table_data, title } = this.state;
    const { t } = this.props;
    return (
      <Div className="molecules-ranking-list-wrapper">
        <Label>{t(title)}</Label>
        {!isNotEmpty ? (
          <Div>
            <Span>{noResult}</Span>
          </Div>
        ) : (
          ""
        )}
        <Table>
          <tbody>
            {table_data.map((item, index) => {
              return (
                <tr key={index}>
                  <td>{item.exam_name}</td>
                  <td className="molecules-ranking-list-points">
                    {item.points}
                  </td>
                </tr>
              );
            })}
          </tbody>
        </Table>
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

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(RankingList);
