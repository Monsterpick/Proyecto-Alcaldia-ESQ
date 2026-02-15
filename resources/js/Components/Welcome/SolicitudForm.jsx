import { useState, useMemo } from 'react';
import { useForm } from '@inertiajs/react';
import { useTheme } from '@/Components/Theme/ThemeProvider';
import Icon from '@/Components/Icons/Icon';
import SectionReveal from '@/Components/UI/SectionReveal';

function capitalizarPalabras(texto) {
    if (!texto || typeof texto !== 'string') return '';
    return texto.trim().split(/\s+/).map(p => p.charAt(0).toUpperCase() + p.slice(1).toLowerCase()).join(' ');
}

export default function SolicitudForm({ tiposSolicitud = [], parroquias = [], circuitosPorParroquia = {}, sectoresPorParroquia = {}, settings }) {
    const theme = useTheme();
    const [chars, setChars] = useState(0);

    const { data, setData, post, processing, errors, reset } = useForm({
        cedula: '',
        nombre: '',
        apellido: '',
        email: '',
        telefono_movil: '',
        whatsapp: '',
        tipo_solicitud_id: '',
        departamento_id: '',
        parroquia_id: '',
        circuito_comunal_id: '',
        sector: '',
        descripcion: '',
        direccion: '',
        acepta_terminos: false,
    });

    const circuitosActuales = useMemo(() => {
        if (!data.parroquia_id) return [];
        return circuitosPorParroquia[data.parroquia_id] || [];
    }, [data.parroquia_id, circuitosPorParroquia]);

    const sectoresActuales = useMemo(() => {
        if (!data.parroquia_id) return [];
        return sectoresPorParroquia[data.parroquia_id] || [];
    }, [data.parroquia_id, sectoresPorParroquia]);

    const departamentoSeleccionado = useMemo(() => {
        const tipo = tiposSolicitud.find(t => String(t.id) === String(data.tipo_solicitud_id));
        return tipo?.departamento || null;
    }, [data.tipo_solicitud_id, tiposSolicitud]);

    // Agrupar servicios por departamento para el select (optgroups)
    const serviciosPorDepartamento = useMemo(() => {
        const grupos = {};
        tiposSolicitud.forEach((tipo) => {
            const nombreDept = tipo.departamento?.nombre || 'Otros';
            if (!grupos[nombreDept]) grupos[nombreDept] = [];
            grupos[nombreDept].push(tipo);
        });
        return grupos;
    }, [tiposSolicitud]);

    const handleServicioChange = (tipoId) => {
        setData('tipo_solicitud_id', tipoId);
        const tipo = tiposSolicitud.find(t => String(t.id) === String(tipoId));
        setData('departamento_id', tipo?.departamento_id || '');
    };

    const handleParroquiaChange = (parroquiaId) => {
        setData('parroquia_id', parroquiaId);
        setData('circuito_comunal_id', '');
        setData('sector', '');
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/solicitud', {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setChars(0);
                // Mostrar mensaje de éxito (puedes usar SweetAlert2 o similar)
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'success',
                        title: 'Solicitud enviada',
                        text: 'Su solicitud ha sido registrada exitosamente. Pronto nos comunicaremos con usted.',
                        timer: 5000,
                    });
                }
            },
        });
    };

    return (
        <SectionReveal id="formulario" className="py-12 sm:py-16 lg:py-24 bg-gradient-to-br from-gray-50 to-gray-100">
            <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center mb-8 sm:mb-16">
                    <h2 className="text-2xl sm:text-4xl lg:text-5xl font-extrabold mb-4 sm:mb-6">
                        <span style={{ color: theme.colors.primary }}>Alcaldía Digital</span>
                    </h2>
                    <p className="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">
                        Participa activamente en la construcción de nuestro municipio. Completa el formulario y un funcionario se pondrá en contacto contigo.
                    </p>
                </div>
                <div className="bg-white rounded-2xl p-4 sm:p-6 md:p-8 lg:p-12 border-t-4 shadow-2xl" style={{ borderTopColor: theme.colors.primary }}>
                    <form onSubmit={handleSubmit} className="space-y-8">
                        {/* Sección: Datos Personales */}
                        <div>
                            <h3 className="text-xl sm:text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <div className="w-10 h-10 rounded-lg flex items-center justify-center text-white" style={{ backgroundColor: theme.colors.primary }}>
                                    <Icon name="user" className="w-5 h-5" />
                                </div>
                                <span>Datos Personales</span>
                            </h3>
                            <div className="grid md:grid-cols-2 gap-5">
                                {/* Cédula */}
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">
                                        Cédula <span style={{ color: theme.colors.primary }}>*</span>
                                    </label>
                                    <div className="flex">
                                        <span className="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-700 font-semibold text-sm">V-</span>
                                        <input
                                            type="text"
                                            value={data.cedula}
                                            onChange={(e) => setData('cedula', e.target.value)}
                                            placeholder="12345678"
                                            inputMode="numeric"
                                            maxLength="8"
                                            className={`flex-1 px-4 py-3 text-base border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                                errors.cedula ? 'border-red-500 ring-1 ring-red-500' : ''
                                            }`}
                                            style={{ '--tw-ring-color': theme.colors.primary }}
                                        />
                                    </div>
                                    {errors.cedula && <p className="mt-1 text-sm text-red-600">{errors.cedula}</p>}
                                </div>

                                {/* Email */}
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">
                                        Email <span style={{ color: theme.colors.primary }}>*</span>
                                    </label>
                                    <input
                                        type="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        placeholder="tucorreo@ejemplo.com"
                                        className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                            errors.email ? 'border-red-500 ring-1 ring-red-500' : ''
                                        }`}
                                        style={{ '--tw-ring-color': theme.colors.primary }}
                                    />
                                    {errors.email && <p className="mt-1 text-sm text-red-600">{errors.email}</p>}
                                </div>

                                {/* Nombre */}
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">
                                        Nombre <span style={{ color: theme.colors.primary }}>*</span>
                                    </label>
                                    <input
                                        type="text"
                                        value={data.nombre}
                                        onChange={(e) => setData('nombre', e.target.value)}
                                        onBlur={(e) => setData('nombre', capitalizarPalabras(e.target.value))}
                                        placeholder="Tu nombre"
                                        className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                            errors.nombre ? 'border-red-500 ring-1 ring-red-500' : ''
                                        }`}
                                        style={{ '--tw-ring-color': theme.colors.primary }}
                                    />
                                    {errors.nombre && <p className="mt-1 text-sm text-red-600">{errors.nombre}</p>}
                                </div>

                                {/* Apellido */}
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">
                                        Apellido <span style={{ color: theme.colors.primary }}>*</span>
                                    </label>
                                    <input
                                        type="text"
                                        value={data.apellido}
                                        onChange={(e) => setData('apellido', e.target.value)}
                                        onBlur={(e) => setData('apellido', capitalizarPalabras(e.target.value))}
                                        placeholder="Tu apellido"
                                        className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                            errors.apellido ? 'border-red-500 ring-1 ring-red-500' : ''
                                        }`}
                                        style={{ '--tw-ring-color': theme.colors.primary }}
                                    />
                                    {errors.apellido && <p className="mt-1 text-sm text-red-600">{errors.apellido}</p>}
                                </div>

                                {/* Teléfono Móvil */}
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">
                                        Teléfono Móvil <span style={{ color: theme.colors.primary }}>*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        value={data.telefono_movil}
                                        onChange={(e) => setData('telefono_movil', e.target.value)}
                                        placeholder="04121234567"
                                        inputMode="tel"
                                        maxLength="11"
                                        className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                            errors.telefono_movil ? 'border-red-500 ring-1 ring-red-500' : ''
                                        }`}
                                        style={{ '--tw-ring-color': theme.colors.primary }}
                                    />
                                    {errors.telefono_movil && <p className="mt-1 text-sm text-red-600">{errors.telefono_movil}</p>}
                                </div>

                                {/* WhatsApp */}
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">
                                        WhatsApp <span style={{ color: theme.colors.primary }}>*</span>
                                        <span className="text-xs text-gray-400 font-normal ml-1">(donde recibirás confirmación)</span>
                                    </label>
                                    <input
                                        type="tel"
                                        value={data.whatsapp}
                                        onChange={(e) => setData('whatsapp', e.target.value)}
                                        placeholder="04121234567"
                                        inputMode="tel"
                                        maxLength="11"
                                        className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                            errors.whatsapp ? 'border-red-500 ring-1 ring-red-500' : ''
                                        }`}
                                        style={{ '--tw-ring-color': theme.colors.primary }}
                                    />
                                    {errors.whatsapp && <p className="mt-1 text-sm text-red-600">{errors.whatsapp}</p>}
                                </div>
                            </div>
                        </div>

                        {/* Sección: Solicitud */}
                        <div>
                            <h3 className="text-xl sm:text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <div className="w-10 h-10 rounded-lg flex items-center justify-center text-white" style={{ backgroundColor: theme.colors.secondary }}>
                                    <Icon name="document" className="w-5 h-5" />
                                </div>
                                <span>Detalles de la Solicitud</span>
                            </h3>

                            {/* Tipo de servicio - agrupado por departamento */}
                            <div className="mb-5">
                                <label className="block text-sm font-bold text-gray-700 mb-2">
                                    Servicio <span style={{ color: theme.colors.primary }}>*</span>
                                </label>
                                <select
                                    value={data.tipo_solicitud_id}
                                    onChange={(e) => handleServicioChange(e.target.value)}
                                    className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                        errors.tipo_solicitud_id ? 'border-red-500 ring-1 ring-red-500' : ''
                                    }`}
                                    style={{ '--tw-ring-color': theme.colors.primary }}
                                >
                                    <option value="">Seleccione un servicio</option>
                                    {Object.entries(serviciosPorDepartamento).map(([nombreDept, servicios]) => (
                                        <optgroup key={nombreDept} label={`${nombreDept.toUpperCase()} — Servicios:`}>
                                            {servicios.map((tipo) => (
                                                <option key={tipo.id} value={tipo.id}>
                                                    {tipo.nombre}
                                                </option>
                                            ))}
                                        </optgroup>
                                    ))}
                                </select>
                                {errors.tipo_solicitud_id && <p className="mt-1 text-sm text-red-600">{errors.tipo_solicitud_id}</p>}
                            </div>

                            {/* Departamento (auto-llenado) */}
                            {departamentoSeleccionado && (
                                <div className="mb-5 p-3 rounded-lg bg-gray-50 border border-gray-200">
                                    <label className="block text-sm font-medium text-gray-600 mb-1">Departamento destino</label>
                                    <p className="text-base font-semibold" style={{ color: theme.colors.primary }}>
                                        {departamentoSeleccionado.nombre}
                                    </p>
                                    <p className="text-xs text-gray-500 mt-1">
                                        La solicitud será atendida por este departamento
                                    </p>
                                </div>
                            )}

                            {/* Parroquia */}
                            <div className="mb-5">
                                <label className="block text-sm font-bold text-gray-700 mb-2">
                                    Parroquia <span style={{ color: theme.colors.primary }}>*</span>
                                </label>
                                <select
                                    value={data.parroquia_id}
                                    onChange={(e) => handleParroquiaChange(e.target.value)}
                                    className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                        errors.parroquia_id ? 'border-red-500 ring-1 ring-red-500' : ''
                                    }`}
                                    style={{ '--tw-ring-color': theme.colors.primary }}
                                >
                                    <option value="">Seleccione su parroquia</option>
                                    {parroquias.map((p) => (
                                        <option key={p.id} value={p.id}>{p.parroquia}</option>
                                    ))}
                                </select>
                                {errors.parroquia_id && <p className="mt-1 text-sm text-red-600">{errors.parroquia_id}</p>}
                            </div>

                            {/* Circuito comunal */}
                            <div className="mb-5">
                                <label className="block text-sm font-bold text-gray-700 mb-2">
                                    Circuito comunal <span style={{ color: theme.colors.primary }}>*</span>
                                </label>
                                <select
                                    value={data.circuito_comunal_id}
                                    onChange={(e) => setData('circuito_comunal_id', e.target.value)}
                                    disabled={!data.parroquia_id}
                                    className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed ${
                                        errors.circuito_comunal_id ? 'border-red-500 ring-1 ring-red-500' : ''
                                    }`}
                                    style={{ '--tw-ring-color': theme.colors.primary }}
                                >
                                    <option value="">
                                        {data.parroquia_id ? 'Seleccione el circuito comunal' : 'Primero seleccione una parroquia'}
                                    </option>
                                    {circuitosActuales.map((c) => (
                                        <option key={c.id} value={c.id}>
                                            {c.codigo ? `${c.codigo} - ` : ''}{c.nombre}
                                        </option>
                                    ))}
                                </select>
                                {errors.circuito_comunal_id && <p className="mt-1 text-sm text-red-600">{errors.circuito_comunal_id}</p>}
                            </div>

                            {/* Sector */}
                            <div className="mb-5">
                                <label className="block text-sm font-bold text-gray-700 mb-2">
                                    Sector
                                </label>
                                <select
                                    value={data.sector}
                                    onChange={(e) => setData('sector', e.target.value)}
                                    disabled={!data.parroquia_id}
                                    className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed ${
                                        errors.sector ? 'border-red-500 ring-1 ring-red-500' : ''
                                    }`}
                                    style={{ '--tw-ring-color': theme.colors.primary }}
                                >
                                    <option value="">
                                        {data.parroquia_id ? 'Seleccione sector (opcional)' : 'Primero seleccione una parroquia'}
                                    </option>
                                    {sectoresActuales.map((s) => (
                                        <option key={s} value={s}>{s}</option>
                                    ))}
                                </select>
                                {errors.sector && <p className="mt-1 text-sm text-red-600">{errors.sector}</p>}
                            </div>

                            {/* Dirección */}
                            <div className="mb-5">
                                <label className="block text-sm font-bold text-gray-700 mb-2">
                                    Dirección <span style={{ color: theme.colors.primary }}>*</span>
                                </label>
                                <input
                                    type="text"
                                    value={data.direccion}
                                    onChange={(e) => setData('direccion', e.target.value)}
                                    placeholder="Calle, sector, urbanización, casa/edificio..."
                                    className={`w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent ${
                                        errors.direccion ? 'border-red-500 ring-1 ring-red-500' : ''
                                    }`}
                                    style={{ '--tw-ring-color': theme.colors.primary }}
                                />
                                {errors.direccion && <p className="mt-1 text-sm text-red-600">{errors.direccion}</p>}
                                <p className="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                    <Icon name="map-pin" className="w-3.5 h-3.5 text-gray-400" />
                                    Indique su dirección completa para facilitar la atención
                                </p>
                            </div>

                            {/* Descripción */}
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">
                                    Descripción de tu solicitud <span style={{ color: theme.colors.primary }}>*</span>
                                </label>
                                <textarea
                                    value={data.descripcion}
                                    onChange={(e) => {
                                        setData('descripcion', e.target.value);
                                        setChars(e.target.value.length);
                                    }}
                                    rows="5"
                                    maxLength="2000"
                                    placeholder="Describe tu solicitud con el mayor detalle posible..."
                                    className={`w-full min-h-[120px] sm:min-h-[140px] px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent resize-none text-base ${
                                        errors.descripcion ? 'border-red-500 ring-1 ring-red-500' : ''
                                    }`}
                                    style={{ '--tw-ring-color': theme.colors.primary }}
                                />
                                <div className="flex justify-between mt-1.5">
                                    {errors.descripcion ? (
                                        <p className="text-sm text-red-600">{errors.descripcion}</p>
                                    ) : (
                                        <p className="text-xs text-gray-500">Mínimo 10 caracteres, máximo 2000</p>
                                    )}
                                    <p className={`text-xs font-semibold ${chars > 1900 ? 'text-red-600' : 'text-gray-500'}`}>
                                        {2000 - chars} restantes
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Términos y condiciones */}
                        <div>
                            <label className="flex items-start cursor-pointer group">
                                <input
                                    type="checkbox"
                                    checked={data.acepta_terminos}
                                    onChange={(e) => setData('acepta_terminos', e.target.checked)}
                                    className={`mt-1 w-5 h-5 border-gray-300 rounded focus:ring-2 ${
                                        errors.acepta_terminos ? 'border-red-500 ring-1 ring-red-500' : ''
                                    }`}
                                    style={{ 
                                        accentColor: theme.colors.primary,
                                        '--tw-ring-color': theme.colors.primary 
                                    }}
                                />
                                <span className="ml-3 text-sm text-gray-700">
                                    Acepto los <a href="#" className="font-bold hover:underline" style={{ color: theme.colors.primary }}>términos y condiciones</a>
                                    {' '}y autorizo el tratamiento de mis datos personales.
                                </span>
                            </label>
                            {errors.acepta_terminos && <p className="mt-1 text-sm text-red-600">{errors.acepta_terminos}</p>}
                        </div>

                        {/* Botón enviar */}
                        <div className="text-center pt-4 overflow-visible">
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex items-center justify-center gap-3 px-8 sm:px-12 py-4 min-h-[52px] text-white rounded-xl font-bold text-base sm:text-lg shadow-xl transition-all duration-300 hover:scale-105 whitespace-nowrap w-full sm:w-auto disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100"
                                style={{ backgroundColor: theme.colors.primary }}
                                onMouseEnter={(e) => { if (!processing) e.currentTarget.style.backgroundColor = '#991b1b'; }}
                                onMouseLeave={(e) => { if (!processing) e.currentTarget.style.backgroundColor = theme.colors.primary; }}
                            >
                                {processing ? (
                                    <>
                                        <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Enviando...</span>
                                    </>
                                ) : (
                                    <>
                                        <Icon name="paper-plane" className="w-5 h-5 flex-shrink-0" />
                                        <span>Enviar Solicitud</span>
                                    </>
                                )}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </SectionReveal>
    );
}
