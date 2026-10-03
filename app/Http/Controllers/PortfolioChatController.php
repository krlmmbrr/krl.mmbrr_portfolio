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
- Always refer to the person as "Karl Justine" and, when needed, "Karl Justine R. Membrere".
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
- Always refer to the person as "Karl Justine". When a full formal name is needed, use "Karl Justine R. Membrere".
- Answer only the question that was asked, but do not restrict your language understanding. Understand natural phrasing, short messages, casual wording, typos, pronouns, and follow-up questions.
- Treat the portfolio as the main source of truth, not as a rigid keyword filter.
- If the user is greeting, thanking, or casually checking in, keep the reply short and natural.
- If the answer is in the portfolio or FAQ, answer it directly and naturally.
- If the information is not documented, say that it is not available.
- If the user asks something clearly unrelated to Karl Justine or his portfolio, politely redirect them: "This chat is mainly for questions about Karl Justine’s portfolio, projects, skills, services, and design experience. :)"
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
                'message' => 'This chat is mainly for questions about Karl Justine’s portfolio, projects, skills, services, and design experience. :)',
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

        if ($normalized === '' || str_contains($normalized, 'hi') || str_contains($normalized, 'hello') || str_contains($normalized, 'hey') || str_contains($normalized, 'good morning') || str_contains($normalized, 'good afternoon') || str_contains($normalized, 'good evening')) {
            return 'Hey! How can I help?';
        }

        if (str_contains($normalized, 'what\'s up') || str_contains($normalized, 'how are you') || str_contains($normalized, 'how are u')) {
            return 'Hey! I’m doing well. What would you like to know about Karl Justine?';
        }

        if (str_contains($normalized, 'thanks') || str_contains($normalized, 'thank you')) {
            return 'You’re welcome! Ask me about Karl Justine’s portfolio, skills, services, or freelance work.';
        }

        return null;
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

        if (! $this->isPortfolioRelatedQuestion($message)) {
            return 'This chat is mainly for questions about Karl Justine’s portfolio, projects, skills, services, and design experience. :)';
        }

        if (str_contains($normalized, 'who is') || str_contains($normalized, 'what does') || str_contains($normalized, 'profession') || str_contains($normalized, 'title')) {
            return 'Karl Justine R. Membrere is a UI/UX Designer focused on creating clear, intuitive, and accessible digital experiences.';
        }

        if (str_contains($normalized, 'objective') || str_contains($normalized, 'goal') || str_contains($normalized, 'career') || str_contains($normalized, 'focus')) {
            return 'Karl Justine is a detail-oriented UI/UX Designer and fresh graduate passionate about transforming complex problems into clean, intuitive, and accessible digital experiences.';
        }

        if (str_contains($normalized, 'skill') || str_contains($normalized, 'software') || str_contains($normalized, 'tool') || str_contains($normalized, 'figma') || str_contains($normalized, 'affinity')) {
            return 'Karl Justine works with Figma and Affinity, and his design skills include wireframing, prototyping, interaction design, visual design, UI/UX design, design systems, mobile app design, web design, and usability.';
        }

        if (str_contains($normalized, 'service') || str_contains($normalized, 'offer') || str_contains($normalized, 'landing page') || str_contains($normalized, 'dashboard')) {
            return 'Karl Justine offers UI/UX design, web design, mobile design, landing page design, design system, and SaaS dashboard design.';
        }

        if (str_contains($normalized, 'process') || str_contains($normalized, 'design') && str_contains($normalized, 'how')) {
            return 'Karl Justine starts by understanding the problem, the target users, and the client’s goals. Then he plans the structure, creates the user flow, designs the interface, and refines it based on feedback.';
        }

        if (str_contains($normalized, 'timeline') || str_contains($normalized, 'duration') || str_contains($normalized, 'take') || str_contains($normalized, 'weeks')) {
            return 'Small projects usually take around 2 to 3 weeks. Larger websites, systems, or more complex projects may take around 4 to 5 weeks depending on scope and requirements.';
        }

        if (str_contains($normalized, 'revision') || str_contains($normalized, 'revisions')) {
            return 'Yes, Karl Justine usually offers 2 to 3 rounds of revisions depending on the project scope and the agreed requirements.';
        }

        if (str_contains($normalized, 'start') || str_contains($normalized, 'get started') || str_contains($normalized, 'contact form')) {
            return 'You can reach out through Karl Justine’s social accounts or the contact form and send your project details, goals, and any relevant references to get started.';
        }

        if (str_contains($normalized, 'freelance') || str_contains($normalized, 'hire') || str_contains($normalized, 'available') || str_contains($normalized, 'work')) {
            return 'Yes, Karl Justine is open for freelance projects. You can reach out directly at karljustinemembrere11272003@gmail.com to discuss the details.';
        }

        if (str_contains($normalized, 'intern') || str_contains($normalized, 'internship')) {
            return 'Karl Justine completed his internship as a UI/UX Designer / Developer Intern at City Health Office 1 in 2026, where he worked on the Queuing Management System using Figma, PHP, HTML, CSS, JavaScript, and MySQL.';
        }

        if (str_contains($normalized, 'capstone') || str_contains($normalized, 'city health connect')) {
            return 'Karl Justine’s capstone project was City Health Connect, a multi-platform health services management system for the City Health Office of Urdaneta City. He served as a UI/UX Designer / Programmer and used Figma, PHP, HTML, CSS, JavaScript, Flutter, and MySQL.';
        }

        if (str_contains($normalized, 'study') || str_contains($normalized, 'school') || str_contains($normalized, 'degree') || str_contains($normalized, 'college') || str_contains($normalized, 'education')) {
            return 'Karl Justine studied Bachelor of Science in Information Technology at Urdaneta City University from 2022 to 2026 and also completed Senior High School and Junior High School at Mataas Na Paaralang Juan C. Laya (MPJCL).';
        }

        if (str_contains($normalized, 'contact') || str_contains($normalized, 'email') || str_contains($normalized, 'linkedin') || str_contains($normalized, 'portfolio')) {
            return 'Karl Justine can be contacted via email at karljustinemembrere11272003@gmail.com, on LinkedIn at linkedin.com/in/membrere-karl-justine-r-6927343a6, and through his portfolio at https://karljustinemembrere-portfolio.vercel.app/.';
        }

        if (str_contains($normalized, 'facebook') || str_contains($normalized, 'fb')) {
            return 'Karl Justine’s Facebook profile is not documented in the current portfolio information.';
        }

        if (str_contains($normalized, 'unavailable') || str_contains($normalized, 'not available')) {
            return 'The information you are asking for is not documented in Karl Justine’s profile.';
        }

        return 'I can help with Karl Justine’s professional background, skills, experience, education, and contact information.';
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
