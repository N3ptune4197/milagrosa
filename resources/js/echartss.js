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
          name: `${docente.nombres} ${docente.a_paterno}`
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
    
    title: {
      text: 'Docentes con más Préstamos Totales',
      subtext: '',
      left: 'center'
    },
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





const getOptionsChar3 = (data3) => {
  return{
    title: {
      text: 'Categorias más utilizados',
      subtext: '',
      left: 'center'
    },
    tooltip: {
      trigger: 'item'
    },
    legend: {
      bottom: 'bottom',
      left: 'center',
    },
    series: [
      {
        name: 'Categorias más utilizados',
        type: 'pie',
        radius: ['40%', '70%'],
        avoidLabelOverlap: false,
        itemStyle: {
          borderRadius: 10,
          borderColor: '#fff',
          borderWidth: 2
        },
        label: {
          show: false,
          position: 'center'
        },
        emphasis: {
          label: {
            show: true,
            fontSize: 40,
            fontWeight: 'bold'
          }
        },
        labelLine: {
          show: false
        },
        data: data3.map(item => ({ value: item.cantidad_prestamos, 
                                    name: `${item.nombre}` })) 
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





        
    const barras3 = echarts.init(document.getElementById("barras3"));

    fetch('/prestamos-getCategoriasMasUtilizadas') 
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data3 => {
      // Verifica los datos obtenidos
      
      // Establece la opción de ECharts con los datos formateados
      barras3.setOption(getOptionsChar3(data3));
    })
    .catch(error => {
      console.error('Error al cargar los datos:', error);
    });
};





window.addEventListener("load", () => {
  initCharts();
});



