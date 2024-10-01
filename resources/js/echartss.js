import * as echarts from 'echarts';

const getOptionsChar1 = (data) => {
  return {
    title: {
      text: 'Personal con más recursos prestados',
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
        name: 'Total Préstamos',
        type: 'pie',
        radius: '50%',
        data: data.map(docente => ({
          value: docente.total_prestamos,
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
      console.log('Datos obtenidos:', data); // Imprime los datos en la consola
      
      // Establece la opción de ECharts con los datos formateados
      barras1.setOption(getOptionsChar1(data));
    })
    .catch(error => {
      console.error('Error al cargar los datos:', error);
    });
};

window.addEventListener("load", () => {
  initCharts();
});
