<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParroquiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        

        \DB::table('parroquias')->delete();
        
        \DB::table('parroquias')->insert(array (
            0 => 
            array (
                'id' => 1,
                'municipio_id' => 1,
                'parroquia' => 'Alto Orinoco',
            ),
            1 => 
            array (
                'id' => 2,
                'municipio_id' => 1,
                'parroquia' => 'Huachamacare Acanaña',
            ),
            2 => 
            array (
                'id' => 3,
                'municipio_id' => 1,
                'parroquia' => 'Marawaka Toky Shamanaña',
            ),
            3 => 
            array (
                'id' => 4,
                'municipio_id' => 1,
                'parroquia' => 'Mavaka Mavaka',
            ),
            4 => 
            array (
                'id' => 5,
                'municipio_id' => 1,
                'parroquia' => 'Sierra Parima Parimabé',
            ),
            5 => 
            array (
                'id' => 6,
                'municipio_id' => 2,
                'parroquia' => 'Ucata Laja Lisa',
            ),
            6 => 
            array (
                'id' => 7,
                'municipio_id' => 2,
                'parroquia' => 'Yapacana Macuruco',
            ),
            7 => 
            array (
                'id' => 8,
                'municipio_id' => 2,
                'parroquia' => 'Caname Guarinuma',
            ),
            8 => 
            array (
                'id' => 9,
                'municipio_id' => 3,
                'parroquia' => 'Fernando Girón Tovar',
            ),
            9 => 
            array (
                'id' => 10,
                'municipio_id' => 3,
                'parroquia' => 'Luis Alberto Gómez',
            ),
            10 => 
            array (
                'id' => 11,
                'municipio_id' => 3,
                'parroquia' => 'Pahueña Limón de Parhueña',
            ),
            11 => 
            array (
                'id' => 12,
                'municipio_id' => 3,
                'parroquia' => 'Platanillal Platanillal',
            ),
            12 => 
            array (
                'id' => 13,
                'municipio_id' => 4,
                'parroquia' => 'Samariapo',
            ),
            13 => 
            array (
                'id' => 14,
                'municipio_id' => 4,
                'parroquia' => 'Sipapo',
            ),
            14 => 
            array (
                'id' => 15,
                'municipio_id' => 4,
                'parroquia' => 'Munduapo',
            ),
            15 => 
            array (
                'id' => 16,
                'municipio_id' => 4,
                'parroquia' => 'Guayapo',
            ),
            16 => 
            array (
                'id' => 17,
                'municipio_id' => 5,
                'parroquia' => 'Alto Ventuari',
            ),
            17 => 
            array (
                'id' => 18,
                'municipio_id' => 5,
                'parroquia' => 'Medio Ventuari',
            ),
            18 => 
            array (
                'id' => 19,
                'municipio_id' => 5,
                'parroquia' => 'Bajo Ventuari',
            ),
            19 => 
            array (
                'id' => 20,
                'municipio_id' => 6,
                'parroquia' => 'Victorino',
            ),
            20 => 
            array (
                'id' => 21,
                'municipio_id' => 6,
                'parroquia' => 'Comunidad',
            ),
            21 => 
            array (
                'id' => 22,
                'municipio_id' => 7,
                'parroquia' => 'Casiquiare',
            ),
            22 => 
            array (
                'id' => 23,
                'municipio_id' => 7,
                'parroquia' => 'Cocuy',
            ),
            23 => 
            array (
                'id' => 24,
                'municipio_id' => 7,
                'parroquia' => 'San Carlos de Río Negro',
            ),
            24 => 
            array (
                'id' => 25,
                'municipio_id' => 7,
                'parroquia' => 'Solano',
            ),
            25 => 
            array (
                'id' => 26,
                'municipio_id' => 8,
                'parroquia' => 'Anaco',
            ),
            26 => 
            array (
                'id' => 27,
                'municipio_id' => 8,
                'parroquia' => 'San Joaquín',
            ),
            27 => 
            array (
                'id' => 28,
                'municipio_id' => 9,
                'parroquia' => 'Cachipo',
            ),
            28 => 
            array (
                'id' => 29,
                'municipio_id' => 9,
                'parroquia' => 'Aragua de Barcelona',
            ),
            29 => 
            array (
                'id' => 30,
                'municipio_id' => 11,
                'parroquia' => 'Lechería',
            ),
            30 => 
            array (
                'id' => 31,
                'municipio_id' => 11,
                'parroquia' => 'El Morro',
            ),
            31 => 
            array (
                'id' => 32,
                'municipio_id' => 12,
                'parroquia' => 'Puerto Píritu',
            ),
            32 => 
            array (
                'id' => 33,
                'municipio_id' => 12,
                'parroquia' => 'San Miguel',
            ),
            33 => 
            array (
                'id' => 34,
                'municipio_id' => 12,
                'parroquia' => 'Sucre',
            ),
            34 => 
            array (
                'id' => 35,
                'municipio_id' => 13,
                'parroquia' => 'Valle de Guanape',
            ),
            35 => 
            array (
                'id' => 36,
                'municipio_id' => 13,
                'parroquia' => 'Santa Bárbara',
            ),
            36 => 
            array (
                'id' => 37,
                'municipio_id' => 14,
                'parroquia' => 'El Chaparro',
            ),
            37 => 
            array (
                'id' => 38,
                'municipio_id' => 14,
                'parroquia' => 'Tomás Alfaro',
            ),
            38 => 
            array (
                'id' => 39,
                'municipio_id' => 14,
                'parroquia' => 'Calatrava',
            ),
            39 => 
            array (
                'id' => 40,
                'municipio_id' => 15,
                'parroquia' => 'Guanta',
            ),
            40 => 
            array (
                'id' => 41,
                'municipio_id' => 15,
                'parroquia' => 'Chorrerón',
            ),
            41 => 
            array (
                'id' => 42,
                'municipio_id' => 16,
                'parroquia' => 'Mamo',
            ),
            42 => 
            array (
                'id' => 43,
                'municipio_id' => 16,
                'parroquia' => 'Soledad',
            ),
            43 => 
            array (
                'id' => 44,
                'municipio_id' => 17,
                'parroquia' => 'Mapire',
            ),
            44 => 
            array (
                'id' => 45,
                'municipio_id' => 17,
                'parroquia' => 'Piar',
            ),
            45 => 
            array (
                'id' => 46,
                'municipio_id' => 17,
                'parroquia' => 'Santa Clara',
            ),
            46 => 
            array (
                'id' => 47,
                'municipio_id' => 17,
                'parroquia' => 'San Diego de Cabrutica',
            ),
            47 => 
            array (
                'id' => 48,
                'municipio_id' => 17,
                'parroquia' => 'Uverito',
            ),
            48 => 
            array (
                'id' => 49,
                'municipio_id' => 17,
                'parroquia' => 'Zuata',
            ),
            49 => 
            array (
                'id' => 50,
                'municipio_id' => 18,
                'parroquia' => 'Puerto La Cruz',
            ),
            50 => 
            array (
                'id' => 51,
                'municipio_id' => 18,
                'parroquia' => 'Pozuelos',
            ),
            51 => 
            array (
                'id' => 52,
                'municipio_id' => 19,
                'parroquia' => 'Onoto',
            ),
            52 => 
            array (
                'id' => 53,
                'municipio_id' => 19,
                'parroquia' => 'San Pablo',
            ),
            53 => 
            array (
                'id' => 54,
                'municipio_id' => 20,
                'parroquia' => 'San Mateo',
            ),
            54 => 
            array (
                'id' => 55,
                'municipio_id' => 20,
                'parroquia' => 'El Carito',
            ),
            55 => 
            array (
                'id' => 56,
                'municipio_id' => 20,
                'parroquia' => 'Santa Inés',
            ),
            56 => 
            array (
                'id' => 57,
                'municipio_id' => 20,
                'parroquia' => 'La Romereña',
            ),
            57 => 
            array (
                'id' => 58,
                'municipio_id' => 21,
                'parroquia' => 'Atapirire',
            ),
            58 => 
            array (
                'id' => 59,
                'municipio_id' => 21,
                'parroquia' => 'Boca del Pao',
            ),
            59 => 
            array (
                'id' => 60,
                'municipio_id' => 21,
                'parroquia' => 'El Pao',
            ),
            60 => 
            array (
                'id' => 61,
                'municipio_id' => 21,
                'parroquia' => 'Pariaguán',
            ),
            61 => 
            array (
                'id' => 62,
                'municipio_id' => 22,
                'parroquia' => 'Cantaura',
            ),
            62 => 
            array (
                'id' => 63,
                'municipio_id' => 22,
                'parroquia' => 'Libertador',
            ),
            63 => 
            array (
                'id' => 64,
                'municipio_id' => 22,
                'parroquia' => 'Santa Rosa',
            ),
            64 => 
            array (
                'id' => 65,
                'municipio_id' => 22,
                'parroquia' => 'Urica',
            ),
            65 => 
            array (
                'id' => 66,
                'municipio_id' => 23,
                'parroquia' => 'Píritu',
            ),
            66 => 
            array (
                'id' => 67,
                'municipio_id' => 23,
                'parroquia' => 'San Francisco',
            ),
            67 => 
            array (
                'id' => 68,
                'municipio_id' => 24,
                'parroquia' => 'San José de Guanipa',
            ),
            68 => 
            array (
                'id' => 69,
                'municipio_id' => 25,
                'parroquia' => 'Boca de Uchire',
            ),
            69 => 
            array (
                'id' => 70,
                'municipio_id' => 25,
                'parroquia' => 'Boca de Chávez',
            ),
            70 => 
            array (
                'id' => 71,
                'municipio_id' => 26,
                'parroquia' => 'Pueblo Nuevo',
            ),
            71 => 
            array (
                'id' => 72,
                'municipio_id' => 26,
                'parroquia' => 'Santa Ana',
            ),
            72 => 
            array (
                'id' => 73,
                'municipio_id' => 27,
                'parroquia' => 'Bergantín',
            ),
            73 => 
            array (
                'id' => 74,
                'municipio_id' => 27,
                'parroquia' => 'Caigua',
            ),
            74 => 
            array (
                'id' => 75,
                'municipio_id' => 27,
                'parroquia' => 'El Carmen',
            ),
            75 => 
            array (
                'id' => 76,
                'municipio_id' => 27,
                'parroquia' => 'El Pilar',
            ),
            76 => 
            array (
                'id' => 77,
                'municipio_id' => 27,
                'parroquia' => 'Naricual',
            ),
            77 => 
            array (
                'id' => 78,
                'municipio_id' => 27,
                'parroquia' => 'San Crsitóbal',
            ),
            78 => 
            array (
                'id' => 79,
                'municipio_id' => 28,
                'parroquia' => 'Edmundo Barrios',
            ),
            79 => 
            array (
                'id' => 80,
                'municipio_id' => 28,
                'parroquia' => 'Miguel Otero Silva',
            ),
            80 => 
            array (
                'id' => 81,
                'municipio_id' => 29,
                'parroquia' => 'Achaguas',
            ),
            81 => 
            array (
                'id' => 82,
                'municipio_id' => 29,
                'parroquia' => 'Apurito',
            ),
            82 => 
            array (
                'id' => 83,
                'municipio_id' => 29,
                'parroquia' => 'El Yagual',
            ),
            83 => 
            array (
                'id' => 84,
                'municipio_id' => 29,
                'parroquia' => 'Guachara',
            ),
            84 => 
            array (
                'id' => 85,
                'municipio_id' => 29,
                'parroquia' => 'Mucuritas',
            ),
            85 => 
            array (
                'id' => 86,
                'municipio_id' => 29,
                'parroquia' => 'Queseras del medio',
            ),
            86 => 
            array (
                'id' => 87,
                'municipio_id' => 30,
                'parroquia' => 'Biruaca',
            ),
            87 => 
            array (
                'id' => 88,
                'municipio_id' => 31,
                'parroquia' => 'Bruzual',
            ),
            88 => 
            array (
                'id' => 89,
                'municipio_id' => 31,
                'parroquia' => 'Mantecal',
            ),
            89 => 
            array (
                'id' => 90,
                'municipio_id' => 31,
                'parroquia' => 'Quintero',
            ),
            90 => 
            array (
                'id' => 91,
                'municipio_id' => 31,
                'parroquia' => 'Rincón Hondo',
            ),
            91 => 
            array (
                'id' => 92,
                'municipio_id' => 31,
                'parroquia' => 'San Vicente',
            ),
            92 => 
            array (
                'id' => 93,
                'municipio_id' => 32,
                'parroquia' => 'Guasdualito',
            ),
            93 => 
            array (
                'id' => 94,
                'municipio_id' => 32,
                'parroquia' => 'Aramendi',
            ),
            94 => 
            array (
                'id' => 95,
                'municipio_id' => 32,
                'parroquia' => 'El Amparo',
            ),
            95 => 
            array (
                'id' => 96,
                'municipio_id' => 32,
                'parroquia' => 'San Camilo',
            ),
            96 => 
            array (
                'id' => 97,
                'municipio_id' => 32,
                'parroquia' => 'Urdaneta',
            ),
            97 => 
            array (
                'id' => 98,
                'municipio_id' => 33,
                'parroquia' => 'San Juan de Payara',
            ),
            98 => 
            array (
                'id' => 99,
                'municipio_id' => 33,
                'parroquia' => 'Codazzi',
            ),
            99 => 
            array (
                'id' => 100,
                'municipio_id' => 33,
                'parroquia' => 'Cunaviche',
            ),
            100 => 
            array (
                'id' => 101,
                'municipio_id' => 34,
                'parroquia' => 'Elorza',
            ),
            101 => 
            array (
                'id' => 102,
                'municipio_id' => 34,
                'parroquia' => 'La Trinidad',
            ),
            102 => 
            array (
                'id' => 103,
                'municipio_id' => 35,
                'parroquia' => 'San Fernando',
            ),
            103 => 
            array (
                'id' => 104,
                'municipio_id' => 35,
                'parroquia' => 'El Recreo',
            ),
            104 => 
            array (
                'id' => 105,
                'municipio_id' => 35,
                'parroquia' => 'Peñalver',
            ),
            105 => 
            array (
                'id' => 106,
                'municipio_id' => 35,
                'parroquia' => 'San Rafael de Atamaica',
            ),
            106 => 
            array (
                'id' => 107,
                'municipio_id' => 36,
                'parroquia' => 'Pedro José Ovalles',
            ),
            107 => 
            array (
                'id' => 108,
                'municipio_id' => 36,
                'parroquia' => 'Joaquín Crespo',
            ),
            108 => 
            array (
                'id' => 109,
                'municipio_id' => 36,
                'parroquia' => 'José Casanova Godoy',
            ),
            109 => 
            array (
                'id' => 110,
                'municipio_id' => 36,
                'parroquia' => 'Madre María de San José',
            ),
            110 => 
            array (
                'id' => 111,
                'municipio_id' => 36,
                'parroquia' => 'Andrés Eloy Blanco',
            ),
            111 => 
            array (
                'id' => 112,
                'municipio_id' => 36,
                'parroquia' => 'Los Tacarigua',
            ),
            112 => 
            array (
                'id' => 113,
                'municipio_id' => 36,
                'parroquia' => 'Las Delicias',
            ),
            113 => 
            array (
                'id' => 114,
                'municipio_id' => 36,
                'parroquia' => 'Choroní',
            ),
            114 => 
            array (
                'id' => 115,
                'municipio_id' => 37,
                'parroquia' => 'Bolívar',
            ),
            115 => 
            array (
                'id' => 116,
                'municipio_id' => 38,
                'parroquia' => 'Camatagua',
            ),
            116 => 
            array (
                'id' => 117,
                'municipio_id' => 38,
                'parroquia' => 'Carmen de Cura',
            ),
            117 => 
            array (
                'id' => 118,
                'municipio_id' => 39,
                'parroquia' => 'Santa Rita',
            ),
            118 => 
            array (
                'id' => 119,
                'municipio_id' => 39,
                'parroquia' => 'Francisco de Miranda',
            ),
            119 => 
            array (
                'id' => 120,
                'municipio_id' => 39,
                'parroquia' => 'Moseñor Feliciano González',
            ),
            120 => 
            array (
                'id' => 121,
                'municipio_id' => 40,
                'parroquia' => 'Santa Cruz',
            ),
            121 => 
            array (
                'id' => 122,
                'municipio_id' => 41,
                'parroquia' => 'José Félix Ribas',
            ),
            122 => 
            array (
                'id' => 123,
                'municipio_id' => 41,
                'parroquia' => 'Castor Nieves Ríos',
            ),
            123 => 
            array (
                'id' => 124,
                'municipio_id' => 41,
                'parroquia' => 'Las Guacamayas',
            ),
            124 => 
            array (
                'id' => 125,
                'municipio_id' => 41,
                'parroquia' => 'Pao de Zárate',
            ),
            125 => 
            array (
                'id' => 126,
                'municipio_id' => 41,
                'parroquia' => 'Zuata',
            ),
            126 => 
            array (
                'id' => 127,
                'municipio_id' => 42,
                'parroquia' => 'José Rafael Revenga',
            ),
            127 => 
            array (
                'id' => 128,
                'municipio_id' => 43,
                'parroquia' => 'Palo Negro',
            ),
            128 => 
            array (
                'id' => 129,
                'municipio_id' => 43,
                'parroquia' => 'San Martín de Porres',
            ),
            129 => 
            array (
                'id' => 130,
                'municipio_id' => 44,
                'parroquia' => 'El Limón',
            ),
            130 => 
            array (
                'id' => 131,
                'municipio_id' => 44,
                'parroquia' => 'Caña de Azúcar',
            ),
            131 => 
            array (
                'id' => 132,
                'municipio_id' => 45,
                'parroquia' => 'Ocumare de la Costa',
            ),
            132 => 
            array (
                'id' => 133,
                'municipio_id' => 46,
                'parroquia' => 'San Casimiro',
            ),
            133 => 
            array (
                'id' => 134,
                'municipio_id' => 46,
                'parroquia' => 'Güiripa',
            ),
            134 => 
            array (
                'id' => 135,
                'municipio_id' => 46,
                'parroquia' => 'Ollas de Caramacate',
            ),
            135 => 
            array (
                'id' => 136,
                'municipio_id' => 46,
                'parroquia' => 'Valle Morín',
            ),
            136 => 
            array (
                'id' => 137,
                'municipio_id' => 47,
                'parroquia' => 'San Sebastían',
            ),
            137 => 
            array (
                'id' => 138,
                'municipio_id' => 48,
                'parroquia' => 'Turmero',
            ),
            138 => 
            array (
                'id' => 139,
                'municipio_id' => 48,
                'parroquia' => 'Arevalo Aponte',
            ),
            139 => 
            array (
                'id' => 140,
                'municipio_id' => 48,
                'parroquia' => 'Chuao',
            ),
            140 => 
            array (
                'id' => 141,
                'municipio_id' => 48,
                'parroquia' => 'Samán de Güere',
            ),
            141 => 
            array (
                'id' => 142,
                'municipio_id' => 48,
                'parroquia' => 'Alfredo Pacheco Miranda',
            ),
            142 => 
            array (
                'id' => 143,
                'municipio_id' => 49,
                'parroquia' => 'Santos Michelena',
            ),
            143 => 
            array (
                'id' => 144,
                'municipio_id' => 49,
                'parroquia' => 'Tiara',
            ),
            144 => 
            array (
                'id' => 145,
                'municipio_id' => 50,
                'parroquia' => 'Cagua',
            ),
            145 => 
            array (
                'id' => 146,
                'municipio_id' => 50,
                'parroquia' => 'Bella Vista',
            ),
            146 => 
            array (
                'id' => 147,
                'municipio_id' => 51,
                'parroquia' => 'Tovar',
            ),
            147 => 
            array (
                'id' => 148,
                'municipio_id' => 52,
                'parroquia' => 'Urdaneta',
            ),
            148 => 
            array (
                'id' => 149,
                'municipio_id' => 52,
                'parroquia' => 'Las Peñitas',
            ),
            149 => 
            array (
                'id' => 150,
                'municipio_id' => 52,
                'parroquia' => 'San Francisco de Cara',
            ),
            150 => 
            array (
                'id' => 151,
                'municipio_id' => 52,
                'parroquia' => 'Taguay',
            ),
            151 => 
            array (
                'id' => 152,
                'municipio_id' => 53,
                'parroquia' => 'Zamora',
            ),
            152 => 
            array (
                'id' => 153,
                'municipio_id' => 53,
                'parroquia' => 'Magdaleno',
            ),
            153 => 
            array (
                'id' => 154,
                'municipio_id' => 53,
                'parroquia' => 'San Francisco de Asís',
            ),
            154 => 
            array (
                'id' => 155,
                'municipio_id' => 53,
                'parroquia' => 'Valles de Tucutunemo',
            ),
            155 => 
            array (
                'id' => 156,
                'municipio_id' => 53,
                'parroquia' => 'Augusto Mijares',
            ),
            156 => 
            array (
                'id' => 157,
                'municipio_id' => 54,
                'parroquia' => 'Sabaneta',
            ),
            157 => 
            array (
                'id' => 158,
                'municipio_id' => 54,
                'parroquia' => 'Juan Antonio Rodríguez Domínguez',
            ),
            158 => 
            array (
                'id' => 159,
                'municipio_id' => 55,
                'parroquia' => 'El Cantón',
            ),
            159 => 
            array (
                'id' => 160,
                'municipio_id' => 55,
                'parroquia' => 'Santa Cruz de Guacas',
            ),
            160 => 
            array (
                'id' => 161,
                'municipio_id' => 55,
                'parroquia' => 'Puerto Vivas',
            ),
            161 => 
            array (
                'id' => 162,
                'municipio_id' => 56,
                'parroquia' => 'Ticoporo',
            ),
            162 => 
            array (
                'id' => 163,
                'municipio_id' => 56,
                'parroquia' => 'Nicolás Pulido',
            ),
            163 => 
            array (
                'id' => 164,
                'municipio_id' => 56,
                'parroquia' => 'Andrés Bello',
            ),
            164 => 
            array (
                'id' => 165,
                'municipio_id' => 57,
                'parroquia' => 'Arismendi',
            ),
            165 => 
            array (
                'id' => 166,
                'municipio_id' => 57,
                'parroquia' => 'Guadarrama',
            ),
            166 => 
            array (
                'id' => 167,
                'municipio_id' => 57,
                'parroquia' => 'La Unión',
            ),
            167 => 
            array (
                'id' => 168,
                'municipio_id' => 57,
                'parroquia' => 'San Antonio',
            ),
            168 => 
            array (
                'id' => 169,
                'municipio_id' => 58,
                'parroquia' => 'Barinas',
            ),
            169 => 
            array (
                'id' => 170,
                'municipio_id' => 58,
                'parroquia' => 'Alberto Arvelo Larriva',
            ),
            170 => 
            array (
                'id' => 171,
                'municipio_id' => 58,
                'parroquia' => 'San Silvestre',
            ),
            171 => 
            array (
                'id' => 172,
                'municipio_id' => 58,
                'parroquia' => 'Santa Inés',
            ),
            172 => 
            array (
                'id' => 173,
                'municipio_id' => 58,
                'parroquia' => 'Santa Lucía',
            ),
            173 => 
            array (
                'id' => 174,
                'municipio_id' => 58,
                'parroquia' => 'Torumos',
            ),
            174 => 
            array (
                'id' => 175,
                'municipio_id' => 58,
                'parroquia' => 'El Carmen',
            ),
            175 => 
            array (
                'id' => 176,
                'municipio_id' => 58,
                'parroquia' => 'Rómulo Betancourt',
            ),
            176 => 
            array (
                'id' => 177,
                'municipio_id' => 58,
                'parroquia' => 'Corazón de Jesús',
            ),
            177 => 
            array (
                'id' => 178,
                'municipio_id' => 58,
                'parroquia' => 'Ramón Ignacio Méndez',
            ),
            178 => 
            array (
                'id' => 179,
                'municipio_id' => 58,
                'parroquia' => 'Alto Barinas',
            ),
            179 => 
            array (
                'id' => 180,
                'municipio_id' => 58,
                'parroquia' => 'Manuel Palacio Fajardo',
            ),
            180 => 
            array (
                'id' => 181,
                'municipio_id' => 58,
                'parroquia' => 'Juan Antonio Rodríguez Domínguez',
            ),
            181 => 
            array (
                'id' => 182,
                'municipio_id' => 58,
                'parroquia' => 'Dominga Ortiz de Páez',
            ),
            182 => 
            array (
                'id' => 183,
                'municipio_id' => 59,
                'parroquia' => 'Barinitas',
            ),
            183 => 
            array (
                'id' => 184,
                'municipio_id' => 59,
                'parroquia' => 'Altamira de Cáceres',
            ),
            184 => 
            array (
                'id' => 185,
                'municipio_id' => 59,
                'parroquia' => 'Calderas',
            ),
            185 => 
            array (
                'id' => 186,
                'municipio_id' => 60,
                'parroquia' => 'Barrancas',
            ),
            186 => 
            array (
                'id' => 187,
                'municipio_id' => 60,
                'parroquia' => 'El Socorro',
            ),
            187 => 
            array (
                'id' => 188,
                'municipio_id' => 60,
                'parroquia' => 'Mazparrito',
            ),
            188 => 
            array (
                'id' => 189,
                'municipio_id' => 61,
                'parroquia' => 'Santa Bárbara',
            ),
            189 => 
            array (
                'id' => 190,
                'municipio_id' => 61,
                'parroquia' => 'Pedro Briceño Méndez',
            ),
            190 => 
            array (
                'id' => 191,
                'municipio_id' => 61,
                'parroquia' => 'Ramón Ignacio Méndez',
            ),
            191 => 
            array (
                'id' => 192,
                'municipio_id' => 61,
                'parroquia' => 'José Ignacio del Pumar',
            ),
            192 => 
            array (
                'id' => 193,
                'municipio_id' => 62,
                'parroquia' => 'Obispos',
            ),
            193 => 
            array (
                'id' => 194,
                'municipio_id' => 62,
                'parroquia' => 'Guasimitos',
            ),
            194 => 
            array (
                'id' => 195,
                'municipio_id' => 62,
                'parroquia' => 'El Real',
            ),
            195 => 
            array (
                'id' => 196,
                'municipio_id' => 62,
                'parroquia' => 'La Luz',
            ),
            196 => 
            array (
                'id' => 197,
                'municipio_id' => 63,
                'parroquia' => 'Ciudad Bolívia',
            ),
            197 => 
            array (
                'id' => 198,
                'municipio_id' => 63,
                'parroquia' => 'José Ignacio Briceño',
            ),
            198 => 
            array (
                'id' => 199,
                'municipio_id' => 63,
                'parroquia' => 'José Félix Ribas',
            ),
            199 => 
            array (
                'id' => 200,
                'municipio_id' => 63,
                'parroquia' => 'Páez',
            ),
            200 => 
            array (
                'id' => 201,
                'municipio_id' => 64,
                'parroquia' => 'Libertad',
            ),
            201 => 
            array (
                'id' => 202,
                'municipio_id' => 64,
                'parroquia' => 'Dolores',
            ),
            202 => 
            array (
                'id' => 203,
                'municipio_id' => 64,
                'parroquia' => 'Santa Rosa',
            ),
            203 => 
            array (
                'id' => 204,
                'municipio_id' => 64,
                'parroquia' => 'Palacio Fajardo',
            ),
            204 => 
            array (
                'id' => 205,
                'municipio_id' => 65,
                'parroquia' => 'Ciudad de Nutrias',
            ),
            205 => 
            array (
                'id' => 206,
                'municipio_id' => 65,
                'parroquia' => 'El Regalo',
            ),
            206 => 
            array (
                'id' => 207,
                'municipio_id' => 65,
                'parroquia' => 'Puerto Nutrias',
            ),
            207 => 
            array (
                'id' => 208,
                'municipio_id' => 65,
                'parroquia' => 'Santa Catalina',
            ),
            208 => 
            array (
                'id' => 209,
                'municipio_id' => 66,
                'parroquia' => 'Cachamay',
            ),
            209 => 
            array (
                'id' => 210,
                'municipio_id' => 66,
                'parroquia' => 'Chirica',
            ),
            210 => 
            array (
                'id' => 211,
                'municipio_id' => 66,
                'parroquia' => 'Dalla Costa',
            ),
            211 => 
            array (
                'id' => 212,
                'municipio_id' => 66,
                'parroquia' => 'Once de Abril',
            ),
            212 => 
            array (
                'id' => 213,
                'municipio_id' => 66,
                'parroquia' => 'Simón Bolívar',
            ),
            213 => 
            array (
                'id' => 214,
                'municipio_id' => 66,
                'parroquia' => 'Unare',
            ),
            214 => 
            array (
                'id' => 215,
                'municipio_id' => 66,
                'parroquia' => 'Universidad',
            ),
            215 => 
            array (
                'id' => 216,
                'municipio_id' => 66,
                'parroquia' => 'Vista al Sol',
            ),
            216 => 
            array (
                'id' => 217,
                'municipio_id' => 66,
                'parroquia' => 'Pozo Verde',
            ),
            217 => 
            array (
                'id' => 218,
                'municipio_id' => 66,
                'parroquia' => 'Yocoima',
            ),
            218 => 
            array (
                'id' => 219,
                'municipio_id' => 66,
                'parroquia' => '5 de Julio',
            ),
            219 => 
            array (
                'id' => 220,
                'municipio_id' => 67,
                'parroquia' => 'Cedeño',
            ),
            220 => 
            array (
                'id' => 221,
                'municipio_id' => 67,
                'parroquia' => 'Altagracia',
            ),
            221 => 
            array (
                'id' => 222,
                'municipio_id' => 67,
                'parroquia' => 'Ascensión Farreras',
            ),
            222 => 
            array (
                'id' => 223,
                'municipio_id' => 67,
                'parroquia' => 'Guaniamo',
            ),
            223 => 
            array (
                'id' => 224,
                'municipio_id' => 67,
                'parroquia' => 'La Urbana',
            ),
            224 => 
            array (
                'id' => 225,
                'municipio_id' => 67,
                'parroquia' => 'Pijiguaos',
            ),
            225 => 
            array (
                'id' => 226,
                'municipio_id' => 68,
                'parroquia' => 'El Callao',
            ),
            226 => 
            array (
                'id' => 227,
                'municipio_id' => 69,
                'parroquia' => 'Gran Sabana',
            ),
            227 => 
            array (
                'id' => 228,
                'municipio_id' => 69,
                'parroquia' => 'Ikabarú',
            ),
            228 => 
            array (
                'id' => 229,
                'municipio_id' => 70,
                'parroquia' => 'Catedral',
            ),
            229 => 
            array (
                'id' => 230,
                'municipio_id' => 70,
                'parroquia' => 'Zea',
            ),
            230 => 
            array (
                'id' => 231,
                'municipio_id' => 70,
                'parroquia' => 'Orinoco',
            ),
            231 => 
            array (
                'id' => 232,
                'municipio_id' => 70,
                'parroquia' => 'José Antonio Páez',
            ),
            232 => 
            array (
                'id' => 233,
                'municipio_id' => 70,
                'parroquia' => 'Marhuanta',
            ),
            233 => 
            array (
                'id' => 234,
                'municipio_id' => 70,
                'parroquia' => 'Agua Salada',
            ),
            234 => 
            array (
                'id' => 235,
                'municipio_id' => 70,
                'parroquia' => 'Vista Hermosa',
            ),
            235 => 
            array (
                'id' => 236,
                'municipio_id' => 70,
                'parroquia' => 'La Sabanita',
            ),
            236 => 
            array (
                'id' => 237,
                'municipio_id' => 70,
                'parroquia' => 'Panapana',
            ),
            237 => 
            array (
                'id' => 238,
                'municipio_id' => 71,
                'parroquia' => 'Andrés Eloy Blanco',
            ),
            238 => 
            array (
                'id' => 239,
                'municipio_id' => 71,
                'parroquia' => 'Pedro Cova',
            ),
            239 => 
            array (
                'id' => 240,
                'municipio_id' => 72,
                'parroquia' => 'Raúl Leoni',
            ),
            240 => 
            array (
                'id' => 241,
                'municipio_id' => 72,
                'parroquia' => 'Barceloneta',
            ),
            241 => 
            array (
                'id' => 242,
                'municipio_id' => 72,
                'parroquia' => 'Santa Bárbara',
            ),
            242 => 
            array (
                'id' => 243,
                'municipio_id' => 72,
                'parroquia' => 'San Francisco',
            ),
            243 => 
            array (
                'id' => 244,
                'municipio_id' => 73,
                'parroquia' => 'Roscio',
            ),
            244 => 
            array (
                'id' => 245,
                'municipio_id' => 73,
                'parroquia' => 'Salóm',
            ),
            245 => 
            array (
                'id' => 246,
                'municipio_id' => 74,
                'parroquia' => 'Sifontes',
            ),
            246 => 
            array (
                'id' => 247,
                'municipio_id' => 74,
                'parroquia' => 'Dalla Costa',
            ),
            247 => 
            array (
                'id' => 248,
                'municipio_id' => 74,
                'parroquia' => 'San Isidro',
            ),
            248 => 
            array (
                'id' => 249,
                'municipio_id' => 75,
                'parroquia' => 'Sucre',
            ),
            249 => 
            array (
                'id' => 250,
                'municipio_id' => 75,
                'parroquia' => 'Aripao',
            ),
            250 => 
            array (
                'id' => 251,
                'municipio_id' => 75,
                'parroquia' => 'Guarataro',
            ),
            251 => 
            array (
                'id' => 252,
                'municipio_id' => 75,
                'parroquia' => 'Las Majadas',
            ),
            252 => 
            array (
                'id' => 253,
                'municipio_id' => 75,
                'parroquia' => 'Moitaco',
            ),
            253 => 
            array (
                'id' => 254,
                'municipio_id' => 76,
                'parroquia' => 'Padre Pedro Chien',
            ),
            254 => 
            array (
                'id' => 255,
                'municipio_id' => 76,
                'parroquia' => 'Río Grande',
            ),
            255 => 
            array (
                'id' => 256,
                'municipio_id' => 77,
                'parroquia' => 'Bejuma',
            ),
            256 => 
            array (
                'id' => 257,
                'municipio_id' => 77,
                'parroquia' => 'Canoabo',
            ),
            257 => 
            array (
                'id' => 258,
                'municipio_id' => 77,
                'parroquia' => 'Simón Bolívar',
            ),
            258 => 
            array (
                'id' => 259,
                'municipio_id' => 78,
                'parroquia' => 'Güigüe',
            ),
            259 => 
            array (
                'id' => 260,
                'municipio_id' => 78,
                'parroquia' => 'Carabobo',
            ),
            260 => 
            array (
                'id' => 261,
                'municipio_id' => 78,
                'parroquia' => 'Tacarigua',
            ),
            261 => 
            array (
                'id' => 262,
                'municipio_id' => 79,
                'parroquia' => 'Mariara',
            ),
            262 => 
            array (
                'id' => 263,
                'municipio_id' => 79,
                'parroquia' => 'Aguas Calientes',
            ),
            263 => 
            array (
                'id' => 264,
                'municipio_id' => 80,
                'parroquia' => 'Ciudad Alianza',
            ),
            264 => 
            array (
                'id' => 265,
                'municipio_id' => 80,
                'parroquia' => 'Guacara',
            ),
            265 => 
            array (
                'id' => 266,
                'municipio_id' => 80,
                'parroquia' => 'Yagua',
            ),
            266 => 
            array (
                'id' => 267,
                'municipio_id' => 81,
                'parroquia' => 'Morón',
            ),
            267 => 
            array (
                'id' => 268,
                'municipio_id' => 81,
                'parroquia' => 'Yagua',
            ),
            268 => 
            array (
                'id' => 269,
                'municipio_id' => 82,
                'parroquia' => 'Tocuyito',
            ),
            269 => 
            array (
                'id' => 270,
                'municipio_id' => 82,
                'parroquia' => 'Independencia',
            ),
            270 => 
            array (
                'id' => 271,
                'municipio_id' => 83,
                'parroquia' => 'Los Guayos',
            ),
            271 => 
            array (
                'id' => 272,
                'municipio_id' => 84,
                'parroquia' => 'Miranda',
            ),
            272 => 
            array (
                'id' => 273,
                'municipio_id' => 85,
                'parroquia' => 'Montalbán',
            ),
            273 => 
            array (
                'id' => 274,
                'municipio_id' => 86,
                'parroquia' => 'Naguanagua',
            ),
            274 => 
            array (
                'id' => 275,
                'municipio_id' => 87,
                'parroquia' => 'Bartolomé Salóm',
            ),
            275 => 
            array (
                'id' => 276,
                'municipio_id' => 87,
                'parroquia' => 'Democracia',
            ),
            276 => 
            array (
                'id' => 277,
                'municipio_id' => 87,
                'parroquia' => 'Fraternidad',
            ),
            277 => 
            array (
                'id' => 278,
                'municipio_id' => 87,
                'parroquia' => 'Goaigoaza',
            ),
            278 => 
            array (
                'id' => 279,
                'municipio_id' => 87,
                'parroquia' => 'Juan José Flores',
            ),
            279 => 
            array (
                'id' => 280,
                'municipio_id' => 87,
                'parroquia' => 'Unión',
            ),
            280 => 
            array (
                'id' => 281,
                'municipio_id' => 87,
                'parroquia' => 'Borburata',
            ),
            281 => 
            array (
                'id' => 282,
                'municipio_id' => 87,
                'parroquia' => 'Patanemo',
            ),
            282 => 
            array (
                'id' => 283,
                'municipio_id' => 88,
                'parroquia' => 'San Diego',
            ),
            283 => 
            array (
                'id' => 284,
                'municipio_id' => 89,
                'parroquia' => 'San Joaquín',
            ),
            284 => 
            array (
                'id' => 285,
                'municipio_id' => 90,
                'parroquia' => 'Candelaria',
            ),
            285 => 
            array (
                'id' => 286,
                'municipio_id' => 90,
                'parroquia' => 'Catedral',
            ),
            286 => 
            array (
                'id' => 287,
                'municipio_id' => 90,
                'parroquia' => 'El Socorro',
            ),
            287 => 
            array (
                'id' => 288,
                'municipio_id' => 90,
                'parroquia' => 'Miguel Peña',
            ),
            288 => 
            array (
                'id' => 289,
                'municipio_id' => 90,
                'parroquia' => 'Rafael Urdaneta',
            ),
            289 => 
            array (
                'id' => 290,
                'municipio_id' => 90,
                'parroquia' => 'San Blas',
            ),
            290 => 
            array (
                'id' => 291,
                'municipio_id' => 90,
                'parroquia' => 'San José',
            ),
            291 => 
            array (
                'id' => 292,
                'municipio_id' => 90,
                'parroquia' => 'Santa Rosa',
            ),
            292 => 
            array (
                'id' => 293,
                'municipio_id' => 90,
                'parroquia' => 'Negro Primero',
            ),
            293 => 
            array (
                'id' => 294,
                'municipio_id' => 91,
                'parroquia' => 'Cojedes',
            ),
            294 => 
            array (
                'id' => 295,
                'municipio_id' => 91,
                'parroquia' => 'Juan de Mata Suárez',
            ),
            295 => 
            array (
                'id' => 296,
                'municipio_id' => 92,
                'parroquia' => 'Tinaquillo',
            ),
            296 => 
            array (
                'id' => 297,
                'municipio_id' => 93,
                'parroquia' => 'El Baúl',
            ),
            297 => 
            array (
                'id' => 298,
                'municipio_id' => 93,
                'parroquia' => 'Sucre',
            ),
            298 => 
            array (
                'id' => 299,
                'municipio_id' => 94,
                'parroquia' => 'La Aguadita',
            ),
            299 => 
            array (
                'id' => 300,
                'municipio_id' => 94,
                'parroquia' => 'Macapo',
            ),
            300 => 
            array (
                'id' => 301,
                'municipio_id' => 95,
                'parroquia' => 'El Pao',
            ),
            301 => 
            array (
                'id' => 302,
                'municipio_id' => 96,
                'parroquia' => 'El Amparo',
            ),
            302 => 
            array (
                'id' => 303,
                'municipio_id' => 96,
                'parroquia' => 'Libertad de Cojedes',
            ),
            303 => 
            array (
                'id' => 304,
                'municipio_id' => 97,
                'parroquia' => 'Rómulo Gallegos',
            ),
            304 => 
            array (
                'id' => 305,
                'municipio_id' => 98,
                'parroquia' => 'San Carlos de Austria',
            ),
            305 => 
            array (
                'id' => 306,
                'municipio_id' => 98,
                'parroquia' => 'Juan Ángel Bravo',
            ),
            306 => 
            array (
                'id' => 307,
                'municipio_id' => 98,
                'parroquia' => 'Manuel Manrique',
            ),
            307 => 
            array (
                'id' => 308,
                'municipio_id' => 99,
                'parroquia' => 'General en Jefe José Laurencio Silva',
            ),
            308 => 
            array (
                'id' => 309,
                'municipio_id' => 100,
                'parroquia' => 'Curiapo',
            ),
            309 => 
            array (
                'id' => 310,
                'municipio_id' => 100,
                'parroquia' => 'Almirante Luis Brión',
            ),
            310 => 
            array (
                'id' => 311,
                'municipio_id' => 100,
                'parroquia' => 'Francisco Aniceto Lugo',
            ),
            311 => 
            array (
                'id' => 312,
                'municipio_id' => 100,
                'parroquia' => 'Manuel Renaud',
            ),
            312 => 
            array (
                'id' => 313,
                'municipio_id' => 100,
                'parroquia' => 'Padre Barral',
            ),
            313 => 
            array (
                'id' => 314,
                'municipio_id' => 100,
                'parroquia' => 'Santos de Abelgas',
            ),
            314 => 
            array (
                'id' => 315,
                'municipio_id' => 101,
                'parroquia' => 'Imataca',
            ),
            315 => 
            array (
                'id' => 316,
                'municipio_id' => 101,
                'parroquia' => 'Cinco de Julio',
            ),
            316 => 
            array (
                'id' => 317,
                'municipio_id' => 101,
                'parroquia' => 'Juan Bautista Arismendi',
            ),
            317 => 
            array (
                'id' => 318,
                'municipio_id' => 101,
                'parroquia' => 'Manuel Piar',
            ),
            318 => 
            array (
                'id' => 319,
                'municipio_id' => 101,
                'parroquia' => 'Rómulo Gallegos',
            ),
            319 => 
            array (
                'id' => 320,
                'municipio_id' => 102,
                'parroquia' => 'Pedernales',
            ),
            320 => 
            array (
                'id' => 321,
                'municipio_id' => 102,
                'parroquia' => 'Luis Beltrán Prieto Figueroa',
            ),
            321 => 
            array (
                'id' => 322,
                'municipio_id' => 103,
            'parroquia' => 'San José (Delta Amacuro)',
            ),
            322 => 
            array (
                'id' => 323,
                'municipio_id' => 103,
                'parroquia' => 'José Vidal Marcano',
            ),
            323 => 
            array (
                'id' => 324,
                'municipio_id' => 103,
                'parroquia' => 'Juan Millán',
            ),
            324 => 
            array (
                'id' => 325,
                'municipio_id' => 103,
                'parroquia' => 'Leonardo Ruíz Pineda',
            ),
            325 => 
            array (
                'id' => 326,
                'municipio_id' => 103,
                'parroquia' => 'Mariscal Antonio José de Sucre',
            ),
            326 => 
            array (
                'id' => 327,
                'municipio_id' => 103,
                'parroquia' => 'Monseñor Argimiro García',
            ),
            327 => 
            array (
                'id' => 328,
                'municipio_id' => 103,
            'parroquia' => 'San Rafael (Delta Amacuro)',
            ),
            328 => 
            array (
                'id' => 329,
                'municipio_id' => 103,
                'parroquia' => 'Virgen del Valle',
            ),
            329 => 
            array (
                'id' => 330,
                'municipio_id' => 10,
                'parroquia' => 'Clarines',
            ),
            330 => 
            array (
                'id' => 331,
                'municipio_id' => 10,
                'parroquia' => 'Guanape',
            ),
            331 => 
            array (
                'id' => 332,
                'municipio_id' => 10,
                'parroquia' => 'Sabana de Uchire',
            ),
            332 => 
            array (
                'id' => 333,
                'municipio_id' => 104,
                'parroquia' => 'Capadare',
            ),
            333 => 
            array (
                'id' => 334,
                'municipio_id' => 104,
                'parroquia' => 'La Pastora',
            ),
            334 => 
            array (
                'id' => 335,
                'municipio_id' => 104,
                'parroquia' => 'Libertador',
            ),
            335 => 
            array (
                'id' => 336,
                'municipio_id' => 104,
                'parroquia' => 'San Juan de los Cayos',
            ),
            336 => 
            array (
                'id' => 337,
                'municipio_id' => 105,
                'parroquia' => 'Aracua',
            ),
            337 => 
            array (
                'id' => 338,
                'municipio_id' => 105,
                'parroquia' => 'La Peña',
            ),
            338 => 
            array (
                'id' => 339,
                'municipio_id' => 105,
                'parroquia' => 'San Luis',
            ),
            339 => 
            array (
                'id' => 340,
                'municipio_id' => 106,
                'parroquia' => 'Bariro',
            ),
            340 => 
            array (
                'id' => 341,
                'municipio_id' => 106,
                'parroquia' => 'Borojó',
            ),
            341 => 
            array (
                'id' => 342,
                'municipio_id' => 106,
                'parroquia' => 'Capatárida',
            ),
            342 => 
            array (
                'id' => 343,
                'municipio_id' => 106,
                'parroquia' => 'Guajiro',
            ),
            343 => 
            array (
                'id' => 344,
                'municipio_id' => 106,
                'parroquia' => 'Seque',
            ),
            344 => 
            array (
                'id' => 345,
                'municipio_id' => 106,
                'parroquia' => 'Zazárida',
            ),
            345 => 
            array (
                'id' => 346,
                'municipio_id' => 106,
                'parroquia' => 'Valle de Eroa',
            ),
            346 => 
            array (
                'id' => 347,
                'municipio_id' => 107,
                'parroquia' => 'Cacique Manaure',
            ),
            347 => 
            array (
                'id' => 348,
                'municipio_id' => 108,
                'parroquia' => 'Norte',
            ),
            348 => 
            array (
                'id' => 349,
                'municipio_id' => 108,
                'parroquia' => 'Carirubana',
            ),
            349 => 
            array (
                'id' => 350,
                'municipio_id' => 108,
                'parroquia' => 'Santa Ana',
            ),
            350 => 
            array (
                'id' => 351,
                'municipio_id' => 108,
                'parroquia' => 'Urbana Punta Cardón',
            ),
            351 => 
            array (
                'id' => 352,
                'municipio_id' => 109,
                'parroquia' => 'La Vela de Coro',
            ),
            352 => 
            array (
                'id' => 353,
                'municipio_id' => 109,
                'parroquia' => 'Acurigua',
            ),
            353 => 
            array (
                'id' => 354,
                'municipio_id' => 109,
                'parroquia' => 'Guaibacoa',
            ),
            354 => 
            array (
                'id' => 355,
                'municipio_id' => 109,
                'parroquia' => 'Las Calderas',
            ),
            355 => 
            array (
                'id' => 356,
                'municipio_id' => 109,
                'parroquia' => 'Macoruca',
            ),
            356 => 
            array (
                'id' => 357,
                'municipio_id' => 110,
                'parroquia' => 'Dabajuro',
            ),
            357 => 
            array (
                'id' => 358,
                'municipio_id' => 111,
                'parroquia' => 'Agua Clara',
            ),
            358 => 
            array (
                'id' => 359,
                'municipio_id' => 111,
                'parroquia' => 'Avaria',
            ),
            359 => 
            array (
                'id' => 360,
                'municipio_id' => 111,
                'parroquia' => 'Pedregal',
            ),
            360 => 
            array (
                'id' => 361,
                'municipio_id' => 111,
                'parroquia' => 'Piedra Grande',
            ),
            361 => 
            array (
                'id' => 362,
                'municipio_id' => 111,
                'parroquia' => 'Purureche',
            ),
            362 => 
            array (
                'id' => 363,
                'municipio_id' => 112,
                'parroquia' => 'Adaure',
            ),
            363 => 
            array (
                'id' => 364,
                'municipio_id' => 112,
                'parroquia' => 'Adícora',
            ),
            364 => 
            array (
                'id' => 365,
                'municipio_id' => 112,
                'parroquia' => 'Baraived',
            ),
            365 => 
            array (
                'id' => 366,
                'municipio_id' => 112,
                'parroquia' => 'Buena Vista',
            ),
            366 => 
            array (
                'id' => 367,
                'municipio_id' => 112,
                'parroquia' => 'Jadacaquiva',
            ),
            367 => 
            array (
                'id' => 368,
                'municipio_id' => 112,
                'parroquia' => 'El Vínculo',
            ),
            368 => 
            array (
                'id' => 369,
                'municipio_id' => 112,
                'parroquia' => 'El Hato',
            ),
            369 => 
            array (
                'id' => 370,
                'municipio_id' => 112,
                'parroquia' => 'Moruy',
            ),
            370 => 
            array (
                'id' => 371,
                'municipio_id' => 112,
                'parroquia' => 'Pueblo Nuevo',
            ),
            371 => 
            array (
                'id' => 372,
                'municipio_id' => 113,
                'parroquia' => 'Agua Larga',
            ),
            372 => 
            array (
                'id' => 373,
                'municipio_id' => 113,
                'parroquia' => 'El Paují',
            ),
            373 => 
            array (
                'id' => 374,
                'municipio_id' => 113,
                'parroquia' => 'Independencia',
            ),
            374 => 
            array (
                'id' => 375,
                'municipio_id' => 113,
                'parroquia' => 'Mapararí',
            ),
            375 => 
            array (
                'id' => 376,
                'municipio_id' => 114,
                'parroquia' => 'Agua Linda',
            ),
            376 => 
            array (
                'id' => 377,
                'municipio_id' => 114,
                'parroquia' => 'Araurima',
            ),
            377 => 
            array (
                'id' => 378,
                'municipio_id' => 114,
                'parroquia' => 'Jacura',
            ),
            378 => 
            array (
                'id' => 379,
                'municipio_id' => 115,
                'parroquia' => 'Tucacas',
            ),
            379 => 
            array (
                'id' => 380,
                'municipio_id' => 115,
                'parroquia' => 'Boca de Aroa',
            ),
            380 => 
            array (
                'id' => 381,
                'municipio_id' => 116,
                'parroquia' => 'Los Taques',
            ),
            381 => 
            array (
                'id' => 382,
                'municipio_id' => 116,
                'parroquia' => 'Judibana',
            ),
            382 => 
            array (
                'id' => 383,
                'municipio_id' => 117,
                'parroquia' => 'Mene de Mauroa',
            ),
            383 => 
            array (
                'id' => 384,
                'municipio_id' => 117,
                'parroquia' => 'San Félix',
            ),
            384 => 
            array (
                'id' => 385,
                'municipio_id' => 117,
                'parroquia' => 'Casigua',
            ),
            385 => 
            array (
                'id' => 386,
                'municipio_id' => 118,
                'parroquia' => 'Guzmán Guillermo',
            ),
            386 => 
            array (
                'id' => 387,
                'municipio_id' => 118,
                'parroquia' => 'Mitare',
            ),
            387 => 
            array (
                'id' => 388,
                'municipio_id' => 118,
                'parroquia' => 'Río Seco',
            ),
            388 => 
            array (
                'id' => 389,
                'municipio_id' => 118,
                'parroquia' => 'Sabaneta',
            ),
            389 => 
            array (
                'id' => 390,
                'municipio_id' => 118,
                'parroquia' => 'San Antonio',
            ),
            390 => 
            array (
                'id' => 391,
                'municipio_id' => 118,
                'parroquia' => 'San Gabriel',
            ),
            391 => 
            array (
                'id' => 392,
                'municipio_id' => 118,
                'parroquia' => 'Santa Ana',
            ),
            392 => 
            array (
                'id' => 393,
                'municipio_id' => 119,
                'parroquia' => 'Boca del Tocuyo',
            ),
            393 => 
            array (
                'id' => 394,
                'municipio_id' => 119,
                'parroquia' => 'Chichiriviche',
            ),
            394 => 
            array (
                'id' => 395,
                'municipio_id' => 119,
                'parroquia' => 'Tocuyo de la Costa',
            ),
            395 => 
            array (
                'id' => 396,
                'municipio_id' => 120,
                'parroquia' => 'Palmasola',
            ),
            396 => 
            array (
                'id' => 397,
                'municipio_id' => 121,
                'parroquia' => 'Cabure',
            ),
            397 => 
            array (
                'id' => 398,
                'municipio_id' => 121,
                'parroquia' => 'Colina',
            ),
            398 => 
            array (
                'id' => 399,
                'municipio_id' => 121,
                'parroquia' => 'Curimagua',
            ),
            399 => 
            array (
                'id' => 400,
                'municipio_id' => 122,
                'parroquia' => 'San José de la Costa',
            ),
            400 => 
            array (
                'id' => 401,
                'municipio_id' => 122,
                'parroquia' => 'Píritu',
            ),
            401 => 
            array (
                'id' => 402,
                'municipio_id' => 123,
                'parroquia' => 'San Francisco',
            ),
            402 => 
            array (
                'id' => 403,
                'municipio_id' => 124,
                'parroquia' => 'Sucre',
            ),
            403 => 
            array (
                'id' => 404,
                'municipio_id' => 124,
                'parroquia' => 'Pecaya',
            ),
            404 => 
            array (
                'id' => 405,
                'municipio_id' => 125,
                'parroquia' => 'Tocópero',
            ),
            405 => 
            array (
                'id' => 406,
                'municipio_id' => 126,
                'parroquia' => 'El Charal',
            ),
            406 => 
            array (
                'id' => 407,
                'municipio_id' => 126,
                'parroquia' => 'Las Vegas del Tuy',
            ),
            407 => 
            array (
                'id' => 408,
                'municipio_id' => 126,
                'parroquia' => 'Santa Cruz de Bucaral',
            ),
            408 => 
            array (
                'id' => 409,
                'municipio_id' => 127,
                'parroquia' => 'Bruzual',
            ),
            409 => 
            array (
                'id' => 410,
                'municipio_id' => 127,
                'parroquia' => 'Urumaco',
            ),
            410 => 
            array (
                'id' => 411,
                'municipio_id' => 128,
                'parroquia' => 'Puerto Cumarebo',
            ),
            411 => 
            array (
                'id' => 412,
                'municipio_id' => 128,
                'parroquia' => 'La Ciénaga',
            ),
            412 => 
            array (
                'id' => 413,
                'municipio_id' => 128,
                'parroquia' => 'La Soledad',
            ),
            413 => 
            array (
                'id' => 414,
                'municipio_id' => 128,
                'parroquia' => 'Pueblo Cumarebo',
            ),
            414 => 
            array (
                'id' => 415,
                'municipio_id' => 128,
                'parroquia' => 'Zazárida',
            ),
            415 => 
            array (
                'id' => 416,
                'municipio_id' => 113,
                'parroquia' => 'Churuguara',
            ),
            416 => 
            array (
                'id' => 417,
                'municipio_id' => 129,
                'parroquia' => 'Camaguán',
            ),
            417 => 
            array (
                'id' => 418,
                'municipio_id' => 129,
                'parroquia' => 'Puerto Miranda',
            ),
            418 => 
            array (
                'id' => 419,
                'municipio_id' => 129,
                'parroquia' => 'Uverito',
            ),
            419 => 
            array (
                'id' => 420,
                'municipio_id' => 130,
                'parroquia' => 'Chaguaramas',
            ),
            420 => 
            array (
                'id' => 421,
                'municipio_id' => 131,
                'parroquia' => 'El Socorro',
            ),
            421 => 
            array (
                'id' => 422,
                'municipio_id' => 132,
                'parroquia' => 'Tucupido',
            ),
            422 => 
            array (
                'id' => 423,
                'municipio_id' => 132,
                'parroquia' => 'San Rafael de Laya',
            ),
            423 => 
            array (
                'id' => 424,
                'municipio_id' => 133,
                'parroquia' => 'Altagracia de Orituco',
            ),
            424 => 
            array (
                'id' => 425,
                'municipio_id' => 133,
                'parroquia' => 'San Rafael de Orituco',
            ),
            425 => 
            array (
                'id' => 426,
                'municipio_id' => 133,
                'parroquia' => 'San Francisco Javier de Lezama',
            ),
            426 => 
            array (
                'id' => 427,
                'municipio_id' => 133,
                'parroquia' => 'Paso Real de Macaira',
            ),
            427 => 
            array (
                'id' => 428,
                'municipio_id' => 133,
                'parroquia' => 'Carlos Soublette',
            ),
            428 => 
            array (
                'id' => 429,
                'municipio_id' => 133,
                'parroquia' => 'San Francisco de Macaira',
            ),
            429 => 
            array (
                'id' => 430,
                'municipio_id' => 133,
                'parroquia' => 'Libertad de Orituco',
            ),
            430 => 
            array (
                'id' => 431,
                'municipio_id' => 134,
                'parroquia' => 'Cantaclaro',
            ),
            431 => 
            array (
                'id' => 432,
                'municipio_id' => 134,
                'parroquia' => 'San Juan de los Morros',
            ),
            432 => 
            array (
                'id' => 433,
                'municipio_id' => 134,
                'parroquia' => 'Parapara',
            ),
            433 => 
            array (
                'id' => 434,
                'municipio_id' => 135,
                'parroquia' => 'El Sombrero',
            ),
            434 => 
            array (
                'id' => 435,
                'municipio_id' => 135,
                'parroquia' => 'Sosa',
            ),
            435 => 
            array (
                'id' => 436,
                'municipio_id' => 136,
                'parroquia' => 'Las Mercedes',
            ),
            436 => 
            array (
                'id' => 437,
                'municipio_id' => 136,
                'parroquia' => 'Cabruta',
            ),
            437 => 
            array (
                'id' => 438,
                'municipio_id' => 136,
                'parroquia' => 'Santa Rita de Manapire',
            ),
            438 => 
            array (
                'id' => 439,
                'municipio_id' => 137,
                'parroquia' => 'Valle de la Pascua',
            ),
            439 => 
            array (
                'id' => 440,
                'municipio_id' => 137,
                'parroquia' => 'Espino',
            ),
            440 => 
            array (
                'id' => 441,
                'municipio_id' => 138,
                'parroquia' => 'San José de Unare',
            ),
            441 => 
            array (
                'id' => 442,
                'municipio_id' => 138,
                'parroquia' => 'Zaraza',
            ),
            442 => 
            array (
                'id' => 443,
                'municipio_id' => 139,
                'parroquia' => 'San José de Tiznados',
            ),
            443 => 
            array (
                'id' => 444,
                'municipio_id' => 139,
                'parroquia' => 'San Francisco de Tiznados',
            ),
            444 => 
            array (
                'id' => 445,
                'municipio_id' => 139,
                'parroquia' => 'San Lorenzo de Tiznados',
            ),
            445 => 
            array (
                'id' => 446,
                'municipio_id' => 139,
                'parroquia' => 'Ortiz',
            ),
            446 => 
            array (
                'id' => 447,
                'municipio_id' => 140,
                'parroquia' => 'Guayabal',
            ),
            447 => 
            array (
                'id' => 448,
                'municipio_id' => 140,
                'parroquia' => 'Cazorla',
            ),
            448 => 
            array (
                'id' => 449,
                'municipio_id' => 141,
                'parroquia' => 'San José de Guaribe',
            ),
            449 => 
            array (
                'id' => 450,
                'municipio_id' => 141,
                'parroquia' => 'Uveral',
            ),
            450 => 
            array (
                'id' => 451,
                'municipio_id' => 142,
                'parroquia' => 'Santa María de Ipire',
            ),
            451 => 
            array (
                'id' => 452,
                'municipio_id' => 142,
                'parroquia' => 'Altamira',
            ),
            452 => 
            array (
                'id' => 453,
                'municipio_id' => 143,
                'parroquia' => 'El Calvario',
            ),
            453 => 
            array (
                'id' => 454,
                'municipio_id' => 143,
                'parroquia' => 'El Rastro',
            ),
            454 => 
            array (
                'id' => 455,
                'municipio_id' => 143,
                'parroquia' => 'Guardatinajas',
            ),
            455 => 
            array (
                'id' => 456,
                'municipio_id' => 143,
                'parroquia' => 'Capital Urbana Calabozo',
            ),
            456 => 
            array (
                'id' => 457,
                'municipio_id' => 144,
                'parroquia' => 'Quebrada Honda de Guache',
            ),
            457 => 
            array (
                'id' => 458,
                'municipio_id' => 144,
                'parroquia' => 'Pío Tamayo',
            ),
            458 => 
            array (
                'id' => 459,
                'municipio_id' => 144,
                'parroquia' => 'Yacambú',
            ),
            459 => 
            array (
                'id' => 460,
                'municipio_id' => 145,
                'parroquia' => 'Fréitez',
            ),
            460 => 
            array (
                'id' => 461,
                'municipio_id' => 145,
                'parroquia' => 'José María Blanco',
            ),
            461 => 
            array (
                'id' => 462,
                'municipio_id' => 146,
                'parroquia' => 'Catedral',
            ),
            462 => 
            array (
                'id' => 463,
                'municipio_id' => 146,
                'parroquia' => 'Concepción',
            ),
            463 => 
            array (
                'id' => 464,
                'municipio_id' => 146,
                'parroquia' => 'El Cují',
            ),
            464 => 
            array (
                'id' => 465,
                'municipio_id' => 146,
                'parroquia' => 'Juan de Villegas',
            ),
            465 => 
            array (
                'id' => 466,
                'municipio_id' => 146,
                'parroquia' => 'Santa Rosa',
            ),
            466 => 
            array (
                'id' => 467,
                'municipio_id' => 146,
                'parroquia' => 'Tamaca',
            ),
            467 => 
            array (
                'id' => 468,
                'municipio_id' => 146,
                'parroquia' => 'Unión',
            ),
            468 => 
            array (
                'id' => 469,
                'municipio_id' => 146,
                'parroquia' => 'Aguedo Felipe Alvarado',
            ),
            469 => 
            array (
                'id' => 470,
                'municipio_id' => 146,
                'parroquia' => 'Buena Vista',
            ),
            470 => 
            array (
                'id' => 471,
                'municipio_id' => 146,
                'parroquia' => 'Juárez',
            ),
            471 => 
            array (
                'id' => 472,
                'municipio_id' => 147,
                'parroquia' => 'Juan Bautista Rodríguez',
            ),
            472 => 
            array (
                'id' => 473,
                'municipio_id' => 147,
                'parroquia' => 'Cuara',
            ),
            473 => 
            array (
                'id' => 474,
                'municipio_id' => 147,
                'parroquia' => 'Diego de Lozada',
            ),
            474 => 
            array (
                'id' => 475,
                'municipio_id' => 147,
                'parroquia' => 'Paraíso de San José',
            ),
            475 => 
            array (
                'id' => 476,
                'municipio_id' => 147,
                'parroquia' => 'San Miguel',
            ),
            476 => 
            array (
                'id' => 477,
                'municipio_id' => 147,
                'parroquia' => 'Tintorero',
            ),
            477 => 
            array (
                'id' => 478,
                'municipio_id' => 147,
                'parroquia' => 'José Bernardo Dorante',
            ),
            478 => 
            array (
                'id' => 479,
                'municipio_id' => 147,
                'parroquia' => 'Coronel Mariano Peraza ',
            ),
            479 => 
            array (
                'id' => 480,
                'municipio_id' => 148,
                'parroquia' => 'Bolívar',
            ),
            480 => 
            array (
                'id' => 481,
                'municipio_id' => 148,
                'parroquia' => 'Anzoátegui',
            ),
            481 => 
            array (
                'id' => 482,
                'municipio_id' => 148,
                'parroquia' => 'Guarico',
            ),
            482 => 
            array (
                'id' => 483,
                'municipio_id' => 148,
                'parroquia' => 'Hilario Luna y Luna',
            ),
            483 => 
            array (
                'id' => 484,
                'municipio_id' => 148,
                'parroquia' => 'Humocaro Alto',
            ),
            484 => 
            array (
                'id' => 485,
                'municipio_id' => 148,
                'parroquia' => 'Humocaro Bajo',
            ),
            485 => 
            array (
                'id' => 486,
                'municipio_id' => 148,
                'parroquia' => 'La Candelaria',
            ),
            486 => 
            array (
                'id' => 487,
                'municipio_id' => 148,
                'parroquia' => 'Morán',
            ),
            487 => 
            array (
                'id' => 488,
                'municipio_id' => 149,
                'parroquia' => 'Cabudare',
            ),
            488 => 
            array (
                'id' => 489,
                'municipio_id' => 149,
                'parroquia' => 'José Gregorio Bastidas',
            ),
            489 => 
            array (
                'id' => 490,
                'municipio_id' => 149,
                'parroquia' => 'Agua Viva',
            ),
            490 => 
            array (
                'id' => 491,
                'municipio_id' => 150,
                'parroquia' => 'Sarare',
            ),
            491 => 
            array (
                'id' => 492,
                'municipio_id' => 150,
                'parroquia' => 'Buría',
            ),
            492 => 
            array (
                'id' => 493,
                'municipio_id' => 150,
                'parroquia' => 'Gustavo Vegas León',
            ),
            493 => 
            array (
                'id' => 494,
                'municipio_id' => 151,
                'parroquia' => 'Trinidad Samuel',
            ),
            494 => 
            array (
                'id' => 495,
                'municipio_id' => 151,
                'parroquia' => 'Antonio Díaz',
            ),
            495 => 
            array (
                'id' => 496,
                'municipio_id' => 151,
                'parroquia' => 'Camacaro',
            ),
            496 => 
            array (
                'id' => 497,
                'municipio_id' => 151,
                'parroquia' => 'Castañeda',
            ),
            497 => 
            array (
                'id' => 498,
                'municipio_id' => 151,
                'parroquia' => 'Cecilio Zubillaga',
            ),
            498 => 
            array (
                'id' => 499,
                'municipio_id' => 151,
                'parroquia' => 'Chiquinquirá',
            ),
            499 => 
            array (
                'id' => 500,
                'municipio_id' => 151,
                'parroquia' => 'El Blanco',
            ),
        ));
        \DB::table('parroquias')->insert(array (
            0 => 
            array (
                'id' => 501,
                'municipio_id' => 151,
                'parroquia' => 'Espinoza de los Monteros',
            ),
            1 => 
            array (
                'id' => 502,
                'municipio_id' => 151,
                'parroquia' => 'Lara',
            ),
            2 => 
            array (
                'id' => 503,
                'municipio_id' => 151,
                'parroquia' => 'Las Mercedes',
            ),
            3 => 
            array (
                'id' => 504,
                'municipio_id' => 151,
                'parroquia' => 'Manuel Morillo',
            ),
            4 => 
            array (
                'id' => 505,
                'municipio_id' => 151,
                'parroquia' => 'Montaña Verde',
            ),
            5 => 
            array (
                'id' => 506,
                'municipio_id' => 151,
                'parroquia' => 'Montes de Oca',
            ),
            6 => 
            array (
                'id' => 507,
                'municipio_id' => 151,
                'parroquia' => 'Torres',
            ),
            7 => 
            array (
                'id' => 508,
                'municipio_id' => 151,
                'parroquia' => 'Heriberto Arroyo',
            ),
            8 => 
            array (
                'id' => 509,
                'municipio_id' => 151,
                'parroquia' => 'Reyes Vargas',
            ),
            9 => 
            array (
                'id' => 510,
                'municipio_id' => 151,
                'parroquia' => 'Altagracia',
            ),
            10 => 
            array (
                'id' => 511,
                'municipio_id' => 152,
                'parroquia' => 'Siquisique',
            ),
            11 => 
            array (
                'id' => 512,
                'municipio_id' => 152,
                'parroquia' => 'Moroturo',
            ),
            12 => 
            array (
                'id' => 513,
                'municipio_id' => 152,
                'parroquia' => 'San Miguel',
            ),
            13 => 
            array (
                'id' => 514,
                'municipio_id' => 152,
                'parroquia' => 'Xaguas',
            ),
            14 => 
            array (
                'id' => 515,
                'municipio_id' => 179,
                'parroquia' => 'Presidente Betancourt',
            ),
            15 => 
            array (
                'id' => 516,
                'municipio_id' => 179,
                'parroquia' => 'Presidente Páez',
            ),
            16 => 
            array (
                'id' => 517,
                'municipio_id' => 179,
                'parroquia' => 'Presidente Rómulo Gallegos',
            ),
            17 => 
            array (
                'id' => 518,
                'municipio_id' => 179,
                'parroquia' => 'Gabriel Picón González',
            ),
            18 => 
            array (
                'id' => 519,
                'municipio_id' => 179,
                'parroquia' => 'Héctor Amable Mora',
            ),
            19 => 
            array (
                'id' => 520,
                'municipio_id' => 179,
                'parroquia' => 'José Nucete Sardi',
            ),
            20 => 
            array (
                'id' => 521,
                'municipio_id' => 179,
                'parroquia' => 'Pulido Méndez',
            ),
            21 => 
            array (
                'id' => 522,
                'municipio_id' => 180,
                'parroquia' => 'La Azulita',
            ),
            22 => 
            array (
                'id' => 523,
                'municipio_id' => 181,
                'parroquia' => 'Santa Cruz de Mora',
            ),
            23 => 
            array (
                'id' => 524,
                'municipio_id' => 181,
                'parroquia' => 'Mesa Bolívar',
            ),
            24 => 
            array (
                'id' => 525,
                'municipio_id' => 181,
                'parroquia' => 'Mesa de Las Palmas',
            ),
            25 => 
            array (
                'id' => 526,
                'municipio_id' => 182,
                'parroquia' => 'Aricagua',
            ),
            26 => 
            array (
                'id' => 527,
                'municipio_id' => 182,
                'parroquia' => 'San Antonio',
            ),
            27 => 
            array (
                'id' => 528,
                'municipio_id' => 183,
                'parroquia' => 'Canagua',
            ),
            28 => 
            array (
                'id' => 529,
                'municipio_id' => 183,
                'parroquia' => 'Capurí',
            ),
            29 => 
            array (
                'id' => 530,
                'municipio_id' => 183,
                'parroquia' => 'Chacantá',
            ),
            30 => 
            array (
                'id' => 531,
                'municipio_id' => 183,
                'parroquia' => 'El Molino',
            ),
            31 => 
            array (
                'id' => 532,
                'municipio_id' => 183,
                'parroquia' => 'Guaimaral',
            ),
            32 => 
            array (
                'id' => 533,
                'municipio_id' => 183,
                'parroquia' => 'Mucutuy',
            ),
            33 => 
            array (
                'id' => 534,
                'municipio_id' => 183,
                'parroquia' => 'Mucuchachí',
            ),
            34 => 
            array (
                'id' => 535,
                'municipio_id' => 184,
                'parroquia' => 'Fernández Peña',
            ),
            35 => 
            array (
                'id' => 536,
                'municipio_id' => 184,
                'parroquia' => 'Matriz',
            ),
            36 => 
            array (
                'id' => 537,
                'municipio_id' => 184,
                'parroquia' => 'Montalbán',
            ),
            37 => 
            array (
                'id' => 538,
                'municipio_id' => 184,
                'parroquia' => 'Acequias',
            ),
            38 => 
            array (
                'id' => 539,
                'municipio_id' => 184,
                'parroquia' => 'Jají',
            ),
            39 => 
            array (
                'id' => 540,
                'municipio_id' => 184,
                'parroquia' => 'La Mesa',
            ),
            40 => 
            array (
                'id' => 541,
                'municipio_id' => 184,
                'parroquia' => 'San José del Sur',
            ),
            41 => 
            array (
                'id' => 542,
                'municipio_id' => 185,
                'parroquia' => 'Tucaní',
            ),
            42 => 
            array (
                'id' => 543,
                'municipio_id' => 185,
                'parroquia' => 'Florencio Ramírez',
            ),
            43 => 
            array (
                'id' => 544,
                'municipio_id' => 186,
                'parroquia' => 'Santo Domingo',
            ),
            44 => 
            array (
                'id' => 545,
                'municipio_id' => 186,
                'parroquia' => 'Las Piedras',
            ),
            45 => 
            array (
                'id' => 546,
                'municipio_id' => 187,
                'parroquia' => 'Guaraque',
            ),
            46 => 
            array (
                'id' => 547,
                'municipio_id' => 187,
                'parroquia' => 'Mesa de Quintero',
            ),
            47 => 
            array (
                'id' => 548,
                'municipio_id' => 187,
                'parroquia' => 'Río Negro',
            ),
            48 => 
            array (
                'id' => 549,
                'municipio_id' => 188,
                'parroquia' => 'Arapuey',
            ),
            49 => 
            array (
                'id' => 550,
                'municipio_id' => 188,
                'parroquia' => 'Palmira',
            ),
            50 => 
            array (
                'id' => 551,
                'municipio_id' => 189,
                'parroquia' => 'San Cristóbal de Torondoy',
            ),
            51 => 
            array (
                'id' => 552,
                'municipio_id' => 189,
                'parroquia' => 'Torondoy',
            ),
            52 => 
            array (
                'id' => 553,
                'municipio_id' => 190,
                'parroquia' => 'Antonio Spinetti Dini',
            ),
            53 => 
            array (
                'id' => 554,
                'municipio_id' => 190,
                'parroquia' => 'Arias',
            ),
            54 => 
            array (
                'id' => 555,
                'municipio_id' => 190,
                'parroquia' => 'Caracciolo Parra Pérez',
            ),
            55 => 
            array (
                'id' => 556,
                'municipio_id' => 190,
                'parroquia' => 'Domingo Peña',
            ),
            56 => 
            array (
                'id' => 557,
                'municipio_id' => 190,
                'parroquia' => 'El Llano',
            ),
            57 => 
            array (
                'id' => 558,
                'municipio_id' => 190,
                'parroquia' => 'Gonzalo Picón Febres',
            ),
            58 => 
            array (
                'id' => 559,
                'municipio_id' => 190,
                'parroquia' => 'Jacinto Plaza',
            ),
            59 => 
            array (
                'id' => 560,
                'municipio_id' => 190,
                'parroquia' => 'Juan Rodríguez Suárez',
            ),
            60 => 
            array (
                'id' => 561,
                'municipio_id' => 190,
                'parroquia' => 'Lasso de la Vega',
            ),
            61 => 
            array (
                'id' => 562,
                'municipio_id' => 190,
                'parroquia' => 'Mariano Picón Salas',
            ),
            62 => 
            array (
                'id' => 563,
                'municipio_id' => 190,
                'parroquia' => 'Milla',
            ),
            63 => 
            array (
                'id' => 564,
                'municipio_id' => 190,
                'parroquia' => 'Osuna Rodríguez',
            ),
            64 => 
            array (
                'id' => 565,
                'municipio_id' => 190,
                'parroquia' => 'Sagrario',
            ),
            65 => 
            array (
                'id' => 566,
                'municipio_id' => 190,
                'parroquia' => 'El Morro',
            ),
            66 => 
            array (
                'id' => 567,
                'municipio_id' => 190,
                'parroquia' => 'Los Nevados',
            ),
            67 => 
            array (
                'id' => 568,
                'municipio_id' => 191,
                'parroquia' => 'Andrés Eloy Blanco',
            ),
            68 => 
            array (
                'id' => 569,
                'municipio_id' => 191,
                'parroquia' => 'La Venta',
            ),
            69 => 
            array (
                'id' => 570,
                'municipio_id' => 191,
                'parroquia' => 'Piñango',
            ),
            70 => 
            array (
                'id' => 571,
                'municipio_id' => 191,
                'parroquia' => 'Timotes',
            ),
            71 => 
            array (
                'id' => 572,
                'municipio_id' => 192,
                'parroquia' => 'Eloy Paredes',
            ),
            72 => 
            array (
                'id' => 573,
                'municipio_id' => 192,
                'parroquia' => 'San Rafael de Alcázar',
            ),
            73 => 
            array (
                'id' => 574,
                'municipio_id' => 192,
                'parroquia' => 'Santa Elena de Arenales',
            ),
            74 => 
            array (
                'id' => 575,
                'municipio_id' => 193,
                'parroquia' => 'Santa María de Caparo',
            ),
            75 => 
            array (
                'id' => 576,
                'municipio_id' => 194,
                'parroquia' => 'Pueblo Llano',
            ),
            76 => 
            array (
                'id' => 577,
                'municipio_id' => 195,
                'parroquia' => 'Cacute',
            ),
            77 => 
            array (
                'id' => 578,
                'municipio_id' => 195,
                'parroquia' => 'La Toma',
            ),
            78 => 
            array (
                'id' => 579,
                'municipio_id' => 195,
                'parroquia' => 'Mucuchíes',
            ),
            79 => 
            array (
                'id' => 580,
                'municipio_id' => 195,
                'parroquia' => 'Mucurubá',
            ),
            80 => 
            array (
                'id' => 581,
                'municipio_id' => 195,
                'parroquia' => 'San Rafael',
            ),
            81 => 
            array (
                'id' => 582,
                'municipio_id' => 196,
                'parroquia' => 'Gerónimo Maldonado',
            ),
            82 => 
            array (
                'id' => 583,
                'municipio_id' => 196,
                'parroquia' => 'Bailadores',
            ),
            83 => 
            array (
                'id' => 584,
                'municipio_id' => 197,
                'parroquia' => 'Tabay',
            ),
            84 => 
            array (
                'id' => 585,
                'municipio_id' => 198,
                'parroquia' => 'Chiguará',
            ),
            85 => 
            array (
                'id' => 586,
                'municipio_id' => 198,
                'parroquia' => 'Estánquez',
            ),
            86 => 
            array (
                'id' => 587,
                'municipio_id' => 198,
                'parroquia' => 'Lagunillas',
            ),
            87 => 
            array (
                'id' => 588,
                'municipio_id' => 198,
                'parroquia' => 'La Trampa',
            ),
            88 => 
            array (
                'id' => 589,
                'municipio_id' => 198,
                'parroquia' => 'Pueblo Nuevo del Sur',
            ),
            89 => 
            array (
                'id' => 590,
                'municipio_id' => 198,
                'parroquia' => 'San Juan',
            ),
            90 => 
            array (
                'id' => 591,
                'municipio_id' => 199,
                'parroquia' => 'El Amparo',
            ),
            91 => 
            array (
                'id' => 592,
                'municipio_id' => 199,
                'parroquia' => 'El Llano',
            ),
            92 => 
            array (
                'id' => 593,
                'municipio_id' => 199,
                'parroquia' => 'San Francisco',
            ),
            93 => 
            array (
                'id' => 594,
                'municipio_id' => 199,
                'parroquia' => 'Tovar',
            ),
            94 => 
            array (
                'id' => 595,
                'municipio_id' => 200,
                'parroquia' => 'Independencia',
            ),
            95 => 
            array (
                'id' => 596,
                'municipio_id' => 200,
                'parroquia' => 'María de la Concepción Palacios Blanco',
            ),
            96 => 
            array (
                'id' => 597,
                'municipio_id' => 200,
                'parroquia' => 'Nueva Bolivia',
            ),
            97 => 
            array (
                'id' => 598,
                'municipio_id' => 200,
                'parroquia' => 'Santa Apolonia',
            ),
            98 => 
            array (
                'id' => 599,
                'municipio_id' => 201,
                'parroquia' => 'Caño El Tigre',
            ),
            99 => 
            array (
                'id' => 600,
                'municipio_id' => 201,
                'parroquia' => 'Zea',
            ),
            100 => 
            array (
                'id' => 601,
                'municipio_id' => 223,
                'parroquia' => 'Aragüita',
            ),
            101 => 
            array (
                'id' => 602,
                'municipio_id' => 223,
                'parroquia' => 'Arévalo González',
            ),
            102 => 
            array (
                'id' => 603,
                'municipio_id' => 223,
                'parroquia' => 'Capaya',
            ),
            103 => 
            array (
                'id' => 604,
                'municipio_id' => 223,
                'parroquia' => 'Caucagua',
            ),
            104 => 
            array (
                'id' => 605,
                'municipio_id' => 223,
                'parroquia' => 'Panaquire',
            ),
            105 => 
            array (
                'id' => 606,
                'municipio_id' => 223,
                'parroquia' => 'Ribas',
            ),
            106 => 
            array (
                'id' => 607,
                'municipio_id' => 223,
                'parroquia' => 'El Café',
            ),
            107 => 
            array (
                'id' => 608,
                'municipio_id' => 223,
                'parroquia' => 'Marizapa',
            ),
            108 => 
            array (
                'id' => 609,
                'municipio_id' => 224,
                'parroquia' => 'Cumbo',
            ),
            109 => 
            array (
                'id' => 610,
                'municipio_id' => 224,
                'parroquia' => 'San José de Barlovento',
            ),
            110 => 
            array (
                'id' => 611,
                'municipio_id' => 225,
                'parroquia' => 'El Cafetal',
            ),
            111 => 
            array (
                'id' => 612,
                'municipio_id' => 225,
                'parroquia' => 'Las Minas',
            ),
            112 => 
            array (
                'id' => 613,
                'municipio_id' => 225,
                'parroquia' => 'Nuestra Señora del Rosario',
            ),
            113 => 
            array (
                'id' => 614,
                'municipio_id' => 226,
                'parroquia' => 'Higuerote',
            ),
            114 => 
            array (
                'id' => 615,
                'municipio_id' => 226,
                'parroquia' => 'Curiepe',
            ),
            115 => 
            array (
                'id' => 616,
                'municipio_id' => 226,
                'parroquia' => 'Tacarigua de Brión',
            ),
            116 => 
            array (
                'id' => 617,
                'municipio_id' => 227,
                'parroquia' => 'Mamporal',
            ),
            117 => 
            array (
                'id' => 618,
                'municipio_id' => 228,
                'parroquia' => 'Carrizal',
            ),
            118 => 
            array (
                'id' => 619,
                'municipio_id' => 229,
                'parroquia' => 'Chacao',
            ),
            119 => 
            array (
                'id' => 620,
                'municipio_id' => 230,
                'parroquia' => 'Charallave',
            ),
            120 => 
            array (
                'id' => 621,
                'municipio_id' => 230,
                'parroquia' => 'Las Brisas',
            ),
            121 => 
            array (
                'id' => 622,
                'municipio_id' => 231,
                'parroquia' => 'El Hatillo',
            ),
            122 => 
            array (
                'id' => 623,
                'municipio_id' => 232,
                'parroquia' => 'Altagracia de la Montaña',
            ),
            123 => 
            array (
                'id' => 624,
                'municipio_id' => 232,
                'parroquia' => 'Cecilio Acosta',
            ),
            124 => 
            array (
                'id' => 625,
                'municipio_id' => 232,
                'parroquia' => 'Los Teques',
            ),
            125 => 
            array (
                'id' => 626,
                'municipio_id' => 232,
                'parroquia' => 'El Jarillo',
            ),
            126 => 
            array (
                'id' => 627,
                'municipio_id' => 232,
                'parroquia' => 'San Pedro',
            ),
            127 => 
            array (
                'id' => 628,
                'municipio_id' => 232,
                'parroquia' => 'Tácata',
            ),
            128 => 
            array (
                'id' => 629,
                'municipio_id' => 232,
                'parroquia' => 'Paracotos',
            ),
            129 => 
            array (
                'id' => 630,
                'municipio_id' => 233,
                'parroquia' => 'Cartanal',
            ),
            130 => 
            array (
                'id' => 631,
                'municipio_id' => 233,
                'parroquia' => 'Santa Teresa del Tuy',
            ),
            131 => 
            array (
                'id' => 632,
                'municipio_id' => 234,
                'parroquia' => 'La Democracia',
            ),
            132 => 
            array (
                'id' => 633,
                'municipio_id' => 234,
                'parroquia' => 'Ocumare del Tuy',
            ),
            133 => 
            array (
                'id' => 634,
                'municipio_id' => 234,
                'parroquia' => 'Santa Bárbara',
            ),
            134 => 
            array (
                'id' => 635,
                'municipio_id' => 235,
                'parroquia' => 'San Antonio de los Altos',
            ),
            135 => 
            array (
                'id' => 636,
                'municipio_id' => 236,
                'parroquia' => 'Río Chico',
            ),
            136 => 
            array (
                'id' => 637,
                'municipio_id' => 236,
                'parroquia' => 'El Guapo',
            ),
            137 => 
            array (
                'id' => 638,
                'municipio_id' => 236,
                'parroquia' => 'Tacarigua de la Laguna',
            ),
            138 => 
            array (
                'id' => 639,
                'municipio_id' => 236,
                'parroquia' => 'Paparo',
            ),
            139 => 
            array (
                'id' => 640,
                'municipio_id' => 236,
                'parroquia' => 'San Fernando del Guapo',
            ),
            140 => 
            array (
                'id' => 641,
                'municipio_id' => 237,
                'parroquia' => 'Santa Lucía del Tuy',
            ),
            141 => 
            array (
                'id' => 642,
                'municipio_id' => 238,
                'parroquia' => 'Cúpira',
            ),
            142 => 
            array (
                'id' => 643,
                'municipio_id' => 238,
                'parroquia' => 'Machurucuto',
            ),
            143 => 
            array (
                'id' => 644,
                'municipio_id' => 239,
                'parroquia' => 'Guarenas',
            ),
            144 => 
            array (
                'id' => 645,
                'municipio_id' => 240,
                'parroquia' => 'San Antonio de Yare',
            ),
            145 => 
            array (
                'id' => 646,
                'municipio_id' => 240,
                'parroquia' => 'San Francisco de Yare',
            ),
            146 => 
            array (
                'id' => 647,
                'municipio_id' => 241,
                'parroquia' => 'Leoncio Martínez',
            ),
            147 => 
            array (
                'id' => 648,
                'municipio_id' => 241,
                'parroquia' => 'Petare',
            ),
            148 => 
            array (
                'id' => 649,
                'municipio_id' => 241,
                'parroquia' => 'Caucagüita',
            ),
            149 => 
            array (
                'id' => 650,
                'municipio_id' => 241,
                'parroquia' => 'Filas de Mariche',
            ),
            150 => 
            array (
                'id' => 651,
                'municipio_id' => 241,
                'parroquia' => 'La Dolorita',
            ),
            151 => 
            array (
                'id' => 652,
                'municipio_id' => 242,
                'parroquia' => 'Cúa',
            ),
            152 => 
            array (
                'id' => 653,
                'municipio_id' => 242,
                'parroquia' => 'Nueva Cúa',
            ),
            153 => 
            array (
                'id' => 654,
                'municipio_id' => 243,
                'parroquia' => 'Guatire',
            ),
            154 => 
            array (
                'id' => 655,
                'municipio_id' => 243,
                'parroquia' => 'Bolívar',
            ),
            155 => 
            array (
                'id' => 656,
                'municipio_id' => 258,
                'parroquia' => 'San Antonio de Maturín',
            ),
            156 => 
            array (
                'id' => 657,
                'municipio_id' => 258,
                'parroquia' => 'San Francisco de Maturín',
            ),
            157 => 
            array (
                'id' => 658,
                'municipio_id' => 259,
                'parroquia' => 'Aguasay',
            ),
            158 => 
            array (
                'id' => 659,
                'municipio_id' => 260,
                'parroquia' => 'Caripito',
            ),
            159 => 
            array (
                'id' => 660,
                'municipio_id' => 261,
                'parroquia' => 'El Guácharo',
            ),
            160 => 
            array (
                'id' => 661,
                'municipio_id' => 261,
                'parroquia' => 'La Guanota',
            ),
            161 => 
            array (
                'id' => 662,
                'municipio_id' => 261,
                'parroquia' => 'Sabana de Piedra',
            ),
            162 => 
            array (
                'id' => 663,
                'municipio_id' => 261,
                'parroquia' => 'San Agustín',
            ),
            163 => 
            array (
                'id' => 664,
                'municipio_id' => 261,
                'parroquia' => 'Teresen',
            ),
            164 => 
            array (
                'id' => 665,
                'municipio_id' => 261,
                'parroquia' => 'Caripe',
            ),
            165 => 
            array (
                'id' => 666,
                'municipio_id' => 262,
                'parroquia' => 'Areo',
            ),
            166 => 
            array (
                'id' => 667,
                'municipio_id' => 262,
                'parroquia' => 'Capital Cedeño',
            ),
            167 => 
            array (
                'id' => 668,
                'municipio_id' => 262,
                'parroquia' => 'San Félix de Cantalicio',
            ),
            168 => 
            array (
                'id' => 669,
                'municipio_id' => 262,
                'parroquia' => 'Viento Fresco',
            ),
            169 => 
            array (
                'id' => 670,
                'municipio_id' => 263,
                'parroquia' => 'El Tejero',
            ),
            170 => 
            array (
                'id' => 671,
                'municipio_id' => 263,
                'parroquia' => 'Punta de Mata',
            ),
            171 => 
            array (
                'id' => 672,
                'municipio_id' => 264,
                'parroquia' => 'Chaguaramas',
            ),
            172 => 
            array (
                'id' => 673,
                'municipio_id' => 264,
                'parroquia' => 'Las Alhuacas',
            ),
            173 => 
            array (
                'id' => 674,
                'municipio_id' => 264,
                'parroquia' => 'Tabasca',
            ),
            174 => 
            array (
                'id' => 675,
                'municipio_id' => 264,
                'parroquia' => 'Temblador',
            ),
            175 => 
            array (
                'id' => 676,
                'municipio_id' => 265,
                'parroquia' => 'Alto de los Godos',
            ),
            176 => 
            array (
                'id' => 677,
                'municipio_id' => 265,
                'parroquia' => 'Boquerón',
            ),
            177 => 
            array (
                'id' => 678,
                'municipio_id' => 265,
                'parroquia' => 'Las Cocuizas',
            ),
            178 => 
            array (
                'id' => 679,
                'municipio_id' => 265,
                'parroquia' => 'La Cruz',
            ),
            179 => 
            array (
                'id' => 680,
                'municipio_id' => 265,
                'parroquia' => 'San Simón',
            ),
            180 => 
            array (
                'id' => 681,
                'municipio_id' => 265,
                'parroquia' => 'El Corozo',
            ),
            181 => 
            array (
                'id' => 682,
                'municipio_id' => 265,
                'parroquia' => 'El Furrial',
            ),
            182 => 
            array (
                'id' => 683,
                'municipio_id' => 265,
                'parroquia' => 'Jusepín',
            ),
            183 => 
            array (
                'id' => 684,
                'municipio_id' => 265,
                'parroquia' => 'La Pica',
            ),
            184 => 
            array (
                'id' => 685,
                'municipio_id' => 265,
                'parroquia' => 'San Vicente',
            ),
            185 => 
            array (
                'id' => 686,
                'municipio_id' => 266,
                'parroquia' => 'Aparicio',
            ),
            186 => 
            array (
                'id' => 687,
                'municipio_id' => 266,
                'parroquia' => 'Aragua de Maturín',
            ),
            187 => 
            array (
                'id' => 688,
                'municipio_id' => 266,
                'parroquia' => 'Chaguamal',
            ),
            188 => 
            array (
                'id' => 689,
                'municipio_id' => 266,
                'parroquia' => 'El Pinto',
            ),
            189 => 
            array (
                'id' => 690,
                'municipio_id' => 266,
                'parroquia' => 'Guanaguana',
            ),
            190 => 
            array (
                'id' => 691,
                'municipio_id' => 266,
                'parroquia' => 'La Toscana',
            ),
            191 => 
            array (
                'id' => 692,
                'municipio_id' => 266,
                'parroquia' => 'Taguaya',
            ),
            192 => 
            array (
                'id' => 693,
                'municipio_id' => 267,
                'parroquia' => 'Cachipo',
            ),
            193 => 
            array (
                'id' => 694,
                'municipio_id' => 267,
                'parroquia' => 'Quiriquire',
            ),
            194 => 
            array (
                'id' => 695,
                'municipio_id' => 268,
                'parroquia' => 'Santa Bárbara',
            ),
            195 => 
            array (
                'id' => 696,
                'municipio_id' => 269,
                'parroquia' => 'Barrancas',
            ),
            196 => 
            array (
                'id' => 697,
                'municipio_id' => 269,
                'parroquia' => 'Los Barrancos de Fajardo',
            ),
            197 => 
            array (
                'id' => 698,
                'municipio_id' => 270,
                'parroquia' => 'Uracoa',
            ),
            198 => 
            array (
                'id' => 699,
                'municipio_id' => 271,
                'parroquia' => 'Antolín del Campo',
            ),
            199 => 
            array (
                'id' => 700,
                'municipio_id' => 272,
                'parroquia' => 'Arismendi',
            ),
            200 => 
            array (
                'id' => 701,
                'municipio_id' => 273,
                'parroquia' => 'García',
            ),
            201 => 
            array (
                'id' => 702,
                'municipio_id' => 273,
                'parroquia' => 'Francisco Fajardo',
            ),
            202 => 
            array (
                'id' => 703,
                'municipio_id' => 274,
                'parroquia' => 'Bolívar',
            ),
            203 => 
            array (
                'id' => 704,
                'municipio_id' => 274,
                'parroquia' => 'Guevara',
            ),
            204 => 
            array (
                'id' => 705,
                'municipio_id' => 274,
                'parroquia' => 'Matasiete',
            ),
            205 => 
            array (
                'id' => 706,
                'municipio_id' => 274,
                'parroquia' => 'Santa Ana',
            ),
            206 => 
            array (
                'id' => 707,
                'municipio_id' => 274,
                'parroquia' => 'Sucre',
            ),
            207 => 
            array (
                'id' => 708,
                'municipio_id' => 275,
                'parroquia' => 'Aguirre',
            ),
            208 => 
            array (
                'id' => 709,
                'municipio_id' => 275,
                'parroquia' => 'Maneiro',
            ),
            209 => 
            array (
                'id' => 710,
                'municipio_id' => 276,
                'parroquia' => 'Adrián',
            ),
            210 => 
            array (
                'id' => 711,
                'municipio_id' => 276,
                'parroquia' => 'Juan Griego',
            ),
            211 => 
            array (
                'id' => 712,
                'municipio_id' => 276,
                'parroquia' => 'Yaguaraparo',
            ),
            212 => 
            array (
                'id' => 713,
                'municipio_id' => 277,
                'parroquia' => 'Porlamar',
            ),
            213 => 
            array (
                'id' => 714,
                'municipio_id' => 278,
                'parroquia' => 'San Francisco de Macanao',
            ),
            214 => 
            array (
                'id' => 715,
                'municipio_id' => 278,
                'parroquia' => 'Boca de Río',
            ),
            215 => 
            array (
                'id' => 716,
                'municipio_id' => 279,
                'parroquia' => 'Tubores',
            ),
            216 => 
            array (
                'id' => 717,
                'municipio_id' => 279,
                'parroquia' => 'Los Baleales',
            ),
            217 => 
            array (
                'id' => 718,
                'municipio_id' => 280,
                'parroquia' => 'Vicente Fuentes',
            ),
            218 => 
            array (
                'id' => 719,
                'municipio_id' => 280,
                'parroquia' => 'Villalba',
            ),
            219 => 
            array (
                'id' => 720,
                'municipio_id' => 281,
                'parroquia' => 'San Juan Bautista',
            ),
            220 => 
            array (
                'id' => 721,
                'municipio_id' => 281,
                'parroquia' => 'Zabala',
            ),
            221 => 
            array (
                'id' => 722,
                'municipio_id' => 283,
                'parroquia' => 'Capital Araure',
            ),
            222 => 
            array (
                'id' => 723,
                'municipio_id' => 283,
                'parroquia' => 'Río Acarigua',
            ),
            223 => 
            array (
                'id' => 724,
                'municipio_id' => 284,
                'parroquia' => 'Capital Esteller',
            ),
            224 => 
            array (
                'id' => 725,
                'municipio_id' => 284,
                'parroquia' => 'Uveral',
            ),
            225 => 
            array (
                'id' => 726,
                'municipio_id' => 285,
                'parroquia' => 'Guanare',
            ),
            226 => 
            array (
                'id' => 727,
                'municipio_id' => 285,
                'parroquia' => 'Córdoba',
            ),
            227 => 
            array (
                'id' => 728,
                'municipio_id' => 285,
                'parroquia' => 'San José de la Montaña',
            ),
            228 => 
            array (
                'id' => 729,
                'municipio_id' => 285,
                'parroquia' => 'San Juan de Guanaguanare',
            ),
            229 => 
            array (
                'id' => 730,
                'municipio_id' => 285,
                'parroquia' => 'Virgen de la Coromoto',
            ),
            230 => 
            array (
                'id' => 731,
                'municipio_id' => 286,
                'parroquia' => 'Guanarito',
            ),
            231 => 
            array (
                'id' => 732,
                'municipio_id' => 286,
                'parroquia' => 'Trinidad de la Capilla',
            ),
            232 => 
            array (
                'id' => 733,
                'municipio_id' => 286,
                'parroquia' => 'Divina Pastora',
            ),
            233 => 
            array (
                'id' => 734,
                'municipio_id' => 287,
                'parroquia' => 'Monseñor José Vicente de Unda',
            ),
            234 => 
            array (
                'id' => 735,
                'municipio_id' => 287,
                'parroquia' => 'Peña Blanca',
            ),
            235 => 
            array (
                'id' => 736,
                'municipio_id' => 288,
                'parroquia' => 'Capital Ospino',
            ),
            236 => 
            array (
                'id' => 737,
                'municipio_id' => 288,
                'parroquia' => 'Aparición',
            ),
            237 => 
            array (
                'id' => 738,
                'municipio_id' => 288,
                'parroquia' => 'La Estación',
            ),
            238 => 
            array (
                'id' => 739,
                'municipio_id' => 289,
                'parroquia' => 'Páez',
            ),
            239 => 
            array (
                'id' => 740,
                'municipio_id' => 289,
                'parroquia' => 'Payara',
            ),
            240 => 
            array (
                'id' => 741,
                'municipio_id' => 289,
                'parroquia' => 'Pimpinela',
            ),
            241 => 
            array (
                'id' => 742,
                'municipio_id' => 289,
                'parroquia' => 'Ramón Peraza',
            ),
            242 => 
            array (
                'id' => 743,
                'municipio_id' => 290,
                'parroquia' => 'Papelón',
            ),
            243 => 
            array (
                'id' => 744,
                'municipio_id' => 290,
                'parroquia' => 'Caño Delgadito',
            ),
            244 => 
            array (
                'id' => 745,
                'municipio_id' => 291,
                'parroquia' => 'San Genaro de Boconoito',
            ),
            245 => 
            array (
                'id' => 746,
                'municipio_id' => 291,
                'parroquia' => 'Antolín Tovar',
            ),
            246 => 
            array (
                'id' => 747,
                'municipio_id' => 292,
                'parroquia' => 'San Rafael de Onoto',
            ),
            247 => 
            array (
                'id' => 748,
                'municipio_id' => 292,
                'parroquia' => 'Santa Fe',
            ),
            248 => 
            array (
                'id' => 749,
                'municipio_id' => 292,
                'parroquia' => 'Thermo Morles',
            ),
            249 => 
            array (
                'id' => 750,
                'municipio_id' => 293,
                'parroquia' => 'Santa Rosalía',
            ),
            250 => 
            array (
                'id' => 751,
                'municipio_id' => 293,
                'parroquia' => 'Florida',
            ),
            251 => 
            array (
                'id' => 752,
                'municipio_id' => 294,
                'parroquia' => 'Sucre',
            ),
            252 => 
            array (
                'id' => 753,
                'municipio_id' => 294,
                'parroquia' => 'Concepción',
            ),
            253 => 
            array (
                'id' => 754,
                'municipio_id' => 294,
                'parroquia' => 'San Rafael de Palo Alzado',
            ),
            254 => 
            array (
                'id' => 755,
                'municipio_id' => 294,
                'parroquia' => 'Uvencio Antonio Velásquez',
            ),
            255 => 
            array (
                'id' => 756,
                'municipio_id' => 294,
                'parroquia' => 'San José de Saguaz',
            ),
            256 => 
            array (
                'id' => 757,
                'municipio_id' => 294,
                'parroquia' => 'Villa Rosa',
            ),
            257 => 
            array (
                'id' => 758,
                'municipio_id' => 295,
                'parroquia' => 'Turén',
            ),
            258 => 
            array (
                'id' => 759,
                'municipio_id' => 295,
                'parroquia' => 'Canelones',
            ),
            259 => 
            array (
                'id' => 760,
                'municipio_id' => 295,
                'parroquia' => 'Santa Cruz',
            ),
            260 => 
            array (
                'id' => 761,
                'municipio_id' => 295,
                'parroquia' => 'San Isidro Labrador',
            ),
            261 => 
            array (
                'id' => 762,
                'municipio_id' => 296,
                'parroquia' => 'Mariño',
            ),
            262 => 
            array (
                'id' => 763,
                'municipio_id' => 296,
                'parroquia' => 'Rómulo Gallegos',
            ),
            263 => 
            array (
                'id' => 764,
                'municipio_id' => 297,
                'parroquia' => 'San José de Aerocuar',
            ),
            264 => 
            array (
                'id' => 765,
                'municipio_id' => 297,
                'parroquia' => 'Tavera Acosta',
            ),
            265 => 
            array (
                'id' => 766,
                'municipio_id' => 298,
                'parroquia' => 'Río Caribe',
            ),
            266 => 
            array (
                'id' => 767,
                'municipio_id' => 298,
                'parroquia' => 'Antonio José de Sucre',
            ),
            267 => 
            array (
                'id' => 768,
                'municipio_id' => 298,
                'parroquia' => 'El Morro de Puerto Santo',
            ),
            268 => 
            array (
                'id' => 769,
                'municipio_id' => 298,
                'parroquia' => 'Puerto Santo',
            ),
            269 => 
            array (
                'id' => 770,
                'municipio_id' => 298,
                'parroquia' => 'San Juan de las Galdonas',
            ),
            270 => 
            array (
                'id' => 771,
                'municipio_id' => 299,
                'parroquia' => 'El Pilar',
            ),
            271 => 
            array (
                'id' => 772,
                'municipio_id' => 299,
                'parroquia' => 'El Rincón',
            ),
            272 => 
            array (
                'id' => 773,
                'municipio_id' => 299,
                'parroquia' => 'General Francisco Antonio Váquez',
            ),
            273 => 
            array (
                'id' => 774,
                'municipio_id' => 299,
                'parroquia' => 'Guaraúnos',
            ),
            274 => 
            array (
                'id' => 775,
                'municipio_id' => 299,
                'parroquia' => 'Tunapuicito',
            ),
            275 => 
            array (
                'id' => 776,
                'municipio_id' => 299,
                'parroquia' => 'Unión',
            ),
            276 => 
            array (
                'id' => 777,
                'municipio_id' => 300,
                'parroquia' => 'Santa Catalina',
            ),
            277 => 
            array (
                'id' => 778,
                'municipio_id' => 300,
                'parroquia' => 'Santa Rosa',
            ),
            278 => 
            array (
                'id' => 779,
                'municipio_id' => 300,
                'parroquia' => 'Santa Teresa',
            ),
            279 => 
            array (
                'id' => 780,
                'municipio_id' => 300,
                'parroquia' => 'Bolívar',
            ),
            280 => 
            array (
                'id' => 781,
                'municipio_id' => 300,
                'parroquia' => 'Maracapana',
            ),
            281 => 
            array (
                'id' => 782,
                'municipio_id' => 302,
                'parroquia' => 'Libertad',
            ),
            282 => 
            array (
                'id' => 783,
                'municipio_id' => 302,
                'parroquia' => 'El Paujil',
            ),
            283 => 
            array (
                'id' => 784,
                'municipio_id' => 302,
                'parroquia' => 'Yaguaraparo',
            ),
            284 => 
            array (
                'id' => 785,
                'municipio_id' => 303,
                'parroquia' => 'Cruz Salmerón Acosta',
            ),
            285 => 
            array (
                'id' => 786,
                'municipio_id' => 303,
                'parroquia' => 'Chacopata',
            ),
            286 => 
            array (
                'id' => 787,
                'municipio_id' => 303,
                'parroquia' => 'Manicuare',
            ),
            287 => 
            array (
                'id' => 788,
                'municipio_id' => 304,
                'parroquia' => 'Tunapuy',
            ),
            288 => 
            array (
                'id' => 789,
                'municipio_id' => 304,
                'parroquia' => 'Campo Elías',
            ),
            289 => 
            array (
                'id' => 790,
                'municipio_id' => 305,
                'parroquia' => 'Irapa',
            ),
            290 => 
            array (
                'id' => 791,
                'municipio_id' => 305,
                'parroquia' => 'Campo Claro',
            ),
            291 => 
            array (
                'id' => 792,
                'municipio_id' => 305,
                'parroquia' => 'Maraval',
            ),
            292 => 
            array (
                'id' => 793,
                'municipio_id' => 305,
                'parroquia' => 'San Antonio de Irapa',
            ),
            293 => 
            array (
                'id' => 794,
                'municipio_id' => 305,
                'parroquia' => 'Soro',
            ),
            294 => 
            array (
                'id' => 795,
                'municipio_id' => 306,
                'parroquia' => 'Mejía',
            ),
            295 => 
            array (
                'id' => 796,
                'municipio_id' => 307,
                'parroquia' => 'Cumanacoa',
            ),
            296 => 
            array (
                'id' => 797,
                'municipio_id' => 307,
                'parroquia' => 'Arenas',
            ),
            297 => 
            array (
                'id' => 798,
                'municipio_id' => 307,
                'parroquia' => 'Aricagua',
            ),
            298 => 
            array (
                'id' => 799,
                'municipio_id' => 307,
                'parroquia' => 'Cogollar',
            ),
            299 => 
            array (
                'id' => 800,
                'municipio_id' => 307,
                'parroquia' => 'San Fernando',
            ),
            300 => 
            array (
                'id' => 801,
                'municipio_id' => 307,
                'parroquia' => 'San Lorenzo',
            ),
            301 => 
            array (
                'id' => 802,
                'municipio_id' => 308,
            'parroquia' => 'Villa Frontado (Muelle de Cariaco)',
            ),
            302 => 
            array (
                'id' => 803,
                'municipio_id' => 308,
                'parroquia' => 'Catuaro',
            ),
            303 => 
            array (
                'id' => 804,
                'municipio_id' => 308,
                'parroquia' => 'Rendón',
            ),
            304 => 
            array (
                'id' => 805,
                'municipio_id' => 308,
                'parroquia' => 'San Cruz',
            ),
            305 => 
            array (
                'id' => 806,
                'municipio_id' => 308,
                'parroquia' => 'Santa María',
            ),
            306 => 
            array (
                'id' => 807,
                'municipio_id' => 309,
                'parroquia' => 'Altagracia',
            ),
            307 => 
            array (
                'id' => 808,
                'municipio_id' => 309,
                'parroquia' => 'Santa Inés',
            ),
            308 => 
            array (
                'id' => 809,
                'municipio_id' => 309,
                'parroquia' => 'Valentín Valiente',
            ),
            309 => 
            array (
                'id' => 810,
                'municipio_id' => 309,
                'parroquia' => 'Ayacucho',
            ),
            310 => 
            array (
                'id' => 811,
                'municipio_id' => 309,
                'parroquia' => 'San Juan',
            ),
            311 => 
            array (
                'id' => 812,
                'municipio_id' => 309,
                'parroquia' => 'Raúl Leoni',
            ),
            312 => 
            array (
                'id' => 813,
                'municipio_id' => 309,
                'parroquia' => 'Gran Mariscal',
            ),
            313 => 
            array (
                'id' => 814,
                'municipio_id' => 310,
                'parroquia' => 'Cristóbal Colón',
            ),
            314 => 
            array (
                'id' => 815,
                'municipio_id' => 310,
                'parroquia' => 'Bideau',
            ),
            315 => 
            array (
                'id' => 816,
                'municipio_id' => 310,
                'parroquia' => 'Punta de Piedras',
            ),
            316 => 
            array (
                'id' => 817,
                'municipio_id' => 310,
                'parroquia' => 'Güiria',
            ),
            317 => 
            array (
                'id' => 818,
                'municipio_id' => 341,
                'parroquia' => 'Andrés Bello',
            ),
            318 => 
            array (
                'id' => 819,
                'municipio_id' => 342,
                'parroquia' => 'Antonio Rómulo Costa',
            ),
            319 => 
            array (
                'id' => 820,
                'municipio_id' => 343,
                'parroquia' => 'Ayacucho',
            ),
            320 => 
            array (
                'id' => 821,
                'municipio_id' => 343,
                'parroquia' => 'Rivas Berti',
            ),
            321 => 
            array (
                'id' => 822,
                'municipio_id' => 343,
                'parroquia' => 'San Pedro del Río',
            ),
            322 => 
            array (
                'id' => 823,
                'municipio_id' => 344,
                'parroquia' => 'Bolívar',
            ),
            323 => 
            array (
                'id' => 824,
                'municipio_id' => 344,
                'parroquia' => 'Palotal',
            ),
            324 => 
            array (
                'id' => 825,
                'municipio_id' => 344,
                'parroquia' => 'General Juan Vicente Gómez',
            ),
            325 => 
            array (
                'id' => 826,
                'municipio_id' => 344,
                'parroquia' => 'Isaías Medina Angarita',
            ),
            326 => 
            array (
                'id' => 827,
                'municipio_id' => 345,
                'parroquia' => 'Cárdenas',
            ),
            327 => 
            array (
                'id' => 828,
                'municipio_id' => 345,
                'parroquia' => 'Amenodoro Ángel Lamus',
            ),
            328 => 
            array (
                'id' => 829,
                'municipio_id' => 345,
                'parroquia' => 'La Florida',
            ),
            329 => 
            array (
                'id' => 830,
                'municipio_id' => 346,
                'parroquia' => 'Córdoba',
            ),
            330 => 
            array (
                'id' => 831,
                'municipio_id' => 347,
                'parroquia' => 'Fernández Feo',
            ),
            331 => 
            array (
                'id' => 832,
                'municipio_id' => 347,
                'parroquia' => 'Alberto Adriani',
            ),
            332 => 
            array (
                'id' => 833,
                'municipio_id' => 347,
                'parroquia' => 'Santo Domingo',
            ),
            333 => 
            array (
                'id' => 834,
                'municipio_id' => 348,
                'parroquia' => 'Francisco de Miranda',
            ),
            334 => 
            array (
                'id' => 835,
                'municipio_id' => 349,
                'parroquia' => 'García de Hevia',
            ),
            335 => 
            array (
                'id' => 836,
                'municipio_id' => 349,
                'parroquia' => 'Boca de Grita',
            ),
            336 => 
            array (
                'id' => 837,
                'municipio_id' => 349,
                'parroquia' => 'José Antonio Páez',
            ),
            337 => 
            array (
                'id' => 838,
                'municipio_id' => 350,
                'parroquia' => 'Guásimos',
            ),
            338 => 
            array (
                'id' => 839,
                'municipio_id' => 351,
                'parroquia' => 'Independencia',
            ),
            339 => 
            array (
                'id' => 840,
                'municipio_id' => 351,
                'parroquia' => 'Juan Germán Roscio',
            ),
            340 => 
            array (
                'id' => 841,
                'municipio_id' => 351,
                'parroquia' => 'Román Cárdenas',
            ),
            341 => 
            array (
                'id' => 842,
                'municipio_id' => 352,
                'parroquia' => 'Jáuregui',
            ),
            342 => 
            array (
                'id' => 843,
                'municipio_id' => 352,
                'parroquia' => 'Emilio Constantino Guerrero',
            ),
            343 => 
            array (
                'id' => 844,
                'municipio_id' => 352,
                'parroquia' => 'Monseñor Miguel Antonio Salas',
            ),
            344 => 
            array (
                'id' => 845,
                'municipio_id' => 353,
                'parroquia' => 'José María Vargas',
            ),
            345 => 
            array (
                'id' => 846,
                'municipio_id' => 354,
                'parroquia' => 'Junín',
            ),
            346 => 
            array (
                'id' => 847,
                'municipio_id' => 354,
                'parroquia' => 'La Petrólea',
            ),
            347 => 
            array (
                'id' => 848,
                'municipio_id' => 354,
                'parroquia' => 'Quinimarí',
            ),
            348 => 
            array (
                'id' => 849,
                'municipio_id' => 354,
                'parroquia' => 'Bramón',
            ),
            349 => 
            array (
                'id' => 850,
                'municipio_id' => 355,
                'parroquia' => 'Libertad',
            ),
            350 => 
            array (
                'id' => 851,
                'municipio_id' => 355,
                'parroquia' => 'Cipriano Castro',
            ),
            351 => 
            array (
                'id' => 852,
                'municipio_id' => 355,
                'parroquia' => 'Manuel Felipe Rugeles',
            ),
            352 => 
            array (
                'id' => 853,
                'municipio_id' => 356,
                'parroquia' => 'Libertador',
            ),
            353 => 
            array (
                'id' => 854,
                'municipio_id' => 356,
                'parroquia' => 'Doradas',
            ),
            354 => 
            array (
                'id' => 855,
                'municipio_id' => 356,
                'parroquia' => 'Emeterio Ochoa',
            ),
            355 => 
            array (
                'id' => 856,
                'municipio_id' => 356,
                'parroquia' => 'San Joaquín de Navay',
            ),
            356 => 
            array (
                'id' => 857,
                'municipio_id' => 357,
                'parroquia' => 'Lobatera',
            ),
            357 => 
            array (
                'id' => 858,
                'municipio_id' => 357,
                'parroquia' => 'Constitución',
            ),
            358 => 
            array (
                'id' => 859,
                'municipio_id' => 358,
                'parroquia' => 'Michelena',
            ),
            359 => 
            array (
                'id' => 860,
                'municipio_id' => 359,
                'parroquia' => 'Panamericano',
            ),
            360 => 
            array (
                'id' => 861,
                'municipio_id' => 359,
                'parroquia' => 'La Palmita',
            ),
            361 => 
            array (
                'id' => 862,
                'municipio_id' => 360,
                'parroquia' => 'Pedro María Ureña',
            ),
            362 => 
            array (
                'id' => 863,
                'municipio_id' => 360,
                'parroquia' => 'Nueva Arcadia',
            ),
            363 => 
            array (
                'id' => 864,
                'municipio_id' => 361,
                'parroquia' => 'Delicias',
            ),
            364 => 
            array (
                'id' => 865,
                'municipio_id' => 361,
                'parroquia' => 'Pecaya',
            ),
            365 => 
            array (
                'id' => 866,
                'municipio_id' => 362,
                'parroquia' => 'Samuel Darío Maldonado',
            ),
            366 => 
            array (
                'id' => 867,
                'municipio_id' => 362,
                'parroquia' => 'Boconó',
            ),
            367 => 
            array (
                'id' => 868,
                'municipio_id' => 362,
                'parroquia' => 'Hernández',
            ),
            368 => 
            array (
                'id' => 869,
                'municipio_id' => 363,
                'parroquia' => 'La Concordia',
            ),
            369 => 
            array (
                'id' => 870,
                'municipio_id' => 363,
                'parroquia' => 'San Juan Bautista',
            ),
            370 => 
            array (
                'id' => 871,
                'municipio_id' => 363,
                'parroquia' => 'Pedro María Morantes',
            ),
            371 => 
            array (
                'id' => 872,
                'municipio_id' => 363,
                'parroquia' => 'San Sebastián',
            ),
            372 => 
            array (
                'id' => 873,
                'municipio_id' => 363,
                'parroquia' => 'Dr. Francisco Romero Lobo',
            ),
            373 => 
            array (
                'id' => 874,
                'municipio_id' => 364,
                'parroquia' => 'Seboruco',
            ),
            374 => 
            array (
                'id' => 875,
                'municipio_id' => 365,
                'parroquia' => 'Simón Rodríguez',
            ),
            375 => 
            array (
                'id' => 876,
                'municipio_id' => 366,
                'parroquia' => 'Sucre',
            ),
            376 => 
            array (
                'id' => 877,
                'municipio_id' => 366,
                'parroquia' => 'Eleazar López Contreras',
            ),
            377 => 
            array (
                'id' => 878,
                'municipio_id' => 366,
                'parroquia' => 'San Pablo',
            ),
            378 => 
            array (
                'id' => 879,
                'municipio_id' => 367,
                'parroquia' => 'Torbes',
            ),
            379 => 
            array (
                'id' => 880,
                'municipio_id' => 368,
                'parroquia' => 'Uribante',
            ),
            380 => 
            array (
                'id' => 881,
                'municipio_id' => 368,
                'parroquia' => 'Cárdenas',
            ),
            381 => 
            array (
                'id' => 882,
                'municipio_id' => 368,
                'parroquia' => 'Juan Pablo Peñalosa',
            ),
            382 => 
            array (
                'id' => 883,
                'municipio_id' => 368,
                'parroquia' => 'Potosí',
            ),
            383 => 
            array (
                'id' => 884,
                'municipio_id' => 369,
                'parroquia' => 'San Judas Tadeo',
            ),
            384 => 
            array (
                'id' => 885,
                'municipio_id' => 370,
                'parroquia' => 'Araguaney',
            ),
            385 => 
            array (
                'id' => 886,
                'municipio_id' => 370,
                'parroquia' => 'El Jaguito',
            ),
            386 => 
            array (
                'id' => 887,
                'municipio_id' => 370,
                'parroquia' => 'La Esperanza',
            ),
            387 => 
            array (
                'id' => 888,
                'municipio_id' => 370,
                'parroquia' => 'Santa Isabel',
            ),
            388 => 
            array (
                'id' => 889,
                'municipio_id' => 371,
                'parroquia' => 'Boconó',
            ),
            389 => 
            array (
                'id' => 890,
                'municipio_id' => 371,
                'parroquia' => 'El Carmen',
            ),
            390 => 
            array (
                'id' => 891,
                'municipio_id' => 371,
                'parroquia' => 'Mosquey',
            ),
            391 => 
            array (
                'id' => 892,
                'municipio_id' => 371,
                'parroquia' => 'Ayacucho',
            ),
            392 => 
            array (
                'id' => 893,
                'municipio_id' => 371,
                'parroquia' => 'Burbusay',
            ),
            393 => 
            array (
                'id' => 894,
                'municipio_id' => 371,
                'parroquia' => 'General Ribas',
            ),
            394 => 
            array (
                'id' => 895,
                'municipio_id' => 371,
                'parroquia' => 'Guaramacal',
            ),
            395 => 
            array (
                'id' => 896,
                'municipio_id' => 371,
                'parroquia' => 'Vega de Guaramacal',
            ),
            396 => 
            array (
                'id' => 897,
                'municipio_id' => 371,
                'parroquia' => 'Monseñor Jáuregui',
            ),
            397 => 
            array (
                'id' => 898,
                'municipio_id' => 371,
                'parroquia' => 'Rafael Rangel',
            ),
            398 => 
            array (
                'id' => 899,
                'municipio_id' => 371,
                'parroquia' => 'San Miguel',
            ),
            399 => 
            array (
                'id' => 900,
                'municipio_id' => 371,
                'parroquia' => 'San José',
            ),
            400 => 
            array (
                'id' => 901,
                'municipio_id' => 372,
                'parroquia' => 'Sabana Grande',
            ),
            401 => 
            array (
                'id' => 902,
                'municipio_id' => 372,
                'parroquia' => 'Cheregüé',
            ),
            402 => 
            array (
                'id' => 903,
                'municipio_id' => 372,
                'parroquia' => 'Granados',
            ),
            403 => 
            array (
                'id' => 904,
                'municipio_id' => 373,
                'parroquia' => 'Arnoldo Gabaldón',
            ),
            404 => 
            array (
                'id' => 905,
                'municipio_id' => 373,
                'parroquia' => 'Bolivia',
            ),
            405 => 
            array (
                'id' => 906,
                'municipio_id' => 373,
                'parroquia' => 'Carrillo',
            ),
            406 => 
            array (
                'id' => 907,
                'municipio_id' => 373,
                'parroquia' => 'Cegarra',
            ),
            407 => 
            array (
                'id' => 908,
                'municipio_id' => 373,
                'parroquia' => 'Chejendé',
            ),
            408 => 
            array (
                'id' => 909,
                'municipio_id' => 373,
                'parroquia' => 'Manuel Salvador Ulloa',
            ),
            409 => 
            array (
                'id' => 910,
                'municipio_id' => 373,
                'parroquia' => 'San José',
            ),
            410 => 
            array (
                'id' => 911,
                'municipio_id' => 374,
                'parroquia' => 'Carache',
            ),
            411 => 
            array (
                'id' => 912,
                'municipio_id' => 374,
                'parroquia' => 'La Concepción',
            ),
            412 => 
            array (
                'id' => 913,
                'municipio_id' => 374,
                'parroquia' => 'Cuicas',
            ),
            413 => 
            array (
                'id' => 914,
                'municipio_id' => 374,
                'parroquia' => 'Panamericana',
            ),
            414 => 
            array (
                'id' => 915,
                'municipio_id' => 374,
                'parroquia' => 'Santa Cruz',
            ),
            415 => 
            array (
                'id' => 916,
                'municipio_id' => 375,
                'parroquia' => 'Escuque',
            ),
            416 => 
            array (
                'id' => 917,
                'municipio_id' => 375,
                'parroquia' => 'La Unión',
            ),
            417 => 
            array (
                'id' => 918,
                'municipio_id' => 375,
                'parroquia' => 'Santa Rita',
            ),
            418 => 
            array (
                'id' => 919,
                'municipio_id' => 375,
                'parroquia' => 'Sabana Libre',
            ),
            419 => 
            array (
                'id' => 920,
                'municipio_id' => 376,
                'parroquia' => 'El Socorro',
            ),
            420 => 
            array (
                'id' => 921,
                'municipio_id' => 376,
                'parroquia' => 'Los Caprichos',
            ),
            421 => 
            array (
                'id' => 922,
                'municipio_id' => 376,
                'parroquia' => 'Antonio José de Sucre',
            ),
            422 => 
            array (
                'id' => 923,
                'municipio_id' => 377,
                'parroquia' => 'Campo Elías',
            ),
            423 => 
            array (
                'id' => 924,
                'municipio_id' => 377,
                'parroquia' => 'Arnoldo Gabaldón',
            ),
            424 => 
            array (
                'id' => 925,
                'municipio_id' => 378,
                'parroquia' => 'Santa Apolonia',
            ),
            425 => 
            array (
                'id' => 926,
                'municipio_id' => 378,
                'parroquia' => 'El Progreso',
            ),
            426 => 
            array (
                'id' => 927,
                'municipio_id' => 378,
                'parroquia' => 'La Ceiba',
            ),
            427 => 
            array (
                'id' => 928,
                'municipio_id' => 378,
                'parroquia' => 'Tres de Febrero',
            ),
            428 => 
            array (
                'id' => 929,
                'municipio_id' => 379,
                'parroquia' => 'El Dividive',
            ),
            429 => 
            array (
                'id' => 930,
                'municipio_id' => 379,
                'parroquia' => 'Agua Santa',
            ),
            430 => 
            array (
                'id' => 931,
                'municipio_id' => 379,
                'parroquia' => 'Agua Caliente',
            ),
            431 => 
            array (
                'id' => 932,
                'municipio_id' => 379,
                'parroquia' => 'El Cenizo',
            ),
            432 => 
            array (
                'id' => 933,
                'municipio_id' => 379,
                'parroquia' => 'Valerita',
            ),
            433 => 
            array (
                'id' => 934,
                'municipio_id' => 380,
                'parroquia' => 'Monte Carmelo',
            ),
            434 => 
            array (
                'id' => 935,
                'municipio_id' => 380,
                'parroquia' => 'Buena Vista',
            ),
            435 => 
            array (
                'id' => 936,
                'municipio_id' => 380,
                'parroquia' => 'Santa María del Horcón',
            ),
            436 => 
            array (
                'id' => 937,
                'municipio_id' => 381,
                'parroquia' => 'Motatán',
            ),
            437 => 
            array (
                'id' => 938,
                'municipio_id' => 381,
                'parroquia' => 'El Baño',
            ),
            438 => 
            array (
                'id' => 939,
                'municipio_id' => 381,
                'parroquia' => 'Jalisco',
            ),
            439 => 
            array (
                'id' => 940,
                'municipio_id' => 382,
                'parroquia' => 'Pampán',
            ),
            440 => 
            array (
                'id' => 941,
                'municipio_id' => 382,
                'parroquia' => 'Flor de Patria',
            ),
            441 => 
            array (
                'id' => 942,
                'municipio_id' => 382,
                'parroquia' => 'La Paz',
            ),
            442 => 
            array (
                'id' => 943,
                'municipio_id' => 382,
                'parroquia' => 'Santa Ana',
            ),
            443 => 
            array (
                'id' => 944,
                'municipio_id' => 383,
                'parroquia' => 'Pampanito',
            ),
            444 => 
            array (
                'id' => 945,
                'municipio_id' => 383,
                'parroquia' => 'La Concepción',
            ),
            445 => 
            array (
                'id' => 946,
                'municipio_id' => 383,
                'parroquia' => 'Pampanito II',
            ),
            446 => 
            array (
                'id' => 947,
                'municipio_id' => 384,
                'parroquia' => 'Betijoque',
            ),
            447 => 
            array (
                'id' => 948,
                'municipio_id' => 384,
                'parroquia' => 'José Gregorio Hernández',
            ),
            448 => 
            array (
                'id' => 949,
                'municipio_id' => 384,
                'parroquia' => 'La Pueblita',
            ),
            449 => 
            array (
                'id' => 950,
                'municipio_id' => 384,
                'parroquia' => 'Los Cedros',
            ),
            450 => 
            array (
                'id' => 951,
                'municipio_id' => 385,
                'parroquia' => 'Carvajal',
            ),
            451 => 
            array (
                'id' => 952,
                'municipio_id' => 385,
                'parroquia' => 'Campo Alegre',
            ),
            452 => 
            array (
                'id' => 953,
                'municipio_id' => 385,
                'parroquia' => 'Antonio Nicolás Briceño',
            ),
            453 => 
            array (
                'id' => 954,
                'municipio_id' => 385,
                'parroquia' => 'José Leonardo Suárez',
            ),
            454 => 
            array (
                'id' => 955,
                'municipio_id' => 386,
                'parroquia' => 'Sabana de Mendoza',
            ),
            455 => 
            array (
                'id' => 956,
                'municipio_id' => 386,
                'parroquia' => 'Junín',
            ),
            456 => 
            array (
                'id' => 957,
                'municipio_id' => 386,
                'parroquia' => 'Valmore Rodríguez',
            ),
            457 => 
            array (
                'id' => 958,
                'municipio_id' => 386,
                'parroquia' => 'El Paraíso',
            ),
            458 => 
            array (
                'id' => 959,
                'municipio_id' => 387,
                'parroquia' => 'Andrés Linares',
            ),
            459 => 
            array (
                'id' => 960,
                'municipio_id' => 387,
                'parroquia' => 'Chiquinquirá',
            ),
            460 => 
            array (
                'id' => 961,
                'municipio_id' => 387,
                'parroquia' => 'Cristóbal Mendoza',
            ),
            461 => 
            array (
                'id' => 962,
                'municipio_id' => 387,
                'parroquia' => 'Cruz Carrillo',
            ),
            462 => 
            array (
                'id' => 963,
                'municipio_id' => 387,
                'parroquia' => 'Matriz',
            ),
            463 => 
            array (
                'id' => 964,
                'municipio_id' => 387,
                'parroquia' => 'Monseñor Carrillo',
            ),
            464 => 
            array (
                'id' => 965,
                'municipio_id' => 387,
                'parroquia' => 'Tres Esquinas',
            ),
            465 => 
            array (
                'id' => 966,
                'municipio_id' => 388,
                'parroquia' => 'Cabimbú',
            ),
            466 => 
            array (
                'id' => 967,
                'municipio_id' => 388,
                'parroquia' => 'Jajó',
            ),
            467 => 
            array (
                'id' => 968,
                'municipio_id' => 388,
                'parroquia' => 'La Mesa de Esnujaque',
            ),
            468 => 
            array (
                'id' => 969,
                'municipio_id' => 388,
                'parroquia' => 'Santiago',
            ),
            469 => 
            array (
                'id' => 970,
                'municipio_id' => 388,
                'parroquia' => 'Tuñame',
            ),
            470 => 
            array (
                'id' => 971,
                'municipio_id' => 388,
                'parroquia' => 'La Quebrada',
            ),
            471 => 
            array (
                'id' => 972,
                'municipio_id' => 389,
                'parroquia' => 'Juan Ignacio Montilla',
            ),
            472 => 
            array (
                'id' => 973,
                'municipio_id' => 389,
                'parroquia' => 'La Beatriz',
            ),
            473 => 
            array (
                'id' => 974,
                'municipio_id' => 389,
                'parroquia' => 'La Puerta',
            ),
            474 => 
            array (
                'id' => 975,
                'municipio_id' => 389,
                'parroquia' => 'Mendoza del Valle de Momboy',
            ),
            475 => 
            array (
                'id' => 976,
                'municipio_id' => 389,
                'parroquia' => 'Mercedes Díaz',
            ),
            476 => 
            array (
                'id' => 977,
                'municipio_id' => 389,
                'parroquia' => 'San Luis',
            ),
            477 => 
            array (
                'id' => 978,
                'municipio_id' => 390,
                'parroquia' => 'Caraballeda',
            ),
            478 => 
            array (
                'id' => 979,
                'municipio_id' => 390,
                'parroquia' => 'Carayaca',
            ),
            479 => 
            array (
                'id' => 980,
                'municipio_id' => 390,
                'parroquia' => 'Carlos Soublette',
            ),
            480 => 
            array (
                'id' => 981,
                'municipio_id' => 390,
                'parroquia' => 'Caruao Chuspa',
            ),
            481 => 
            array (
                'id' => 982,
                'municipio_id' => 390,
                'parroquia' => 'Catia La Mar',
            ),
            482 => 
            array (
                'id' => 983,
                'municipio_id' => 390,
                'parroquia' => 'El Junko',
            ),
            483 => 
            array (
                'id' => 984,
                'municipio_id' => 390,
                'parroquia' => 'La Guaira',
            ),
            484 => 
            array (
                'id' => 985,
                'municipio_id' => 390,
                'parroquia' => 'Macuto',
            ),
            485 => 
            array (
                'id' => 986,
                'municipio_id' => 390,
                'parroquia' => 'Maiquetía',
            ),
            486 => 
            array (
                'id' => 987,
                'municipio_id' => 390,
                'parroquia' => 'Naiguatá',
            ),
            487 => 
            array (
                'id' => 988,
                'municipio_id' => 390,
                'parroquia' => 'Urimare',
            ),
            488 => 
            array (
                'id' => 989,
                'municipio_id' => 391,
                'parroquia' => 'Arístides Bastidas',
            ),
            489 => 
            array (
                'id' => 990,
                'municipio_id' => 392,
                'parroquia' => 'Bolívar',
            ),
            490 => 
            array (
                'id' => 991,
                'municipio_id' => 407,
                'parroquia' => 'Chivacoa',
            ),
            491 => 
            array (
                'id' => 992,
                'municipio_id' => 407,
                'parroquia' => 'Campo Elías',
            ),
            492 => 
            array (
                'id' => 993,
                'municipio_id' => 408,
                'parroquia' => 'Cocorote',
            ),
            493 => 
            array (
                'id' => 994,
                'municipio_id' => 409,
                'parroquia' => 'Independencia',
            ),
            494 => 
            array (
                'id' => 995,
                'municipio_id' => 410,
                'parroquia' => 'José Antonio Páez',
            ),
            495 => 
            array (
                'id' => 996,
                'municipio_id' => 411,
                'parroquia' => 'La Trinidad',
            ),
            496 => 
            array (
                'id' => 997,
                'municipio_id' => 412,
                'parroquia' => 'Manuel Monge',
            ),
            497 => 
            array (
                'id' => 998,
                'municipio_id' => 413,
                'parroquia' => 'Salóm',
            ),
            498 => 
            array (
                'id' => 999,
                'municipio_id' => 413,
                'parroquia' => 'Temerla',
            ),
            499 => 
            array (
                'id' => 1000,
                'municipio_id' => 413,
                'parroquia' => 'Nirgua',
            ),
        ));
        \DB::table('parroquias')->insert(array (
            0 => 
            array (
                'id' => 1001,
                'municipio_id' => 414,
                'parroquia' => 'San Andrés',
            ),
            1 => 
            array (
                'id' => 1002,
                'municipio_id' => 414,
                'parroquia' => 'Yaritagua',
            ),
            2 => 
            array (
                'id' => 1003,
                'municipio_id' => 415,
                'parroquia' => 'San Javier',
            ),
            3 => 
            array (
                'id' => 1004,
                'municipio_id' => 415,
                'parroquia' => 'Albarico',
            ),
            4 => 
            array (
                'id' => 1005,
                'municipio_id' => 415,
                'parroquia' => 'San Felipe',
            ),
            5 => 
            array (
                'id' => 1006,
                'municipio_id' => 416,
                'parroquia' => 'Sucre',
            ),
            6 => 
            array (
                'id' => 1007,
                'municipio_id' => 417,
                'parroquia' => 'Urachiche',
            ),
            7 => 
            array (
                'id' => 1008,
                'municipio_id' => 418,
                'parroquia' => 'El Guayabo',
            ),
            8 => 
            array (
                'id' => 1009,
                'municipio_id' => 418,
                'parroquia' => 'Farriar',
            ),
            9 => 
            array (
                'id' => 1010,
                'municipio_id' => 441,
                'parroquia' => 'Isla de Toas',
            ),
            10 => 
            array (
                'id' => 1011,
                'municipio_id' => 441,
                'parroquia' => 'Monagas',
            ),
            11 => 
            array (
                'id' => 1012,
                'municipio_id' => 442,
                'parroquia' => 'San Timoteo',
            ),
            12 => 
            array (
                'id' => 1013,
                'municipio_id' => 442,
                'parroquia' => 'General Urdaneta',
            ),
            13 => 
            array (
                'id' => 1014,
                'municipio_id' => 442,
                'parroquia' => 'Libertador',
            ),
            14 => 
            array (
                'id' => 1015,
                'municipio_id' => 442,
                'parroquia' => 'Marcelino Briceño',
            ),
            15 => 
            array (
                'id' => 1016,
                'municipio_id' => 442,
                'parroquia' => 'Pueblo Nuevo',
            ),
            16 => 
            array (
                'id' => 1017,
                'municipio_id' => 442,
                'parroquia' => 'Manuel Guanipa Matos',
            ),
            17 => 
            array (
                'id' => 1018,
                'municipio_id' => 443,
                'parroquia' => 'Ambrosio',
            ),
            18 => 
            array (
                'id' => 1019,
                'municipio_id' => 443,
                'parroquia' => 'Carmen Herrera',
            ),
            19 => 
            array (
                'id' => 1020,
                'municipio_id' => 443,
                'parroquia' => 'La Rosa',
            ),
            20 => 
            array (
                'id' => 1021,
                'municipio_id' => 443,
                'parroquia' => 'Germán Ríos Linares',
            ),
            21 => 
            array (
                'id' => 1022,
                'municipio_id' => 443,
                'parroquia' => 'San Benito',
            ),
            22 => 
            array (
                'id' => 1023,
                'municipio_id' => 443,
                'parroquia' => 'Rómulo Betancourt',
            ),
            23 => 
            array (
                'id' => 1024,
                'municipio_id' => 443,
                'parroquia' => 'Jorge Hernández',
            ),
            24 => 
            array (
                'id' => 1025,
                'municipio_id' => 443,
                'parroquia' => 'Punta Gorda',
            ),
            25 => 
            array (
                'id' => 1026,
                'municipio_id' => 443,
                'parroquia' => 'Arístides Calvani',
            ),
            26 => 
            array (
                'id' => 1027,
                'municipio_id' => 444,
                'parroquia' => 'Encontrados',
            ),
            27 => 
            array (
                'id' => 1028,
                'municipio_id' => 444,
                'parroquia' => 'Udón Pérez',
            ),
            28 => 
            array (
                'id' => 1029,
                'municipio_id' => 445,
                'parroquia' => 'Moralito',
            ),
            29 => 
            array (
                'id' => 1030,
                'municipio_id' => 445,
                'parroquia' => 'San Carlos del Zulia',
            ),
            30 => 
            array (
                'id' => 1031,
                'municipio_id' => 445,
                'parroquia' => 'Santa Cruz del Zulia',
            ),
            31 => 
            array (
                'id' => 1032,
                'municipio_id' => 445,
                'parroquia' => 'Santa Bárbara',
            ),
            32 => 
            array (
                'id' => 1033,
                'municipio_id' => 445,
                'parroquia' => 'Urribarrí',
            ),
            33 => 
            array (
                'id' => 1034,
                'municipio_id' => 446,
                'parroquia' => 'Carlos Quevedo',
            ),
            34 => 
            array (
                'id' => 1035,
                'municipio_id' => 446,
                'parroquia' => 'Francisco Javier Pulgar',
            ),
            35 => 
            array (
                'id' => 1036,
                'municipio_id' => 446,
                'parroquia' => 'Simón Rodríguez',
            ),
            36 => 
            array (
                'id' => 1037,
                'municipio_id' => 446,
                'parroquia' => 'Guamo-Gavilanes',
            ),
            37 => 
            array (
                'id' => 1038,
                'municipio_id' => 448,
                'parroquia' => 'La Concepción',
            ),
            38 => 
            array (
                'id' => 1039,
                'municipio_id' => 448,
                'parroquia' => 'San José',
            ),
            39 => 
            array (
                'id' => 1040,
                'municipio_id' => 448,
                'parroquia' => 'Mariano Parra León',
            ),
            40 => 
            array (
                'id' => 1041,
                'municipio_id' => 448,
                'parroquia' => 'José Ramón Yépez',
            ),
            41 => 
            array (
                'id' => 1042,
                'municipio_id' => 449,
                'parroquia' => 'Jesús María Semprún',
            ),
            42 => 
            array (
                'id' => 1043,
                'municipio_id' => 449,
                'parroquia' => 'Barí',
            ),
            43 => 
            array (
                'id' => 1044,
                'municipio_id' => 450,
                'parroquia' => 'Concepción',
            ),
            44 => 
            array (
                'id' => 1045,
                'municipio_id' => 450,
                'parroquia' => 'Andrés Bello',
            ),
            45 => 
            array (
                'id' => 1046,
                'municipio_id' => 450,
                'parroquia' => 'Chiquinquirá',
            ),
            46 => 
            array (
                'id' => 1047,
                'municipio_id' => 450,
                'parroquia' => 'El Carmelo',
            ),
            47 => 
            array (
                'id' => 1048,
                'municipio_id' => 450,
                'parroquia' => 'Potreritos',
            ),
            48 => 
            array (
                'id' => 1049,
                'municipio_id' => 451,
                'parroquia' => 'Libertad',
            ),
            49 => 
            array (
                'id' => 1050,
                'municipio_id' => 451,
                'parroquia' => 'Alonso de Ojeda',
            ),
            50 => 
            array (
                'id' => 1051,
                'municipio_id' => 451,
                'parroquia' => 'Venezuela',
            ),
            51 => 
            array (
                'id' => 1052,
                'municipio_id' => 451,
                'parroquia' => 'Eleazar López Contreras',
            ),
            52 => 
            array (
                'id' => 1053,
                'municipio_id' => 451,
                'parroquia' => 'Campo Lara',
            ),
            53 => 
            array (
                'id' => 1054,
                'municipio_id' => 452,
                'parroquia' => 'Bartolomé de las Casas',
            ),
            54 => 
            array (
                'id' => 1055,
                'municipio_id' => 452,
                'parroquia' => 'Libertad',
            ),
            55 => 
            array (
                'id' => 1056,
                'municipio_id' => 452,
                'parroquia' => 'Río Negro',
            ),
            56 => 
            array (
                'id' => 1057,
                'municipio_id' => 452,
                'parroquia' => 'San José de Perijá',
            ),
            57 => 
            array (
                'id' => 1058,
                'municipio_id' => 453,
                'parroquia' => 'San Rafael',
            ),
            58 => 
            array (
                'id' => 1059,
                'municipio_id' => 453,
                'parroquia' => 'La Sierrita',
            ),
            59 => 
            array (
                'id' => 1060,
                'municipio_id' => 453,
                'parroquia' => 'Las Parcelas',
            ),
            60 => 
            array (
                'id' => 1061,
                'municipio_id' => 453,
                'parroquia' => 'Luis de Vicente',
            ),
            61 => 
            array (
                'id' => 1062,
                'municipio_id' => 453,
                'parroquia' => 'Monseñor Marcos Sergio Godoy',
            ),
            62 => 
            array (
                'id' => 1063,
                'municipio_id' => 453,
                'parroquia' => 'Ricaurte',
            ),
            63 => 
            array (
                'id' => 1064,
                'municipio_id' => 453,
                'parroquia' => 'Tamare',
            ),
            64 => 
            array (
                'id' => 1065,
                'municipio_id' => 454,
                'parroquia' => 'Antonio Borjas Romero',
            ),
            65 => 
            array (
                'id' => 1066,
                'municipio_id' => 454,
                'parroquia' => 'Bolívar',
            ),
            66 => 
            array (
                'id' => 1067,
                'municipio_id' => 454,
                'parroquia' => 'Cacique Mara',
            ),
            67 => 
            array (
                'id' => 1068,
                'municipio_id' => 454,
                'parroquia' => 'Carracciolo Parra Pérez',
            ),
            68 => 
            array (
                'id' => 1069,
                'municipio_id' => 454,
                'parroquia' => 'Cecilio Acosta',
            ),
            69 => 
            array (
                'id' => 1070,
                'municipio_id' => 454,
                'parroquia' => 'Cristo de Aranza',
            ),
            70 => 
            array (
                'id' => 1071,
                'municipio_id' => 454,
                'parroquia' => 'Coquivacoa',
            ),
            71 => 
            array (
                'id' => 1072,
                'municipio_id' => 454,
                'parroquia' => 'Chiquinquirá',
            ),
            72 => 
            array (
                'id' => 1073,
                'municipio_id' => 454,
                'parroquia' => 'Francisco Eugenio Bustamante',
            ),
            73 => 
            array (
                'id' => 1074,
                'municipio_id' => 454,
                'parroquia' => 'Idelfonzo Vásquez',
            ),
            74 => 
            array (
                'id' => 1075,
                'municipio_id' => 454,
                'parroquia' => 'Juana de Ávila',
            ),
            75 => 
            array (
                'id' => 1076,
                'municipio_id' => 454,
                'parroquia' => 'Luis Hurtado Higuera',
            ),
            76 => 
            array (
                'id' => 1077,
                'municipio_id' => 454,
                'parroquia' => 'Manuel Dagnino',
            ),
            77 => 
            array (
                'id' => 1078,
                'municipio_id' => 454,
                'parroquia' => 'Olegario Villalobos',
            ),
            78 => 
            array (
                'id' => 1079,
                'municipio_id' => 454,
                'parroquia' => 'Raúl Leoni',
            ),
            79 => 
            array (
                'id' => 1080,
                'municipio_id' => 454,
                'parroquia' => 'Santa Lucía',
            ),
            80 => 
            array (
                'id' => 1081,
                'municipio_id' => 454,
                'parroquia' => 'Venancio Pulgar',
            ),
            81 => 
            array (
                'id' => 1082,
                'municipio_id' => 454,
                'parroquia' => 'San Isidro',
            ),
            82 => 
            array (
                'id' => 1083,
                'municipio_id' => 455,
                'parroquia' => 'Altagracia',
            ),
            83 => 
            array (
                'id' => 1084,
                'municipio_id' => 455,
                'parroquia' => 'Faría',
            ),
            84 => 
            array (
                'id' => 1085,
                'municipio_id' => 455,
                'parroquia' => 'Ana María Campos',
            ),
            85 => 
            array (
                'id' => 1086,
                'municipio_id' => 455,
                'parroquia' => 'San Antonio',
            ),
            86 => 
            array (
                'id' => 1087,
                'municipio_id' => 455,
                'parroquia' => 'San José',
            ),
            87 => 
            array (
                'id' => 1088,
                'municipio_id' => 456,
                'parroquia' => 'Donaldo García',
            ),
            88 => 
            array (
                'id' => 1089,
                'municipio_id' => 456,
                'parroquia' => 'El Rosario',
            ),
            89 => 
            array (
                'id' => 1090,
                'municipio_id' => 456,
                'parroquia' => 'Sixto Zambrano',
            ),
            90 => 
            array (
                'id' => 1091,
                'municipio_id' => 457,
                'parroquia' => 'San Francisco',
            ),
            91 => 
            array (
                'id' => 1092,
                'municipio_id' => 457,
                'parroquia' => 'El Bajo',
            ),
            92 => 
            array (
                'id' => 1093,
                'municipio_id' => 457,
                'parroquia' => 'Domitila Flores',
            ),
            93 => 
            array (
                'id' => 1094,
                'municipio_id' => 457,
                'parroquia' => 'Francisco Ochoa',
            ),
            94 => 
            array (
                'id' => 1095,
                'municipio_id' => 457,
                'parroquia' => 'Los Cortijos',
            ),
            95 => 
            array (
                'id' => 1096,
                'municipio_id' => 457,
                'parroquia' => 'Marcial Hernández',
            ),
            96 => 
            array (
                'id' => 1097,
                'municipio_id' => 458,
                'parroquia' => 'Santa Rita',
            ),
            97 => 
            array (
                'id' => 1098,
                'municipio_id' => 458,
                'parroquia' => 'El Mene',
            ),
            98 => 
            array (
                'id' => 1099,
                'municipio_id' => 458,
                'parroquia' => 'Pedro Lucas Urribarrí',
            ),
            99 => 
            array (
                'id' => 1100,
                'municipio_id' => 458,
                'parroquia' => 'José Cenobio Urribarrí',
            ),
            100 => 
            array (
                'id' => 1101,
                'municipio_id' => 459,
                'parroquia' => 'Rafael Maria Baralt',
            ),
            101 => 
            array (
                'id' => 1102,
                'municipio_id' => 459,
                'parroquia' => 'Manuel Manrique',
            ),
            102 => 
            array (
                'id' => 1103,
                'municipio_id' => 459,
                'parroquia' => 'Rafael Urdaneta',
            ),
            103 => 
            array (
                'id' => 1104,
                'municipio_id' => 460,
                'parroquia' => 'Bobures',
            ),
            104 => 
            array (
                'id' => 1105,
                'municipio_id' => 460,
                'parroquia' => 'Gibraltar',
            ),
            105 => 
            array (
                'id' => 1106,
                'municipio_id' => 460,
                'parroquia' => 'Heras',
            ),
            106 => 
            array (
                'id' => 1107,
                'municipio_id' => 460,
                'parroquia' => 'Monseñor Arturo Álvarez',
            ),
            107 => 
            array (
                'id' => 1108,
                'municipio_id' => 460,
                'parroquia' => 'Rómulo Gallegos',
            ),
            108 => 
            array (
                'id' => 1109,
                'municipio_id' => 460,
                'parroquia' => 'El Batey',
            ),
            109 => 
            array (
                'id' => 1110,
                'municipio_id' => 461,
                'parroquia' => 'Rafael Urdaneta',
            ),
            110 => 
            array (
                'id' => 1111,
                'municipio_id' => 461,
                'parroquia' => 'La Victoria',
            ),
            111 => 
            array (
                'id' => 1112,
                'municipio_id' => 461,
                'parroquia' => 'Raúl Cuenca',
            ),
            112 => 
            array (
                'id' => 1113,
                'municipio_id' => 447,
                'parroquia' => 'Sinamaica',
            ),
            113 => 
            array (
                'id' => 1114,
                'municipio_id' => 447,
                'parroquia' => 'Alta Guajira',
            ),
            114 => 
            array (
                'id' => 1115,
                'municipio_id' => 447,
                'parroquia' => 'Elías Sánchez Rubio',
            ),
            115 => 
            array (
                'id' => 1116,
                'municipio_id' => 447,
                'parroquia' => 'Guajira',
            ),
            116 => 
            array (
                'id' => 1117,
                'municipio_id' => 462,
                'parroquia' => 'Altagracia',
            ),
            117 => 
            array (
                'id' => 1118,
                'municipio_id' => 462,
                'parroquia' => 'Antímano',
            ),
            118 => 
            array (
                'id' => 1119,
                'municipio_id' => 462,
                'parroquia' => 'Caricuao',
            ),
            119 => 
            array (
                'id' => 1120,
                'municipio_id' => 462,
                'parroquia' => 'Catedral',
            ),
            120 => 
            array (
                'id' => 1121,
                'municipio_id' => 462,
                'parroquia' => 'Coche',
            ),
            121 => 
            array (
                'id' => 1122,
                'municipio_id' => 462,
                'parroquia' => 'El Junquito',
            ),
            122 => 
            array (
                'id' => 1123,
                'municipio_id' => 462,
                'parroquia' => 'El Paraíso',
            ),
            123 => 
            array (
                'id' => 1124,
                'municipio_id' => 462,
                'parroquia' => 'El Recreo',
            ),
            124 => 
            array (
                'id' => 1125,
                'municipio_id' => 462,
                'parroquia' => 'El Valle',
            ),
            125 => 
            array (
                'id' => 1126,
                'municipio_id' => 462,
                'parroquia' => 'La Candelaria',
            ),
            126 => 
            array (
                'id' => 1127,
                'municipio_id' => 462,
                'parroquia' => 'La Pastora',
            ),
            127 => 
            array (
                'id' => 1128,
                'municipio_id' => 462,
                'parroquia' => 'La Vega',
            ),
            128 => 
            array (
                'id' => 1129,
                'municipio_id' => 462,
                'parroquia' => 'Macarao',
            ),
            129 => 
            array (
                'id' => 1130,
                'municipio_id' => 462,
                'parroquia' => 'San Agustín',
            ),
            130 => 
            array (
                'id' => 1131,
                'municipio_id' => 462,
                'parroquia' => 'San Bernardino',
            ),
            131 => 
            array (
                'id' => 1132,
                'municipio_id' => 462,
                'parroquia' => 'San José',
            ),
            132 => 
            array (
                'id' => 1133,
                'municipio_id' => 462,
                'parroquia' => 'San Juan',
            ),
            133 => 
            array (
                'id' => 1134,
                'municipio_id' => 462,
                'parroquia' => 'San Pedro',
            ),
            134 => 
            array (
                'id' => 1135,
                'municipio_id' => 462,
                'parroquia' => 'Santa Rosalía',
            ),
            135 => 
            array (
                'id' => 1136,
                'municipio_id' => 462,
                'parroquia' => 'Santa Teresa',
            ),
            136 => 
            array (
                'id' => 1137,
                'municipio_id' => 462,
            'parroquia' => 'Sucre (Catia)',
            ),
            137 => 
            array (
                'id' => 1138,
                'municipio_id' => 462,
                'parroquia' => '23 de enero',
            ),
        ));
        
        
    }
}
