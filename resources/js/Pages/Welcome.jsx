import { Head } from '@inertiajs/react';
import Layout from '@/Layouts/Layout';
import Hero from '@/Components/Welcome/Hero';
import StatsBar from '@/Components/Welcome/StatsBar';
import QuickAccess from '@/Components/Welcome/QuickAccess';
import Services from '@/Components/Welcome/Services';
import SolicitudForm from '@/Components/Welcome/SolicitudForm';
import Contact from '@/Components/Welcome/Contact';
import Footer from '@/Components/Welcome/Footer';

export default function Welcome({ 
    stats = {},
    settings = {},
    tiposSolicitud = [],
    parroquias = [],
    circuitosPorParroquia = {},
    sectoresPorParroquia = {},
    ...props 
}) {
    return (
        <Layout settings={settings}>
            <Head title="Inicio" />
            
            <Hero settings={settings} />
            <StatsBar stats={stats} />
            <QuickAccess settings={settings} />
            <Services settings={settings} />
            <SolicitudForm 
                tiposSolicitud={tiposSolicitud} 
                parroquias={parroquias} 
                circuitosPorParroquia={circuitosPorParroquia}
                sectoresPorParroquia={sectoresPorParroquia}
                settings={settings} 
            />
            <Contact settings={settings} />
            <Footer settings={settings} />
        </Layout>
    );
}
