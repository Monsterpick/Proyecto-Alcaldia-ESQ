<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        

        \DB::table('municipios')->delete();
        
        \DB::table('municipios')->insert(array (
            0 => 
            array (
                'id' => 1,
                'estado_id' => 1,
                'municipio' => 'Alto Orinoco',
            ),
            1 => 
            array (
                'id' => 2,
                'estado_id' => 1,
                'municipio' => 'Atabapo',
            ),
            2 => 
            array (
                'id' => 3,
                'estado_id' => 1,
                'municipio' => 'Atures',
            ),
            3 => 
            array (
                'id' => 4,
                'estado_id' => 1,
                'municipio' => 'Autana',
            ),
            4 => 
            array (
                'id' => 5,
                'estado_id' => 1,
                'municipio' => 'Manapiare',
            ),
            5 => 
            array (
                'id' => 6,
                'estado_id' => 1,
                'municipio' => 'Maroa',
            ),
            6 => 
            array (
                'id' => 7,
                'estado_id' => 1,
                'municipio' => 'Río Negro',
            ),
            7 => 
            array (
                'id' => 8,
                'estado_id' => 2,
                'municipio' => 'Anaco',
            ),
            8 => 
            array (
                'id' => 9,
                'estado_id' => 2,
                'municipio' => 'Aragua',
            ),
            9 => 
            array (
                'id' => 10,
                'estado_id' => 2,
                'municipio' => 'Manuel Ezequiel Bruzual',
            ),
            10 => 
            array (
                'id' => 11,
                'estado_id' => 2,
                'municipio' => 'Diego Bautista Urbaneja',
            ),
            11 => 
            array (
                'id' => 12,
                'estado_id' => 2,
                'municipio' => 'Fernando Peñalver',
            ),
            12 => 
            array (
                'id' => 13,
                'estado_id' => 2,
                'municipio' => 'Francisco Del Carmen Carvajal',
            ),
            13 => 
            array (
                'id' => 14,
                'estado_id' => 2,
                'municipio' => 'General Sir Arthur McGregor',
            ),
            14 => 
            array (
                'id' => 15,
                'estado_id' => 2,
                'municipio' => 'Guanta',
            ),
            15 => 
            array (
                'id' => 16,
                'estado_id' => 2,
                'municipio' => 'Independencia',
            ),
            16 => 
            array (
                'id' => 17,
                'estado_id' => 2,
                'municipio' => 'José Gregorio Monagas',
            ),
            17 => 
            array (
                'id' => 18,
                'estado_id' => 2,
                'municipio' => 'Juan Antonio Sotillo',
            ),
            18 => 
            array (
                'id' => 19,
                'estado_id' => 2,
                'municipio' => 'Juan Manuel Cajigal',
            ),
            19 => 
            array (
                'id' => 20,
                'estado_id' => 2,
                'municipio' => 'Libertad',
            ),
            20 => 
            array (
                'id' => 21,
                'estado_id' => 2,
                'municipio' => 'Francisco de Miranda',
            ),
            21 => 
            array (
                'id' => 22,
                'estado_id' => 2,
                'municipio' => 'Pedro María Freites',
            ),
            22 => 
            array (
                'id' => 23,
                'estado_id' => 2,
                'municipio' => 'Píritu',
            ),
            23 => 
            array (
                'id' => 24,
                'estado_id' => 2,
                'municipio' => 'San José de Guanipa',
            ),
            24 => 
            array (
                'id' => 25,
                'estado_id' => 2,
                'municipio' => 'San Juan de Capistrano',
            ),
            25 => 
            array (
                'id' => 26,
                'estado_id' => 2,
                'municipio' => 'Santa Ana',
            ),
            26 => 
            array (
                'id' => 27,
                'estado_id' => 2,
                'municipio' => 'Simón Bolívar',
            ),
            27 => 
            array (
                'id' => 28,
                'estado_id' => 2,
                'municipio' => 'Simón Rodríguez',
            ),
            28 => 
            array (
                'id' => 29,
                'estado_id' => 3,
                'municipio' => 'Achaguas',
            ),
            29 => 
            array (
                'id' => 30,
                'estado_id' => 3,
                'municipio' => 'Biruaca',
            ),
            30 => 
            array (
                'id' => 31,
                'estado_id' => 3,
                'municipio' => 'Muñóz',
            ),
            31 => 
            array (
                'id' => 32,
                'estado_id' => 3,
                'municipio' => 'Páez',
            ),
            32 => 
            array (
                'id' => 33,
                'estado_id' => 3,
                'municipio' => 'Pedro Camejo',
            ),
            33 => 
            array (
                'id' => 34,
                'estado_id' => 3,
                'municipio' => 'Rómulo Gallegos',
            ),
            34 => 
            array (
                'id' => 35,
                'estado_id' => 3,
                'municipio' => 'San Fernando',
            ),
            35 => 
            array (
                'id' => 36,
                'estado_id' => 4,
                'municipio' => 'Atanasio Girardot',
            ),
            36 => 
            array (
                'id' => 37,
                'estado_id' => 4,
                'municipio' => 'Bolívar',
            ),
            37 => 
            array (
                'id' => 38,
                'estado_id' => 4,
                'municipio' => 'Camatagua',
            ),
            38 => 
            array (
                'id' => 39,
                'estado_id' => 4,
                'municipio' => 'Francisco Linares Alcántara',
            ),
            39 => 
            array (
                'id' => 40,
                'estado_id' => 4,
                'municipio' => 'José Ángel Lamas',
            ),
            40 => 
            array (
                'id' => 41,
                'estado_id' => 4,
                'municipio' => 'José Félix Ribas',
            ),
            41 => 
            array (
                'id' => 42,
                'estado_id' => 4,
                'municipio' => 'José Rafael Revenga',
            ),
            42 => 
            array (
                'id' => 43,
                'estado_id' => 4,
                'municipio' => 'Libertador',
            ),
            43 => 
            array (
                'id' => 44,
                'estado_id' => 4,
                'municipio' => 'Mario Briceño Iragorry',
            ),
            44 => 
            array (
                'id' => 45,
                'estado_id' => 4,
                'municipio' => 'Ocumare de la Costa de Oro',
            ),
            45 => 
            array (
                'id' => 46,
                'estado_id' => 4,
                'municipio' => 'San Casimiro',
            ),
            46 => 
            array (
                'id' => 47,
                'estado_id' => 4,
                'municipio' => 'San Sebastián',
            ),
            47 => 
            array (
                'id' => 48,
                'estado_id' => 4,
                'municipio' => 'Santiago Mariño',
            ),
            48 => 
            array (
                'id' => 49,
                'estado_id' => 4,
                'municipio' => 'Santos Michelena',
            ),
            49 => 
            array (
                'id' => 50,
                'estado_id' => 4,
                'municipio' => 'Sucre',
            ),
            50 => 
            array (
                'id' => 51,
                'estado_id' => 4,
                'municipio' => 'Tovar',
            ),
            51 => 
            array (
                'id' => 52,
                'estado_id' => 4,
                'municipio' => 'Urdaneta',
            ),
            52 => 
            array (
                'id' => 53,
                'estado_id' => 4,
                'municipio' => 'Zamora',
            ),
            53 => 
            array (
                'id' => 54,
                'estado_id' => 5,
                'municipio' => 'Alberto Arvelo Torrealba',
            ),
            54 => 
            array (
                'id' => 55,
                'estado_id' => 5,
                'municipio' => 'Andrés Eloy Blanco',
            ),
            55 => 
            array (
                'id' => 56,
                'estado_id' => 5,
                'municipio' => 'Antonio José de Sucre',
            ),
            56 => 
            array (
                'id' => 57,
                'estado_id' => 5,
                'municipio' => 'Arismendi',
            ),
            57 => 
            array (
                'id' => 58,
                'estado_id' => 5,
                'municipio' => 'Barinas',
            ),
            58 => 
            array (
                'id' => 59,
                'estado_id' => 5,
                'municipio' => 'Bolívar',
            ),
            59 => 
            array (
                'id' => 60,
                'estado_id' => 5,
                'municipio' => 'Cruz Paredes',
            ),
            60 => 
            array (
                'id' => 61,
                'estado_id' => 5,
                'municipio' => 'Ezequiel Zamora',
            ),
            61 => 
            array (
                'id' => 62,
                'estado_id' => 5,
                'municipio' => 'Obispos',
            ),
            62 => 
            array (
                'id' => 63,
                'estado_id' => 5,
                'municipio' => 'Pedraza',
            ),
            63 => 
            array (
                'id' => 64,
                'estado_id' => 5,
                'municipio' => 'Rojas',
            ),
            64 => 
            array (
                'id' => 65,
                'estado_id' => 5,
                'municipio' => 'Sosa',
            ),
            65 => 
            array (
                'id' => 66,
                'estado_id' => 6,
                'municipio' => 'Caroní',
            ),
            66 => 
            array (
                'id' => 67,
                'estado_id' => 6,
                'municipio' => 'Cedeño',
            ),
            67 => 
            array (
                'id' => 68,
                'estado_id' => 6,
                'municipio' => 'El Callao',
            ),
            68 => 
            array (
                'id' => 69,
                'estado_id' => 6,
                'municipio' => 'Gran Sabana',
            ),
            69 => 
            array (
                'id' => 70,
                'estado_id' => 6,
                'municipio' => 'Heres',
            ),
            70 => 
            array (
                'id' => 71,
                'estado_id' => 6,
                'municipio' => 'Piar',
            ),
            71 => 
            array (
                'id' => 72,
                'estado_id' => 6,
            'municipio' => 'Angostura (Raúl Leoni)',
            ),
            72 => 
            array (
                'id' => 73,
                'estado_id' => 6,
                'municipio' => 'Roscio',
            ),
            73 => 
            array (
                'id' => 74,
                'estado_id' => 6,
                'municipio' => 'Sifontes',
            ),
            74 => 
            array (
                'id' => 75,
                'estado_id' => 6,
                'municipio' => 'Sucre',
            ),
            75 => 
            array (
                'id' => 76,
                'estado_id' => 6,
                'municipio' => 'Padre Pedro Chien',
            ),
            76 => 
            array (
                'id' => 77,
                'estado_id' => 7,
                'municipio' => 'Bejuma',
            ),
            77 => 
            array (
                'id' => 78,
                'estado_id' => 7,
                'municipio' => 'Carlos Arvelo',
            ),
            78 => 
            array (
                'id' => 79,
                'estado_id' => 7,
                'municipio' => 'Diego Ibarra',
            ),
            79 => 
            array (
                'id' => 80,
                'estado_id' => 7,
                'municipio' => 'Guacara',
            ),
            80 => 
            array (
                'id' => 81,
                'estado_id' => 7,
                'municipio' => 'Juan José Mora',
            ),
            81 => 
            array (
                'id' => 82,
                'estado_id' => 7,
                'municipio' => 'Libertador',
            ),
            82 => 
            array (
                'id' => 83,
                'estado_id' => 7,
                'municipio' => 'Los Guayos',
            ),
            83 => 
            array (
                'id' => 84,
                'estado_id' => 7,
                'municipio' => 'Miranda',
            ),
            84 => 
            array (
                'id' => 85,
                'estado_id' => 7,
                'municipio' => 'Montalbán',
            ),
            85 => 
            array (
                'id' => 86,
                'estado_id' => 7,
                'municipio' => 'Naguanagua',
            ),
            86 => 
            array (
                'id' => 87,
                'estado_id' => 7,
                'municipio' => 'Puerto Cabello',
            ),
            87 => 
            array (
                'id' => 88,
                'estado_id' => 7,
                'municipio' => 'San Diego',
            ),
            88 => 
            array (
                'id' => 89,
                'estado_id' => 7,
                'municipio' => 'San Joaquín',
            ),
            89 => 
            array (
                'id' => 90,
                'estado_id' => 7,
                'municipio' => 'Valencia',
            ),
            90 => 
            array (
                'id' => 91,
                'estado_id' => 8,
                'municipio' => 'Anzoátegui',
            ),
            91 => 
            array (
                'id' => 92,
                'estado_id' => 8,
                'municipio' => 'Tinaquillo',
            ),
            92 => 
            array (
                'id' => 93,
                'estado_id' => 8,
                'municipio' => 'Girardot',
            ),
            93 => 
            array (
                'id' => 94,
                'estado_id' => 8,
                'municipio' => 'Lima Blanco',
            ),
            94 => 
            array (
                'id' => 95,
                'estado_id' => 8,
                'municipio' => 'Pao de San Juan Bautista',
            ),
            95 => 
            array (
                'id' => 96,
                'estado_id' => 8,
                'municipio' => 'Ricaurte',
            ),
            96 => 
            array (
                'id' => 97,
                'estado_id' => 8,
                'municipio' => 'Rómulo Gallegos',
            ),
            97 => 
            array (
                'id' => 98,
                'estado_id' => 8,
                'municipio' => 'San Carlos',
            ),
            98 => 
            array (
                'id' => 99,
                'estado_id' => 8,
                'municipio' => 'Tinaco',
            ),
            99 => 
            array (
                'id' => 100,
                'estado_id' => 9,
                'municipio' => 'Antonio Díaz',
            ),
            100 => 
            array (
                'id' => 101,
                'estado_id' => 9,
                'municipio' => 'Casacoima',
            ),
            101 => 
            array (
                'id' => 102,
                'estado_id' => 9,
                'municipio' => 'Pedernales',
            ),
            102 => 
            array (
                'id' => 103,
                'estado_id' => 9,
                'municipio' => 'Tucupita',
            ),
            103 => 
            array (
                'id' => 104,
                'estado_id' => 10,
                'municipio' => 'Acosta',
            ),
            104 => 
            array (
                'id' => 105,
                'estado_id' => 10,
                'municipio' => 'Bolívar',
            ),
            105 => 
            array (
                'id' => 106,
                'estado_id' => 10,
                'municipio' => 'Buchivacoa',
            ),
            106 => 
            array (
                'id' => 107,
                'estado_id' => 10,
                'municipio' => 'Cacique Manaure',
            ),
            107 => 
            array (
                'id' => 108,
                'estado_id' => 10,
                'municipio' => 'Carirubana',
            ),
            108 => 
            array (
                'id' => 109,
                'estado_id' => 10,
                'municipio' => 'Colina',
            ),
            109 => 
            array (
                'id' => 110,
                'estado_id' => 10,
                'municipio' => 'Dabajuro',
            ),
            110 => 
            array (
                'id' => 111,
                'estado_id' => 10,
                'municipio' => 'Democracia',
            ),
            111 => 
            array (
                'id' => 112,
                'estado_id' => 10,
                'municipio' => 'Falcón',
            ),
            112 => 
            array (
                'id' => 113,
                'estado_id' => 10,
                'municipio' => 'Federación',
            ),
            113 => 
            array (
                'id' => 114,
                'estado_id' => 10,
                'municipio' => 'Jacura',
            ),
            114 => 
            array (
                'id' => 115,
                'estado_id' => 10,
                'municipio' => 'José Laurencio Silva',
            ),
            115 => 
            array (
                'id' => 116,
                'estado_id' => 10,
                'municipio' => 'Los Taques',
            ),
            116 => 
            array (
                'id' => 117,
                'estado_id' => 10,
                'municipio' => 'Mauroa',
            ),
            117 => 
            array (
                'id' => 118,
                'estado_id' => 10,
                'municipio' => 'Miranda',
            ),
            118 => 
            array (
                'id' => 119,
                'estado_id' => 10,
                'municipio' => 'Monseñor Iturriza',
            ),
            119 => 
            array (
                'id' => 120,
                'estado_id' => 10,
                'municipio' => 'Palmasola',
            ),
            120 => 
            array (
                'id' => 121,
                'estado_id' => 10,
                'municipio' => 'Petit',
            ),
            121 => 
            array (
                'id' => 122,
                'estado_id' => 10,
                'municipio' => 'Píritu',
            ),
            122 => 
            array (
                'id' => 123,
                'estado_id' => 10,
                'municipio' => 'San Francisco',
            ),
            123 => 
            array (
                'id' => 124,
                'estado_id' => 10,
                'municipio' => 'Sucre',
            ),
            124 => 
            array (
                'id' => 125,
                'estado_id' => 10,
                'municipio' => 'Tocópero',
            ),
            125 => 
            array (
                'id' => 126,
                'estado_id' => 10,
                'municipio' => 'Unión',
            ),
            126 => 
            array (
                'id' => 127,
                'estado_id' => 10,
                'municipio' => 'Urumaco',
            ),
            127 => 
            array (
                'id' => 128,
                'estado_id' => 10,
                'municipio' => 'Zamora',
            ),
            128 => 
            array (
                'id' => 129,
                'estado_id' => 11,
                'municipio' => 'Camaguán',
            ),
            129 => 
            array (
                'id' => 130,
                'estado_id' => 11,
                'municipio' => 'Chaguaramas',
            ),
            130 => 
            array (
                'id' => 131,
                'estado_id' => 11,
                'municipio' => 'El Socorro',
            ),
            131 => 
            array (
                'id' => 132,
                'estado_id' => 11,
                'municipio' => 'José Félix Ribas',
            ),
            132 => 
            array (
                'id' => 133,
                'estado_id' => 11,
                'municipio' => 'José Tadeo Monagas',
            ),
            133 => 
            array (
                'id' => 134,
                'estado_id' => 11,
                'municipio' => 'Juan Germán Roscio',
            ),
            134 => 
            array (
                'id' => 135,
                'estado_id' => 11,
                'municipio' => 'Julián Mellado',
            ),
            135 => 
            array (
                'id' => 136,
                'estado_id' => 11,
                'municipio' => 'Las Mercedes',
            ),
            136 => 
            array (
                'id' => 137,
                'estado_id' => 11,
                'municipio' => 'Leonardo Infante',
            ),
            137 => 
            array (
                'id' => 138,
                'estado_id' => 11,
                'municipio' => 'Pedro Zaraza',
            ),
            138 => 
            array (
                'id' => 139,
                'estado_id' => 11,
                'municipio' => 'Ortíz',
            ),
            139 => 
            array (
                'id' => 140,
                'estado_id' => 11,
                'municipio' => 'San Gerónimo de Guayabal',
            ),
            140 => 
            array (
                'id' => 141,
                'estado_id' => 11,
                'municipio' => 'San José de Guaribe',
            ),
            141 => 
            array (
                'id' => 142,
                'estado_id' => 11,
                'municipio' => 'Santa María de Ipire',
            ),
            142 => 
            array (
                'id' => 143,
                'estado_id' => 11,
                'municipio' => 'Sebastián Francisco de Miranda',
            ),
            143 => 
            array (
                'id' => 144,
                'estado_id' => 12,
                'municipio' => 'Andrés Eloy Blanco',
            ),
            144 => 
            array (
                'id' => 145,
                'estado_id' => 12,
                'municipio' => 'Crespo',
            ),
            145 => 
            array (
                'id' => 146,
                'estado_id' => 12,
                'municipio' => 'Iribarren',
            ),
            146 => 
            array (
                'id' => 147,
                'estado_id' => 12,
                'municipio' => 'Jiménez',
            ),
            147 => 
            array (
                'id' => 148,
                'estado_id' => 12,
                'municipio' => 'Morán',
            ),
            148 => 
            array (
                'id' => 149,
                'estado_id' => 12,
                'municipio' => 'Palavecino',
            ),
            149 => 
            array (
                'id' => 150,
                'estado_id' => 12,
                'municipio' => 'Simón Planas',
            ),
            150 => 
            array (
                'id' => 151,
                'estado_id' => 12,
                'municipio' => 'Torres',
            ),
            151 => 
            array (
                'id' => 152,
                'estado_id' => 12,
                'municipio' => 'Urdaneta',
            ),
            152 => 
            array (
                'id' => 179,
                'estado_id' => 13,
                'municipio' => 'Alberto Adriani',
            ),
            153 => 
            array (
                'id' => 180,
                'estado_id' => 13,
                'municipio' => 'Andrés Bello',
            ),
            154 => 
            array (
                'id' => 181,
                'estado_id' => 13,
                'municipio' => 'Antonio Pinto Salinas',
            ),
            155 => 
            array (
                'id' => 182,
                'estado_id' => 13,
                'municipio' => 'Aricagua',
            ),
            156 => 
            array (
                'id' => 183,
                'estado_id' => 13,
                'municipio' => 'Arzobispo Chacón',
            ),
            157 => 
            array (
                'id' => 184,
                'estado_id' => 13,
                'municipio' => 'Campo Elías',
            ),
            158 => 
            array (
                'id' => 185,
                'estado_id' => 13,
                'municipio' => 'Caracciolo Parra Olmedo',
            ),
            159 => 
            array (
                'id' => 186,
                'estado_id' => 13,
                'municipio' => 'Cardenal Quintero',
            ),
            160 => 
            array (
                'id' => 187,
                'estado_id' => 13,
                'municipio' => 'Guaraque',
            ),
            161 => 
            array (
                'id' => 188,
                'estado_id' => 13,
                'municipio' => 'Julio César Salas',
            ),
            162 => 
            array (
                'id' => 189,
                'estado_id' => 13,
                'municipio' => 'Justo Briceño',
            ),
            163 => 
            array (
                'id' => 190,
                'estado_id' => 13,
                'municipio' => 'Libertador',
            ),
            164 => 
            array (
                'id' => 191,
                'estado_id' => 13,
                'municipio' => 'Miranda',
            ),
            165 => 
            array (
                'id' => 192,
                'estado_id' => 13,
                'municipio' => 'Obispo Ramos de Lora',
            ),
            166 => 
            array (
                'id' => 193,
                'estado_id' => 13,
                'municipio' => 'Padre Noguera',
            ),
            167 => 
            array (
                'id' => 194,
                'estado_id' => 13,
                'municipio' => 'Pueblo Llano',
            ),
            168 => 
            array (
                'id' => 195,
                'estado_id' => 13,
                'municipio' => 'Rangel',
            ),
            169 => 
            array (
                'id' => 196,
                'estado_id' => 13,
                'municipio' => 'Rivas Dávila',
            ),
            170 => 
            array (
                'id' => 197,
                'estado_id' => 13,
                'municipio' => 'Santos Marquina',
            ),
            171 => 
            array (
                'id' => 198,
                'estado_id' => 13,
                'municipio' => 'Sucre',
            ),
            172 => 
            array (
                'id' => 199,
                'estado_id' => 13,
                'municipio' => 'Tovar',
            ),
            173 => 
            array (
                'id' => 200,
                'estado_id' => 13,
                'municipio' => 'Tulio Febres Cordero',
            ),
            174 => 
            array (
                'id' => 201,
                'estado_id' => 13,
                'municipio' => 'Zea',
            ),
            175 => 
            array (
                'id' => 223,
                'estado_id' => 14,
                'municipio' => 'Acevedo',
            ),
            176 => 
            array (
                'id' => 224,
                'estado_id' => 14,
                'municipio' => 'Andrés Bello',
            ),
            177 => 
            array (
                'id' => 225,
                'estado_id' => 14,
                'municipio' => 'Baruta',
            ),
            178 => 
            array (
                'id' => 226,
                'estado_id' => 14,
                'municipio' => 'Brión',
            ),
            179 => 
            array (
                'id' => 227,
                'estado_id' => 14,
                'municipio' => 'Buroz',
            ),
            180 => 
            array (
                'id' => 228,
                'estado_id' => 14,
                'municipio' => 'Carrizal',
            ),
            181 => 
            array (
                'id' => 229,
                'estado_id' => 14,
                'municipio' => 'Chacao',
            ),
            182 => 
            array (
                'id' => 230,
                'estado_id' => 14,
                'municipio' => 'Cristóbal Rojas',
            ),
            183 => 
            array (
                'id' => 231,
                'estado_id' => 14,
                'municipio' => 'El Hatillo',
            ),
            184 => 
            array (
                'id' => 232,
                'estado_id' => 14,
                'municipio' => 'Guaicaipuro',
            ),
            185 => 
            array (
                'id' => 233,
                'estado_id' => 14,
                'municipio' => 'Independencia',
            ),
            186 => 
            array (
                'id' => 234,
                'estado_id' => 14,
                'municipio' => 'Lander',
            ),
            187 => 
            array (
                'id' => 235,
                'estado_id' => 14,
                'municipio' => 'Los Salias',
            ),
            188 => 
            array (
                'id' => 236,
                'estado_id' => 14,
                'municipio' => 'Páez',
            ),
            189 => 
            array (
                'id' => 237,
                'estado_id' => 14,
                'municipio' => 'Paz Castillo',
            ),
            190 => 
            array (
                'id' => 238,
                'estado_id' => 14,
                'municipio' => 'Pedro Gual',
            ),
            191 => 
            array (
                'id' => 239,
                'estado_id' => 14,
                'municipio' => 'Plaza',
            ),
            192 => 
            array (
                'id' => 240,
                'estado_id' => 14,
                'municipio' => 'Simón Bolívar',
            ),
            193 => 
            array (
                'id' => 241,
                'estado_id' => 14,
                'municipio' => 'Sucre',
            ),
            194 => 
            array (
                'id' => 242,
                'estado_id' => 14,
                'municipio' => 'Urdaneta',
            ),
            195 => 
            array (
                'id' => 243,
                'estado_id' => 14,
                'municipio' => 'Zamora',
            ),
            196 => 
            array (
                'id' => 258,
                'estado_id' => 15,
                'municipio' => 'Acosta',
            ),
            197 => 
            array (
                'id' => 259,
                'estado_id' => 15,
                'municipio' => 'Aguasay',
            ),
            198 => 
            array (
                'id' => 260,
                'estado_id' => 15,
                'municipio' => 'Bolívar',
            ),
            199 => 
            array (
                'id' => 261,
                'estado_id' => 15,
                'municipio' => 'Caripe',
            ),
            200 => 
            array (
                'id' => 262,
                'estado_id' => 15,
                'municipio' => 'Cedeño',
            ),
            201 => 
            array (
                'id' => 263,
                'estado_id' => 15,
                'municipio' => 'Ezequiel Zamora',
            ),
            202 => 
            array (
                'id' => 264,
                'estado_id' => 15,
                'municipio' => 'Libertador',
            ),
            203 => 
            array (
                'id' => 265,
                'estado_id' => 15,
                'municipio' => 'Maturín',
            ),
            204 => 
            array (
                'id' => 266,
                'estado_id' => 15,
                'municipio' => 'Piar',
            ),
            205 => 
            array (
                'id' => 267,
                'estado_id' => 15,
                'municipio' => 'Punceres',
            ),
            206 => 
            array (
                'id' => 268,
                'estado_id' => 15,
                'municipio' => 'Santa Bárbara',
            ),
            207 => 
            array (
                'id' => 269,
                'estado_id' => 15,
                'municipio' => 'Sotillo',
            ),
            208 => 
            array (
                'id' => 270,
                'estado_id' => 15,
                'municipio' => 'Uracoa',
            ),
            209 => 
            array (
                'id' => 271,
                'estado_id' => 16,
                'municipio' => 'Antolín del Campo',
            ),
            210 => 
            array (
                'id' => 272,
                'estado_id' => 16,
                'municipio' => 'Arismendi',
            ),
            211 => 
            array (
                'id' => 273,
                'estado_id' => 16,
                'municipio' => 'García',
            ),
            212 => 
            array (
                'id' => 274,
                'estado_id' => 16,
                'municipio' => 'Gómez',
            ),
            213 => 
            array (
                'id' => 275,
                'estado_id' => 16,
                'municipio' => 'Maneiro',
            ),
            214 => 
            array (
                'id' => 276,
                'estado_id' => 16,
                'municipio' => 'Marcano',
            ),
            215 => 
            array (
                'id' => 277,
                'estado_id' => 16,
                'municipio' => 'Mariño',
            ),
            216 => 
            array (
                'id' => 278,
                'estado_id' => 16,
                'municipio' => 'Península de Macanao',
            ),
            217 => 
            array (
                'id' => 279,
                'estado_id' => 16,
                'municipio' => 'Tubores',
            ),
            218 => 
            array (
                'id' => 280,
                'estado_id' => 16,
                'municipio' => 'Villalba',
            ),
            219 => 
            array (
                'id' => 281,
                'estado_id' => 16,
                'municipio' => 'Díaz',
            ),
            220 => 
            array (
                'id' => 282,
                'estado_id' => 17,
                'municipio' => 'Agua Blanca',
            ),
            221 => 
            array (
                'id' => 283,
                'estado_id' => 17,
                'municipio' => 'Araure',
            ),
            222 => 
            array (
                'id' => 284,
                'estado_id' => 17,
                'municipio' => 'Esteller',
            ),
            223 => 
            array (
                'id' => 285,
                'estado_id' => 17,
                'municipio' => 'Guanare',
            ),
            224 => 
            array (
                'id' => 286,
                'estado_id' => 17,
                'municipio' => 'Guanarito',
            ),
            225 => 
            array (
                'id' => 287,
                'estado_id' => 17,
                'municipio' => 'Monseñor José Vicente de Unda',
            ),
            226 => 
            array (
                'id' => 288,
                'estado_id' => 17,
                'municipio' => 'Ospino',
            ),
            227 => 
            array (
                'id' => 289,
                'estado_id' => 17,
                'municipio' => 'Páez',
            ),
            228 => 
            array (
                'id' => 290,
                'estado_id' => 17,
                'municipio' => 'Papelón',
            ),
            229 => 
            array (
                'id' => 291,
                'estado_id' => 17,
                'municipio' => 'San Genaro de Boconoíto',
            ),
            230 => 
            array (
                'id' => 292,
                'estado_id' => 17,
                'municipio' => 'San Rafael de Onoto',
            ),
            231 => 
            array (
                'id' => 293,
                'estado_id' => 17,
                'municipio' => 'Santa Rosalía',
            ),
            232 => 
            array (
                'id' => 294,
                'estado_id' => 17,
                'municipio' => 'Sucre',
            ),
            233 => 
            array (
                'id' => 295,
                'estado_id' => 17,
                'municipio' => 'Turén',
            ),
            234 => 
            array (
                'id' => 296,
                'estado_id' => 18,
                'municipio' => 'Andrés Eloy Blanco',
            ),
            235 => 
            array (
                'id' => 297,
                'estado_id' => 18,
                'municipio' => 'Andrés Mata',
            ),
            236 => 
            array (
                'id' => 298,
                'estado_id' => 18,
                'municipio' => 'Arismendi',
            ),
            237 => 
            array (
                'id' => 299,
                'estado_id' => 18,
                'municipio' => 'Benítez',
            ),
            238 => 
            array (
                'id' => 300,
                'estado_id' => 18,
                'municipio' => 'Bermúdez',
            ),
            239 => 
            array (
                'id' => 301,
                'estado_id' => 18,
                'municipio' => 'Bolívar',
            ),
            240 => 
            array (
                'id' => 302,
                'estado_id' => 18,
                'municipio' => 'Cajigal',
            ),
            241 => 
            array (
                'id' => 303,
                'estado_id' => 18,
                'municipio' => 'Cruz Salmerón Acosta',
            ),
            242 => 
            array (
                'id' => 304,
                'estado_id' => 18,
                'municipio' => 'Libertador',
            ),
            243 => 
            array (
                'id' => 305,
                'estado_id' => 18,
                'municipio' => 'Mariño',
            ),
            244 => 
            array (
                'id' => 306,
                'estado_id' => 18,
                'municipio' => 'Mejía',
            ),
            245 => 
            array (
                'id' => 307,
                'estado_id' => 18,
                'municipio' => 'Montes',
            ),
            246 => 
            array (
                'id' => 308,
                'estado_id' => 18,
                'municipio' => 'Ribero',
            ),
            247 => 
            array (
                'id' => 309,
                'estado_id' => 18,
                'municipio' => 'Sucre',
            ),
            248 => 
            array (
                'id' => 310,
                'estado_id' => 18,
                'municipio' => 'Valdéz',
            ),
            249 => 
            array (
                'id' => 341,
                'estado_id' => 19,
                'municipio' => 'Andrés Bello',
            ),
            250 => 
            array (
                'id' => 342,
                'estado_id' => 19,
                'municipio' => 'Antonio Rómulo Costa',
            ),
            251 => 
            array (
                'id' => 343,
                'estado_id' => 19,
                'municipio' => 'Ayacucho',
            ),
            252 => 
            array (
                'id' => 344,
                'estado_id' => 19,
                'municipio' => 'Bolívar',
            ),
            253 => 
            array (
                'id' => 345,
                'estado_id' => 19,
                'municipio' => 'Cárdenas',
            ),
            254 => 
            array (
                'id' => 346,
                'estado_id' => 19,
                'municipio' => 'Córdoba',
            ),
            255 => 
            array (
                'id' => 347,
                'estado_id' => 19,
                'municipio' => 'Fernández Feo',
            ),
            256 => 
            array (
                'id' => 348,
                'estado_id' => 19,
                'municipio' => 'Francisco de Miranda',
            ),
            257 => 
            array (
                'id' => 349,
                'estado_id' => 19,
                'municipio' => 'García de Hevia',
            ),
            258 => 
            array (
                'id' => 350,
                'estado_id' => 19,
                'municipio' => 'Guásimos',
            ),
            259 => 
            array (
                'id' => 351,
                'estado_id' => 19,
                'municipio' => 'Independencia',
            ),
            260 => 
            array (
                'id' => 352,
                'estado_id' => 19,
                'municipio' => 'Jáuregui',
            ),
            261 => 
            array (
                'id' => 353,
                'estado_id' => 19,
                'municipio' => 'José María Vargas',
            ),
            262 => 
            array (
                'id' => 354,
                'estado_id' => 19,
                'municipio' => 'Junín',
            ),
            263 => 
            array (
                'id' => 355,
                'estado_id' => 19,
                'municipio' => 'Libertad',
            ),
            264 => 
            array (
                'id' => 356,
                'estado_id' => 19,
                'municipio' => 'Libertador',
            ),
            265 => 
            array (
                'id' => 357,
                'estado_id' => 19,
                'municipio' => 'Lobatera',
            ),
            266 => 
            array (
                'id' => 358,
                'estado_id' => 19,
                'municipio' => 'Michelena',
            ),
            267 => 
            array (
                'id' => 359,
                'estado_id' => 19,
                'municipio' => 'Panamericano',
            ),
            268 => 
            array (
                'id' => 360,
                'estado_id' => 19,
                'municipio' => 'Pedro María Ureña',
            ),
            269 => 
            array (
                'id' => 361,
                'estado_id' => 19,
                'municipio' => 'Rafael Urdaneta',
            ),
            270 => 
            array (
                'id' => 362,
                'estado_id' => 19,
                'municipio' => 'Samuel Darío Maldonado',
            ),
            271 => 
            array (
                'id' => 363,
                'estado_id' => 19,
                'municipio' => 'San Cristóbal',
            ),
            272 => 
            array (
                'id' => 364,
                'estado_id' => 19,
                'municipio' => 'Seboruco',
            ),
            273 => 
            array (
                'id' => 365,
                'estado_id' => 19,
                'municipio' => 'Simón Rodríguez',
            ),
            274 => 
            array (
                'id' => 366,
                'estado_id' => 19,
                'municipio' => 'Sucre',
            ),
            275 => 
            array (
                'id' => 367,
                'estado_id' => 19,
                'municipio' => 'Torbes',
            ),
            276 => 
            array (
                'id' => 368,
                'estado_id' => 19,
                'municipio' => 'Uribante',
            ),
            277 => 
            array (
                'id' => 369,
                'estado_id' => 19,
                'municipio' => 'San Judas Tadeo',
            ),
            278 => 
            array (
                'id' => 370,
                'estado_id' => 20,
                'municipio' => 'Andrés Bello',
            ),
            279 => 
            array (
                'id' => 371,
                'estado_id' => 20,
                'municipio' => 'Boconó',
            ),
            280 => 
            array (
                'id' => 372,
                'estado_id' => 20,
                'municipio' => 'Bolívar',
            ),
            281 => 
            array (
                'id' => 373,
                'estado_id' => 20,
                'municipio' => 'Candelaria',
            ),
            282 => 
            array (
                'id' => 374,
                'estado_id' => 20,
                'municipio' => 'Carache',
            ),
            283 => 
            array (
                'id' => 375,
                'estado_id' => 20,
                'municipio' => 'Escuque',
            ),
            284 => 
            array (
                'id' => 376,
                'estado_id' => 20,
                'municipio' => 'José Felipe Márquez Cañizalez',
            ),
            285 => 
            array (
                'id' => 377,
                'estado_id' => 20,
                'municipio' => 'Juan Vicente Campos Elías',
            ),
            286 => 
            array (
                'id' => 378,
                'estado_id' => 20,
                'municipio' => 'La Ceiba',
            ),
            287 => 
            array (
                'id' => 379,
                'estado_id' => 20,
                'municipio' => 'Miranda',
            ),
            288 => 
            array (
                'id' => 380,
                'estado_id' => 20,
                'municipio' => 'Monte Carmelo',
            ),
            289 => 
            array (
                'id' => 381,
                'estado_id' => 20,
                'municipio' => 'Motatán',
            ),
            290 => 
            array (
                'id' => 382,
                'estado_id' => 20,
                'municipio' => 'Pampán',
            ),
            291 => 
            array (
                'id' => 383,
                'estado_id' => 20,
                'municipio' => 'Pampanito',
            ),
            292 => 
            array (
                'id' => 384,
                'estado_id' => 20,
                'municipio' => 'Rafael Rangel',
            ),
            293 => 
            array (
                'id' => 385,
                'estado_id' => 20,
                'municipio' => 'San Rafael de Carvajal',
            ),
            294 => 
            array (
                'id' => 386,
                'estado_id' => 20,
                'municipio' => 'Sucre',
            ),
            295 => 
            array (
                'id' => 387,
                'estado_id' => 20,
                'municipio' => 'Trujillo',
            ),
            296 => 
            array (
                'id' => 388,
                'estado_id' => 20,
                'municipio' => 'Urdaneta',
            ),
            297 => 
            array (
                'id' => 389,
                'estado_id' => 20,
                'municipio' => 'Valera',
            ),
            298 => 
            array (
                'id' => 390,
                'estado_id' => 21,
                'municipio' => 'Vargas',
            ),
            299 => 
            array (
                'id' => 391,
                'estado_id' => 22,
                'municipio' => 'Arístides Bastidas',
            ),
            300 => 
            array (
                'id' => 392,
                'estado_id' => 22,
                'municipio' => 'Bolívar',
            ),
            301 => 
            array (
                'id' => 407,
                'estado_id' => 22,
                'municipio' => 'Bruzual',
            ),
            302 => 
            array (
                'id' => 408,
                'estado_id' => 22,
                'municipio' => 'Cocorote',
            ),
            303 => 
            array (
                'id' => 409,
                'estado_id' => 22,
                'municipio' => 'Independencia',
            ),
            304 => 
            array (
                'id' => 410,
                'estado_id' => 22,
                'municipio' => 'José Antonio Páez',
            ),
            305 => 
            array (
                'id' => 411,
                'estado_id' => 22,
                'municipio' => 'La Trinidad',
            ),
            306 => 
            array (
                'id' => 412,
                'estado_id' => 22,
                'municipio' => 'Manuel Monge',
            ),
            307 => 
            array (
                'id' => 413,
                'estado_id' => 22,
                'municipio' => 'Nirgua',
            ),
            308 => 
            array (
                'id' => 414,
                'estado_id' => 22,
                'municipio' => 'Peña',
            ),
            309 => 
            array (
                'id' => 415,
                'estado_id' => 22,
                'municipio' => 'San Felipe',
            ),
            310 => 
            array (
                'id' => 416,
                'estado_id' => 22,
                'municipio' => 'Sucre',
            ),
            311 => 
            array (
                'id' => 417,
                'estado_id' => 22,
                'municipio' => 'Urachiche',
            ),
            312 => 
            array (
                'id' => 418,
                'estado_id' => 22,
                'municipio' => 'José Joaquín Veroes',
            ),
            313 => 
            array (
                'id' => 441,
                'estado_id' => 23,
                'municipio' => 'Almirante Padilla',
            ),
            314 => 
            array (
                'id' => 442,
                'estado_id' => 23,
                'municipio' => 'Baralt',
            ),
            315 => 
            array (
                'id' => 443,
                'estado_id' => 23,
                'municipio' => 'Cabimas',
            ),
            316 => 
            array (
                'id' => 444,
                'estado_id' => 23,
                'municipio' => 'Catatumbo',
            ),
            317 => 
            array (
                'id' => 445,
                'estado_id' => 23,
                'municipio' => 'Colón',
            ),
            318 => 
            array (
                'id' => 446,
                'estado_id' => 23,
                'municipio' => 'Francisco Javier Pulgar',
            ),
            319 => 
            array (
                'id' => 447,
                'estado_id' => 23,
                'municipio' => 'Páez',
            ),
            320 => 
            array (
                'id' => 448,
                'estado_id' => 23,
                'municipio' => 'Jesús Enrique Losada',
            ),
            321 => 
            array (
                'id' => 449,
                'estado_id' => 23,
                'municipio' => 'Jesús María Semprún',
            ),
            322 => 
            array (
                'id' => 450,
                'estado_id' => 23,
                'municipio' => 'La Cañada de Urdaneta',
            ),
            323 => 
            array (
                'id' => 451,
                'estado_id' => 23,
                'municipio' => 'Lagunillas',
            ),
            324 => 
            array (
                'id' => 452,
                'estado_id' => 23,
                'municipio' => 'Machiques de Perijá',
            ),
            325 => 
            array (
                'id' => 453,
                'estado_id' => 23,
                'municipio' => 'Mara',
            ),
            326 => 
            array (
                'id' => 454,
                'estado_id' => 23,
                'municipio' => 'Maracaibo',
            ),
            327 => 
            array (
                'id' => 455,
                'estado_id' => 23,
                'municipio' => 'Miranda',
            ),
            328 => 
            array (
                'id' => 456,
                'estado_id' => 23,
                'municipio' => 'Rosario de Perijá',
            ),
            329 => 
            array (
                'id' => 457,
                'estado_id' => 23,
                'municipio' => 'San Francisco',
            ),
            330 => 
            array (
                'id' => 458,
                'estado_id' => 23,
                'municipio' => 'Santa Rita',
            ),
            331 => 
            array (
                'id' => 459,
                'estado_id' => 23,
                'municipio' => 'Simón Bolívar',
            ),
            332 => 
            array (
                'id' => 460,
                'estado_id' => 23,
                'municipio' => 'Sucre',
            ),
            333 => 
            array (
                'id' => 461,
                'estado_id' => 23,
                'municipio' => 'Valmore Rodríguez',
            ),
            334 => 
            array (
                'id' => 462,
                'estado_id' => 24,
                'municipio' => 'Libertador',
            ),
        ));
    }
}
