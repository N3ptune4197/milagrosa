import * as echarts from 'echarts';

const getOptionsChar1 = (data) => {
  return {
    title: {
      text: 'Personal con Préstamos Activos',
      subtext: '',
      left: 'center'
    },
    tooltip: {
      trigger: 'item'
    },
    legend: {
      orient: 'horizontal',
      bottom: 'bottom'
    },
    series: [
      {
        name: 'Préstamos Activos',
        type: 'pie',
        radius: '50%',
        data: data.map(docente => ({
          value: docente.prestamos_activos,
          name: `${docente.nombres} ${docente.a_paterno} ${docente.a_materno}`
        })),
        emphasis: {
          itemStyle: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)'
          }
        }
      }
    ]
  };
};


const getOptionsChar2 = (data2) => {
  return {
    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'shadow'
      }
    },
    grid: {
      left: '3%',
      right: '4%',
      bottom: '3%',
      containLabel: true
    },
    xAxis: [
      {
        type: 'category',
        data: data2.map(docente => `${docente.a_paterno} ${docente.a_materno}`),
        axisTick: {
          alignWithLabel: true
        }
      }
    ],
    yAxis: [
      {
        type: 'value'
      }
    ],
    series: [
      {
        name: 'Préstamos totales',
        type: 'bar',
        barWidth: '55%',
        data: data2.map(docente => docente.total_prestamos)
      }
    ]
  };
}



const initCharts = () => {
  const barras1 = echarts.init(document.getElementById("barras1"));

  // Realizamos la consulta
  fetch('/prestamos-obtenerDocentesConPrestamosActivos') // Cambia esta URL a tu endpoint
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      // Verifica los datos obtenidos
      
      // Establece la opción de ECharts con los datos formateados
      barras1.setOption(getOptionsChar1(data));
    })
    .catch(error => {
      console.error('Error al cargar los datos:', error);
    });



    const barras2 = echarts.init(document.getElementById("barras2"));

    fetch('/prestamos-obtenerDocentesConMasPrestamos') 
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data2 => {
      // Verifica los datos obtenidos
      
      // Establece la opción de ECharts con los datos formateados
      barras2.setOption(getOptionsChar2(data2));
    })
    .catch(error => {
      console.error('Error al cargar los datos:', error);
    });

};





window.addEventListener("load", () => {
  initCharts();
});



