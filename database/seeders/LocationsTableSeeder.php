<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('locations')->delete();
        
        \DB::table('locations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => 75,
                'city_id' => 2,
                'lat' => '23.21468810213655',
                'long' => '55.23010322824858',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            1 => 
            array (
                'id' => 2,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => 80,
                'city_id' => 2,
                'lat' => '36.496754501429216',
                'long' => '22.75151862681426',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            2 => 
            array (
                'id' => 3,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => 81,
                'city_id' => 2,
                'lat' => '25.18881618233327',
                'long' => '55.26967406272888',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            3 => 
            array (
                'id' => 4,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a></li>
<li><a href="https://meetings.accor.com/our-event-types/training_session/index.en.shtml">Movenpick Hotel Downtown Dubai</a></li>
<li><a href="https://www.google.com/maps/place/Clover+Bay+Tower/@25.1888109,55.269682,15z/data=!4m5!3m4!1s0x0:0xdda585df6ed9b8ae!8m2!3d25.1888109!4d55.269682">LPC Training Dubai Branch - Clover Bay Tower, Business Bay</a></li>
</ul>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => 85,
                'city_id' => 2,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            4 => 
            array (
                'id' => 5,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a></li>
<li><a href="https://meetings.accor.com/our-event-types/training_session/index.en.shtml">Movenpick Hotel Downtown Dubai</a></li>
</ul>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => 97,
                'city_id' => 2,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            5 => 
            array (
                'id' => 6,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; : Address: 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/">Hard Rock Hotel London</a>&nbsp;:&nbsp; Address:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => 115,
                'city_id' => 1,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            6 => 
            array (
                'id' => 7,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/">Hard Rock Hotel London</a>&nbsp;:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => 118,
                'city_id' => 1,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            7 => 
            array (
                'id' => 8,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/area-guide.aspx">Hard Rock Hotel London&nbsp;</a>:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => 122,
                'city_id' => 1,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            8 => 
            array (
                'id' => 9,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.hardrockhotels.com/london/area-guide.aspx">Hard Rock Hotel London&nbsp;</a>:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => 123,
                'city_id' => 1,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            9 => 
            array (
                'id' => 10,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.movenpick.com/en/middle-east/uae/dubai/dubai-downtown/overview/?utm_source=google&amp;utm_medium=local&amp;utm_campaign=hotel-MHR-Downtown-Dubai&amp;y_source=1_MTUzNjI2MDMtNzE1LWxvY2F0aW9uLndlYnNpdGU%3D">Movenpick Hotel Downtown Dubai</a>&nbsp;:&nbsp;Al Ohood Street Burj Khalifa Area - Dubai - United Arab Emirates</li>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a>&nbsp;: Boulevard Street - Dubai - United Arab Emirates</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => 134,
                'city_id' => 2,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            10 => 
            array (
                'id' => 11,
                'lang_code' => 'en',
                'title' => 'Harum quaerat quo modi.',
                'description' => 'Sed nam voluptatem enim omnis nesciunt non quo nesciunt.',
                'external_id' => 172,
                'city_id' => 32,
                'lat' => 'Suscipit voluptas porro quas commodi.',
                'long' => 'Velit magni ipsam iste consequatur enim.',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            11 => 
            array (
                'id' => 12,
                'lang_code' => 'en',
                'title' => 'Porro quis voluptatem enim nostrum.',
                'description' => 'Itaque fugiat officiis repellat corrupti repudiandae dolores qui.',
                'external_id' => 173,
                'city_id' => 33,
                'lat' => 'Soluta sed commodi sunt nihil repellendus eum repudiandae alias.',
                'long' => 'Voluptate recusandae voluptatem aut dolores enim molestias cum.',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            12 => 
            array (
                'id' => 13,
                'lang_code' => 'en',
                'title' => 'Distinctio nulla ut aliquid et praesentium quae.',
                'description' => 'Ullam accusamus voluptatem ea quis similique quia.',
                'external_id' => 174,
                'city_id' => 34,
                'lat' => 'Numquam expedita est dolorum.',
                'long' => 'Animi in sint hic quas.',
                'created_at' => '2024-09-09 08:58:59',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            13 => 
            array (
                'id' => 14,
                'lang_code' => 'en',
                'title' => 'test title',
                'description' => 'test desc',
                'external_id' => 175,
                'city_id' => 35,
                'lat' => 'test lat',
                'long' => 'test long',
                'created_at' => '2024-09-09 08:59:00',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            14 => 
            array (
                'id' => 15,
                'lang_code' => 'en',
                'title' => 'Ipsam ut cum molestiae veritatis qui doloribus error.',
                'description' => 'Error ad reiciendis vero voluptatem fugiat.',
                'external_id' => 176,
                'city_id' => 36,
                'lat' => 'Et eaque cupiditate vel qui ex non commodi.',
                'long' => 'Veniam rerum sequi possimus et reiciendis.',
                'created_at' => '2024-09-09 08:59:00',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            15 => 
            array (
                'id' => 16,
                'lang_code' => 'en',
                'title' => 'test title',
                'description' => 'test desc',
                'external_id' => 177,
                'city_id' => 38,
                'lat' => 'test lat',
                'long' => 'test long',
                'created_at' => '2024-09-09 08:59:00',
                'updated_at' => '2024-09-09 09:14:37',
            ),
            16 => 
            array (
                'id' => 17,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '23.21468810213655',
                'long' => '55.23010322824858',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            17 => 
            array (
                'id' => 18,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '36.496754501429216',
                'long' => '22.75151862681426',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            18 => 
            array (
                'id' => 19,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.18881618233327',
                'long' => '55.26967406272888',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            19 => 
            array (
                'id' => 20,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a></li>
<li><a href="https://meetings.accor.com/our-event-types/training_session/index.en.shtml">Movenpick Hotel Downtown Dubai</a></li>
<li><a href="https://www.google.com/maps/place/Clover+Bay+Tower/@25.1888109,55.269682,15z/data=!4m5!3m4!1s0x0:0xdda585df6ed9b8ae!8m2!3d25.1888109!4d55.269682">LPC Training Dubai Branch - Clover Bay Tower, Business Bay</a></li>
</ul>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            20 => 
            array (
                'id' => 21,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a></li>
<li><a href="https://meetings.accor.com/our-event-types/training_session/index.en.shtml">Movenpick Hotel Downtown Dubai</a></li>
</ul>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            21 => 
            array (
                'id' => 22,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; : Address: 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/">Hard Rock Hotel London</a>&nbsp;:&nbsp; Address:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            22 => 
            array (
                'id' => 23,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/">Hard Rock Hotel London</a>&nbsp;:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            23 => 
            array (
                'id' => 24,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/area-guide.aspx">Hard Rock Hotel London&nbsp;</a>:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            24 => 
            array (
                'id' => 25,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.hardrockhotels.com/london/area-guide.aspx">Hard Rock Hotel London&nbsp;</a>:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            25 => 
            array (
                'id' => 26,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.movenpick.com/en/middle-east/uae/dubai/dubai-downtown/overview/?utm_source=google&amp;utm_medium=local&amp;utm_campaign=hotel-MHR-Downtown-Dubai&amp;y_source=1_MTUzNjI2MDMtNzE1LWxvY2F0aW9uLndlYnNpdGU%3D">Movenpick Hotel Downtown Dubai</a>&nbsp;:&nbsp;Al Ohood Street Burj Khalifa Area - Dubai - United Arab Emirates</li>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a>&nbsp;: Boulevard Street - Dubai - United Arab Emirates</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2025-01-04 12:37:40',
                'updated_at' => '2025-01-04 12:37:40',
            ),
            26 => 
            array (
                'id' => 27,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '23.21468810213655',
                'long' => '55.23010322824858',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            27 => 
            array (
                'id' => 28,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '36.496754501429216',
                'long' => '22.75151862681426',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            28 => 
            array (
                'id' => 29,
                'lang_code' => 'en',
                'title' => NULL,
                'description' => '<p>dubai</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.18881618233327',
                'long' => '55.26967406272888',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            29 => 
            array (
                'id' => 30,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a></li>
<li><a href="https://meetings.accor.com/our-event-types/training_session/index.en.shtml">Movenpick Hotel Downtown Dubai</a></li>
<li><a href="https://www.google.com/maps/place/Clover+Bay+Tower/@25.1888109,55.269682,15z/data=!4m5!3m4!1s0x0:0xdda585df6ed9b8ae!8m2!3d25.1888109!4d55.269682">LPC Training Dubai Branch - Clover Bay Tower, Business Bay</a></li>
</ul>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            30 => 
            array (
                'id' => 31,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a></li>
<li><a href="https://meetings.accor.com/our-event-types/training_session/index.en.shtml">Movenpick Hotel Downtown Dubai</a></li>
</ul>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            31 => 
            array (
                'id' => 32,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; : Address: 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/">Hard Rock Hotel London</a>&nbsp;:&nbsp; Address:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            32 => 
            array (
                'id' => 33,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/">Hard Rock Hotel London</a>&nbsp;:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            33 => 
            array (
                'id' => 34,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
<li><a href="https://www.hardrockhotels.com/london/area-guide.aspx">Hard Rock Hotel London&nbsp;</a>:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            34 => 
            array (
                'id' => 35,
                'lang_code' => 'en',
                'title' => 'Hard Rock Hotel London',
                'description' => '<p>&nbsp;</p>

<p>Our courses in London take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.hardrockhotels.com/london/area-guide.aspx">Hard Rock Hotel London&nbsp;</a>:&nbsp; Marble Arch&nbsp;- PlMarylebone, London,&nbsp;W1H 7D</li>
<li><a href="https://corushotels.com/corus-hyde-park/">Corus Hotel Hyde Park</a>&nbsp; :&nbsp; 1 Lancaster Gate, London W2 3LG</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.513723658345725',
                'long' => '-0.15895843505859375',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            35 => 
            array (
                'id' => 36,
                'lang_code' => 'en',
                'title' => 'LPC - Mövenpick Hotel Downtown Dubai',
                'description' => '<p>Our courses in Dubai take place at the following locations :</p>

<p>&nbsp;</p>

<ul>
<li><a href="https://www.movenpick.com/en/middle-east/uae/dubai/dubai-downtown/overview/?utm_source=google&amp;utm_medium=local&amp;utm_campaign=hotel-MHR-Downtown-Dubai&amp;y_source=1_MTUzNjI2MDMtNzE1LWxvY2F0aW9uLndlYnNpdGU%3D">Movenpick Hotel Downtown Dubai</a>&nbsp;:&nbsp;Al Ohood Street Burj Khalifa Area - Dubai - United Arab Emirates</li>
<li><a href="https://www.rdtdubai.com/en/">Ramada Hotel&nbsp;by Wyndham Downtown Dubai</a>&nbsp;: Boulevard Street - Dubai - United Arab Emirates</li>
</ul>

<p>&nbsp;</p>

<p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.194417934533675',
                'long' => '55.283567905426025',
                'created_at' => '2025-01-09 12:57:44',
                'updated_at' => '2025-01-09 12:57:44',
            ),
            36 => 
            array (
                'id' => 37,
                'lang_code' => 'en',
                'title' => 'Arts Hotel Istanbul- LPC Training',
            'description' => '<p>Our courses in Istanbul take place at the following locations :</p><p>&nbsp;</p><ul><li><a href="https://www.pointhotel.com/point-hotel-taksim" rel="noopener noreferrer" target="_blank">Point Hotel Taksim</a></li><li><a href="https://www.artshotel.com.tr/istanbul/contact/" rel="noopener noreferrer" target="_blank">Arts Hotel Istanbul</a></li></ul><p>&nbsp;</p><p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p><p>&nbsp;</p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location is subject to availability; the course time will be precise one week before the course start date! We may change the course location if there is no availability, and we will let you know about the location change once it happens.</p>',
                'external_id' => NULL,
                'city_id' => 4,
                'lat' => '41.048496',
                'long' => '28.987223',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            37 => 
            array (
                'id' => 38,
                'lang_code' => 'en',
                'title' => 'The Work Boulevard',
            'description' => '<p>Our courses in Singapore take place at the following location&nbsp;:</p><p><br></p><ul><li><a href="https://maps.app.goo.gl/ohCYnRcRy5ZseiwR6" rel="noopener noreferrer" target="_blank">The Work Boulevard:</a> 79 anson road level 21 Singapore 079906</li></ul><p><br></p><p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p><p>&nbsp;</p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location is subject to availability; the course time will be precise one week before the course start date! We may change the course location if there is no availability, and we will let you know about the location change once it happens.</p>',
                'external_id' => NULL,
                'city_id' => 39,
                'lat' => '1.2742309',
                'long' => '103.845569',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            38 => 
            array (
                'id' => 39,
                'lang_code' => 'en',
                'title' => 'LPC Oxford Street Offices',
            'description' => '<p>Our courses in London are held at the LPC office located at:</p><p><br></p><p><br></p><ul><li>LPC Oxford Street Offices:&nbsp;<a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">25 N Row, London W1K 6DJ</a></li></ul><p><br></p><ul><li>London Head Office: <u> </u><a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">47 - 49 Park Royal Road, London NW10 7LQ</a></li></ul><p><br></p><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.5131436',
                'long' => '-0.1546136',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            39 => 
            array (
                'id' => 40,
                'lang_code' => 'en',
                'title' => 'London Head Office',
            'description' => '<p>Our courses in London are held at the LPC office located at:</p><p><br></p><p><br></p><ul><li>LPC Oxford Street Offices:&nbsp;<a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">25 N Row, London W1K 6DJ</a></li></ul><p><br></p><ul><li>London Head Office: <u> </u><a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">47 - 49 Park Royal Road, London NW10 7LQ</a></li></ul><p><br></p><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.52818199999999',
                'long' => '-0.2674443',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            40 => 
            array (
                'id' => 41,
                'lang_code' => 'en',
                'title' => 'LPC Office in Barcelona Networkia Business Center Paseo de Gracia',
            'description' => '<p>Our courses in Barcelona are held at the LPC office located at:</p><p>&nbsp;</p><ul><li><a href="https://networkia.es/" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);"> Passeig de Gràcia, 21, planta principal, 08007 Barcelona, Spain</a></li></ul><p>&nbsp;</p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 41,
                'lat' => '41.39024420000001',
                'long' => '2.166457',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            41 => 
            array (
                'id' => 42,
                'lang_code' => 'en',
                'title' => 'LPC Office',
            'description' => '<p>Our courses in Dubai are held at the LPC office located at:</p><p><br></p><p><br></p><ul><li><span style="color: rgb(178, 107, 0);"> </span><a href="https://maps.app.goo.gl/aa2ewrqgqZarcmm98" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 102, 204);">ParkLane Tower - Business Bay - Dubai, 7th Floor, Office 718.</a></li></ul><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.1852338',
                'long' => '55.26189389999999',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            42 => 
            array (
                'id' => 43,
                'lang_code' => 'en',
                'title' => 'LPC Office - Paris',
            'description' => '<p>Our courses in Paris are held at the LPC office located at:</p><p><br></p><ul><li> <a href="https://g.co/kgs/djvzJt7" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">75 BD Haussmann ,75 Boulevard Haussmann, Paris, 75008</a></li></ul><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 5,
                'lat' => '48.8741837',
                'long' => '2.3225033',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            43 => 
            array (
                'id' => 44,
                'lang_code' => 'en',
                'title' => 'LPC Offices Spaces Herengracht',
            'description' => '<p>Our courses in Amsterdam are held at the LPC office located at:</p><p><br></p><p>&nbsp;</p><ul><li><a href="https://maps.app.goo.gl/xW9vBFo5Negeui9h8" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">Herengracht 124-128, 1015 BT Amsterdam, Netherlands</a></li></ul><p><br></p><p>&nbsp;</p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 38,
                'lat' => '52.3756952',
                'long' => '4.8883079',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            44 => 
            array (
                'id' => 45,
                'lang_code' => 'en',
                'title' => 'LPC Office - Kuala Lumpur',
            'description' => '<p>Our courses in Kuala Lumpur take place at the following location&nbsp;:</p><p>&nbsp;</p><ul><li><a href="https://maps.app.goo.gl/9x8BEf7XxwCBXgwk7" rel="noopener noreferrer" target="_blank" style="color: rgb(178, 107, 0);"> </a><a href="https://maps.app.goo.gl/9x8BEf7XxwCBXgwk7" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 102, 204);">Level 32 , Menara Prestige, 1, Jalan Pinang, Kuala Lumpur, 50450 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia</a></li></ul><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p><p>&nbsp;</p>',
                'external_id' => NULL,
                'city_id' => 3,
                'lat' => '3.155655700000001',
                'long' => '101.7095427',
                'created_at' => '2025-01-09 13:23:27',
                'updated_at' => '2025-01-09 13:23:27',
            ),
            45 => 
            array (
                'id' => 46,
                'lang_code' => 'en',
                'title' => 'Arts Hotel Istanbul- LPC Training',
            'description' => '<p>Our courses in Istanbul take place at the following locations :</p><p>&nbsp;</p><ul><li><a href="https://www.pointhotel.com/point-hotel-taksim" rel="noopener noreferrer" target="_blank">Point Hotel Taksim</a></li><li><a href="https://www.artshotel.com.tr/istanbul/contact/" rel="noopener noreferrer" target="_blank">Arts Hotel Istanbul</a></li></ul><p>&nbsp;</p><p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p><p>&nbsp;</p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location is subject to availability; the course time will be precise one week before the course start date! We may change the course location if there is no availability, and we will let you know about the location change once it happens.</p>',
                'external_id' => NULL,
                'city_id' => 4,
                'lat' => '41.048496',
                'long' => '28.987223',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            46 => 
            array (
                'id' => 47,
                'lang_code' => 'en',
                'title' => 'The Work Boulevard',
            'description' => '<p>Our courses in Singapore take place at the following location&nbsp;:</p><p><br></p><ul><li><a href="https://maps.app.goo.gl/ohCYnRcRy5ZseiwR6" rel="noopener noreferrer" target="_blank">The Work Boulevard:</a> 79 anson road level 21 Singapore 079906</li></ul><p><br></p><p>Once you register for this course, we will subsequently send the invoice and course information, including location, trainer, and other logistics.</p><p>&nbsp;</p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location is subject to availability; the course time will be precise one week before the course start date! We may change the course location if there is no availability, and we will let you know about the location change once it happens.</p>',
                'external_id' => NULL,
                'city_id' => 39,
                'lat' => '1.2742309',
                'long' => '103.845569',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            47 => 
            array (
                'id' => 48,
                'lang_code' => 'en',
                'title' => 'LPC Oxford Street Offices',
            'description' => '<p>Our courses in London are held at the LPC office located at:</p><p><br></p><p><br></p><ul><li>LPC Oxford Street Offices:&nbsp;<a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">25 N Row, London W1K 6DJ</a></li></ul><p><br></p><ul><li>London Head Office: <u> </u><a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">47 - 49 Park Royal Road, London NW10 7LQ</a></li></ul><p><br></p><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.5131436',
                'long' => '-0.1546136',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            48 => 
            array (
                'id' => 49,
                'lang_code' => 'en',
                'title' => 'London Head Office',
            'description' => '<p>Our courses in London are held at the LPC office located at:</p><p><br></p><p><br></p><ul><li>LPC Oxford Street Offices:&nbsp;<a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">25 N Row, London W1K 6DJ</a></li></ul><p><br></p><ul><li>London Head Office: <u> </u><a href="https://www.google.com/maps/place/Regus+-+London,+Oxford+Street+(Marble+Arch)/@51.5132,-0.15479,15z/data=!4m2!3m1!1s0x0:0x499d055460782789?sa=X&amp;ved=2ahUKEwiItJ6s34D7AhVEZMAKHZTiB_oQ_BJ6BAhzEAU" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">47 - 49 Park Royal Road, London NW10 7LQ</a></li></ul><p><br></p><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 27,
                'lat' => '51.52818199999999',
                'long' => '-0.2674443',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            49 => 
            array (
                'id' => 50,
                'lang_code' => 'en',
                'title' => 'LPC Office in Barcelona Networkia Business Center Paseo de Gracia',
            'description' => '<p>Our courses in Barcelona are held at the LPC office located at:</p><p>&nbsp;</p><ul><li><a href="https://networkia.es/" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);"> Passeig de Gràcia, 21, planta principal, 08007 Barcelona, Spain</a></li></ul><p>&nbsp;</p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 41,
                'lat' => '41.39024420000001',
                'long' => '2.166457',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            50 => 
            array (
                'id' => 51,
                'lang_code' => 'en',
                'title' => 'LPC Office',
            'description' => '<p>Our courses in Dubai are held at the LPC office located at:</p><p><br></p><p><br></p><ul><li><span style="color: rgb(178, 107, 0);"> </span><a href="https://maps.app.goo.gl/aa2ewrqgqZarcmm98" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 102, 204);">ParkLane Tower - Business Bay - Dubai, 7th Floor, Office 718.</a></li></ul><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 30,
                'lat' => '25.1852338',
                'long' => '55.26189389999999',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            51 => 
            array (
                'id' => 52,
                'lang_code' => 'en',
                'title' => 'LPC Office - Paris',
            'description' => '<p>Our courses in Paris are held at the LPC office located at:</p><p><br></p><ul><li> <a href="https://g.co/kgs/djvzJt7" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">75 BD Haussmann ,75 Boulevard Haussmann, Paris, 75008</a></li></ul><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 5,
                'lat' => '48.8741837',
                'long' => '2.3225033',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            52 => 
            array (
                'id' => 53,
                'lang_code' => 'en',
                'title' => 'LPC Offices Spaces Herengracht',
            'description' => '<p>Our courses in Amsterdam are held at the LPC office located at:</p><p><br></p><p>&nbsp;</p><ul><li><a href="https://maps.app.goo.gl/xW9vBFo5Negeui9h8" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 71, 178);">Herengracht 124-128, 1015 BT Amsterdam, Netherlands</a></li></ul><p><br></p><p>&nbsp;</p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p>',
                'external_id' => NULL,
                'city_id' => 38,
                'lat' => '52.3756952',
                'long' => '4.8883079',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
            53 => 
            array (
                'id' => 54,
                'lang_code' => 'en',
                'title' => 'LPC Office - Kuala Lumpur',
            'description' => '<p>Our courses in Kuala Lumpur take place at the following location&nbsp;:</p><p>&nbsp;</p><ul><li><a href="https://maps.app.goo.gl/9x8BEf7XxwCBXgwk7" rel="noopener noreferrer" target="_blank" style="color: rgb(178, 107, 0);"> </a><a href="https://maps.app.goo.gl/9x8BEf7XxwCBXgwk7" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 102, 204);">Level 32 , Menara Prestige, 1, Jalan Pinang, Kuala Lumpur, 50450 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia</a></li></ul><p><br></p><p>Once you register, we will subsequently send you the course details, including the location, trainer, and other logistical information.</p><p><br></p><p><span style="color: rgb(230, 0, 0);">Pay Attention, Please! </span>The course location at our offices is subject to availability. Should our office be unavailable, we will secure an alternative nearby venue and promptly inform you of the change. The exact time and location will be confirmed one week prior to the course commencement.</p><p>&nbsp;</p>',
                'external_id' => NULL,
                'city_id' => 3,
                'lat' => '3.155655700000001',
                'long' => '101.7095427',
                'created_at' => '2025-01-09 13:24:05',
                'updated_at' => '2025-01-09 13:24:05',
            ),
        ));
        
        
    }
}