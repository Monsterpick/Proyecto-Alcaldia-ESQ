/**
 * Configuración de Dropzone en Español
 * Este archivo contiene todas las configuraciones necesarias para usar Dropzone en español
 */

// Configuración completa en español
export const DropzoneSpanishConfig = {
    // Mensajes básicos
    dictDefaultMessage: "Arrastre y suelte los archivos aquí para cargarlos",
    dictFallbackMessage: "Su navegador no soporta la carga de archivos por arrastre y soltar.",
    dictFallbackText: "Por favor, use el formulario de carga alternativo a continuación para cargar sus archivos como en los viejos tiempos.",
    dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MiB). Tamaño máximo: {{maxFilesize}}MiB.",
    dictInvalidFileType: "No se puede cargar archivos de este tipo.",
    dictResponseError: "El servidor respondió con el código {{statusCode}}.",
    
    // Mensajes de control
    dictCancelUpload: "Cancelar carga",
    dictCancelUploadConfirmation: "¿Está seguro de querer cancelar esta carga?",
    dictRemoveFile: "Eliminar archivo",
    dictRemoveFileConfirmation: "¿Está seguro de querer eliminar este archivo?",
    dictMaxFilesExceeded: "No se puede cargar más archivos.",
    dictUploadCanceled: "Carga cancelada.",
    
    // Unidades de tamaño de archivo
    dictFileSizeUnits: {
        tb: "TB",
        gb: "GB", 
        mb: "MB",
        kb: "KB",
        b: "bytes"
    }
};

// Función helper para crear Dropzone con configuración en español
export const createSpanishDropzone = (element, userOptions = {}) => {
    const options = { ...DropzoneSpanishConfig, ...userOptions };
    return new Dropzone(element, options);
};

// Función para aplicar configuración global automáticamente
export const applyGlobalSpanishConfig = () => {
    if (typeof window !== 'undefined' && window.Dropzone) {
        // Interceptar el constructor de Dropzone
        const OriginalDropzone = window.Dropzone;
        
        window.Dropzone = function(element, options = {}) {
            const spanishOptions = { ...DropzoneSpanishConfig, ...options };
            return new OriginalDropzone(element, spanishOptions);
        };
        
        // Preservar propiedades estáticas
        Object.setPrototypeOf(window.Dropzone, OriginalDropzone);
        Object.assign(window.Dropzone, OriginalDropzone);
        
        // Para modo declarativo - configurar Dropzone.options
        if (!window.Dropzone.options) {
            window.Dropzone.options = {};
        }
        
        // Función para configurar automáticamente nuevas instancias declarativas
        const originalDiscover = window.Dropzone.discover;
        window.Dropzone.discover = function() {
            // Aplicar configuración española a todas las opciones existentes
            Object.keys(window.Dropzone.options).forEach(key => {
                if (window.Dropzone.options[key] && typeof window.Dropzone.options[key] === 'object') {
                    window.Dropzone.options[key] = { ...DropzoneSpanishConfig, ...window.Dropzone.options[key] };
                }
            });
            
            // Llamar al discover original si existe
            if (originalDiscover) {
                return originalDiscover.call(this);
            }
        };
        
        // Hacer disponibles las configuraciones globalmente
        window.DropzoneSpanishConfig = DropzoneSpanishConfig;
        window.createSpanishDropzone = createSpanishDropzone;
    }
};

// Auto-aplicar si estamos en el navegador
if (typeof window !== 'undefined') {
    document.addEventListener('DOMContentLoaded', applyGlobalSpanishConfig);
}