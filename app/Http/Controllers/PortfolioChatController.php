<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortfolioChatController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = trim($validated['message']);

        if ($message === '') {
            return response()->json([
                'message' => 'Please enter a portfolio-related question.',
            ], 400);
        }

        $apiKey = (string) config('services.gemini.key');
        if ($apiKey === '') {
            return response()->json([
                'message' => $this->fallbackPortfolioResponse($message),
            ]);
        }

        $portfolioContext = <<<'CONTEXT'
Karl Justine R. Membrere is a UI/UX Designer focused on creating clear, intuitive, and accessible digital experiences.

Full name: Karl Justine R. Membrere
Short name: Karl Justine
Professional title: UI/UX Designer
Location: Guiset Norte, San Manuel, Pangasinan, Philippines
Phone: +63 976 056 9838
Email: karljustinemembrere11272003@gmail.com
LinkedIn: linkedin.com/in/membrere-karl-justine-r-6927343a6
Portfolio: https://karljustinemembrere-portfolio.vercel.app/

Career objective:
Karl Justine is a detail-oriented UI/UX Designer and fresh graduate passionate about transforming complex problems into clean, intuitive, and accessible digital experiences. He is skilled in Figma, wireframing, and design systems, and he aims to contribute user-focused solutions and modern interface designs to a collaborative team.

Availability:
- Karl Justine is currently available for full-time work.
- Karl Justine is also open for freelance projects.

Skills and tools:
Software:
- Figma
- Affinity

Design skills:
- Wireframing
- Prototyping
- Interaction Design
- Visual Design
- UI/UX Design
- Design Systems
- Mobile Application Design
- Web Design
- Usability

Current freelance experience:
Position: Freelance UI/UX Designer
Status: Present
Responsibilities:
- Designs and prototypes user interfaces and user experiences for client and personal projects.
- Creates mobile and web solutions balancing aesthetics and functionality.
- Creates wireframes, interactive prototypes, and visual interfaces using Figma.
- Focuses on usability and visual consistency.
- Delivers user-focused design solutions aligned with project requirements.
- Works toward improving user satisfaction and ease of use.

Services and FAQ:
- Services offered: UI/UX design, web design, mobile design, landing page design, design system, and SaaS dashboard design.
- Design process: Understand the problem, target users, and goals; plan the structure; create the user flow; design the interface; refine it based on feedback.
- Project timing: Small projects usually take around 2 to 3 weeks; larger websites, systems, or more complex projects may take around 4 to 5 weeks depending on scope and requirements.
- Revisions: Usually 2 to 3 rounds depending on the project scope and agreed requirements.
- Getting started: Reach out through social accounts or the contact form and send project details, goals, and any relevant references.

Internship:
Position: UI/UX Designer / Developer Intern
Year: 2026
Organization: City Health Office 1, Urdaneta City, Pangasinan
Project: Queuing Management System
Tools: Figma, PHP, HTML, CSS, JavaScript, MySQL
Responsibilities:
- Designed the UI/UX of a web-based Queuing Management System using Figma.
- Created wireframes, layouts, and prototypes based on office workflows.
- Developed web interfaces using PHP, HTML, CSS, JavaScript, and MySQL.
- Deployed approved UI designs into the live system.
- Improved interface clarity and usability.
- Organized information and streamlined interactions according to operational requirements.

Capstone experience:
Role: UI/UX Designer / Programmer
Period: 2025–2026
Project: City Health Connect: A Multi-Platform Health Services Management System for the City Health Office of Urdaneta City
Tools: Figma, PHP, HTML, CSS, JavaScript, Flutter, MySQL
Responsibilities:
- Designed web and mobile system interfaces using Figma.
- Created wireframes and interactive prototypes.
- Aligned interfaces with project requirements and user workflows.
- Developed full-stack web and mobile interfaces.
- Used PHP, HTML, CSS, JavaScript, Flutter, and MySQL.
- Supported health-service processes.
- Improved system consistency and usability.
- Created clean layouts and reusable interface patterns.
- Improved the overall user experience.

Education:
Bachelor of Science in Information Technology
- School: Urdaneta City University
- Period: 2022–2026
- Address: 1 San Vicente West, Urdaneta City, Pangasinan

Senior High School
- School: Mataas Na Paaralang Juan C. Laya (MPJCL)
- Strand: General Academic Strand (GAS)
- Period: 2020–2022
- Address: Quirino Street, Guiset Sur, San Manuel, Pangasinan, Philippines

Junior High School
- School: Mataas Na Paaralang Juan C. Laya (MPJCL)
- Period: 2016–2020
- Address: Quirino Street, Guiset Sur, San Manuel, Pangasinan, Philippines

Rules:
- When naming the person, use "Karl Justine" and, when needed, "Karl Justine R. Membrere"; otherwise speak in first person as Karl Justine.
- Use only the verified information in this context.
- Never invent clients, employers, rates, contracts, projects, technologies, certifications, awards, hobbies, or personal details.
- Focus on Karl Justine's profile, skills, design work, internship, capstone, education, freelance availability, services, FAQ answers, and contact information.
- Use the FAQ information in this context for questions about services, design process, project timing, revisions, and how to get started.
- Answer the actual question the visitor asked.
- Keep answers concise, relevant, and natural.
- Do not dump the entire profile unless the user asks for a broad overview.
- If the answer is not documented, say that the information is not available.
- For social and contact requests, provide the direct link or detail requested.
CONTEXT;

        $systemPrompt = <<<PROMPT
You are the AI assistant for Karl Justine R. Membrere.

Your job is to help visitors understand Karl Justine's professional identity, career objective, skills, tools, freelance experience, internship, capstone experience, education, services, FAQ answers, contact information, and availability.

Use the verified profile context below as the source of truth, and treat it as the primary context for Karl Justine and his portfolio.

Portfolio context:
{$portfolioContext}

Primary response rules:
- When answering about personal information, speak naturally as Karl Justine in first person. Prefer "I", "me", and "my" over "Karl Justine", "Karl Justine's", "he", or "his"; use the name only when it is necessary to identify the portfolio.
- Do not describe Karl Justine in the third person in a response. Answer as the portfolio owner, using first-person phrasing while preserving the documented facts.
- Phrase biographical answers as personal statements rather than summaries about Karl Justine. For example, describe education with "I studied" or "I graduated", skills with "I use" or "my skills include", and experience or projects with "I worked on", "I designed", or "I developed", as appropriate to the documented facts.
- Preserve the name exactly as "Karl Justine" or "Karl Justine R. Membrere"; do not use alternate spellings or abbreviated variations.
- Answer only the question that was asked, but do not restrict your language understanding. Understand natural phrasing, short messages, casual wording, typos, pronouns, and follow-up questions.
- Treat the portfolio as the main source of truth, not as a rigid keyword filter.
- Distinguish full-time employment availability from freelance availability. For work, job, employment, or full-time availability questions, answer that I am currently available for full-time work. For freelance-specific questions, answer that I am open for freelance projects. Mention both only when the question asks generally about availability.
- For professional and project experience questions, give a useful, specific overview rather than a one-line title: describe the relevant freelance work, internship, capstone, responsibilities, design and development work, and tools documented in the context. Include dates, project names, and technologies when relevant to the question.
- For broad experience questions, organize the answer by my freelance work, internship, and capstone, explaining my role and contributions in each. For a question about one role, project, or skill, focus on that subject and include only useful supporting details.
- Provide enough detail to satisfy the question, generally a few well-formed sentences or concise paragraphs. Be direct and professional; do not give a generic chatbot answer or repeat the entire profile.
- Format every response for readability. Put the direct answer first; use short paragraphs, blank lines between sections, and concise hyphen bullets for related lists when they improve scanning.
- Use short, descriptive plain-text headings when an answer covers multiple areas. Group related responsibilities, projects, services, or technologies together, and keep the most relevant information first.
- Keep simple questions to one or two natural sentences. Do not force headings or lists into short answers, and do not turn every response into a long list.
- Balance completeness with relevance: include only details that directly answer the question, avoid repetition and long paragraphs, and do not dump all profile facts into an unrelated or narrow answer.
- Keep responses as plain text. Do not use Markdown bold markers, HTML, or decorative formatting; the chat displays text and line breaks directly.
- Answer college, senior-high-school, and junior-high-school questions with only the requested level. Include all levels only when the user asks for a complete educational background.
- If the user is greeting, reply naturally and briefly, then invite them to describe a project or ask about design support. The interface will provide relevant clickable conversation starters.
- If the answer is in the portfolio or FAQ, answer it directly and naturally.
- If the information is not documented, say that it is not available.
- If the user asks something clearly unrelated to my portfolio, politely redirect them: "This chat is mainly for questions about my portfolio, projects, skills, services, and design experience. :)"
- Use the FAQ information for service, process, timeline, revisions, and start questions when relevant.
- For social or contact requests, provide the requested direct link or detail.
- Keep answers concise, relevant, and conversational.
- Do not dump an entire resume or profile unless the user specifically asks for a broad overview.
- Do not invent clients, employers, prices, contracts, certifications, awards, or personal details.

The goal is to help with portfolio conversations naturally, while keeping the structure and facts grounded in Karl Justine's verified portfolio information.
PROMPT;

        $input = $systemPrompt."\n\nUser question:\n".$message;

        $quickReply = $this->quickPortfolioReply($message);
        if ($quickReply !== null) {
            return response()->json([
                'message' => $quickReply,
            ]);
        }

        if (! $this->isPortfolioRelatedQuestion($message)) {
            return response()->json([
                'message' => 'This chat is mainly for questions about my portfolio, projects, skills, services, and design experience. :)',
            ]);
        }

        try {
            $response = Http::asJson()
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->timeout(20)
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $input],
                            ],
                        ],
                    ],
                ]);

            if ($response->status() === 429) {
                return response()->json([
                    'message' => 'The assistant is temporarily busy. Please try again later.',
                ], 429);
            }

            if ($response->status() === 503) {
                return response()->json([
                    'message' => 'The AI service is temporarily unavailable. Please try again later.',
                ], 503);
            }

            if ($response->failed()) {
                Log::warning('Gemini portfolio chat request failed; using fallback response.', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return response()->json([
                    'message' => $this->fallbackPortfolioResponse($message),
                ]);
            }

            $answer = $this->extractGeminiText($response->json());

            if ($answer === null || trim($answer) === '') {
                return response()->json([
                    'message' => $this->fallbackPortfolioResponse($message),
                ]);
            }

            return response()->json([
                'message' => $answer,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini portfolio chat exception.', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => $this->fallbackPortfolioResponse($message),
            ]);
        }
    }

    protected function quickPortfolioReply(string $message): ?string
    {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $message) ?? $message));
        $greeting = preg_replace('/[.!?,;:]+$/', '', $normalized) ?? $normalized;

        $greetingReplies = [
            'hi' => 'Hi! How can I help you today? Have a project in mind, or are you looking for a UI/UX designer for your team?',
            'hello' => 'Hello! Are you exploring design support for a project, or looking for a UI/UX designer to join your team?',
            'hey' => 'Hey! How can I help? I can share my design services, experience, or availability for your project or team.',
            'hi there' => 'Hi there! Have a project in mind, or would you like to learn more about my design services?',
            'hello there' => 'Hello! Are you looking for design support on a project or a UI/UX designer for your team?',
            'hey there' => 'Hey there! I can help you explore my services, project experience, and availability.',
            'good morning' => 'Good morning! How can I help? Are you planning a project or looking for a UI/UX designer for your team?',
            'good afternoon' => 'Good afternoon! Have a project in mind, or are you exploring UI/UX design support for your team?',
            'good evening' => 'Good evening! I can help with my services, experience, or how to get started on a project.',
        ];

        if (isset($greetingReplies[$greeting])) {
            return $greetingReplies[$greeting];
        }

        if (str_contains($normalized, 'what\'s up') || str_contains($normalized, 'how are you') || str_contains($normalized, 'how are u')) {
            return 'Hey! I’m doing well, thanks for asking. Are you exploring design support for a project or looking for a UI/UX designer for your team?';
        }

        if (str_contains($normalized, 'thanks') || str_contains($normalized, 'thank you')) {
            return 'You’re welcome! Ask me about my portfolio, skills, services, or freelance work.';
        }

        $asksAboutInternship = str_contains($normalized, 'internship')
            || str_contains($normalized, 'intern')
            || str_contains($normalized, 'queuing management system');
        $asksAboutCapstone = str_contains($normalized, 'capstone')
            || str_contains($normalized, 'city health connect');

        if ($asksAboutInternship && ! $asksAboutCapstone) {
            return "Internship — UI/UX Designer / Developer Intern, 2026\nCity Health Office 1, Urdaneta City, Pangasinan\n\n- Project: Web-based Queuing Management System.\n- I designed workflows, wireframes, layouts, and prototypes in Figma.\n- I developed and deployed approved interfaces using PHP, HTML, CSS, JavaScript, and MySQL.\n- I focused on clear, usable interfaces aligned with office workflows.";
        }

        if ($asksAboutCapstone && ! $asksAboutInternship) {
            return "My capstone was City Health Connect, a multi-platform health services management system for the City Health Office of Urdaneta City (2025–2026).\n\nMy role: UI/UX Designer / Programmer\n- I designed web and mobile interfaces, wireframes, and interactive prototypes around user workflows.\n- I contributed to web and mobile development using Figma, PHP, HTML, CSS, JavaScript, Flutter, and MySQL.\n- I focused on consistent, usable interface patterns.";
        }

        if ($this->asksAboutExperience($normalized)) {
            if ($this->asksAboutProjects($normalized) && ! str_contains($normalized, 'experience')) {
                return "My documented project work includes:\n\nQueuing Management System — Internship, 2026\nMy role: UI/UX Designer / Developer Intern\n- I designed workflows, wireframes, layouts, and prototypes in Figma.\n- I developed web interfaces using PHP, HTML, CSS, JavaScript, and MySQL.\n\nCity Health Connect — Capstone, 2025–2026\nMy role: UI/UX Designer / Programmer\n- I designed web and mobile interfaces and interactive prototypes.\n- I contributed to development using Figma, PHP, HTML, CSS, JavaScript, Flutter, and MySQL.\n\nI also design and prototype mobile and web experiences for client and personal projects as a Freelance UI/UX Designer.";
            }

            return "I am a UI/UX Designer and a fresh graduate with a Bachelor of Science in Information Technology from Urdaneta City University. My experience spans freelance design, an internship, and a capstone project.\n\nFreelance UI/UX Designer — Present\n- I design and prototype mobile and web experiences for client and personal projects.\n- I create wireframes, interactive prototypes, and visual interfaces using Figma, with a focus on usability and consistency.\n\nUI/UX Designer / Developer Intern — 2026\nCity Health Office 1, Urdaneta City, Pangasinan\n- I designed workflows, layouts, and prototypes for a Queuing Management System.\n- I developed web interfaces using Figma, PHP, HTML, CSS, JavaScript, and MySQL.\n\nUI/UX Designer / Programmer — Capstone, 2025–2026\nCity Health Connect, a multi-platform health services management system\n- I designed web and mobile interfaces and prototypes around project requirements and user workflows.\n- I contributed to development using Figma, PHP, HTML, CSS, JavaScript, Flutter, and MySQL.";
        }

        $availabilityReply = $this->availabilityPortfolioReply($normalized);
        if ($availabilityReply !== null) {
            return $availabilityReply;
        }

        if (
            str_contains($normalized, 'professional background')
            || str_contains($normalized, 'career background')
            || str_contains($normalized, 'who is karl')
            || (preg_match('/\btell me about (?:karl|him)\b/', $normalized) === 1
                && ! preg_match('/\b(skills?|services?|projects?|experience|education|availability|contact)\b/', $normalized))
        ) {
            return "I am a UI/UX Designer and a Bachelor of Science in Information Technology graduate of Urdaneta City University in Urdaneta City, Pangasinan.\n\nMy experience includes:\n- Freelance UI/UX Designer — Present: I design and prototype mobile and web experiences for client and personal projects.\n- UI/UX Designer / Developer Intern — 2026: I designed and developed interfaces for City Health Office 1’s Queuing Management System.\n- UI/UX Designer / Programmer — City Health Connect capstone, 2025–2026: I contributed to interface design, prototyping, and development.";
        }

        $asksCollege = str_contains($normalized, 'college')
            || str_contains($normalized, 'bachelor')
            || str_contains($normalized, 'university')
            || str_contains($normalized, 'degree')
            || str_contains($normalized, 'graduate');
        $asksJuniorHigh = str_contains($normalized, 'junior high');
        $asksSeniorHigh = str_contains($normalized, 'senior high')
            || (str_contains($normalized, 'high school') && ! $asksJuniorHigh);
        $asksEducation = str_contains($normalized, 'education') || str_contains($normalized, 'studied');

        $asksCompleteEducation = str_contains($normalized, 'complete education')
            || str_contains($normalized, 'complete educational')
            || str_contains($normalized, 'full educational')
            || str_contains($normalized, 'entire education')
            || str_contains($normalized, 'educational background')
            || str_contains($normalized, 'education history')
            || str_contains($normalized, 'all education')
            || ($asksEducation && ! $asksCollege && ! $asksJuniorHigh && ! $asksSeniorHigh);

        if ($asksCompleteEducation) {
            return "My education:\n- I completed a Bachelor of Science in Information Technology at Urdaneta City University (2022–2026).\n- I completed Senior High School in the General Academic Strand (GAS) at Mataas Na Paaralang Juan C. Laya (MPJCL) (2020–2022).\n- I completed Junior High School at MPJCL (2016–2020).";
        }

        if ($asksCollege || $asksJuniorHigh || $asksSeniorHigh) {
            $educationDetails = [];

            if ($asksCollege) {
                $educationDetails[] = 'a Bachelor of Science in Information Technology at Urdaneta City University in Urdaneta City, Pangasinan (2022–2026)';
            }

            if ($asksSeniorHigh) {
                $educationDetails[] = 'Senior High School in the General Academic Strand (GAS) at Mataas Na Paaralang Juan C. Laya (MPJCL) (2020–2022)';
            }

            if ($asksJuniorHigh) {
                $educationDetails[] = 'Junior High School at Mataas Na Paaralang Juan C. Laya (MPJCL) (2016–2020)';
            }

            return 'I completed '.implode(' and ', $educationDetails).'.';
        }

        return null;
    }

    protected function asksAboutExperience(string $normalizedMessage): bool
    {
        return preg_match('/\b(experience|work history|career history|worked on|work(?:ed)? professionally|professional background)\b/', $normalizedMessage) === 1
            || (str_contains($normalizedMessage, 'what has') && str_contains($normalizedMessage, 'worked'))
            || preg_match('/\b(what|which)\b.{0,35}\bprojects?\b|\bprojects?\b.{0,35}\b(has|have|did|does|worked|work)\b/', $normalizedMessage) === 1;
    }

    protected function asksAboutProjects(string $normalizedMessage): bool
    {
        return preg_match('/\b(projects?|capstone|city health connect|queuing management system)\b/', $normalizedMessage) === 1;
    }

    protected function availabilityPortfolioReply(string $normalizedMessage): ?string
    {
        $asksAvailability = preg_match('/\b(available|availability|hire|hiring|accept|accepting|taking on|open for|open to|work together)\b/', $normalizedMessage) === 1;

        if (! $asksAvailability) {
            return null;
        }

        if (
            preg_match('/\b(are|is)\s+(you|karl(?:\s+justine)?)\s+hiring\b/', $normalizedMessage) === 1
        ) {
            return 'My portfolio does not list me as hiring. I am currently available for full-time work and am also open for freelance projects.';
        }

        if (
            str_contains($normalizedMessage, 'freelance')
            && preg_match('/\b(available|availability|hire|accept|accepting|taking on|open for|open to)\b/', $normalizedMessage) === 1
        ) {
            return 'Yes, I am open for freelance projects.';
        }

        if (
            str_contains($normalizedMessage, 'full-time')
            || str_contains($normalizedMessage, 'full time')
            || str_contains($normalizedMessage, 'employment')
            || str_contains($normalizedMessage, 'job')
            || preg_match('/\b(?:available|availability)\b.{0,24}\bwork\b|\bwork\b.{0,24}\b(?:available|availability)\b/', $normalizedMessage) === 1
        ) {
            return 'Yes, I am currently available for full-time work.';
        }

        return 'Yes, I am currently available for full-time work and am also open for freelance projects.';
    }

    protected function isPortfolioRelatedQuestion(string $message): bool
    {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $message) ?? $message));

        if ($normalized === '') {
            return false;
        }

        $keywords = [
            'karl', 'justine', 'portfolio', 'profile', 'skill', 'skills', 'tool', 'tools', 'design', 'ux', 'ui', 'experience', 'education', 'school', 'degree', 'service', 'services', 'project', 'projects', 'freelance', 'hire', 'available', 'availability', 'contact', 'email', 'linkedin', 'faq', 'get started', 'process', 'timeline', 'revisions', 'resume', 'capstone', 'internship', 'work together', 'client', 'designer', 'design process', 'what do you use', 'what tools', 'who is',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($normalized, $keyword)) {
                return true;
            }
        }

        return false;
    }

    protected function fallbackPortfolioResponse(string $message): string
    {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $message) ?? $message));
        $quickReply = $this->quickPortfolioReply($message);

        if ($quickReply !== null) {
            return $quickReply;
        }

        if (! $this->isPortfolioRelatedQuestion($message)) {
            return 'This chat is mainly for questions about my portfolio, projects, skills, services, and design experience. :)';
        }

        if (str_contains($normalized, 'who is') || str_contains($normalized, 'what does') || str_contains($normalized, 'profession') || str_contains($normalized, 'title')) {
            return 'I am Karl Justine R. Membrere, a UI/UX Designer focused on creating clear, intuitive, and accessible digital experiences.';
        }

        if (str_contains($normalized, 'objective') || str_contains($normalized, 'goal') || str_contains($normalized, 'career') || str_contains($normalized, 'focus')) {
            return 'I am a detail-oriented UI/UX Designer and fresh graduate passionate about transforming complex problems into clean, intuitive, and accessible digital experiences.';
        }

        if (str_contains($normalized, 'skill') || str_contains($normalized, 'software') || str_contains($normalized, 'tool') || str_contains($normalized, 'figma') || str_contains($normalized, 'affinity')) {
            return 'My main tools and design skills focus on creating clear, usable interfaces. I use Figma and Affinity, and my design skills include UI/UX and visual design, wireframing, interactive prototyping, interaction design, design systems, mobile application design, web design, and usability. I use these skills to plan user flows, shape clear interfaces, and refine experiences around project requirements.';
        }

        if (str_contains($normalized, 'service') || str_contains($normalized, 'offer') || str_contains($normalized, 'landing page') || str_contains($normalized, 'dashboard')) {
            return "I offer design support for:\n- UI/UX design for web and mobile products.\n- Landing pages and SaaS dashboards.\n- Design systems.\n\nMy process can include understanding user needs and goals, planning structure and user flows, creating wireframes and prototypes, designing interfaces, and refining them through feedback.";
        }

        if (str_contains($normalized, 'process') || str_contains($normalized, 'design') && str_contains($normalized, 'how')) {
            return 'I start by understanding the problem, the target users, and the client’s goals. Then I plan the structure, create the user flow, design the interface, and refine it based on feedback.';
        }

        if (str_contains($normalized, 'timeline') || str_contains($normalized, 'duration') || str_contains($normalized, 'take') || str_contains($normalized, 'weeks')) {
            return 'Small projects usually take around 2 to 3 weeks. Larger websites, systems, or more complex projects may take around 4 to 5 weeks depending on scope and requirements.';
        }

        if (str_contains($normalized, 'revision') || str_contains($normalized, 'revisions')) {
            return 'Yes, I usually offer 2 to 3 rounds of revisions depending on the project scope and the agreed requirements.';
        }

        if (str_contains($normalized, 'start') || str_contains($normalized, 'get started') || str_contains($normalized, 'contact form')) {
            return 'You can reach me through my social accounts or the contact form. Send your project details, goals, and any relevant references to get started.';
        }

        if (str_contains($normalized, 'freelance') || str_contains($normalized, 'hire') || str_contains($normalized, 'available') || str_contains($normalized, 'work')) {
            return 'Yes, I am open for freelance projects. You can reach me directly at karljustinemembrere11272003@gmail.com to discuss the details.';
        }

        if (str_contains($normalized, 'intern') || str_contains($normalized, 'internship')) {
            return 'I completed my internship as a UI/UX Designer / Developer Intern at City Health Office 1 in 2026, where I worked on the Queuing Management System using Figma, PHP, HTML, CSS, JavaScript, and MySQL.';
        }

        if (str_contains($normalized, 'capstone') || str_contains($normalized, 'city health connect')) {
            return 'My capstone project was City Health Connect, a multi-platform health services management system for the City Health Office of Urdaneta City. I served as a UI/UX Designer / Programmer and used Figma, PHP, HTML, CSS, JavaScript, Flutter, and MySQL.';
        }

        if (str_contains($normalized, 'study') || str_contains($normalized, 'school') || str_contains($normalized, 'degree') || str_contains($normalized, 'college') || str_contains($normalized, 'education')) {
            return 'I studied Bachelor of Science in Information Technology at Urdaneta City University from 2022 to 2026 and also completed Senior High School and Junior High School at Mataas Na Paaralang Juan C. Laya (MPJCL).';
        }

        if (str_contains($normalized, 'contact') || str_contains($normalized, 'email') || str_contains($normalized, 'linkedin') || str_contains($normalized, 'portfolio')) {
            return 'You can contact me via email at karljustinemembrere11272003@gmail.com, on LinkedIn at linkedin.com/in/membrere-karl-justine-r-6927343a6, and through my portfolio at https://karljustinemembrere-portfolio.vercel.app/.';
        }

        if (str_contains($normalized, 'facebook') || str_contains($normalized, 'fb')) {
            return 'My Facebook profile is not documented in the current portfolio information.';
        }

        if (str_contains($normalized, 'unavailable') || str_contains($normalized, 'not available')) {
            return 'The information you are asking for is not documented in my profile.';
        }

        return 'I can help with my professional background, skills, experience, education, and contact information.';
    }

    protected function extractGeminiText(array $payload): ?string
    {
        foreach ($payload['candidates'] ?? [] as $candidate) {
            $parts = $candidate['content']['parts'] ?? [];
            foreach ($parts as $part) {
                if (isset($part['text']) && trim((string) $part['text']) !== '') {
                    return trim((string) $part['text']);
                }
            }
        }

        return null;
    }
}
