<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FooterSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('footer_settings')->delete();
        
        \DB::table('footer_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'external_id' => 1,
                'name' => 'google_code',
            'value' => '<!-- Global site tag (gtag.js) - Google Analytics --> <script async src="https://www.googletagmanager.com/gtag/js?id=UA-164824238-1"></script> <script>   window.dataLayer = window.dataLayer || [];   function gtag(){dataLayer.push(arguments);}   gtag(\'js\', new Date());    gtag(\'config\', \'UA-164824238-1\'); </script>',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            1 => 
            array (
                'id' => 2,
                'external_id' => 2,
                'name' => 'facebook_code',
            'value' => '<!-- Meta Pixel Code --> <script> !function(f,b,e,v,n,t,s) {if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\'; n.queue=[];t=b.createElement(e);t.async=!0; t.src=v;s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window, document,\'script\', \'https://connect.facebook.net/en_US/fbevents.js\'); fbq(\'init\', \'1444313379320028\'); fbq(\'track\', \'PageView\'); </script> <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1444313379320028&ev=PageView&noscript=1" /></noscript> <!-- End Meta Pixel Code -->',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            2 => 
            array (
                'id' => 3,
                'external_id' => 3,
                'name' => 'footer_contact',
                'value' => '<p>47 &ndash; 49 Park Royal Road<br />
NW10 7LQ, London<br />
T: +44 (0) 20 8090 0464<br />
E:&nbsp;<a href="mailto:info@lpcentre.com">info@lpcentre.com</a></p>',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            3 => 
            array (
                'id' => 4,
                'external_id' => 4,
                'name' => 'footer_categories',
                'value' => '',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            4 => 
            array (
                'id' => 5,
                'external_id' => 5,
                'name' => 'footer_youtube',
                'value' => 'LMIA',
                'img' => 'about_us/',
                'alter_img' => 'alter_img',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            5 => 
            array (
                'id' => 6,
                'external_id' => 6,
                'name' => 'footer_whatsapp',
                'value' => 'https://wa.me/+442080900464',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            6 => 
            array (
                'id' => 7,
                'external_id' => 7,
                'name' => 'footer_facebook',
                'value' => 'https://www.facebook.com/lpctraining/',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            7 => 
            array (
                'id' => 8,
                'external_id' => 8,
                'name' => 'footer_youtube',
                'value' => 'https://youtube.com',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            8 => 
            array (
                'id' => 9,
                'external_id' => 9,
                'name' => 'footer_linkedin',
                'value' => 'https://www.linkedin.com/company/london-premier-centre',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            9 => 
            array (
                'id' => 10,
                'external_id' => 10,
                'name' => 'contact_form',
                'value' => 'Don\'t enter any data, please!',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            10 => 
            array (
                'id' => 11,
                'external_id' => 11,
                'name' => 'newsletter_form',
                'value' => 'Don\'t enter any data, please!',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            11 => 
            array (
                'id' => 12,
                'external_id' => 12,
                'name' => 'registration_form',
                'value' => 'Don\'t enter any data, please!',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            12 => 
            array (
                'id' => 13,
                'external_id' => 13,
                'name' => 'live_chat_widget',
                'value' => 'Don\'t enter any data, please!',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            13 => 
            array (
                'id' => 14,
                'external_id' => 22,
                'name' => 'footer_contact1',
            'value' => '<p>47 – 49 Park Royal Road</p><p> NW10 7LQ, London</p><p> +44 (0) 20 80 900 464</p><p> E:&nbsp;<a href="mailto:info@lpcentre.com" rel="noopener noreferrer" target="_blank">info@lpcentre.com</a></p>',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            14 => 
            array (
                'id' => 15,
                'external_id' => 23,
                'name' => 'footer_contact2',
                'value' => '<p>Office 501</p><p> Clover Bay Tower, Business Bay</p><p>T: +971 4 421 4616</p>',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            15 => 
            array (
                'id' => 16,
                'external_id' => 24,
                'name' => 'footer_contact3',
                'value' => '<p>No. 03-06-05,</p><p> UOA Business Park,</p><p> Jalan Pengaturcara U1/51A,</p><p> Section U1,</p><p> Kawasan Perindustrian Temasaya,</p><p> 40150 Shah Alam, Selangor</p><p> T: +60 19-305 5694</p>',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            16 => 
            array (
                'id' => 17,
                'external_id' => 25,
                'name' => 'footer_twitter',
                'value' => 'https://twitter.com/LondonLpc',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            17 => 
            array (
                'id' => 18,
                'external_id' => 26,
                'name' => 'who_we_are',
                'value' => '<p>London Premier Centre is a UK leading training provider based in London and specializes in international short courses.</p>  <p>Our inspiring comprehensive portfolio of more than 400 professional development courses and seminars cover a wide range of professions from Administration, Leadership, HR, Business, Strategy, Finance, Project Management, Health, Safety, Security, as well as a comprehensive suite of Technical Skills courses in Engineering, Power and Energy, Oil and Gas, Maintenance, as well as many others.</p>',
                'img' => 'about_us/about.jpg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            18 => 
            array (
                'id' => 19,
                'external_id' => 28,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 10 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            19 => 
            array (
                'id' => 20,
                'external_id' => 29,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With London Premier Centre being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            20 => 
            array (
                'id' => 21,
                'external_id' => 30,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in London, we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            21 => 
            array (
                'id' => 22,
                'external_id' => 31,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals. London Premier Centre is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            22 => 
            array (
                'id' => 23,
                'external_id' => 34,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 10 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            23 => 
            array (
                'id' => 24,
                'external_id' => 35,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With London Premier Centre being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            24 => 
            array (
                'id' => 25,
                'external_id' => 36,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in London, we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            25 => 
            array (
                'id' => 26,
                'external_id' => 37,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals. London Premier Centre is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            26 => 
            array (
                'id' => 27,
                'external_id' => 40,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 10 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            27 => 
            array (
                'id' => 28,
                'external_id' => 41,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With London Premier Centre being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            28 => 
            array (
                'id' => 29,
                'external_id' => 42,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in London, we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            29 => 
            array (
                'id' => 30,
                'external_id' => 43,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals. London Premier Centre is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            30 => 
            array (
                'id' => 31,
                'external_id' => 46,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 10 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            31 => 
            array (
                'id' => 32,
                'external_id' => 47,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With London Premier Centre being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            32 => 
            array (
                'id' => 33,
                'external_id' => 48,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in London, we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            33 => 
            array (
                'id' => 34,
                'external_id' => 49,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals. London Premier Centre is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            34 => 
            array (
                'id' => 35,
                'external_id' => 51,
                'name' => 'our_goal',
                'value' => '<p>At the <strong>London Premier Centre</strong>, we undertake that safeguarding both the interest and welfare of our delegates supersedes other priorities.</p>

<p>Our ongoing learning program enables Learners to continuously enhance their professional and personal skills for the purpose of their organization&rsquo;s development.</p>',
                'img' => 'about_us/about1.png',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            35 => 
            array (
                'id' => 36,
                'external_id' => 52,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            36 => 
            array (
                'id' => 37,
                'external_id' => 53,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses.</p>

<p>&nbsp;</p>

<p>With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            37 => 
            array (
                'id' => 38,
                'external_id' => 54,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place <strong>mainly in London and Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul.</p>

<p>&nbsp;</p>

<p>The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            38 => 
            array (
                'id' => 39,
                'external_id' => 55,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.</p>

<p>&nbsp;</p>

<p><strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            39 => 
            array (
                'id' => 40,
                'external_id' => 58,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            40 => 
            array (
                'id' => 41,
                'external_id' => 59,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            41 => 
            array (
                'id' => 42,
                'external_id' => 60,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in<strong> London </strong>and<strong> Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            42 => 
            array (
                'id' => 43,
                'external_id' => 61,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.&nbsp;<strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            43 => 
            array (
                'id' => 44,
                'external_id' => 64,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            44 => 
            array (
                'id' => 45,
                'external_id' => 65,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            45 => 
            array (
                'id' => 46,
                'external_id' => 66,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in<strong> London </strong>and<strong> Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            46 => 
            array (
                'id' => 47,
                'external_id' => 67,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.&nbsp;<strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            47 => 
            array (
                'id' => 48,
                'external_id' => 70,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            48 => 
            array (
                'id' => 49,
                'external_id' => 71,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            49 => 
            array (
                'id' => 50,
                'external_id' => 72,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in<strong> London </strong>and<strong> Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            50 => 
            array (
                'id' => 51,
                'external_id' => 73,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.&nbsp;<strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>

<h6>&nbsp;</h6>

<h6>Training Venues</h6>

<p><br />
We work with leading venues partners Hotels, which focus on providing high-quality venues and facilities for corporate training and events. courses venues include lunch and refreshments.<br />
&nbsp;</p>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            51 => 
            array (
                'id' => 52,
                'external_id' => 76,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            52 => 
            array (
                'id' => 53,
                'external_id' => 77,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            53 => 
            array (
                'id' => 54,
                'external_id' => 78,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in<strong> London </strong>and<strong> Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<h6><strong>Training Venues</strong></h6>

<p><br />
We work with leading venues partners Hotels, which focus on providing high-quality venues and facilities for corporate training and events. courses venues include lunch and refreshments.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            54 => 
            array (
                'id' => 55,
                'external_id' => 79,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.&nbsp;<strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>

<h6><br />
&nbsp;</h6>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            55 => 
            array (
                'id' => 56,
                'external_id' => 82,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            56 => 
            array (
                'id' => 57,
                'external_id' => 83,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            57 => 
            array (
                'id' => 58,
                'external_id' => 84,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in<strong> London </strong>and<strong> Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<h6><strong>Training Venues</strong></h6>

<p><br />
We work with leading venues partners Hotels, which focus on providing high-quality venues and facilities for corporate training and events. courses venues include lunch and refreshments.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            58 => 
            array (
                'id' => 59,
                'external_id' => 85,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.&nbsp;<strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>

<p>&nbsp;</p>

<pre>
If you&rsquo;re looking for more specific Training Courses, please call us at +44 (0) 20 8090 0464 or contact us at courses@lpcentre.com

</pre>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            59 => 
            array (
                'id' => 60,
                'external_id' => 88,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            60 => 
            array (
                'id' => 61,
                'external_id' => 89,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            61 => 
            array (
                'id' => 62,
                'external_id' => 90,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in<strong> London </strong>and<strong> Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<h6><strong>Training Venues</strong></h6>

<p><br />
We work with leading venues partners Hotels, which focus on providing high-quality venues and facilities for corporate training and events. courses venues include lunch and refreshments.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            62 => 
            array (
                'id' => 63,
                'external_id' => 91,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.&nbsp;<strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>

<p>&nbsp;</p>

<pre>
If you&rsquo;re looking for more specific Training Courses, 
please call us at +44 (0) 20 8090 0464 
or contact us at courses@lpcentre.com

</pre>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            63 => 
            array (
                'id' => 64,
                'external_id' => 94,
                'name' => 'what_we_do1',
                'value' => '<p>For the last 14 years, we have been providing high standard training programs for Public Sectors in all fields. Due to this, we have gained a trusted reputation and the respect of people around the world. We deliver training courses to the governmental bodies, corporates, and individuals from the Middle East, Gulf Countries, Africa, Indonesia, and South America.</p>',
                'img' => 'about_us/whatwedo1.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            64 => 
            array (
                'id' => 65,
                'external_id' => 95,
                'name' => 'what_we_do2',
                'value' => '<p>We have built an enviable reputation of continual trust in delivering exceptional development solutions by providing winning quality assured training and skills development courses. With <strong>London Premier Centre </strong>being the leading choice for many providers, we have properly rearranged our courses portfolio to suit and further accommodate the rapidly adapting training requisites of today&rsquo;s professionals.</p>',
                'img' => 'about_us/whatwedo2.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            65 => 
            array (
                'id' => 66,
                'external_id' => 96,
                'name' => 'what_we_do3',
                'value' => '<p>Even though our training programs take place mainly in<strong> London </strong>and<strong> Dubai,</strong> we also deliver 35% of the courses in other Capitals around the world, such as Paris, Madrid, Amsterdam, and Istanbul. The unique element in our working system is achieving a high number of training courses each month due to the increase in demand by different sectors. With this, participants looking for a last-minute training course to cover their needs in any field can find suitable options at London Premier Centre.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<h6><strong>Training Venues</strong></h6>

<p><br />
We work with leading venues partners Hotels, which focus on providing high-quality venues and facilities for corporate training and events. courses venues include lunch and refreshments.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>',
                'img' => 'about_us/whatwedo3.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            66 => 
            array (
                'id' => 67,
                'external_id' => 97,
                'name' => 'what_we_do4',
                'value' => '<p>Our broad-scoped Classroom and online training courses involve professional development and innovative learning approaches that are properly organized to cope with the progressing training requisites of today&rsquo;s professionals.&nbsp;<strong>London Premier Centre</strong> is committed to ensuring that delegates receive the best development to maximize their potential and talent through impeccable programs. We are committed to delivering the best possible learning experience as the leading training provider by strengthening our continued working relationship with the world&rsquo;s leading professionals governing, awarding, and certifying bodies.</p>

<p>&nbsp;</p>

<pre>
If you&rsquo;re looking for more specific Training Course, 
please call us at +44 (0) 20 8090 0464 
or contact us at <a href="http://courses@lpcentre.com">courses@lpcentre.com</a>

</pre>',
                'img' => 'about_us/whatwedo4.svg',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            67 => 
            array (
                'id' => 68,
                'external_id' => 98,
                'name' => 'categories_page_content_classic',
                'value' => '<p>Human interaction can create more magic than virtual ones, and a great lot of things<br />
can come from classroom courses. Healthy discussions and debates are fun ways to learn things.</p>

<p>Our classroom training courses enhance learners&#39; critical thinking, it allows<br />
participants to engage in live discussions in which they are forced to use their<br />
critical thinking skills to formulate opinions or arguments.</p>

<p>Find below the classroom training categories, locations and dates, provided by<br />
London Premier Centre.</p>',
                'img' => 'about_us',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            68 => 
            array (
                'id' => 69,
                'external_id' => 99,
                'name' => 'categories_page_content_online',
                'value' => '<p>Online Training courses enable the teacher and the learner to set their own learning<br />
pace, and there&rsquo;s the added flexibility of setting a schedule that fits everyone&rsquo;s<br />
agenda.</p>

<p>Learning online teaches you vital time management skills, making finding an<br />
excellent work-study balance more manageable.</p>

<p>Online Training courses enable you to learn from anywhere in the world.<br />
This means there&rsquo;s no need to commute from one place to another or follow a rigid<br />
schedule. On top of that, not only do you save time, but you also save money, which<br />
can be spent on other priorities.<br />
Find below the Online Training Categories provided by London Premier Centre.</p>',
                'img' => 'about_us',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
            69 => 
            array (
                'id' => 70,
                'external_id' => 100,
                'name' => 'footer_contact4',
                'value' => '<p>No. 03-06-05,</p><p> UOA Business Park,<img src="https://www.lpcentre.com/new_storage/editor/441684620574_1721045577.png" alt="test"></p><p> Jalan Pengaturcara U1/51A,</p><p> Section U1,</p><p> Kawasan Perindustrian Temasaya,</p><p> 40150 Shah Alam, Selangor</p><p> T: +60 19-305 5694</p>',
                'img' => 'about_us/',
                'alter_img' => '',
                'created_at' => '2024-09-24 10:22:58',
                'updated_at' => '2024-09-24 10:22:58',
            ),
        ));
        
        
    }
}