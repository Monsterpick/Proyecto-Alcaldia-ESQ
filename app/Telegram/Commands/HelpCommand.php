<?php

namespace App\Telegram\Commands;

use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'help';
    protected string $description = 'Guía completa del bot';

    public function handle()
    {
        // Verificar autenticación
        $user = $this->requireAuth();
        if (!$user) {
            return;
        }
        
        $from = $this->getUpdate()->getMessage()->getFrom();
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        
        // Mensaje 1: Bienvenida y descripción general
        $text1 = "📖 *GUÍA COMPLETA DEL BOT*\n\n";
        $text1 .= "¡Bienvenido *{$user->name}*! 👋\n\n";
        $text1 .= "Este bot te permite consultar información del *Sistema 1X10 Escuque* directamente desde Telegram.\n\n";
        $text1 .= "🎯 *¿Qué puedes hacer?*\n";
        $text1 .= "• Consultar reportes por parroquia\n";
        $text1 .= "• Ver estadísticas globales y por parroquia\n";
        $text1 .= "• Buscar beneficiarios\n";
        $text1 .= "• Visualizar gráficos en tiempo real\n\n";
        $text1 .= "👇 *Lee los siguientes mensajes para aprender a usar el bot...*";
        
        $this->replyWithMessage([
            'text' => $text1,
            'parse_mode' => 'Markdown',
        ]);
        
        // Mensaje 2: Navegación por parroquias
        $text2 = "📍 *NAVEGACIÓN POR PARROQUIAS*\n\n";
        $text2 .= "El bot está organizado por parroquias. Puedes acceder a:\n\n";
        $text2 .= "🏘️ *Parroquias Disponibles:*\n";
        $text2 .= "• Parroquia Sabana Libre\n";
        $text2 .= "• Parroquia La Unión\n";
        $text2 .= "• Parroquia Santa Rita\n";
        $text2 .= "• Parroquia Escuque\n\n";
        $text2 .= "📌 *Cómo funciona:*\n\n";
        $text2 .= "1️⃣ Presiona el botón de la parroquia que deseas consultar\n\n";
        $text2 .= "2️⃣ Se mostrará un menú con 4 opciones numeradas:\n";
        $text2 .= "   • *1* - Medicamentos\n";
        $text2 .= "   • *2* - Ayudas Técnicas\n";
        $text2 .= "   • *3* - Otros (Alimentos, Educación, Vivienda, Higiene)\n";
        $text2 .= "   • *4* - Estadísticas de la Parroquia\n\n";
        $text2 .= "3️⃣ Presiona el número que deseas consultar\n\n";
        $text2 .= "4️⃣ El bot te mostrará los reportes o estadísticas solicitadas\n\n";
        $text2 .= "💡 *Ejemplo:*\n";
        $text2 .= "Si presionas \"📍 Parroquia Sabana Libre\" y luego \"1️⃣ Medicamentos\", verás todos los reportes de medicamentos de esa parroquia.";
        
        $this->replyWithMessage([
            'text' => $text2,
            'parse_mode' => 'Markdown',
        ]);
        
        // Mensaje 3: Estadísticas
        $text3 = "📊 *ESTADÍSTICAS*\n\n";
        $text3 .= "Hay dos tipos de estadísticas disponibles:\n\n";
        $text3 .= "🌎 *Estadísticas Globales:*\n";
        $text3 .= "• Presiona el botón \"📊 Estadísticas\" del menú principal\n";
        $text3 .= "• Muestra datos de TODAS las parroquias juntas\n";
        $text3 .= "• Incluye gráficos de beneficiarios, reportes y comparación entre parroquias\n\n";
        $text3 .= "📍 *Estadísticas por Parroquia:*\n";
        $text3 .= "• Entra a una parroquia específica\n";
        $text3 .= "• Presiona el botón \"4️⃣ Estadísticas\"\n";
        $text3 .= "• Muestra datos SOLO de esa parroquia\n";
        $text3 .= "• Incluye gráficos específicos de beneficiarios y reportes\n\n";
        $text3 .= "📈 *Información incluida:*\n";
        $text3 .= "• Total de beneficiarios (activos/inactivos)\n";
        $text3 .= "• Total de reportes (entregados/en proceso/no entregados)\n";
        $text3 .= "• Gráficos visuales generados en tiempo real\n";
        $text3 .= "• Fecha y hora de actualización";
        
        $this->replyWithMessage([
            'text' => $text3,
            'parse_mode' => 'Markdown',
        ]);
        
        // Mensaje 4: Reportes por categoría
        $text4 = "📦 *REPORTES POR CATEGORÍA*\n\n";
        $text4 .= "Cada parroquia tiene 3 categorías de reportes:\n\n";
        $text4 .= "💊 *1 - Medicamentos:*\n";
        $text4 .= "• Incluye medicamentos, insumos médicos y productos farmacéuticos\n\n";
        $text4 .= "🦽 *2 - Ayudas Técnicas:*\n";
        $text4 .= "• Incluye ayudas técnicas, dispositivos y recursos de apoyo social comunitario\n\n";
        $text4 .= "📦 *3 - Otros:*\n";
        $text4 .= "• Alimentos y Despensa\n";
        $text4 .= "• Educación y Útiles\n";
        $text4 .= "• Vivienda\n";
        $text4 .= "• Higiene Personal\n\n";
        $text4 .= "📋 *Información mostrada:*\n";
        $text4 .= "• Resumen de reportes (total, entregados, en proceso, no entregados)\n";
        $text4 .= "• Últimos 5 reportes de esa categoría\n";
        $text4 .= "• Detalles: código, producto, beneficiario, fecha y estado\n\n";
        $text4 .= "✅ *Estados de reportes:*\n";
        $text4 .= "• ✅ Entregado - El producto fue entregado al beneficiario\n";
        $text4 .= "• 🔄 En proceso - El reporte está siendo procesado\n";
        $text4 .= "• ❌ No entregado - El producto no pudo ser entregado";
        
        $this->replyWithMessage([
            'text' => $text4,
            'parse_mode' => 'Markdown',
        ]);
        
        // Mensaje 5: Búsqueda inline
        $text5 = "🔍 *BÚSQUEDA DE BENEFICIARIOS*\n\n";
        $text5 .= "Puedes buscar beneficiarios de forma rápida usando búsqueda inline:\n\n";
        $text5 .= "📝 *Cómo buscar:*\n\n";
        $text5 .= "1️⃣ En cualquier chat de Telegram, escribe:\n";
        $text5 .= "   `@nombre_del_bot nombre_o_cedula`\n\n";
        $text5 .= "2️⃣ Aparecerá una lista de resultados\n\n";
        $text5 .= "3️⃣ Toca el resultado que deseas\n\n";
        $text5 .= "4️⃣ Se enviará la información del beneficiario\n\n";
        $text5 .= "🔎 *Puedes buscar por:*\n";
        $text5 .= "• Nombre del beneficiario\n";
        $text5 .= "• Apellido\n";
        $text5 .= "• Número de cédula\n\n";
        $text5 .= "💡 *Ventaja:*\n";
        $text5 .= "Puedes usar esta búsqueda en cualquier chat para compartir información de beneficiarios rápidamente con otros usuarios.";
        
        $this->replyWithMessage([
            'text' => $text5,
            'parse_mode' => 'Markdown',
        ]);
        
        // Mensaje 6: Comandos y botones
        $text6 = "⌨️ *COMANDOS Y BOTONES*\n\n";
        $text6 .= "🔘 *Botones del Teclado:*\n\n";
        $text6 .= "Los botones permanentes en la parte inferior son:\n\n";
        $text6 .= "• 📍 Parroquia Sabana Libre\n";
        $text6 .= "• 📍 Parroquia La Unión\n";
        $text6 .= "• 📍 Parroquia Santa Rita\n";
        $text6 .= "• 📍 Parroquia Escuque\n";
        $text6 .= "• 📊 Estadísticas (globales)\n";
        $text6 .= "• ❓ Ayuda (este mensaje)\n\n";
        $text6 .= "💬 *Comandos de Texto:*\n\n";
        $text6 .= "`/start` - Iniciar el bot\n";
        $text6 .= "`/menu` - Ver menú principal\n";
        $text6 .= "`/stats` - Ver estadísticas globales\n";
        $text6 .= "`/help` - Ver esta guía\n";
        $text6 .= "`/logout` - Cerrar sesión\n\n";
        $text6 .= "💡 *Recomendación:*\n";
        $text6 .= "Usa los botones del teclado, son más rápidos y fáciles que escribir comandos.";
        
        $this->replyWithMessage([
            'text' => $text6,
            'parse_mode' => 'Markdown',
        ]);
        
        // Mensaje 7: Tips y solución de problemas
        $text7 = "💡 *TIPS Y SOLUCIÓN DE PROBLEMAS*\n\n";
        $text7 .= "🎯 *Consejos útiles:*\n\n";
        $text7 .= "• Si no ves los botones del teclado, presiona el ícono de teclado 🎹 en la barra de mensajes\n\n";
        $text7 .= "• Los gráficos se generan en tiempo real, pueden tardar unos segundos\n\n";
        $text7 .= "• Puedes usar el bot en cualquier momento, los datos están siempre actualizados\n\n";
        $text7 .= "• Si necesitas volver al menú principal, usa `/menu` o el botón correspondiente\n\n";
        $text7 .= "⚠️ *¿Problemas?*\n\n";
        $text7 .= "• *No puedo acceder:* Asegúrate de haber iniciado sesión con `/login`\n\n";
        $text7 .= "• *No veo datos:* Verifica que haya información registrada en el sistema\n\n";
        $text7 .= "• *El bot no responde:* Espera unos segundos e intenta de nuevo\n\n";
        $text7 .= "• *Error general:* Contacta al administrador del sistema\n\n";
        $text7 .= "🔐 *Seguridad:*\n\n";
        $text7 .= "• Tu sesión está vinculada a este chat de Telegram\n";
        $text7 .= "• Usa `/logout` para cerrar sesión cuando termines\n";
        $text7 .= "• No compartas tu acceso con otras personas\n\n";
        $text7 .= "📞 *¿Necesitas más ayuda?*\n";
        $text7 .= "Contacta al administrador del sistema para soporte técnico.\n\n";
        $text7 .= "✅ *¡Listo! Ahora estás preparado para usar el bot.*\n";
        $text7 .= "Usa `/menu` para empezar.";
        
        $this->replyWithMessage([
            'text' => $text7,
            'parse_mode' => 'Markdown',
        ]);
        
        // Registrar actividad
        self::logTelegramActivity(
            'Consultó la guía de ayuda completa',
            [
                'command' => 'help',
            ],
            $telegramUser
        );
    }
}
