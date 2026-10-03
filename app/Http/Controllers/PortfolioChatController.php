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
Karl Justijne R. Membrere is a UI/UX Designer and fresh graduate focused on creating clear, intuitive, and accessible digital experiences.

Full name: Karl Justijne R. Membrere
Short name: Karl Justijne
Professional title: UI/UX Designer
Location: Guiset Norte, San Manuel, Pangasinan, Philippines
Phone: +63 976 056 9838
Email: karljustinemembrere11272003@gmail.com
LinkedIn: linkedin.com/in/membrere-karl-justine-r-6927343a6
Portfolio: https://karljustinemembrere-portfolio.vercel.app/

Career objective:
Karl Justijne is a detail-oriented UI/UX Designer and fresh graduate passionate about transforming complex problems into clean, intuitive, and accessible digital experiences. He is skilled in Figma, wireframing, and design systems, and he aims to contribute user-focused solutions and modern interface designs to a collaborative team.

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
- Always refer to the person as "Karl Justijne" and, when needed, "Karl Justijne R. Membrere".
- Use only the verified information in this context.
- Never invent clients, employers, rates, contracts, projects, technologies, certifications, awards, hobbies, or personal details.
- Focus on Karl Justijne's profile, skills, design work, internship, capstone, education, freelance availability, and contact information.
- If the answer is not documented, say that the information is not available.
- Keep answers concise, clear, natural, and relevant to the question.
CONTEXT;

        $systemPrompt = <<<PROMPT
You are the AI assistant for Karl Justijne R. Membrere.

Your job is to help visitors understand Karl Justijne's professional identity, career objective, skills, tools, freelance experience, internship, capstone experience, education, contact information, and availability.

Use the verified profile context below as the source of truth.

Portfolio context:
{$portfolioContext}

Always refer to the person as "Karl Justijne". When a full formal name is needed, use "Karl Justijne R. Membrere".

Focus on the actual question the user is asking. Do not lead with unrelated portfolio projects or generic project summaries unless the question is specifically about an internship or capstone experience.

Answer briefly, clearly, and professionally. If the information is not documented, say it is not available instead of guessing.
PROMPT;

        $input = $systemPrompt."\n\nUser question:\n".$message;

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

    protected function fallbackPortfolioResponse(string $message): string
    {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $message) ?? $message));

        if (str_contains($normalized, 'who is') || str_contains($normalized, 'what does') || str_contains($normalized, 'profession') || str_contains($normalized, 'title')) {
            return 'Karl Justijne R. Membrere is a UI/UX Designer focused on creating clear, intuitive, and accessible digital experiences.';
        }

        if (str_contains($normalized, 'objective') || str_contains($normalized, 'goal') || str_contains($normalized, 'career') || str_contains($normalized, 'focus')) {
            return 'Karl Justijne is a detail-oriented UI/UX Designer and fresh graduate passionate about transforming complex problems into clean, intuitive, and accessible digital experiences.';
        }

        if (str_contains($normalized, 'skill') || str_contains($normalized, 'software') || str_contains($normalized, 'tool') || str_contains($normalized, 'figma') || str_contains($normalized, 'affinity')) {
            return 'Karl Justijne works with Figma and Affinity, and his design skills include wireframing, prototyping, interaction design, visual design, UI/UX design, design systems, mobile app design, web design, and usability.';
        }

        if (str_contains($normalized, 'freelance') || str_contains($normalized, 'hire') || str_contains($normalized, 'available') || str_contains($normalized, 'work')) {
            return 'Karl Justijne is currently working as a Freelance UI/UX Designer and is available for freelance design work.';
        }

        if (str_contains($normalized, 'intern') || str_contains($normalized, 'internship')) {
            return 'Karl Justijne completed his internship as a UI/UX Designer / Developer Intern at City Health Office 1 in 2026, where he worked on the Queuing Management System using Figma, PHP, HTML, CSS, JavaScript, and MySQL.';
        }

        if (str_contains($normalized, 'capstone') || str_contains($normalized, 'city health connect')) {
            return 'Karl Justijne’s capstone project was City Health Connect, a multi-platform health services management system for the City Health Office of Urdaneta City. He served as a UI/UX Designer / Programmer and used Figma, PHP, HTML, CSS, JavaScript, Flutter, and MySQL.';
        }

        if (str_contains($normalized, 'study') || str_contains($normalized, 'school') || str_contains($normalized, 'degree') || str_contains($normalized, 'college') || str_contains($normalized, 'education')) {
            return 'Karl Justijne studied Bachelor of Science in Information Technology at Urdaneta City University from 2022 to 2026 and also completed Senior High School and Junior High School at Mataas Na Paaralang Juan C. Laya (MPJCL).';
        }

        if (str_contains($normalized, 'contact') || str_contains($normalized, 'email') || str_contains($normalized, 'linkedin') || str_contains($normalized, 'portfolio')) {
            return 'Karl Justijne can be contacted via email at karljustinemembrere11272003@gmail.com, on LinkedIn at linkedin.com/in/membrere-karl-justine-r-6927343a6, and through his portfolio at https://karljustinemembrere-portfolio.vercel.app/.';
        }

        if (str_contains($normalized, 'unavailable') || str_contains($normalized, 'not available')) {
            return 'The information you are asking for is not documented in Karl Justijne’s profile.';
        }

        return 'I can help you learn more about Karl Justijne’s skills, experience, education, freelance work, and professional background.';
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
