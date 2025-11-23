<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('courses')->delete();
        
        \DB::table('courses')->insert(array (
            0 => 
            array (
                'id' => 129,
                'lang_code' => 'en',
                'name' => 'Medical Laboratory Technician Training',
                'description' => '<p>A laboratory technician is a person who performs the practical hands-on work in laboratories. Lab techs work in diverse settings which include health care, industry, research, and educational institutions. Lab techs may work in a wide variety of fields such as medicine, biology, chemistry, electronics, geology and the environment.</p>

<p>&nbsp;</p>

<p>Medical Lab Technician Training course&nbsp;is designed for those who have an interest in science and technology in health care, this field will offer you a variety of career opportunities.&nbsp; It covers the skills and knowledge required to apply a range of laboratory technologies to conduct scientific-technical tests and sampling in most industry sectors.</p>

<p>&nbsp;</p>

<p>The course aims to impart basic knowledge to participants with regard to general laboratory technical works in a view to producing competent technicians who will apply basic skills and knowledge in the field of science and laboratory technology. You will gain the skills and confidence to manage functions of a laboratory, from quality to finance and beyond.</p>

<p>&nbsp;</p>

<p>You will receive all the skills and knowledge you will need for a position of responsibility as a technical supervisor in a laboratory or similar workplace where analytical tests are conducted</p>

<p>&nbsp;</p>

<p>&nbsp;</p>',
                'duration' => '5',
                'days_content' => '["<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>\\n\\t<h6>Introduction to Laboratory Technician</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Role of Medical Laboratory Services</h6>\\n\\t</li>\\n</ul>\\n","<h6>&nbsp;</h6>\\n\\n<ul>\\n\\t<li>\\n\\t<h6>Laboratory Policies</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Solutions Used in Medical Laboratory</h6>\\n\\t</li>\\n</ul>\\n\\n<p>&nbsp;</p>\\n","<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>\\n\\t<h6>Laboratory Wares</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Laboratory Instruments</h6>\\n\\t</li>\\n</ul>\\n\\n<p>&nbsp;</p>\\n","<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>\\n\\t<h6>Sterilisation and Disinfection</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Laboratory Accidents and Safety</h6>\\n\\t</li>\\n</ul>\\n\\n<p>&nbsp;</p>\\n","<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>\\n\\t<h6>Quality Assurance</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Glossary</h6>\\n\\t</li>\\n</ul>\\n\\n<p>&nbsp;</p>\\n"]',
                'related_courses' => '[130,755,749]',
                'category_id' => 7,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:44:50',
            ),
            1 => 
            array (
                'id' => 130,
                'lang_code' => 'en',
                'name' => 'Medical Laboratory Technician Training',
                'description' => '<p>A laboratory technician is a person who performs the practical hands-on work in laboratories. Lab techs work in diverse settings which include health care, industry, research, and educational institutions. Lab techs may work in a wide variety of fields such as medicine, biology, chemistry, electronics, geology and the environment.</p>

<p>&nbsp;</p>

<p>Medical Lab Technician Training course&nbsp;is designed for those who have an interest in science and technology in health care, this field will offer you a variety of career opportunities.&nbsp; It covers the skills and knowledge required to apply a range of laboratory technologies to conduct scientific-technical tests and sampling in most industry sectors.</p>

<p>&nbsp;</p>

<p>The course aims to impart basic knowledge to participants with regard to general laboratory technical works in a view to producing competent technicians who will apply basic skills and knowledge in the field of science and laboratory technology. You will gain the skills and confidence to manage functions of a laboratory, from quality to finance and beyond.</p>

<p>&nbsp;</p>

<p>You will receive all the skills and knowledge you will need for a position of responsibility as a technical supervisor in a laboratory or similar workplace where analytical tests are conducted</p>',
                'duration' => '5',
                'days_content' => '["<ul>\\n\\t<li>\\n\\t<h6>Introduction to Laboratory Technician</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Role of Medical Laboratory Services</h6>\\n\\t</li>\\n</ul>\\n","<ul>\\n\\t<li>\\n\\t<h6>Laboratory Policies</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Solutions Used in Medical Laboratory</h6>\\n\\t</li>\\n</ul>\\n","<ul>\\n\\t<li>\\n\\t<h6>Laboratory Wares</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Laboratory Instruments</h6>\\n\\t</li>\\n</ul>\\n","<ul>\\n\\t<li>\\n\\t<h6>Sterilisation and Disinfection</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Laboratory Accidents and Safety</h6>\\n\\t</li>\\n</ul>\\n","<ul>\\n\\t<li>\\n\\t<h6>Quality Assurance</h6>\\n\\t</li>\\n\\t<li>\\n\\t<h6>Glossary</h6>\\n\\t</li>\\n</ul>\\n"]',
                'related_courses' => '[657,750,753]',
                'category_id' => 7,
                'online' => 1,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:44:50',
            ),
            2 => 
            array (
                'id' => 136,
                'lang_code' => 'en',
                'name' => 'Treasury Products & Risk Management',
                'description' => '<p>&nbsp;</p>

<p>The Treasury products &amp; Risk Management function is essential to the success and sustainability of all leading corporate organizations. Never has this been truer, given the ever-increasing pace of change in regulation, compliance, technology, and financial risk.</p>

<p>&nbsp;</p>

<p>The programme places an emphasis on interaction in an aim to expand and enhance delegates&rsquo; abilities to discuss treasury risks with their clients, customers, prospects and colleagues. Further, delegates will have ample opportunity to gain practical skills in treasury management and upon completion of this course, will be equipped with short, medium and long term plans for treasury management development within their own organizations.</p>

<p>&nbsp;</p>

<p>This 5-day course in Treasury Products &amp; Risk Management incorporates case studies, seminars and interactive exercises to teach delegates to effectively utilise treasury products in a trading, hedging and risk management environment.</p>',
                'duration' => '5',
                'days_content' => '["<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>Treasury Risk Management</li>\\n\\t<li>Identifying Risks and Uncertainties</li>\\n\\t<li>Who Uses Treasury Products?</li>\\n\\t<li>Treasury Solutions &ndash; Currency Risk</li>\\n\\t<li>Corporate Treasury Risk Management</li>\\n\\t<li>Commodity Hedging and Trading Simulation</li>\\n</ul>\\n","<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>Treasury Risk Management Meeting: StoraEnso</li>\\n\\t<li>How Hedging Works</li>\\n\\t<li>Strategies for Using Treasury Products</li>\\n\\t<li>Measuring Treasury Performance</li>\\n\\t<li>Evaluating the Benefits of Treasury Management</li>\\n\\t<li>Interest Rate Hedging and Trading Simulation</li>\\n</ul>\\n","<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>Cash Forecasts: Role &amp; Preparation</li>\\n\\t<li>Investment of Cash Surpluses to Maximize Return</li>\\n\\t<li>Meeting Cash Calls and Short-Term Cash Shortages / Short Term Finance</li>\\n\\t<li>Working Capital Management &ndash;&nbsp;Determining the Optimum Level</li>\\n\\t<li>Multi-national &amp; Group Cash Management</li>\\n\\t<li>Cash Budgets: Process &amp; Control</li>\\n</ul>\\n","<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>Interest Rate Risk Management</li>\\n\\t<li>Corporate Asset &amp; Liability Management</li>\\n\\t<li>Currency Hedging and Trading Simulation</li>\\n</ul>\\n","<p>&nbsp;</p>\\n\\n<ul>\\n\\t<li>Medium and Long-Term Financing Strategies Capital Markets &ndash; Equity</li>\\n\\t<li>Capital Markets &ndash; Debt</li>\\n\\t<li>Liquidity Risk Management</li>\\n\\t<li>Asset and Liability Management</li>\\n\\t<li>Debt Management</li>\\n\\t<li>Translating the Training into Action</li>\\n</ul>\\n"]',
                'related_courses' => '[170,183,436,545]',
                'category_id' => 34,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:44:50',
            ),
            3 => 
            array (
                'id' => 142,
                'lang_code' => 'en',
                'name' => 'The Three-Dimensions of Leadership',
                'description' => '<p>The study of leadership &nbsp;traditionally starts by focusing on the leader&rsquo;s personal awareness of themselves and their style in interacting with others. From first line to top line executives, mastering yourself and your interactions permits effective communication, clear performance expectations and teamwork. The second stage concentrates on process knowledge and skills to produce value. Work can only be accomplished when key skills are applied. The third dimension recently became an essential leadership component. It requires agility in setting wise and timely direction or goals based on changing circumstances and situations.&nbsp;</p>

<p>&nbsp;</p>

<p><strong>The Three-Dimensions of Leadership training course </strong>instructs people from the boardroom to the mailroom to support company goals in every situation by identifying and focusing on the mission that matters most, to work with the four types of employees so they rally as resources to cooperate as a team that negotiates the big-picture of organizational channels and politics to convert within the context.</p>

<p>&nbsp;</p>

<p>The Three-Dimensions of Leadership will teach leaders how to approach every situation at work by identifying and maintaining the Three-Dimensional Mission, Resources and Context (3-D MRC) outlook and focus in which all organizational accomplishment is rooted.</p>

<p>&nbsp;</p>

<p>Regardless of how senior a job title you hold, or if you are being considered for your first supervisory promotion, &nbsp;the course will equip you with the frame of reference, the essential values, viewpoints and perspectives necessary to lead effectively in every situation!&nbsp;</p>',
                'duration' => '5',
                'days_content' => '["<ul>\\n\\t<li>New conceptual model of organisational leadership</li>\\n\\t<li>The role of organisational leadership</li>\\n\\t<li>Mediate between organisational task demands and subordinates&rsquo; goals or needs</li>\\n</ul>\\n","<ul>\\n\\t<li>The three proposed dimensions of leader behaviour, illustrated by items from factor analytic Studies, which have high loadings on each dimension</li>\\n\\t<li>Task orientation dimension</li>\\n\\t<li>Presents the theoretical model with the three dimensions specified</li>\\n</ul>\\n","<ul>\\n\\t<li>The Triple-T Delegation Dynamics: Training, Timing, Trusting</li>\\n\\t<li>Supervising The 4 Types of Employees for Productive Working Relationships</li>\\n\\t<li>Achieving The 3-C&rsquo;s of Emotional Intelligence: Communication, Cooperation &amp; Coordination</li>\\n</ul>\\n","<ul>\\n\\t<li>Initiating Incentive Awards That Motivate &amp; Inflate Individual &amp; Team Performance</li>\\n\\t<li>The 3-M&rsquo;s of Change Management: the right Motivation, Map, and Message</li>\\n\\t<li>The 5-Factors of Out-of-the-Box Thinking That Accomplish Innovation</li>\\n</ul>\\n","<ul>\\n\\t<li>Negotiating The Organization&rsquo;s Big Picture, Political &amp; Operational Context</li>\\n\\t<li>Becoming 3-D SEM Masters Who Submit Ego to the Mission</li>\\n\\t<li>Developing &amp; self-assessing your 3-D leadership profile</li>\\n</ul>\\n"]',
                'related_courses' => '[150,154,209,213]',
                'category_id' => 41,
                'online' => 0,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:44:50',
            ),
            4 => 
            array (
                'id' => 143,
                'lang_code' => 'en',
                'name' => 'The Three-Dimensions of Leadership',
                'description' => '<p>The study of leadership &nbsp;traditionally starts by focusing on the leader&rsquo;s personal awareness of themselves and their style in interacting with others. From first line to top line executives, mastering yourself and your interactions permits effective communication, clear performance expectations and teamwork. The second stage concentrates on process knowledge and skills to produce value. Work can only be accomplished when key skills are applied. The third dimension recently became an essential leadership component. It requires agility in setting wise and timely direction or goals based on changing circumstances and situations.&nbsp;</p>

<p><strong>The Three-Dimensions of Leadership training course </strong>instructs people from the boardroom to the mailroom to support company goals in every situation by identifying and focusing on the mission that matters most, to work with the four types of employees so they rally as resources to cooperate as a team that negotiates the big-picture of organizational channels and politics to convert within the context.</p>

<p>The Three-Dimensions of Leadership will teach leaders how to approach every situation at work by identifying and maintaining the Three-Dimensional Mission, Resources and Context (3-D MRC) outlook and focus in which all organizational accomplishment is rooted. From the opening to the closing sessions each participant is given dozens of profound yet practical concepts that are easy to understand, are reinforced with numerous real-life examples and experiences everyone sees around them at work and which immediately can be applied as soon as you arrive back in the shop, unit and office!</p>

<p>The study of leadership &nbsp;traditionally starts by focusing on the leader&rsquo;s personal awareness of themselves and their style in interacting with others. From first line to top line executives, mastering yourself and your interactions permits effective communication, clear performance expectations and teamwork. The second stage concentrates on process knowledge and skills to produce value. Work can only be accomplished when key skills are applied. The third dimension recently became an essential leadership component. It requires agility in setting wise and timely direction or goals based on changing circumstances and situations.&nbsp;</p>

<p><strong>The Three-Dimensions of Leadership training course </strong>instructs people from the boardroom to the mailroom to support company goals in every situation by identifying and focusing on the mission that matters most, to work with the four types of employees so they rally as resources to cooperate as a team that negotiates the big-picture of organizational channels and politics to convert within the context.</p>

<p>The Three-Dimensions of Leadership will teach leaders how to approach every situation at work by identifying and maintaining the Three-Dimensional Mission, Resources and Context (3-D MRC) outlook and focus in which all organizational accomplishment is rooted. From the opening to the closing sessions each participant is given dozens of profound yet practical concepts that are easy to understand, are reinforced with numerous real-life examples and experiences everyone sees around them at work and which immediately can be applied as soon as you arrive back in the shop, unit and office!</p>',
                'duration' => '5',
                'days_content' => '["<ul>\\n\\t<li>New conceptual model of organisational leadership</li>\\n\\t<li>The role of organisational leadership</li>\\n\\t<li>Mediate between organisational task demands and subordinates&rsquo; goals or needs</li>\\n</ul>\\n","<ul>\\n\\t<li>The three proposed dimensions of leader behaviour, illustrated by items from factor analytic Studies, which have high loadings on each dimension</li>\\n\\t<li>Task orientation dimension</li>\\n\\t<li>Presents the theoretical model with the three dimensions specified</li>\\n</ul>\\n","<ul>\\n\\t<li>The Triple-T Delegation Dynamics: Training, Timing, Trusting</li>\\n\\t<li>Supervising The 4 Types of Employees for Productive Working Relationships</li>\\n\\t<li>Achieving The 3-C&rsquo;s of Emotional Intelligence: Communication, Cooperation &amp; Coordination</li>\\n</ul>\\n","<ul>\\n\\t<li>Initiating Incentive Awards That Motivate &amp; Inflate Individual &amp; Team Performance</li>\\n\\t<li>The 3-M&rsquo;s of Change Management: the right Motivation, Map, and Message</li>\\n\\t<li>The 5-Factors of Out-of-the-Box Thinking That Accomplish Innovation</li>\\n</ul>\\n","<ul>\\n\\t<li>Negotiating The Organization&rsquo;s Big Picture, Political &amp; Operational Context</li>\\n\\t<li>Becoming 3-D SEM Masters Who Submit Ego to the Mission</li>\\n\\t<li>Developing &amp; self-assessing your 3-D leadership profile</li>\\n</ul>\\n"]',
                'related_courses' => '[151,155,210,214]',
                'category_id' => 41,
                'online' => 1,
                'created_at' => '2025-01-09 13:39:34',
                'updated_at' => '2025-01-09 13:44:50',
            ),

  ));


    }
}