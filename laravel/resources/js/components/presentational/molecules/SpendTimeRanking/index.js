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
class SpendTimeRanking extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      table_data: [
        {
          id: 1,
          exam_name: "Ausculation sound",
          points: 23,
        },
        {
          id: 2,
          exam_name: "Stethoscope",
          points: 24,
        },
        {
          id: 3,
          exam_name: "Palpation content",
          points: 1,
        },
        {
          id: 4,
          exam_name: "ECG",
          points: 2,
        },
        {
          id: 5,
          exam_name: "Examination",
          points: 3,
        },
        {
          id: 6,
          exam_name: "X-ray list",
          points: 5,
        },
        {
          id: 7,
          exam_name: "Echocardiography ",
          points: 7,
        },
      ],
    };
  }

  render() {
    const { table_data } = this.state;
    return (
      <Div className="molecules-spendTime-ranking-wrapper">
        <Label>Spend Time Ranking</Label>
        <Table size="sm">
          <tbody>
            {table_data.map((item, index) => {
              return (
                <tr key={index}>
                  <td>{item.exam_name}</td>
                  <td className="molecules-spendTime-rank-points">
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
export default SpendTimeRanking;
