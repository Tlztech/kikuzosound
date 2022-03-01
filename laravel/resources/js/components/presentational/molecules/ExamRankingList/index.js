import React from "react";

// Components
import Div from "../../atoms/Div";
import Table from "../../atoms/Table";
import Label from "../../atoms/Label";

// Styles
import "./style.css";

//===================================================
// Component
//===================================================
class ExamRankingList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      table_data: [
        {
          id: 1,
          exam_name: "Ausculation sound",
          points: 2,
        },
        {
          id: 2,
          exam_name: "Stethoscope",
          points: 33,
        },
        {
          id: 2,
          exam_name: "Palpation content",
          points: 11,
        },
        {
          id: 2,
          exam_name: "ECG",
          points: 41,
        },
        {
          id: 2,
          exam_name: "Examination",
          points: 12,
        },
        {
          id: 2,
          exam_name: "X-ray list",
          points: 7,
        },
        {
          id: 2,
          exam_name: "Echocardiography ",
          points: 3,
        },
      ],
    };
  }

  render() {
    const { table_data } = this.state;
    return (
      <Div className="molecules-exam-rank-wrapper">
        <Label>Result Ranking</Label>
        <Table>
          <tbody>
            {table_data.map((item, index) => {
              return (
                <tr key={index}>
                  <td>{item.exam_name}</td>
                  <td className="molecules-exam-rank-points">{item.points}</td>
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
export default ExamRankingList;
