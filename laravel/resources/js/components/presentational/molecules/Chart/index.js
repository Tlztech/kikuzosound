import * as React from "react";

// @material-ui
import Paper from "@material-ui/core/Paper";
import {
  Chart,
  ArgumentAxis,
  ValueAxis,
  LineSeries,
  Title,
  Legend,
} from "@devexpress/dx-react-chart-material-ui";
import { withStyles } from "@material-ui/core/styles";
import { Animation, ValueScale } from "@devexpress/dx-react-chart";

// static json data
// import { confidence as data } from "./data-vizualization";

// styles
import "./style.css";
const chartStyles = () => ({
  chart: {
    paddingRight: "20px",
  },
  title: {
    whiteSpace: "pre",
  },
});

const legendStyles = (theme) => ({
  root: {
    display: "flex",
    margin: "auto",
    flexDirection: "row",
  },
  label: {
    paddingTop: theme.spacing(1),
    whiteSpace: "nowrap",
  },
  item: {
    flexDirection: "column",
  },
});

// components
const legendRootBase = ({ classes, ...restProps }) => (
  <Legend.Root {...restProps} className={classes.root} />
);
const legendLabelBase = ({ classes, ...restProps }) => (
  <Legend.Label className={classes.label} {...restProps} />
);
const legendItemBase = ({ classes, ...restProps }) => (
  <Legend.Item className={classes.item} {...restProps} />
);
const Root = withStyles(legendStyles, { name: "LegendRoot" })(legendRootBase);
const Label = withStyles(legendStyles, { name: "LegendLabel" })(
  legendLabelBase
);
const Item = withStyles(legendStyles, { name: "LegendItem" })(legendItemBase);

//===================================================
// Component
//===================================================
class DemoChart extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      title: `Full function development ${"\n"}(Approximate estimation) `,
    };
  }

  render() {
    const { classes, chartData } = this.props;
    return (
      <Paper className="molecules-Chart-wrapper">
        <Chart data={chartData} className={classes.chart}>
          <ArgumentAxis tickFormat={format} />
          <ValueAxis max={50} position={"right"} labelComponent={ValueLabel} />
          <ValueScale modifyDomain={() => [0, 100]} />
          <LineSeries
            name="UserAA"
            valueField="UserA_answer"
            argumentField="exam_group"
          />
          <LineSeries
            name="UserBB"
            valueField="UserB_answer"
            argumentField="exam_group"
          />
          <Legend
            position="bottom"
            rootComponent={Root}
            itemComponent={Item}
            labelComponent={Label}
          />
          <Title text={this.props.title} textComponent={TitleText} />
          <Animation />
        </Chart>
      </Paper>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================
/**
 * format for argument axis (x-axis)
 */
const format = () => (tick) => tick;

/**
 * renders the value axis label (y-axis)
 * @param {*} props
 */
const ValueLabel = (props) => {
  const { text } = props;
  return <ValueAxis.Label {...props} text={`${text}%`} />;
};

/**
 * renders the title
 */
const TitleText = withStyles(chartStyles)(({ classes, ...props }) => (
  <Title.Text {...props} className={classes.title} />
));

//===================================================
// Export
//===================================================
export default withStyles(chartStyles, { name: "DemoChart" })(DemoChart);
