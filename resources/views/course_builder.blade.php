@extends('layouts.app')

@section('content')
<div class="px-6 py-8 bg-gray-50">
    <div class="w-full"> 
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Course Builder</h1>
            <p class="text-lg text-gray-500 mt-1">A streamlined interface for creating and managing your courses.</p>
        </div>

        {{-- Section 1: Course Information --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Course Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label for="course_title" class="block text-sm font-medium text-gray-700">Course Title</label>
                    <input type="text" id="course_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
                    <input type="text" id="course_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="credit_units" class="block text-sm font-medium text-gray-700">Credit Units</label>
                    <input type="number" id="credit_units" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="contact_hours" class="block text-sm font-medium text-gray-700">Contact Hours</label>
                    <input type="number" id="contact_hours" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="prerequisites" class="block text-sm font-medium text-gray-700">Prerequisites</label>
                    <input type="text" id="prerequisites" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="pre_requisite_to" class="block text-sm font-medium text-gray-700">Pre-requisite to</label>
                    <input type="text" id="pre_requisite_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label for="course_description" class="block text-sm font-medium text-gray-700">Course Description</label>
                    <textarea id="course_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
            </div>
        </div>

        {{-- Section 2: Institutional Information --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Institutional Information</h2>
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">VISION</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <h4 class="font-semibold text-gray-600">SCHOOL</h4>
                            <p class="mt-2 text-gray-700">BCP is committed to provide and promote quality education with a unique, modern and research-based curriculum with delivery systems geared towards excellence.</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <h4 class="font-semibold text-gray-600">DEPARTMENT</h4>
                            <p class="mt-2 text-gray-700">BCP General Education Department innovates, investigate and discovers greatness and prosperity through oneness.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">MISSION</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <h4 class="font-semibold text-gray-600">SCHOOL</h4>
                            <p class="mt-2 text-gray-700">To produce self-motivated and self-directed individual who aims for academic excellence, God-fearing, peaceful, healthy and productive successful citizens.</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <h4 class="font-semibold text-gray-600">DEPARTMENT</h4>
                            <p class="mt-2 text-gray-700">To awaken the curiosity and ignite passion of individuals to excel independents in academics endeavors towards their development into ethically and morally strong people.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">PHILOSOPHY</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <h4 class="font-semibold text-gray-600">SCHOOL</h4>
                            <p class="mt-2 text-gray-700">BCP advocates threefold core values: “Fides”, Faith; “Ratio”, Reason; Pax. Peace. “Fides” represents BCPs, endeavors for expansion, development, and growth amidst the challenges of the new millennium. It is moving forward and lifting its faith its fate to the hands of God, for without His guidance, BCP cannot do and deliver affordable education and multiply God's graces, especially to the less fortunate youth of the land. "Ratio" symbolizes BCP's efforts to provide an education which can be man's tool to be liberated from all forms of ignorance and poverty. Hence, all its academic offerings are relevant tools to empower man to ably use his reason, intellect and will to confront life's challenges. "Pax". BCP is a forerunner in the promotion of a harmonious relationship between the different sectors of its academic community. From the administration, to its work force, and students, peace resides in them as depicted by the dove with the laurel of growth and progress clenched in its beak.</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-md border">
                            <h4 class="font-semibold text-gray-600">DEPARTMENT</h4>
                            <p class="mt-2 text-gray-700">General Education advocates threefold core values “Devotion”, “Serenity’, “Determination” “Devotion” represents General Education commitment and dedication to provide quality education that will fuel the passion of the students for learning in driving academic success “Serenity” symbolizes a crucial element in the overall well-being and success of students by means of creating a more supportive, conducive, and enriching learning environment, enabling them to thrive academically, emotionally, and personally. “Determination” general education is committed to provide a high-quality, equitable, and supportive learning environment that empowers students to succeed.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">CORE VALUES</h3>
                    <div class="p-4 bg-gray-50 rounded-md border">
                        <p class="mt-2 text-gray-700"><span class="font-bold">FAITH (Fides)</span> represents BCP’s endeavor for expansion, development and for growth amidst the global challenges of the new millennium. It is moving forward and lifting its fate to the hands of God for without His guidance, BCP cannot do and deliver affordable quality education and multiply God’s grace especially to the less fortunate youth of the land.</p>
                        <p class="mt-4 text-gray-700"><span class="font-bold">KNOWLEDGE (Cognito)</span> connotes the institution’s efforts to impart excellent lifelong education that can be used as human tool so that one can liberate himself/herself from ignorance and poverty. Hence, all its academic offerings are relevant tools to empower man to effectively use one’s reason, intellect and will to face the challenges of life.</p>
                        <p class="mt-4 text-gray-700"><span class="font-bold">CHARITY (Caritas)</span> is the institution’s commitment towards its clienteles. Sharing and services are the insignia of its soul, the badge of its character</p>
                        <p class="mt-4 text-gray-700"><span class="font-bold">HUMILITY (Humiliates)</span> refers to the institution’s recognition of the human frailty, its imperfection. But its imperfection, it learns how to move forward and learn from do’s and don’ts of life. Simply stated</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Mapping Grids --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Mapping Grids</h2>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <div class="p-6 border rounded-lg bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-700">Program Mapping</h3>
                        <button id="add-program-mapping-row" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">Add Row</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">PILO</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">CTPSS</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">ECC</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">EPP</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">GLC</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="program-mapping-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-6 border rounded-lg bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-700">Course Mapping</h3>
                        <button id="add-course-mapping-row" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">Add Row</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">CILO</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">CTPSS</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">ECC</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">EPP</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-20">GLC</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600 w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="course-mapping-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-6 p-4 bg-gray-100 border rounded-lg">
                <h4 class="font-bold text-gray-700">Legend:</h4>
                <ul class="list-disc list-inside mt-2 text-gray-600 space-y-1 text-sm">
                    <li><span class="font-semibold">L</span> – Facilitate Learning of the competencies</li>
                    <li><span class="font-semibold">P</span> – Allow student to practice competencies</li>
                    <li><span class="font-semibold">O</span> – Provide opportunity for development</li>
                </ul>
            </div>
        </div>
        
        {{-- Section 4: Learning Outcomes --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Learning Outcomes</h2>
            <div class="space-y-6">
                <div class="p-6 border rounded-lg bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">School Goals</h3>
                            <p class="text-gray-600">BCP puts God in the center of all its efforts realize and operationalize its vision and missions through the following.</p>
                            <p class="mt-2 font-semibold text-gray-700">Faith, Instruction, Research, and Extension.</p>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">Program Goals</h3>
                            <p class="text-gray-600">To cultivate a dynamic and inclusive learning environment that empowers students to become self-directed, ethical, and engaged citizens, equipped with the critical thinking, communication, and problem-solving skills necessary to thrive in a rapidly evolving world.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="pilo_outcomes" class="block text-lg font-semibold text-gray-700 mb-2">Program Intended Learning Outcomes (PILO)</label>
                    <textarea id="pilo_outcomes" name="pilo_outcomes" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label for="cilo_outcomes" class="block text-lg font-semibold text-gray-700 mb-2">Course Intended Learning Outcomes (CILO)</label>
                    <textarea id="cilo_outcomes" name="cilo_outcomes" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="p-6 border rounded-lg bg-gray-50">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Expected BCP Graduate Elements</h3>
                    <p class="text-gray-600 mb-4">The BCP ideal graduate demonstrates/internalizes this attribute:</p>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        <li>Critical thinking and problem-solving skills;</li>
                        <li>Communication and Collaboration</li>
                        <li>Ethical and professional practice;</li>
                        <li>Global and lifelong learning commitment</li>
                    </ol>
                </div>
                <div>
                    <label for="learning_outcomes" class="block text-lg font-semibold text-gray-700 mb-2">Learning Outcomes</label>
                    <textarea id="learning_outcomes" name="learning_outcomes" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
            </div>
        </div>

        {{-- Section 5: Weekly Plan --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Weekly Plan (Weeks 1-15)</h2>
            <div class="space-y-8">
                @for ($i = 1; $i <= 15; $i++)
                    <div class="border-b pb-6 last:border-b-0">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Week {{ $i }}</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="week_{{ $i }}_content" class="block text-sm font-medium text-gray-700">Content</label>
                                    <textarea id="week_{{ $i }}_content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                <div>
                                    <label for="week_{{ $i }}_silo" class="block text-sm font-medium text-gray-700">Student Intended Learning Outcomes</label>
                                    <textarea id="week_{{ $i }}_silo" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Assessment Tasks (ATs)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2 p-4 border rounded-md bg-gray-50">
                                    <div>
                                        <label for="week_{{ $i }}_at_onsite" class="block text-xs font-semibold text-gray-600 mb-1">ONSITE</label>
                                        <textarea id="week_{{ $i }}_at_onsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring-indigo-200"></textarea>
                                    </div>
                                    <div>
                                        <label for="week_{{ $i }}_at_offsite" class="block text-xs font-semibold text-gray-600 mb-1">OFFSITE</label>
                                        <textarea id="week_{{ $i }}_at_offsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring-indigo-200"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Suggested Teaching/Learning Activities (TLAs)</label>
                                <div class="mt-2 p-4 border rounded-md bg-gray-50">
                                    <p class="text-xs font-semibold text-gray-600 mb-2">Blended Learning Delivery Modality (BLDM)</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="week_{{ $i }}_tla_onsite" class="block text-xs font-semibold text-gray-600 mb-1">Face to Face (On-Site)</label>
                                            <textarea id="week_{{ $i }}_tla_onsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring-indigo-200"></textarea>
                                        </div>
                                        <div>
                                            <label for="week_{{ $i }}_tla_offsite" class="block text-xs font-semibold text-gray-600 mb-1">Online (Off-Site)</label>
                                            <textarea id="week_{{ $i }}_tla_offsite" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring-indigo-200"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="week_{{ $i }}_ltsm" class="block text-sm font-medium text-gray-700">Learning and Teaching Support Materials (LTSM)</label>
                                    <textarea id="week_{{ $i }}_ltsm" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                <div>
                                    <label for="week_{{ $i }}_output" class="block text-sm font-medium text-gray-700">Output Materials</label>
                                    <textarea id="week_{{ $i }}_output" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Section 6: Course Requirements and Policies --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Course Requirements and Policies</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="basic_readings" class="block text-sm font-medium text-gray-700">Basic Readings / Textbooks</label>
                    <textarea id="basic_readings" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label for="extended_readings" class="block text-sm font-medium text-gray-700">Extended Readings / References</label>
                    <textarea id="extended_readings" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="course_assessment" class="block text-sm font-medium text-gray-700">Course Assessment</label>
                    <textarea id="course_assessment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2 p-6 border rounded-lg bg-gray-50">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">Course Policies and Statements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-white rounded-md border">
                            <h4 class="font-semibold text-gray-600">Learners with Disabilities</h4>
                            <p class="mt-2 text-gray-700 text-sm">This course is committed in providing equal access and participation for all students including those with disabilities. If you have a disability that may require accommodations, please contact the OFFICE OF THE STUDENTS’ AFFAIRS and SERVICES to register in the LIST OF LEARNERS with Disabilities. Please be aware that it is your responsibility to communicate your needs and works with the instructor to ensure that appropriate accommodations can be arranged promptly.</p>
                        </div>
                        <div class="p-4 bg-white rounded-md border">
                            <h4 class="font-semibold text-gray-600">Syllabus Flexibility</h4>
                            <p class="mt-2 text-gray-700 text-sm">The faculty reserves the right to change or amend this syllabus as needed. Any changes to the syllabus will be communicated promptly to the VPAA through the Department Heads / Deans, if any, adjustments will be made to ensure that all students can continue to meet the course objectives. Your feedback and input are valued, and we encourage open communication to facilitate a positive and productive learning experience for all.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="committee_members" class="block text-sm font-medium text-gray-700">Committee Members</label>
                    <textarea id="committee_members" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label for="consultation_schedule" class="block text-sm font-medium text-gray-700">Consultation Schedule</label>
                    <textarea id="consultation_schedule" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
            </div>
        </div>
        
        {{-- Section 7: Approval Section --}}
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Approval</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="prepared_by" class="block text-sm font-medium text-gray-700">Prepared:</label>
                    <input type="text" id="prepared_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Cluster Leader</p>
                </div>
                <div>
                    <label for="reviewed_by" class="block text-sm font-medium text-gray-700">Reviewed:</label>
                    <input type="text" id="reviewed_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">General Education Program Head</p>
                </div>
                <div>
                    <label for="approved_by" class="block text-sm font-medium text-gray-700">Approved:</label>
                    <input type="text" id="approved_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Vice President for Academic Affairs</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors shadow-lg">Save Course</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addProgramMappingBtn = document.getElementById('add-program-mapping-row');
    const programMappingTableBody = document.getElementById('program-mapping-table-body');
    const addCourseMappingBtn = document.getElementById('add-course-mapping-row');
    const courseMappingTableBody = document.getElementById('course-mapping-table-body');

    const createMappingTableRow = (isPilo = true) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="py-2 px-4 border-b">
                <input type="text" placeholder="${isPilo ? 'Enter PILO...' : 'Enter CILO...'}" class="w-full p-2 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </td>
            <td class="py-2 px-4 border-b"><input type="text" class="w-full p-2 text-center border-gray-300 rounded"></td>
            <td class="py-2 px-4 border-b"><input type="text" class="w-full p-2 text-center border-gray-300 rounded"></td>
            <td class="py-2 px-4 border-b"><input type="text" class="w-full p-2 text-center border-gray-300 rounded"></td>
            <td class="py-2 px-4 border-b"><input type="text" class="w-full p-2 text-center border-gray-300 rounded"></td>
            <td class="py-2 px-4 border-b text-center">
                <button class="delete-row-btn text-red-500 hover:text-red-700 font-semibold p-1">Delete</button>
            </td>
        `;
        return row;
    };
    
    addProgramMappingBtn.addEventListener('click', () => {
        programMappingTableBody.appendChild(createMappingTableRow(true));
    });

    addCourseMappingBtn.addEventListener('click', () => {
        courseMappingTableBody.appendChild(createMappingTableRow(false));
    });

    document.querySelector('.max-w-7xl').addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-row-btn')) {
            e.target.closest('tr').remove();
        }
    });
});
</script>

@endsection