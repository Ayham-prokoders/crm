<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 5,
                'lang_code' => 'en',
            'title' => 'Human Resources Courses (Classroom)',
                'description' => '<h6>Human Resources Training&nbsp;Courses are aimed at teaching HR professionals&nbsp;applicable knowledge, skills, and attitudes to be used in their job, HR Training courses&nbsp;increase&nbsp;the organizational commitment of the employees; employees training involve more than basic skills, the commitment among employees increases, employees will work hard for the organizational goals,&nbsp;</h6><h6>LPC Training provides&nbsp;high-quality Human Resources Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</h6><h6>Browse the list of Human Resources courses provided by LPC Training below and complete information requests for any that may be of interest to you</h6>',
                'type' => 'Human Resources',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'human-resources',
            ),
            1 => 
            array (
                'id' => 6,
                'lang_code' => 'en',
            'title' => 'Administration & Secretarial Courses (Classroom)',
                'description' => '<h6><small><tt>In order to bring out the best performance of the administrative personnel, make sure that they pursue the Administration and Secretarial Courses offered by LPC training .&nbsp;Administration and Secretary training&nbsp;courses are specially designed for the admin and office management professionals and are tailored as per the unique needs of the organizations and the changing patterns of the workplace.</tt></small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality Administration and Secretary training&nbsp;courses&nbsp;in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world.</tt></small></h6>

<h6><small><tt>Browse the list of Administration &amp; Secretarial courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Administration & Secretary',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'administration-secretarial',
            ),
            2 => 
            array (
                'id' => 7,
                'lang_code' => 'en',
            'title' => 'Healthcare Management (Classroom)',
                'description' => '<h6><tt>Healthcare management courses provide leadership and direction to organizations that deliver personal health services, and to divisions, departments, units, or services within those organizations.</tt></h6>

<p>&nbsp;</p>

<h6><tt>LPC Training provides high-quality Healthcare Management Training Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona &nbsp;- New York and &nbsp;other countries around the world.</tt></h6>

<p>&nbsp;</p>

<h6><tt>Browse the list of Healthcare Management courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></h6>',
                'type' => 'Healthcare Management',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'healthcare-management',
            ),
            3 => 
            array (
                'id' => 8,
                'lang_code' => 'en',
            'title' => 'Quality & Productivity Courses (Classroom)',
                'description' => '<h6><small><tt>Quality And Productivity &nbsp;are two of the most important and closely interlinked objectives of enterprises and are indicators of their performance, Quality and Productivity Training Courses present a prime opportunity for managers and executives to expand the knowledge base of them and &nbsp;their employees.&nbsp;</tt></small></h6>

<h6><small><tt>LPC Training provides High-standard Quality and Productivity Training Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world.</tt></small></h6>

<h6><small><tt>Browse the list of Quality &amp; Productivity courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Quality & Productivity',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'quality-productivity',
            ),
            4 => 
            array (
                'id' => 9,
                'lang_code' => 'en',
            'title' => 'Customer Service Courses (Classroom)',
                'description' => '<h6><small><tt>Customer Service&nbsp; Courses&nbsp;make Staff feel valued and confident &ndash; staff will feel valued after going through training as they see it as an investment in them as a staff member, and with training, they will become more confident and efficient;</tt></small></h6>

<h6><small><tt>LPC Training provides high-quality Customer Service training courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<h6><small><tt>Browse the list of Customer Service courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Customer Service',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'customer-service',
            ),
            5 => 
            array (
                'id' => 10,
                'lang_code' => 'en',
            'title' => 'Sales and Marketing Courses (Classroom)',
                'description' => '<h6><small><tt>A trained Sales and Marketing team can generate new opportunities which can lead to huge returns for a company. The better trained your sales team is the better results for your entire company. Use the following sales and Marketing Training courses&nbsp;to make your sales team stronger.</tt></small></h6>

<p>&nbsp;</p>

<h6><small><tt>LPC Training provides high-quality Sales and Marketing Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world.</tt></small></h6>

<p>&nbsp;</p>

<h6><small><tt>Browse the list of Sales and Marketing courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Sales & Marketing',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'sales-marketing',
            ),
            6 => 
            array (
                'id' => 11,
                'lang_code' => 'en',
            'title' => 'Facilities Management Courses  (Classroom)',
            'description' => '<h6><small>Facilities management (also known as FM) is the professional process of managing and maintaining the facilities within an organization.&nbsp;Facilities Management Training Courses aim to grow your ability to co-ordinate physical workspace with the people and organization involved, allowing the efficient and effective use of the business-related property.</small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Facilities Management Training Courses<tt> in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world.</tt></small></h6>

<h6><small><tt>Browse the list of Facilities Management&nbsp;courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Facilities Management',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'facilities-management',
            ),
            7 => 
            array (
                'id' => 12,
                'lang_code' => 'en',
            'title' => 'Logistics and Supply Chain Courses (Classroom)',
                'description' => '<h6><small>With efficient Logistics and Supply Chain&nbsp;Training, a business will certainly benefit by meeting customers&rsquo; demand and providing superior service on time.&nbsp;</small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Logistics and Supply Chain training<tt>&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<h6><small><tt>Browse the list of Logistics and Supply Chain courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Logistics & Supply Chain',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'logistics-supply-chain',
            ),
            8 => 
            array (
                'id' => 14,
                'lang_code' => 'en',
            'title' => 'Media and Public Relations Courses (Classroom)',
                'description' => '<h6><small>Public Relations plays a vital role in maintaining a company&#39;s reputation, and insuring that it is viewed positively by the public.&nbsp;Public Relations&nbsp;Training Courses give you professional knowledge and practical skills in client management, media communication and ethical practice.&nbsp;</small></h6>

<p>&nbsp;</p>

<h6><small><tt>LPC Training provides&nbsp;high-quality Media and Public Relations&nbsp;Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<p>&nbsp;</p>

<h6><small><tt>Browse the list of Media and Public Relations courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Media & Public Relations',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'media-public-relations',
            ),
            9 => 
            array (
                'id' => 15,
                'lang_code' => 'en',
            'title' => 'Safety and  Security Courses (Classroom)',
                'description' => '<h6><small>Safety and Security Training minimises the fear and uncertainty of common threats, providing you with a more skilled, confident, and knowledgeable workforce.</small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Safety and Security<tt>&nbsp;Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<h6><small><tt>Browse the list of Safety and Security courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Safety & Security',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'safety-security',
            ),
            10 => 
            array (
                'id' => 16,
                'lang_code' => 'en',
            'title' => 'Constructions & Civil Engineering Courses (Classroom)',
                'description' => '<h6><small>Construction and &nbsp;Civil Engineering courses benefit your business and your company in numerous ways. From small local building firms to construction giants &ndash; there is always room for training and improvement and the many benefits it can bring.</small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Construction and &nbsp;Civil Engineering<tt>&nbsp;Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<h6><small><tt>Browse the list of Constructions &amp; Civil Engineering&nbsp;courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Constructions & Civil Engineering',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'construction-civil-engineering',
            ),
            11 => 
            array (
                'id' => 17,
                'lang_code' => 'en',
            'title' => 'Oil and Gas Courses (Classroom)',
                'description' => '<h6><small>Training fosters employee engagement more than any other activity in a business. When an oil and gas company invests in advanced Oil And Gas&nbsp;Training Programs, it&rsquo;s a clear indication that it wants its employees to grow.</small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Oil And Gas<tt>&nbsp;Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<h6><small><tt>Browse the list of Oil and Gas courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Oil and Gas',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'oil-gas',
            ),
            12 => 
            array (
                'id' => 18,
                'lang_code' => 'en',
            'title' => 'Legal, Contracts and Procurement Courses (Classroom)',
                'description' => '<h6><small>Our exciting&nbsp;Legal, Contracts&nbsp;and Procurement&nbsp; Training Courses are designed to support and develop people in this important aspect of organizational strategy.&nbsp;</small></h6>

<p>&nbsp;</p>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Legal, Contracts&nbsp;and Procurement<tt>&nbsp;Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<p>&nbsp;</p>

<h6><small><tt>Browse the list of Legal, Contracts and Procurement&nbsp;courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Legal, Contracts & Procurement',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'legal-contracts-procurement',
            ),
            13 => 
            array (
                'id' => 19,
                'lang_code' => 'en',
            'title' => 'Power Systems & Maintenance Courses (Classroom)',
                'description' => '<h6>The Power Systems &amp; maintenance courses are designed to provide participants with the necessary knowledge and skills to work at a professional level in industries involved in the production, distribution and consumption of energy, power and maintenance&nbsp;</h6>

<h6>London Premiere Centre Power Systems &amp; maintenance courses include transport, conventional and renewable power generation.</h6>

<h6>Browse the list of &nbsp;Power Systems &amp; Maintenance courses provided by LPC Training below and complete information requests for any that may be of interest to you</h6>',
                'type' => 'Power Systems & Maintenance',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'maintenance-power-systems',
            ),
            14 => 
            array (
                'id' => 34,
                'lang_code' => 'en',
            'title' => 'Accounting, Finance & Budgeting Courses (Classroom)',
                'description' => '<p><small><tt>Accounting Finance and budgeting training courses are so important when navigating your business, company, and organization. Accounting Finance and Budgeting Training Courses&nbsp;are vital for employees to show them that they are dedicated to their career and professional development. All employees in the organization need to understand how business and finance work.</tt></small></p>
<p><small><tt>LPC Training provides high quality&nbsp;Accounting, Finance and Budgeting Training Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona &nbsp;- New York and &nbsp;other countries around the world&nbsp;</tt></small></p>
<p><small><tt>Browse the list of Accounting, Finance &amp; Budgeting&nbsp;courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></p>',
                'type' => 'Accounting, Finance & Budgeting',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'accounting-finance-budgeting',
            ),
            15 => 
            array (
                'id' => 41,
                'lang_code' => 'en',
            'title' => 'Management and Leadership Courses (Classroom)',
                'description' => '<h6><small><tt>Management and Leadership training Courses creates an opportunity for employees to reach new heights and achieve set goals.&nbsp;</tt></small><small><tt>Management and Leadership Courses will help you to develop the&nbsp;skills&nbsp;that are essential for all leaders and managers.</tt></small></h6>

<h6><small><tt>LPC Training provides&nbsp;distinctive and high-quality&nbsp;Management and&nbsp;&nbsp;Leadership Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona &nbsp;- New York and &nbsp;other cities around the world.</tt></small></h6>

<h6><small><tt>Browse the list of Management and Leadership courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Management & Leadership',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'management-leadership',
            ),
            16 => 
            array (
                'id' => 44,
                'lang_code' => 'en',
            'title' => 'Project Management Courses (Classroom)',
                'description' => '<h6><small>Project Management Training Course&nbsp;provide many benefits to a company, regardless of its size. Project management training provides a full understanding of the project goals, objectives, and benefits before committing significant resources.&nbsp;</small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Project Management <tt>&nbsp;Training Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<h6><small><tt>Browse the list of Project Management&nbsp;courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Project Management',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'project-management',
            ),
            17 => 
            array (
                'id' => 45,
                'lang_code' => 'en',
            'title' => 'Environmental Management & Agriculture Courses (Classroom)',
                'description' => '<h6><small>Environmental training is recognized as an investment in your business. Improved environmental management can lead to cost savings if you are able to reduce your organization&rsquo;s environmental liability. Taking part in Environmental Management &amp; Agriculture&nbsp;Training Courses with London Premier Centre&nbsp;can lead to the generation of ideas on how to improve your business&rsquo;s current procedures.&nbsp;</small></h6>

<h6><small><tt>LPC Training provides&nbsp;high-quality </tt>Environmental Management &amp; Agriculture&nbsp;<tt>Training&nbsp;Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world</tt></small></h6>

<h6><small><tt>Browse the list of Environmental Management &amp; Agriculture courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></small></h6>',
                'type' => 'Environmental & Agriculture',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'environmental-management',
            ),
            18 => 
            array (
                'id' => 49,
                'lang_code' => 'en',
            'title' => 'Hospitality & Tourism (Classroom)',
                'description' => '<h6><tt>Hospitality and Tourism are very popular industries that allow for travel and career development through continuing the training of employees and managers.</tt></h6>

<h6><tt>Training and courses enable hospitality and leisure staff to acquire professional qualifications in the areas of the hotel, travel, events, culinary, arts, retail, catering and extends to management and marketing.</tt></h6>

<h6><tt>Browse the list of hospitality &amp; tourism courses provided by LPC Training below and complete information requests for any that may be of interest to you</tt></h6>',
                'type' => 'Hospitality & Tourism',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'hospitality-tourism',
            ),
            19 => 
            array (
                'id' => 50,
                'lang_code' => 'en',
            'title' => 'IT and Computer Science Courses  (Classroom)',
                'description' => '<p>IT and Computer Science training courses are aimed to teach IT professionals the skills and attitudes they should use in their jobs, and IT training courses increase the commitment to keep pace with development; Staff training includes more than basic skills, Data Science Courses, Cybersecurity Courses, Cloud Computing Courses, Web Development Courses, Programming Courses.<br /><br /></p>
<p>LPC Training provides high-quality IT and Computer Science&nbsp;Training Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world<br /><br /></p>
<p>Browse the list of IT and Computer Science&nbsp;courses provided by LPC Training below and complete information requests for any that may be of interest to you</p>',
                'type' => 'IT and Computer Science',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'it-and-computer-science',
            ),
            20 => 
            array (
                'id' => 51,
                'lang_code' => 'en',
            'title' => 'Data Science and Visualisation (Classroom)',
                'description' => '<p>Data Science and Visualisation training courses are aimed to teach Data Science and Visualisation professionals the skills and attitudes they should use in their jobs, and Data Science and Visualisation training courses increase the commitment to keep pace with development; Staff training includes more than basic skills.<br /><br /></p>
<p>LPC Training provides high-quality Data Science and Visualisation Training Courses in London - Dubai - Kuala Lumpur - Istanbul - Paris - Madrid - Geneva - Barcelona - New York and other cities around the world<br /><br /></p>
<p>Browse the list of Data Science and Visualisation courses provided by LPC Training below and complete information requests for any that may be of interest to you</p>',
                'type' => 'Data Science and Visualisation',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'data-science-and-visualisation',
            ),
            21 => 
            array (
                'id' => 52,
                'lang_code' => 'en',
                'title' => 'Product Management Courses',
                'description' => '<p style="text-align: justify;">Discover our comprehensive product management courses to equip you with the skills needed to excel in today\'s dynamic market. Whether you\'re just starting your journey on how to become a product manager or looking to enhance your expertise, our product manager courses cover all essential aspects. From product strategy and vision to understanding the customer and market, our training ensures you\'re well-prepared for real-world challenges.</p>
<p style="text-align: justify;">Join our best product management courses in vibrant cities like London, Dubai, Barcelona, Paris, Istanbul, Kuala Lumpur, Singapore, and Amsterdam.</p>
<p style="text-align: justify;"><strong>Enroll now and transform your career with top-notch product management training.</strong></p>
<p style="text-align: justify;">&nbsp;</p>',
                'type' => 'Product Management',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'product-management',
            ),
            22 => 
            array (
                'id' => 53,
                'lang_code' => 'en',
                'title' => 'Shipping, Maritime & Ports Training Courses',
                'description' => '<p style="text-align: justify;">Our Shipping, Maritime &amp; Ports Courses provide comprehensive maritime training designed to equip professionals with the skills and knowledge necessary for success in the maritime industry. These maritime training courses cover many topics, including international shipping regulations, safety protocols, port management, and modern maritime operations.</p>
<p style="text-align: justify;">Participants will benefit from in-depth modules on cargo handling, vessel operations, and port security, ensuring they are well-prepared for the industry\'s challenges.</p>
<p style="text-align: justify;">Our courses deliver valuable insights and hands-on experience, making them ideal for those seeking to enhance their maritime logistics and port management expertise.</p>
<p style="text-align: justify;">&nbsp;</p>',
                'type' => 'Shipping, Maritime & Ports',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'shipping-maritime-ports',
            ),
            23 => 
            array (
                'id' => 54,
                'lang_code' => 'en',
            'title' => 'Artificial Intelligence (AI) Courses',
            'description' => '<p class="ql-align-justify">Artificial Intelligence (AI) revolutionises industries by enhancing efficiency, driving innovation, and enabling data-driven decision-making. Our AI courses are tailored to provide professionals with the advanced knowledge and practical skills needed to harness the power of AI technologies across various sectors. </p><p class="ql-align-justify">Covering a broad range of applications—from operational enhancements to predictive analytics and intelligent automation—these courses will empower you to implement AI strategies effectively.</p><p class="ql-align-justify">LPC Training offers these AI courses in several global locations, including London, Dubai, Barcelona, Paris, Istanbul, Kuala Lumpur, Singapore, and Amsterdam. This ensures you can access top-tier training in a city that suits you best.</p><p class="ql-align-justify"><br></p>',
            'type' => 'Artificial Intelligence (AI)',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'artificial-intelligence',
            ),
            24 => 
            array (
                'id' => 55,
                'lang_code' => 'en',
            'title' => 'Diplomacy and Public Affairs Courses (Classroom)',
                'description' => '<p>Our <strong>Diplomacy and Public Affairs training courses</strong> bring a hands-on, interactive experience that dives into the heart of international relations and public affairs. These in-person sessions focus on practical skills like <strong>policy advocacy</strong>, <strong>public diplomacy</strong>, and <strong>cross-cultural communication</strong>, all essential for today’s global landscape. </p><p><br></p><p>Led by experienced professionals, you’ll tackle real-world scenarios and engage in dynamic workshops and case studies. These courses are built for professionals looking to elevate their impact and build strong diplomatic skills that last. By joining, you’ll gain not only practical experience but also a network of peers and mentors to support you in confidently navigating complex diplomatic challenges.</p>',
                'type' => 'Diplomacy and Public Affairs',
                'created_at' => '2025-01-09 13:39:33',
                'updated_at' => '2025-01-09 13:44:49',
                'link_id' => 'diplomacy-public-affairs',
            ),
        ));
        
        
    }
}