import './../../vendor/power-components/livewire-powergrid/dist/powergrid'

import { initFlowbite } from 'flowbite'
import Swal from 'sweetalert2';
import Sortable from 'sortablejs';
import Swiper from 'swiper/bundle';
import AOS from 'aos';
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