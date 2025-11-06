/**
 * JavaScript principal para Sistema RRHH
 */

// Confirmación de eliminación
function confirmarEliminacion(mensaje) {
    return confirm(mensaje || '¿Está seguro de que desea eliminar este registro?');
}

// Formatear números como moneda (Guaraníes)
function formatearMoneda(valor) {
    return '₲ ' + parseFloat(valor).toLocaleString('es-PY', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

// Calcular horas trabajadas
function calcularHorasTrabajadas(horaEntrada, horaSalida) {
    if (!horaEntrada || !horaSalida) return 0;
    
    const entrada = new Date('2000-01-01 ' + horaEntrada);
    const salida = new Date('2000-01-01 ' + horaSalida);
    
    if (salida < entrada) {
        salida.setDate(salida.getDate() + 1);
    }
    
    const diff = (salida - entrada) / 1000 / 60 / 60;
    return diff.toFixed(2);
}

// Validar formulario de empleado
function validarFormularioEmpleado(form) {
    const cedula = form.cedula.value;
    const salarioBase = parseFloat(form.salario_base.value);
    
    // Validar formato de cédula (básico)
    if (cedula.length < 6) {
        alert('La cédula debe tener al menos 6 caracteres');
        return false;
    }
    
    // Validar salario
    if (salarioBase <= 0) {
        alert('El salario base debe ser mayor a cero');
        return false;
    }
    
    return true;
}

// Validar formulario de asistencia
function validarFormularioAsistencia(form) {
    const horaEntrada = form.hora_entrada.value;
    const horaSalida = form.hora_salida.value;
    
    if (!horaEntrada || !horaSalida) {
        alert('Debe ingresar hora de entrada y salida');
        return false;
    }
    
    const horas = calcularHorasTrabajadas(horaEntrada, horaSalida);
    
    if (horas <= 0) {
        alert('La hora de salida debe ser posterior a la hora de entrada');
        return false;
    }
    
    // Actualizar campo de horas trabajadas
    if (form.horas_trabajadas) {
        form.horas_trabajadas.value = horas;
    }
    
    return true;
}

// Auto-calcular horas cuando se ingresan entrada y salida
document.addEventListener('DOMContentLoaded', function() {
    const horaEntrada = document.getElementById('hora_entrada');
    const horaSalida = document.getElementById('hora_salida');
    const horasTrabajadas = document.getElementById('horas_trabajadas');
    
    if (horaEntrada && horaSalida && horasTrabajadas) {
        const calcular = function() {
            const horas = calcularHorasTrabajadas(horaEntrada.value, horaSalida.value);
            horasTrabajadas.value = horas;
        };
        
        horaEntrada.addEventListener('change', calcular);
        horaSalida.addEventListener('change', calcular);
    }
});

// Buscar empleado en tiempo real
function buscarEmpleado(termino) {
    if (termino.length < 2) return;
    
    // Aquí se puede implementar búsqueda AJAX si es necesario
    console.log('Buscando:', termino);
}

// Alternar estado activo/inactivo
function toggleEstado(elemento, tipo, id) {
    const confirmacion = confirm('¿Desea cambiar el estado de este registro?');
    
    if (confirmacion) {
        // Implementar llamada AJAX para cambiar estado
        console.log('Cambiar estado:', tipo, id);
    }
}

// Exportar tabla a CSV
function exportarTablaCSV(tablaId, nombreArchivo) {
    const tabla = document.getElementById(tablaId);
    if (!tabla) return;
    
    let csv = [];
    const filas = tabla.querySelectorAll('tr');
    
    for (let i = 0; i < filas.length; i++) {
        const fila = [];
        const cols = filas[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            fila.push(cols[j].innerText);
        }
        
        csv.push(fila.join(','));
    }
    
    descargarCSV(csv.join('\n'), nombreArchivo);
}

// Descargar archivo CSV
function descargarCSV(contenido, nombreArchivo) {
    const blob = new Blob([contenido], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', nombreArchivo || 'export.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Imprimir contenido específico
function imprimirContenido(elementoId) {
    const contenido = document.getElementById(elementoId);
    if (!contenido) return;
    
    const ventana = window.open('', '', 'height=600,width=800');
    ventana.document.write('<html><head><title>Imprimir</title>');
    ventana.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
    ventana.document.write('<link rel="stylesheet" href="assets/css/style.css">');
    ventana.document.write('</head><body>');
    ventana.document.write(contenido.innerHTML);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.print();
}

// Mostrar/ocultar campos según tipo de contrato
function toggleCamposContrato(tipoContrato) {
    const campoMontoBase = document.getElementById('campo-monto-base');
    const campoMontoHora = document.getElementById('campo-monto-hora');
    
    if (!campoMontoBase || !campoMontoHora) return;
    
    if (tipoContrato === 'mensualero' || tipoContrato === 'catedratico') {
        campoMontoBase.style.display = 'block';
        campoMontoHora.style.display = 'none';
    } else if (tipoContrato === 'jornalero') {
        campoMontoBase.style.display = 'none';
        campoMontoHora.style.display = 'block';
    }
}

// Validar fechas
function validarFechas(fechaInicio, fechaFin) {
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);
    
    if (fin < inicio) {
        alert('La fecha de fin debe ser posterior a la fecha de inicio');
        return false;
    }
    
    return true;
}

// Calcular días entre fechas
function calcularDiasEntreFechas(fechaInicio, fechaFin) {
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);
    const diff = Math.abs(fin - inicio);
    return Math.ceil(diff / (1000 * 60 * 60 * 24));
}

// Formatear fecha DD/MM/YYYY
function formatearFecha(fecha) {
    const date = new Date(fecha);
    const dia = String(date.getDate()).padStart(2, '0');
    const mes = String(date.getMonth() + 1).padStart(2, '0');
    const anio = date.getFullYear();
    return `${dia}/${mes}/${anio}`;
}

// Mostrar loading
function mostrarLoading(mensaje) {
    const loading = document.createElement('div');
    loading.id = 'loading-overlay';
    loading.innerHTML = `
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
             style="background-color: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="bg-white p-4 rounded shadow">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 mb-0">${mensaje || 'Cargando...'}</p>
            </div>
        </div>
    `;
    document.body.appendChild(loading);
}

// Ocultar loading
function ocultarLoading() {
    const loading = document.getElementById('loading-overlay');
    if (loading) {
        loading.remove();
    }
}

// Auto-dismiss alerts después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Validación de números positivos
function validarNumeroPositivo(input) {
    const valor = parseFloat(input.value);
    if (valor < 0) {
        input.value = 0;
        alert('El valor no puede ser negativo');
    }
}

// Formatear input de moneda mientras se escribe
function formatearInputMoneda(input) {
    let valor = input.value.replace(/[^\d]/g, '');
    valor = parseInt(valor || 0);
    input.value = valor.toLocaleString('es-PY');
}

console.log('Sistema RRHH cargado correctamente');
