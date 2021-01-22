<script>
import { Line } from "vue-chartjs";

export default {
  name: "LineChart",
  extends: Line,
  props: {
    label: {
      type: String
    },
    chartData: {
      type: Array
    },
    options: {
      type: Object
    },
    chartColors: {
      type: Object
    }
  },
  mounted() {
    const dates = this.chartData.map(d => d.date);
    const totals = this.chartData.map(d => d.gross);

    const {
      borderColor,
      pointBorderColor,
      pointBackgroundColor,
      backgroundColor
    } = this.chartColors;

    this.renderChart(
      {
          type: 'line',
        labels: dates,
        datasets: [
          {
            label: this.label,
            data: totals,
            borderColor: "#ffffff",


            backgroundColor: "#2196F3dd",
            pointBackgroundColor: "#4e5468",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 3,
            pointHitRadius: 5,
            radius: 5,
            pointHoverBorderWidth: 4,
            hoverRadius: 8,
          }
        ]
      },
      this.options
    );
  }
};
</script>
