import './../../vendor/power-components/livewire-powergrid/dist/powergrid'

import { initFlowbite } from 'flowbite'
import Swal from 'sweetalert2';
import Sortable from 'sortablejs';
import Swiper from 'swiper/bundle';
import AOS from 'aos';
import Dropzone from 'dropzone';
import './dropzone-spanish.js';
import axios from 'axios';
import { AreaSeries, BarSeries, BaselineSeries, CandlestickSeries, createChart, LineSeries, HistogramSeries } from 'lightweight-charts';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.listPlugin = listPlugin;

window.createChart = createChart;
window.AreaSeries = AreaSeries;
window.BarSeries = BarSeries;
window.BaselineSeries = BaselineSeries;
window.CandlestickSeries = CandlestickSeries;
window.LineSeries = LineSeries;
window.HistogramSeries = HistogramSeries;

//Inicia Flowbite
initFlowbite();

//Inicia AOS y su configuración básica
AOS.init({
    duration: 1000,
    easing: 'ease-out-cubic',
    once: true,
    offset: 100,
    delay: 0,
    mirror: true,
    anchorPlacement: 'top-bottom'
});

import 'swiper/css/bundle';

//Swal disponible en todas las vistas
window.Swal = Swal;

//Sortable disponible en todas las vistas
window.Sortable = Sortable;

//Swiper
window.Swiper = Swiper;

//Dropzone disponible en todas las vistas
window.Dropzone = Dropzone;

//Axios disponible en todas las vistas
window.axios = axios;

//LightweightCharts disponible en todas las vistas
/* window.LightweightCharts = LightweightCharts; */


window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Configuración global de Dropzone en español
window.DropzoneSpanishDefaults = {
    dictDefaultMessage: "Arrastre y suelte los archivos aquí para cargarlos",
    dictFallbackMessage: "Su navegador no soporta la carga de archivos por arrastre y soltar.",
    dictFallbackText: "Por favor, use el formulario de carga alternativo a continuación para cargar sus archivos como en los viejos tiempos.",
    dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MiB). Tamaño máximo: {{maxFilesize}}MiB.",
    dictInvalidFileType: "No se puede cargar archivos de este tipo.",
    dictResponseError: "El servidor respondió con el código {{statusCode}}.",
    dictCancelUpload: "Cancelar carga",
    dictCancelUploadConfirmation: "¿Está seguro de querer cancelar esta carga?",
    dictRemoveFile: "Eliminar archivo",
    dictMaxFilesExceeded: "No se puede cargar más archivos.",
    dictUploadCanceled: "Carga cancelada.",
    dictCancelUploadConfirmation: "¿Seguro que quiere cancelar esta carga?",
    dictRemoveFileConfirmation: null,
    dictFileSizeUnits: {
        tb: "TB",
        gb: "GB",
        mb: "MB",
        kb: "KB",
        b: "bytes"
    }
};

// Función helper para crear Dropzone con configuración en español
window.createSpanishDropzone = function(element, userOptions = {}) {
    const defaultOptions = Object.assign({}, window.DropzoneSpanishDefaults, userOptions);
    return new window.Dropzone(element, defaultOptions);
};

// Configuración automática para el modo declarativo
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar configuración en español a todas las instancias de Dropzone existentes
    if (typeof window.Dropzone !== 'undefined') {
        // Interceptar la creación de nuevas instancias
        const originalConstructor = window.Dropzone;
        window.Dropzone = function(element, options = {}) {
            const spanishOptions = Object.assign({}, window.DropzoneSpanishDefaults, options);
            return new originalConstructor(element, spanishOptions);
        };
        
        // Mantener todas las propiedades estáticas originales
        Object.setPrototypeOf(window.Dropzone, originalConstructor);
        Object.assign(window.Dropzone, originalConstructor);
        
        // Configurar opciones por defecto para el modo declarativo
        if (window.Dropzone.options) {
            const originalOptions = window.Dropzone.options;
            Object.keys(originalOptions).forEach(key => {
                if (originalOptions[key] && typeof originalOptions[key] === 'object') {
                    originalOptions[key] = Object.assign({}, window.DropzoneSpanishDefaults, originalOptions[key]);
                }
            });
        }
    }
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
