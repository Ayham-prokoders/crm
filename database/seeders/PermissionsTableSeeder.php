<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 6,
                'name' => 'permissions-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:32',
                'updated_at' => '2024-03-23 12:29:32',
            ),
            1 => 
            array (
                'id' => 7,
                'name' => 'permissions-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:32',
                'updated_at' => '2024-03-23 12:29:32',
            ),
            2 => 
            array (
                'id' => 8,
                'name' => 'permissions-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:32',
                'updated_at' => '2024-03-23 12:29:32',
            ),
            3 => 
            array (
                'id' => 9,
                'name' => 'permissions-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:32',
                'updated_at' => '2024-03-23 12:29:32',
            ),
            4 => 
            array (
                'id' => 10,
                'name' => 'permissions-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:32',
                'updated_at' => '2024-03-23 12:29:32',
            ),
            5 => 
            array (
                'id' => 11,
                'name' => 'roles_list-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:46',
                'updated_at' => '2024-03-23 12:29:46',
            ),
            6 => 
            array (
                'id' => 12,
                'name' => 'roles_list-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:46',
                'updated_at' => '2024-03-23 12:29:46',
            ),
            7 => 
            array (
                'id' => 13,
                'name' => 'roles_list-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:46',
                'updated_at' => '2024-03-23 12:29:46',
            ),
            8 => 
            array (
                'id' => 14,
                'name' => 'roles_list-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:46',
                'updated_at' => '2024-03-23 12:29:46',
            ),
            9 => 
            array (
                'id' => 15,
                'name' => 'roles_list-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:46',
                'updated_at' => '2024-03-23 12:29:46',
            ),
            10 => 
            array (
                'id' => 16,
                'name' => 'users-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:53',
                'updated_at' => '2024-03-23 12:29:53',
            ),
            11 => 
            array (
                'id' => 17,
                'name' => 'users-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:53',
                'updated_at' => '2024-03-23 12:29:53',
            ),
            12 => 
            array (
                'id' => 18,
                'name' => 'users-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:53',
                'updated_at' => '2024-03-23 12:29:53',
            ),
            13 => 
            array (
                'id' => 19,
                'name' => 'users-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:53',
                'updated_at' => '2024-03-23 12:29:53',
            ),
            14 => 
            array (
                'id' => 20,
                'name' => 'users-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:29:53',
                'updated_at' => '2024-03-23 12:29:53',
            ),
            15 => 
            array (
                'id' => 21,
                'name' => 'sessions-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:06',
                'updated_at' => '2024-03-23 12:30:06',
            ),
            16 => 
            array (
                'id' => 22,
                'name' => 'sessions-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:06',
                'updated_at' => '2024-03-23 12:30:06',
            ),
            17 => 
            array (
                'id' => 23,
                'name' => 'sessions-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:06',
                'updated_at' => '2024-03-23 12:30:06',
            ),
            18 => 
            array (
                'id' => 24,
                'name' => 'sessions-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:06',
                'updated_at' => '2024-03-23 12:30:06',
            ),
            19 => 
            array (
                'id' => 25,
                'name' => 'sessions-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:06',
                'updated_at' => '2024-03-23 12:30:06',
            ),
            20 => 
            array (
                'id' => 26,
                'name' => 'classes-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:10',
                'updated_at' => '2024-03-23 12:30:10',
            ),
            21 => 
            array (
                'id' => 27,
                'name' => 'classes-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:10',
                'updated_at' => '2024-03-23 12:30:10',
            ),
            22 => 
            array (
                'id' => 28,
                'name' => 'classes-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:10',
                'updated_at' => '2024-03-23 12:30:10',
            ),
            23 => 
            array (
                'id' => 29,
                'name' => 'classes-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:10',
                'updated_at' => '2024-03-23 12:30:10',
            ),
            24 => 
            array (
                'id' => 30,
                'name' => 'classes-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:10',
                'updated_at' => '2024-03-23 12:30:10',
            ),
            25 => 
            array (
                'id' => 31,
                'name' => 'feedbacks-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:16',
                'updated_at' => '2024-03-23 12:30:16',
            ),
            26 => 
            array (
                'id' => 32,
                'name' => 'feedbacks-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:16',
                'updated_at' => '2024-03-23 12:30:16',
            ),
            27 => 
            array (
                'id' => 33,
                'name' => 'feedbacks-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:16',
                'updated_at' => '2024-03-23 12:30:16',
            ),
            28 => 
            array (
                'id' => 34,
                'name' => 'feedbacks-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:16',
                'updated_at' => '2024-03-23 12:30:16',
            ),
            29 => 
            array (
                'id' => 35,
                'name' => 'feedbacks-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:16',
                'updated_at' => '2024-03-23 12:30:16',
            ),
            30 => 
            array (
                'id' => 36,
                'name' => 'attendance_manage-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:31',
                'updated_at' => '2024-03-23 12:30:31',
            ),
            31 => 
            array (
                'id' => 37,
                'name' => 'attendance_manage-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:31',
                'updated_at' => '2024-03-23 12:30:31',
            ),
            32 => 
            array (
                'id' => 38,
                'name' => 'attendance_manage-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:31',
                'updated_at' => '2024-03-23 12:30:31',
            ),
            33 => 
            array (
                'id' => 39,
                'name' => 'attendance_manage-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:31',
                'updated_at' => '2024-03-23 12:30:31',
            ),
            34 => 
            array (
                'id' => 40,
                'name' => 'attendance_manage-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:31',
                'updated_at' => '2024-03-23 12:30:31',
            ),
            35 => 
            array (
                'id' => 41,
                'name' => 'companies-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:42',
                'updated_at' => '2024-03-23 12:30:42',
            ),
            36 => 
            array (
                'id' => 42,
                'name' => 'companies-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:42',
                'updated_at' => '2024-03-23 12:30:42',
            ),
            37 => 
            array (
                'id' => 43,
                'name' => 'companies-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:42',
                'updated_at' => '2024-03-23 12:30:42',
            ),
            38 => 
            array (
                'id' => 44,
                'name' => 'companies-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:42',
                'updated_at' => '2024-03-23 12:30:42',
            ),
            39 => 
            array (
                'id' => 45,
                'name' => 'companies-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:42',
                'updated_at' => '2024-03-23 12:30:42',
            ),
            40 => 
            array (
                'id' => 46,
                'name' => 'articles-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:52',
                'updated_at' => '2024-03-23 12:30:52',
            ),
            41 => 
            array (
                'id' => 47,
                'name' => 'articles-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:52',
                'updated_at' => '2024-03-23 12:30:52',
            ),
            42 => 
            array (
                'id' => 48,
                'name' => 'articles-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:52',
                'updated_at' => '2024-03-23 12:30:52',
            ),
            43 => 
            array (
                'id' => 49,
                'name' => 'articles-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:52',
                'updated_at' => '2024-03-23 12:30:52',
            ),
            44 => 
            array (
                'id' => 50,
                'name' => 'articles-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:52',
                'updated_at' => '2024-03-23 12:30:52',
            ),
            45 => 
            array (
                'id' => 51,
                'name' => 'news-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:56',
                'updated_at' => '2024-03-23 12:30:56',
            ),
            46 => 
            array (
                'id' => 52,
                'name' => 'news-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:56',
                'updated_at' => '2024-03-23 12:30:56',
            ),
            47 => 
            array (
                'id' => 53,
                'name' => 'news-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:56',
                'updated_at' => '2024-03-23 12:30:56',
            ),
            48 => 
            array (
                'id' => 54,
                'name' => 'news-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:56',
                'updated_at' => '2024-03-23 12:30:56',
            ),
            49 => 
            array (
                'id' => 55,
                'name' => 'news-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:56',
                'updated_at' => '2024-03-23 12:30:56',
            ),
            50 => 
            array (
                'id' => 56,
                'name' => 'events-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            51 => 
            array (
                'id' => 57,
                'name' => 'events-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            52 => 
            array (
                'id' => 58,
                'name' => 'events-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            53 => 
            array (
                'id' => 59,
                'name' => 'events-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            54 => 
            array (
                'id' => 60,
                'name' => 'events-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            55 => 
            array (
                'id' => 61,
                'name' => 'certificates-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            56 => 
            array (
                'id' => 62,
                'name' => 'certificates-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            57 => 
            array (
                'id' => 63,
                'name' => 'certificates-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            58 => 
            array (
                'id' => 64,
                'name' => 'certificates-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            59 => 
            array (
                'id' => 65,
                'name' => 'certificates-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            60 => 
            array (
                'id' => 66,
                'name' => 'forms-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            61 => 
            array (
                'id' => 67,
                'name' => 'forms-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            62 => 
            array (
                'id' => 68,
                'name' => 'forms-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            63 => 
            array (
                'id' => 69,
                'name' => 'forms-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            64 => 
            array (
                'id' => 70,
                'name' => 'forms-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-03-23 12:30:59',
                'updated_at' => '2024-03-23 12:30:59',
            ),
            65 => 
            array (
                'id' => 71,
                'name' => 'instructor-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:22:43',
                'updated_at' => '2024-09-05 14:22:43',
            ),
            66 => 
            array (
                'id' => 72,
                'name' => 'instructor-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:22:43',
                'updated_at' => '2024-09-05 14:22:43',
            ),
            67 => 
            array (
                'id' => 73,
                'name' => 'instructor-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:22:43',
                'updated_at' => '2024-09-05 14:22:43',
            ),
            68 => 
            array (
                'id' => 74,
                'name' => 'instructor-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:22:43',
                'updated_at' => '2024-09-05 14:22:43',
            ),
            69 => 
            array (
                'id' => 75,
                'name' => 'instructor-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:22:43',
                'updated_at' => '2024-09-05 14:22:43',
            ),
            70 => 
            array (
                'id' => 76,
                'name' => 'instructors-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:23:45',
                'updated_at' => '2024-09-05 14:23:45',
            ),
            71 => 
            array (
                'id' => 77,
                'name' => 'instructors-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:23:45',
                'updated_at' => '2024-09-05 14:23:45',
            ),
            72 => 
            array (
                'id' => 78,
                'name' => 'instructors-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:23:45',
                'updated_at' => '2024-09-05 14:23:45',
            ),
            73 => 
            array (
                'id' => 79,
                'name' => 'instructors-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:23:45',
                'updated_at' => '2024-09-05 14:23:45',
            ),
            74 => 
            array (
                'id' => 80,
                'name' => 'instructors-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:23:46',
                'updated_at' => '2024-09-05 14:23:46',
            ),
            75 => 
            array (
                'id' => 81,
                'name' => 'annoncments-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            76 => 
            array (
                'id' => 82,
                'name' => 'annoncments-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            77 => 
            array (
                'id' => 83,
                'name' => 'annoncments-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            78 => 
            array (
                'id' => 84,
                'name' => 'annoncments-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            79 => 
            array (
                'id' => 85,
                'name' => 'annoncments-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            80 => 
            array (
                'id' => 86,
                'name' => 'alerts-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            81 => 
            array (
                'id' => 87,
                'name' => 'alerts-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            82 => 
            array (
                'id' => 88,
                'name' => 'alerts-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            83 => 
            array (
                'id' => 89,
                'name' => 'alerts-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            84 => 
            array (
                'id' => 90,
                'name' => 'alerts-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-05 14:24:19',
                'updated_at' => '2024-09-05 14:24:19',
            ),
            85 => 
            array (
                'id' => 91,
                'name' => 'topics-create',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 16:01:26',
                'updated_at' => '2024-09-09 16:01:26',
            ),
            86 => 
            array (
                'id' => 92,
                'name' => 'topics-update',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 16:01:26',
                'updated_at' => '2024-09-09 16:01:26',
            ),
            87 => 
            array (
                'id' => 93,
                'name' => 'topics-read',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 16:01:26',
                'updated_at' => '2024-09-09 16:01:26',
            ),
            88 => 
            array (
                'id' => 94,
                'name' => 'topics-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 16:01:26',
                'updated_at' => '2024-09-09 16:01:26',
            ),
            89 => 
            array (
                'id' => 95,
                'name' => 'topics-all',
                'guard_name' => 'sanctum',
                'created_at' => '2024-09-09 16:01:26',
                'updated_at' => '2024-09-09 16:01:26',
            ),
            90 => 
            array (
                'id' => 96,
                'name' => 'trainees-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-13 16:41:55',
                'updated_at' => '2025-01-13 16:41:55',
            ),
            91 => 
            array (
                'id' => 97,
                'name' => 'trainees-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-13 16:41:55',
                'updated_at' => '2025-01-13 16:41:55',
            ),
            92 => 
            array (
                'id' => 98,
                'name' => 'trainees-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-13 16:41:55',
                'updated_at' => '2025-01-13 16:41:55',
            ),
            93 => 
            array (
                'id' => 99,
                'name' => 'trainees-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-13 16:41:55',
                'updated_at' => '2025-01-13 16:41:55',
            ),
            94 => 
            array (
                'id' => 100,
                'name' => 'trainees-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-13 16:41:55',
                'updated_at' => '2025-01-13 16:41:55',
            ),
            95 => 
            array (
                'id' => 101,
                'name' => 'reports-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-14 14:28:52',
                'updated_at' => '2025-01-14 14:28:52',
            ),
            96 => 
            array (
                'id' => 102,
                'name' => 'reports-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-14 14:28:52',
                'updated_at' => '2025-01-14 14:28:52',
            ),
            97 => 
            array (
                'id' => 103,
                'name' => 'reports-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-14 14:28:52',
                'updated_at' => '2025-01-14 14:28:52',
            ),
            98 => 
            array (
                'id' => 104,
                'name' => 'reports-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-14 14:28:52',
                'updated_at' => '2025-01-14 14:28:52',
            ),
            99 => 
            array (
                'id' => 105,
                'name' => 'reports-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-14 14:28:52',
                'updated_at' => '2025-01-14 14:28:52',
            ),
            100 => 
            array (
                'id' => 106,
                'name' => 'google-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-20 03:10:25',
                'updated_at' => '2025-01-20 03:10:25',
            ),
            101 => 
            array (
                'id' => 107,
                'name' => 'google-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-20 03:10:25',
                'updated_at' => '2025-01-20 03:10:25',
            ),
            102 => 
            array (
                'id' => 108,
                'name' => 'google-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-20 03:10:25',
                'updated_at' => '2025-01-20 03:10:25',
            ),
            103 => 
            array (
                'id' => 109,
                'name' => 'google-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-20 03:10:25',
                'updated_at' => '2025-01-20 03:10:25',
            ),
            104 => 
            array (
                'id' => 110,
                'name' => 'google-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-01-20 03:10:25',
                'updated_at' => '2025-01-20 03:10:25',
            ),
            105 => 
            array (
                'id' => 111,
                'name' => 'trainee_attendance-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-02-16 15:33:24',
                'updated_at' => '2025-02-16 15:33:24',
            ),
            106 => 
            array (
                'id' => 112,
                'name' => 'trainee_attendance-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-02-16 15:33:24',
                'updated_at' => '2025-02-16 15:33:24',
            ),
            107 => 
            array (
                'id' => 113,
                'name' => 'trainee_attendance-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-02-16 15:33:24',
                'updated_at' => '2025-02-16 15:33:24',
            ),
            108 => 
            array (
                'id' => 114,
                'name' => 'trainee_attendance-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-02-16 15:33:24',
                'updated_at' => '2025-02-16 15:33:24',
            ),
            109 => 
            array (
                'id' => 115,
                'name' => 'trainee_attendance-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-02-16 15:33:24',
                'updated_at' => '2025-02-16 15:33:24',
            ),
            110 => 
            array (
                'id' => 116,
                'name' => 'deals-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:23',
                'updated_at' => '2025-03-20 10:23:23',
            ),
            111 => 
            array (
                'id' => 117,
                'name' => 'deals-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:23',
                'updated_at' => '2025-03-20 10:23:23',
            ),
            112 => 
            array (
                'id' => 118,
                'name' => 'deals-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:23',
                'updated_at' => '2025-03-20 10:23:23',
            ),
            113 => 
            array (
                'id' => 119,
                'name' => 'deals-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:23',
                'updated_at' => '2025-03-20 10:23:23',
            ),
            114 => 
            array (
                'id' => 120,
                'name' => 'deals-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:23',
                'updated_at' => '2025-03-20 10:23:23',
            ),
            115 => 
            array (
                'id' => 121,
                'name' => 'tasks-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:39',
                'updated_at' => '2025-03-20 10:23:39',
            ),
            116 => 
            array (
                'id' => 122,
                'name' => 'tasks-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:39',
                'updated_at' => '2025-03-20 10:23:39',
            ),
            117 => 
            array (
                'id' => 123,
                'name' => 'tasks-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:39',
                'updated_at' => '2025-03-20 10:23:39',
            ),
            118 => 
            array (
                'id' => 124,
                'name' => 'tasks-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:39',
                'updated_at' => '2025-03-20 10:23:39',
            ),
            119 => 
            array (
                'id' => 125,
                'name' => 'tasks-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:23:39',
                'updated_at' => '2025-03-20 10:23:39',
            ),
            120 => 
            array (
                'id' => 126,
                'name' => 'email_builder-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:37',
                'updated_at' => '2025-03-20 10:24:37',
            ),
            121 => 
            array (
                'id' => 127,
                'name' => 'email_builder-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:37',
                'updated_at' => '2025-03-20 10:24:37',
            ),
            122 => 
            array (
                'id' => 128,
                'name' => 'email_builder-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:37',
                'updated_at' => '2025-03-20 10:24:37',
            ),
            123 => 
            array (
                'id' => 129,
                'name' => 'email_builder-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:37',
                'updated_at' => '2025-03-20 10:24:37',
            ),
            124 => 
            array (
                'id' => 130,
                'name' => 'email_builder-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:37',
                'updated_at' => '2025-03-20 10:24:37',
            ),
            125 => 
            array (
                'id' => 131,
                'name' => 'invoices-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:52',
                'updated_at' => '2025-03-20 10:24:52',
            ),
            126 => 
            array (
                'id' => 132,
                'name' => 'invoices-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:52',
                'updated_at' => '2025-03-20 10:24:52',
            ),
            127 => 
            array (
                'id' => 133,
                'name' => 'invoices-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:52',
                'updated_at' => '2025-03-20 10:24:52',
            ),
            128 => 
            array (
                'id' => 134,
                'name' => 'invoices-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:52',
                'updated_at' => '2025-03-20 10:24:52',
            ),
            129 => 
            array (
                'id' => 135,
                'name' => 'invoices-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:24:52',
                'updated_at' => '2025-03-20 10:24:52',
            ),
            130 => 
            array (
                'id' => 136,
                'name' => 'banks-create',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:25:56',
                'updated_at' => '2025-03-20 10:25:56',
            ),
            131 => 
            array (
                'id' => 137,
                'name' => 'banks-update',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:25:56',
                'updated_at' => '2025-03-20 10:25:56',
            ),
            132 => 
            array (
                'id' => 138,
                'name' => 'banks-read',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:25:56',
                'updated_at' => '2025-03-20 10:25:56',
            ),
            133 => 
            array (
                'id' => 139,
                'name' => 'banks-delete',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:25:56',
                'updated_at' => '2025-03-20 10:25:56',
            ),
            134 => 
            array (
                'id' => 140,
                'name' => 'banks-all',
                'guard_name' => 'sanctum',
                'created_at' => '2025-03-20 10:25:56',
                'updated_at' => '2025-03-20 10:25:56',
            ),
        ));
        
        
    }
}